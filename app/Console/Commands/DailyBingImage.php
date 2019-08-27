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
        $num = is_numeric($this->argument('num')) ?$this->argument('num') : 1;
        $bing = new BingPhoto([
            'n' => $num
        ]);
        $images = $bing->getImages();

        foreach ($images as $image) {
            $file_name = 'image/bing-'.$image['fullstartdate'].'.jpg';
            // 保存到七牛云
            $url = app(UploadHandler::class)->qiNiuSave($file_name, $image['url']);

            $imageModel = new Image();
            $data = [
                'name' => $image['copyright'],
                'desc' => '',
                'path_type' => 'qiniu',
                'path' => $file_name,
                'original_path' => $image['url'],
                'source' => 'bing',
                'created_at' => date('Y-m-d H:i:s', strtotime($image['fullstartdate']))
            ];
            $imageModel->fill($data);
            $imageModel->save();
            $this->line($url . ' 保存成功！');
        }
    }
}
