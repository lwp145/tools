<?php

namespace App\Console\Commands;

use App\Handlers\UploadHandler;
use App\Services\ImagesService;
use Curl\Curl;
use Illuminate\Console\Command;

class EverydayBingImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bing:everyday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集bing图历史记录';

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
        /**
         * 抓取页面
         * https://uploadbeta.com/picture-gallery/faq.php#api
         */

        $key = 'BingEverydayWallpaperPicture';
        // 获取数量
        $count = file_get_contents("https://uploadbeta.com/api/pictures/count?key=".$key);
        $count = json_decode($count, true)[0];

        // 分页获取
        $offset = 50;
        $sort = 1; // 最近获取

        $page = ceil((int)$count / $offset);

        $bar = $this->output->createProgressBar($count);
        $bar->start();
        $this->line('');

        for ($i = 0; $i < $page; $i++) {
            $start = $i * $offset + 1;
            try {
                $list = file_get_contents("https://uploadbeta.com/api/pictures/search/?key=".$key."&start=".$start."&offset=".$offset."&sort=".$sort);
                $list = json_decode($list, true);
            } catch (\Exception $e) {
                continue;
            }

            if (empty($list)) {
                continue;
            }
            foreach ($list as $l) {
                $name = uniqid(date('Y-m-d-', $l['created'] ?? time())).'.jpg';
                $image_url = 'https://uploadbeta.com/_s/'.$l['url'];

                try {
                    $save_data = ImagesService::instance()->saveBingImage($name, $image_url, $l['title']);
                    if (empty($save_data)) {
                        continue;
                    }
                    // 保存到七牛云
                    $url = app(UploadHandler::class)->qiNiuSave($save_data['path'], $image_url);
                } catch (\Exception $e) {
                    continue;
                }


                $bar->advance();
            }
        }
        $bar->finish();
        $this->line('ok');
    }
}
