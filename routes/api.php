<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['serializer:array', 'bindings']
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => 60,
        'expires' => 1,
    ], function ($api) {

    });

    $api->group([

    ], function ($api) {
        $api->get('test', 'TestsController@test')
            ->name('api.test.test');

        $api->get('bing-image/today', 'BingImagesController@today')
            ->name('api.bing-image.today');

        $api->get('bing-image/random', 'BingImagesController@random')
            ->name('api.bing-image.random');
    });


});
