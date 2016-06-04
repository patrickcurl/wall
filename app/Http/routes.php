<?php
use GuzzleHttp\Client;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $client = new Client([
      'base_uri' => 'https://www.reddit.com/r/wallpapers.json',
      'timeout' => 2.0,
      'headers' => ['User-Agent' => 'testing/1.0'],
    ]);


    $response = $client->request('GET', '');

    return \Response::json(json_decode($response->getBody()))->header('Content-Type', 'application/json');


    // return view('welcome');
});

Route::get('/test', function(){
    $data = ['test' => App\Wallpaper::insertWalls()];
    return view('test', $data); //\Response::json(json_encode())->header('Content-Type', 'application/json');
});

Route::auth();

Route::get('/home', 'HomeController@index');
