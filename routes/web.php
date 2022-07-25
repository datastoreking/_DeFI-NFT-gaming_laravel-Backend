<?php

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

//API接口

//后台地址
Route::get('/admin/login', function () {return view('admin/login');});
Route::post('/admin/login','Admin\IndexController@login');
Route::group(['prefix'=>'admin','middleware'=>['admin_auth']],function (){
    Route::get('index', 'Admin\IndexController@index');
    Route::get('logout', 'Admin\IndexController@logout');
    Route::get('welcome', 'Admin\IndexController@welcome');
    Route::post('upload', 'Admin\IndexController@upload');
    //用户管理
    Route::get('user_index',function(){return view('admin.user.index');});
    Route::get('user_list','Admin\UserController@lists');
    Route::get('user_add','Admin\UserController@add');
    Route::post('user_add','Admin\UserController@postAdd');
    Route::post('user_del','Admin\UserController@del');
    Route::post('user_state','Admin\UserController@state');
    Route::get('user_conf','Admin\UserController@conf');
    Route::post('user_conf','Admin\UserController@postConf');
    //轮播图管理
    Route::get('ad_index', function(){return view('admin.ad.index');});
    Route::get('ad_list','Admin\AdController@lists');
    Route::get('ad_add','Admin\AdController@add');
    Route::post('ad_add','Admin\AdController@postAdd');
    Route::post('ad_del','Admin\AdController@del');
    //分类管理
    Route::get('category_index', function(){return view('admin.category.index');});
    Route::get('category_list','Admin\CategoryController@lists');
    Route::get('category_add','Admin\CategoryController@add');
    Route::post('category_add','Admin\CategoryController@postAdd');
    Route::post('category_del','Admin\CategoryController@del');
    //菜单管理 12/15
    Route::get('menu_index', function(){return view('admin.menu.index');});
    Route::get('menu_list','Admin\AdminMenuController@lists');
    Route::get('menu_add', 'Admin\AdminMenuController@add');
    Route::post('menu_add', 'Admin\AdminMenuController@postadd');
    Route::get('menu_edit', 'Admin\AdminMenuController@edit');
    Route::post('menu_edit', 'Admin\AdminMenuController@postEdit');
    Route::post('menu_del', 'Admin\AdminMenuController@del');
    Route::post('menu_status', 'Admin\AdminMenuController@status');
    Route::post('menu_list_status', 'Admin\AdminMenuController@list_status');
    Route::post('menu_sort', 'Admin\AdminMenuController@sort');
    Route::get('menu_icon', 'Admin\AdminMenuController@icon');
    //管理员管理 12/15
    Route::get('admin_index', function(){return view('admin.account.index');});
    Route::get('admin_list','Admin\AdminController@admin_lists');
    Route::get('admin_add','Admin\AdminController@admin_add');
    Route::post('admin_add','Admin\AdminController@admin_postAdd');
    Route::post('admin_status','Admin\AdminController@admin_status');
    Route::get('admin_edit','Admin\AdminController@admin_edit');
    Route::post('admin_edit','Admin\AdminController@admin_postEdit');
    //角色管理 12/15
    Route::get('auth_index', function(){return view('admin.auth.index');});
    Route::get('auth_list','Admin\AdminAuthController@auth_list');
    Route::get('auth_add','Admin\AdminAuthController@auth_add');
    Route::post('auth_add','Admin\AdminAuthController@auth_postAdd');
    Route::get('auth_edit','Admin\AdminAuthController@auth_edit');
    Route::post('auth_edit','Admin\AdminAuthController@auth_postEdit');
    Route::get('auth_node','Admin\AdminAuthController@auth_node');
    Route::post('auth_node','Admin\AdminAuthController@authPost');
    Route::post('auth_state','Admin\AdminAuthController@auth_state');

    //提现管理
    Route::get('cash_index', function(){return view('admin.cash.index');});
    Route::get('cash_list','Admin\CashController@lists');
    Route::post('cash_pass','Admin\CashController@pass');
    Route::post('cash_back','Admin\CashController@back');
    //单页管理
    Route::get('single_index', 'Admin\SingleController@index');
    Route::get('single_list','Admin\SingleController@lists');
    Route::get('single_add','Admin\SingleController@add');
    Route::post('single_add','Admin\SingleController@postAdd');

    //基本设置
    Route::get('config_index', 'Admin\ConfigController@index');
    Route::post('config_save', 'Admin\ConfigController@save');

    //盲盒管理
    //一番赏
    Route::get('box_one_index', function(){return view('admin.box.one_index');});
    Route::get('box_one_list','Admin\BoxController@one_lists');
    Route::get('box_one_add','Admin\BoxController@one_add');
    Route::post('box_one_add','Admin\BoxController@one_postAdd');
    Route::post('box_one_del','Admin\BoxController@del');
    Route::post('box_one_state','Admin\BoxController@state');
    //竞技赏
    Route::get('box_two_index', function(){return view('admin.box.two_index');});
    Route::get('box_two_list','Admin\BoxController@two_lists');
    Route::get('box_two_add','Admin\BoxController@two_add');
    Route::post('box_two_add','Admin\BoxController@two_postAdd');
    Route::post('box_two_del','Admin\BoxController@del');
    Route::post('box_two_state','Admin\BoxController@state');
    //无限赏
    Route::get('box_three_index', function(){return view('admin.box.three_index');});
    Route::get('box_three_list','Admin\BoxController@three_lists');
    Route::get('box_three_add','Admin\BoxController@three_add');
    Route::post('box_three_add','Admin\BoxController@three_postAdd');
    Route::post('box_three_del','Admin\BoxController@del');
    Route::post('box_three_state','Admin\BoxController@state');
    Route::get('box_three_prize','Admin\BoxController@three_prize');
    Route::post('box_three_prize','Admin\BoxController@three_postPrize');
    //扭蛋机
    Route::get('box_egg_index', function(){return view('admin.box.egg_index');});
    Route::get('box_egg_list','Admin\BoxController@egg_lists');
    Route::get('box_egg_add','Admin\BoxController@egg_add');
    Route::post('box_egg_add','Admin\BoxController@egg_postAdd');
    Route::post('box_egg_del','Admin\BoxController@del');
    Route::post('box_egg_state','Admin\BoxController@state');
    //盲盒概率管理
    Route::get('box_level_index','Admin\BoxController@level_index');
    Route::get('box_level_list','Admin\BoxController@level_lists');
    Route::get('box_level_add','Admin\BoxController@level_add');
    Route::post('box_level_add','Admin\BoxController@level_postAdd');
    Route::post('box_level_del','Admin\BoxController@level_del');

    //商品管理
    Route::get('goods_index', function(){return view('admin.goods.index');});
    Route::get('goods_list','Admin\GoodsController@lists');
    Route::get('goods_add','Admin\GoodsController@add');
    Route::post('goods_add','Admin\GoodsController@postAdd');
    Route::post('goods_del','Admin\GoodsController@del');
    Route::get('goods_search','Admin\GoodsController@search');
    Route::get('goods_three_search','Admin\GoodsController@three_search');
    Route::get('goods_egg_search','Admin\GoodsController@egg_search');
    //级别管理
    Route::get('level_index', function(){return view('admin.level.index');});
    Route::get('level_list','Admin\LevelController@lists');
    Route::get('level_add','Admin\LevelController@add');
    Route::post('level_add','Admin\LevelController@postAdd');
    Route::post('level_del','Admin\LevelController@del');
    //订单管理
    Route::get('order_index', function(){return view('admin.order.index');});
    Route::get('order_list','Admin\OrderController@lists');
    Route::get('order_goods_index', function(){return view('admin.order.order');});
    Route::get('order_goods_list','Admin\OrderController@goods_lists');
    Route::get('order_deliver_index', function(){return view('admin.order.goods');});
    Route::get('order_deliver_list','Admin\OrderController@deliver_lists');
    Route::get('order_deliver','Admin\OrderController@deliver');
    Route::post('order_deliver','Admin\OrderController@postDeliver');

    //充值
    Route::get('recharge_index', function(){return view('admin.recharge.index');});
    Route::get('recharge_list','Admin\RechargeController@lists');
    Route::get('recharge_add','Admin\RechargeController@add');
    Route::post('recharge_add','Admin\RechargeController@postAdd');
    Route::post('recharge_del','Admin\RechargeController@del');

    //优惠券
    Route::get('coupon_index', function(){return view('admin.coupon.index');});
    Route::get('coupon_list','Admin\CouponController@lists');
    Route::get('coupon_add','Admin\CouponController@add');
    Route::post('coupon_add','Admin\CouponController@postAdd');
    Route::post('coupon_del','Admin\CouponController@del');
    Route::get('coupon_give','Admin\CouponController@give');
    Route::post('coupon_give','Admin\CouponController@postGive');

    //消费统计
    Route::get('consume_day_index', function(){return view('admin.consume.consume_day');});
    Route::get('consume_day_list','Admin\ConsumeController@consume_day_lists');
    Route::get('consume_week_index', function(){return view('admin.consume.consume_week');});
    Route::get('consume_week_list','Admin\ConsumeController@consume_week_lists');
    Route::get('consume_month_index', function(){return view('admin.consume.consume_month');});
    Route::get('consume_month_list','Admin\ConsumeController@consume_month_lists');

    Route::get('times_day_index', function(){return view('admin.consume.times_day');});
    Route::get('times_day_list','Admin\ConsumeController@times_day_lists');
    Route::get('times_week_index', function(){return view('admin.consume.times_week');});
    Route::get('times_week_list','Admin\ConsumeController@times_week_lists');
    Route::get('times_month_index', function(){return view('admin.consume.times_month');});
    Route::get('times_month_list','Admin\ConsumeController@times_month_lists');

    Route::get('infinite_day_index', function(){return view('admin.consume.infinite_day');});
    Route::get('infinite_day_list','Admin\ConsumeController@infinite_day_lists');
    Route::get('infinite_week_index', function(){return view('admin.consume.infinite_week');});
    Route::get('infinite_week_list','Admin\ConsumeController@infinite_week_lists');
    Route::get('infinite_month_index', function(){return view('admin.consume.infinite_month');});
    Route::get('infinite_month_list','Admin\ConsumeController@infinite_month_lists');

    Route::get('consume_total_day_index', function(){return view('admin.consume.total_day');});
    Route::get('consume_total_day_list','Admin\ConsumeController@consume_total_day_lists');
    Route::get('consume_total_week_index', function(){return view('admin.consume.total_week');});
    Route::get('consume_total_week_list','Admin\ConsumeController@consume_total_week_lists');
    Route::get('consume_total_month_index', function(){return view('admin.consume.total_month');});
    Route::get('consume_total_month_list','Admin\ConsumeController@consume_total_month_lists');
});


