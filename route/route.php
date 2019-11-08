<?php

//Miss 路由开启后，默认的普通模式也将无法访问
Route::miss('api/v1.Miss/miss');

//upload
Route::rule('ajax/upload', 'app\common\service\Upload@plupload');

// user && token
Route::group('api/:version/user', function () {
    Route::post('/login', 'api/:version.User/login');
    Route::get('/verify', 'api/:version.User/verify');
    Route::get('/refresh', 'api/:version.User/refresh');
    Route::post('/edit', 'api/:version.User/edit');
    Route::post('/change/:type', 'api/:version.User/change');
});

// banner
Route::group('api/:version/banner', function () {
    Route::get('/index', 'api/:version.Banner/index');
    Route::post('/create', 'api/:version.Banner/create');
    Route::post('/edit', 'api/:version.Banner/edit');
    Route::get('/delete', 'api/:version.Banner/delete');
    Route::get('/info/:id', 'api/:version.Banner/info');
});


// test
Route::group('api/:version/test', function () {
    Route::get('/auth', 'api/:version.Test/auth');
    Route::get('/verify', 'api/:version.Test/verify');
    Route::get('/random', 'api/:version.Test/random');
});
