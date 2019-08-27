<?php

namespace App\Handlers;

use Illuminate\Support\Facades\Storage;

class UploadHandler
{
    /**
     * 七牛云上传
     * @param $filename
     * @param $file_path
     * @return string
     */
    public function qiNiuSave($filename, $file_path)
    {
        $disk = Storage::disk('qiniu');
        $disk->put($filename, file_get_contents($file_path));
        return $disk->url($filename);
    }
}
