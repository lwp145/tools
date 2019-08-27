<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    public $fillable = [
        'name', 'desc', 'path_type', 'path', 'original_path', 'source', 'created_at'
    ];

    /**
     * 获取访问路径.
     *
     * @param  string  $value
     * @return string
     */
    public function getPathAttribute($value)
    {
        $url = '';
        switch ($this->path_type) {
            case 'qiniu':
                $url = Storage::disk('qiniu')->url($value);
                break;
            default:
        }
        return $url;
    }
}
