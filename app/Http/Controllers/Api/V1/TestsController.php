<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class TestsController extends ApiController
{
    public function test()
    {
        $data = [
            'test' => foo()
        ];
        return $this->response->array($data);
    }
}
