<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodesController extends Controller
{
    public function show(Request $request)
    {
        $text = $request->get('text', 'https://lihq.xyz');
        $image = QrCode::format('png')->margin(0)->size(300)->generate($text);

        header('Content-Type: image/png;text/html; charset=utf-8');
        echo $image;exit();
    }
}
