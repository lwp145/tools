<?php

namespace App\Console\Commands;

use App\Handlers\UploadHandler;
use App\Services\ImagesService;
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

        $save_data = ImagesService::instance()->saveBingImage($name, $image['url'], $image['copyright']);

        if (empty($save_data)) {
            $this->error('已经存在'); return ;
        }

        // 保存到七牛云
        $url = app(UploadHandler::class)->qiNiuSave($save_data['path'], $image['url']);

        $this->line($url . ' 保存成功！');
    }
}
