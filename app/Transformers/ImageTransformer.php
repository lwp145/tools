<?php

namespace App\Transformers;

use App\Models\Image;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    protected $type = '';

    public function __construct($type = '')
    {
        $this->type = $type;
    }

    public function transform(Image $image)
    {
        switch ($this->type) {
            case 'url':
                return [
                    'id' => $image->id,
                    'url' => $image->path
                ];
            default:
                return [];
        }
    }
}
