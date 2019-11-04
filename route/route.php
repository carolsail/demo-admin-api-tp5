<?php

//Miss 路由开启后，默认的普通模式也将无法访问
Route::miss('api/v1.Miss/miss');

//upload
Route::rule('ajax/upload', 'app\api\service\Upload@plupload');

// user && token
Route::group('api/:version/user', function () {
    Route::post('/login', 'api/:version.User/login');
    Route::post('/verify', 'api/:version.User/verify');
    Route::get('/current', 'api/:version.User/current');
    Route::get('/logout', 'api/:version.User/logout');
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
Route::group('api/:version/test', function(){
    Route::get('/auth', 'api/:version.Test/auth');
    Route::get('/verify', 'api/:version.Test/verify');
});