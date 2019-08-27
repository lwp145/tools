<?php

namespace App\Http\Controllers\Api\V1;

use App\Handlers\UploadHandler;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\ImageRequest;
use App\Models\Image;
use App\Transformers\ImageTransformer;
use grubersjoe\BingPhoto;

class BingImagesController extends ApiController
{
    /**
     * 获取今日bing图
     * @return mixed
     * @throws \Exception
     */
    public function today()
    {
        $imageModel = new Image();
        // 先查看今天是否存在
        $today = $imageModel->query()->whereDate('created_at', '=', date('Y-m-d'))->first();
        if ($today) {
            return $this->response->array([
                'url' => $today->path
            ]);
        }

        $bing = new BingPhoto();
        $image = $bing->getImage();

        if (!is_today(strtotime($image['fullstartdate']))) {
            // 今天的图还没出来，先拿最近的吧
            return $this->response->array([
                'url' => $image['url']
            ]);
        }

        // 上传七牛并保存数据库

        $file_name = 'image/bing-'.$image['fullstartdate'].'.jpg';
        // 保存到七牛云
        $url = app(UploadHandler::class)->qiNiuSave($file_name, $image['url']);

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

        return $this->response->array(compact('url'));
    }

    /**
     * 获取随机数据
     * @param ImageRequest $request
     * @param Image $images
     * @return \Dingo\Api\Http\Response
     */
    public function random(ImageRequest $request, Image $images)
    {
        $min_id = $images->query()->min('id');
        $max_id = $images->query()->max('id');
        $row = $images->query()->count();
        $num = $request->get('num', 1);
        if ($row < $num) {
            $num = $row;
        }

        // 获取随机数
        $random_id = unique_rand($min_id, $max_id, $num);

        $list = $images->query()->whereIn('id', $random_id)->select(['id', 'path', 'path_type'])->inRandomOrder()->get();

        return $this->response->collection($list, new ImageTransformer('url'));
    }
}
