<?php

namespace App\Http\Controllers\Api\V1;

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
        $bing = new BingPhoto();
        $image = $bing->getImage();
        return $this->response->array($image);
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
