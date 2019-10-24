<?php
//Miss 404
//Miss 路由开启后，默认的普通模式也将无法访问
Route::miss('api/v1.Miss/miss');

// banner
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');


// product
Route::group('api/:version/product', function(){
	Route::get('/by_category/paginate', 'api/:version.Product/getByCategory');
	Route::get('/by_category', 'api/:version.Product/getAllInCategory');
	Route::get('/:id', 'api/:version.Product/getOne',[],['id'=>'\d+']);
	Route::get('/recent', 'api/:version.Product/getRecent');
});

// category
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');


// token
Route::group('api/:version/token', function(){
	//cms应用token
	Route::post('/app', 'api/:version.Token/getAppToken');
	//wechat
	Route::post('/user', 'api/:version.Token/getToken');
	Route::post('/verify', 'api/:version.Token/verifyToken');
});


// order
Route::group('api/:version/order', function(){
	//cms 获取订单列表
	Route::get('/paginate', 'api/:version.Order/getSummary');	
	//cms 发货处理
	Route::put('/delivery', 'api/:version.Order/delivery');

	//小程序
	Route::post('/', 'api/:version.Order/placeOrder');
	Route::get('/by_user', 'api/:version.Order/getSummaryByUser');
});
