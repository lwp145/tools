<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function random(Image $image)
    {
        // 先随机取一条数据
        $item = $image->query()->select(['id', 'path', 'path_type'])->inRandomOrder()->first()->toArray();

        header('content-type:image/jpg;');

        $content = file_get_contents($item['path']);
        echo $content;
    }
}
