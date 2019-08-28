<?php

namespace App\Console\Commands;

use App\Handlers\UploadHandler;
use App\Models\Image;
use grubersjoe\BingPhoto;
use Illuminate\Console\Command;

class DailyBingImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bing:daily {num=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日bing图保存';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bing = new BingPhoto();
        $image = $bing->getImage();

        if (empty($image['url'])) {
            $this->error('url为空'); return ;
        }

        $name = uniqid(date('Y-m-d-')).'.jpg';
        $path = 'image/bing/'.$name;

        $md5 = md5_file($image['url']);
        $sha1 = sha1_file($image['url']);

        $imageModel = new Image();
        // 如果文件存在就不保存了
        $where = [
            'md5' => $md5,
            'sha1' => $sha1,
        ];
        if ($imageModel->query()->where($where)->first()) {
            $this->error('文件已存在'); return ;
        }

        $data = [
            'name' => $name,
            'desc' => $image['copyright'] ?? '',
            'path_type' => 'qiniu',
            'path' => $path,
            'original_path' => $image['url'],
            'source' => 'bing',
            'md5' => $md5,
            'sha1' => $sha1
        ];
        $imageModel->fill($data);
        $imageModel->save();

        // 保存到七牛云
        $url = app(UploadHandler::class)->qiNiuSave($path, $image['url']);

        $this->line($url . ' 保存成功！');
    }
}
