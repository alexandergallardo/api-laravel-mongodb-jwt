<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
Route::get('/', function () {
    // check if redis has posts.all key exits
    // if posts found then it will return all post without touching the database
    if ($posts = Redis::get('posts.all')) {
        return json_decode($posts);
    }
    // get all post
    $posts = Post::all();

    // store data into redis for next 24 hours
    Redis::setex('posts.all', 60 * 60 * 24, $posts);

    // return all posts
    return $posts;
});
*/
