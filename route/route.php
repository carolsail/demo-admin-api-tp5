<?php

//Miss 路由开启后，默认的普通模式也将无法访问
Route::miss('api/v1.Miss/miss');

// token
Route::group('api/:version/token', function () {
    Route::post('/get', 'api/:version.Token/get');
    Route::post('/verify', 'api/:version.Token/verify');
    Route::get('/current', 'api/:version.Token/current');
    Route::get('/logout', 'api/:version.Token/logout');
});

// banner
Route::group('api/:version/banner', function () {
    Route::get('/index', 'api/:version.Banner/index');
    Route::post('/create', 'api/:version.Banner/create');
    Route::post('/edit', 'api/:version.Banner/edit');
    Route::get('/delete', 'api/:version.Banner/delete');
    Route::get('/info/:id', 'api/:version.Banner/info');
});

// product
Route::group('api/:version/product', function () {
    Route::get('/by_category/paginate', 'api/:version.Product/getByCategory');
    Route::get('/by_category', 'api/:version.Product/getAllInCategory');
    Route::get('/:id', 'api/:version.Product/getOne', [], ['id'=>'\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
});

// category
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');
