<?php

namespace App\Services;

use App\Models\Image;
use App\Traits\Singleton;

class ImagesService
{
    use Singleton;

    public function saveBingImage($name, $image_url, $desc = '', $path_type = 'qiniu', $source = 'bing')
    {
        $path = 'image/'.$source.'/'.$name;

        $md5 = md5_file($image_url);
        $sha1 = sha1_file($image_url);

        $imageModel = new Image();
        // 如果文件存在就不保存了
        $where = [
            'md5' => $md5,
            'sha1' => $sha1,
        ];
        if ($imageModel->query()->where($where)->first()) {
           return [];
        }

        $data = [
            'name' => $name,
            'desc' => $desc,
            'path_type' => $path_type,
            'path' => $path,
            'original_path' => $image_url,
            'source' => $source,
            'md5' => $md5,
            'sha1' => $sha1
        ];
        $imageModel->fill($data);
        $imageModel->save();
        return $data;
    }
}
