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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('upload','Api\CommonController@upload');//上传图片
Route::post('single','Api\CommonController@single');//单页协议
Route::post('getConfig','Api\CommonController@getConfig');//系统配置
Route::post('sendSms','Api\CommonController@sendSms');//发送验证码
Route::post('register','Api\LoginController@register');//注册
Route::post('forget','Api\LoginController@forget');//忘记密码
Route::post('ad','Api\IndexController@ad');//轮播图
Route::post('category','Api\IndexController@category');//分类
Route::post('level','Api\IndexController@level');//等级
Route::post('bidAdd','Api\IndexController@bidAdd');//出价添加
Route::post('login','Api\LoginController@login');//NewAPI1
Route::post('getBlindBoxesList','Api\IndexController@getBlindBoxesList');//NewAPI2
Route::post('getBlindBoxe', 'Api\IndexController@getBlindBoxe');//-NewAPI3
Route::post('blindBoxeDetailList','Api\IndexController@blindBoxeDetailList');// NewAPI4
Route::post('blindBoxeRankingList', 'Api\IndexController@blindBoxeRankingList');//NewAPI5
Route::post('blindBoxeRecordList', 'Api\IndexController@blindBoxeRecordList');//NewAPI6
Route::post('getChangeBoxList', 'Api\IndexController@getChangeBoxList');//NewAPI7
Route::post('buyNFT','Api\IndexController@buyNFT');//NewAPI8
Route::post('getUserInfo','Api\IndexController@getUserInfo');//NewAPI9
Route::post('getUserTransactions','Api\IndexController@getUserTransactions');//NewAPI10
Route::post('getUserReturnList','Api\IndexController@getUserReturnList');//NewAPI12
Route::post('getUserTransactionsList','Api\IndexController@getUserTransactionsList');//NewAPI13
Route::post('returnNFTs','Api\IndexController@returnNFTs');//NewAPI14
Route::post('withdrawalNFTs','Api\IndexController@withdrawalNFTs');//NewAPI15
Route::post('getAllNFTs','Api\IndexController@getAllNFTs');//NewAPI16
Route::post('getRewardTransaction','Api\IndexController@getRewardTransaction');//NewAPI16
Route::post('boxList','Api\IndexController@boxList');//盲盒列表
Route::post('boxDetail','Api\IndexController@boxDetail');//盲盒详情
Route::post('suitNum','Api\IndexController@suitNum');//盲盒详情余量
Route::post('boxGoods','Api\IndexController@boxGoods');//盲盒详情记录
Route::post('boxGoodsLog','Api\IndexController@boxGoodsLog');//竞技赏记录
Route::post('timesRank','Api\IndexController@timesRank');//盲盒详情次数排行
Route::post('timesRankLog','Api\IndexController@timesRankLog');//盲盒详情次数排行
Route::post('luckRank','Api\IndexController@luckRank');//盲盒详情欧皇排行
Route::post('luckRankLog','Api\IndexController@luckRankLog');//盲盒详情欧皇排行
Route::post('suit','Api\IndexController@suit');//换箱子-箱子列表
Route::post('choiceSuit','Api\IndexController@choiceSuit');//换箱子-选择箱子
Route::post('goodsList','Api\IndexController@goodsList');//无限赏列表
Route::post('goodsDetail','Api\IndexController@goodsDetail');//赏品详情
Route::post('awardList','Api\IndexController@awardList');//无限赏-中奖记录
Route::post('awardListLog','Api\IndexController@awardListLog');//无限赏-中奖记录-展开
Route::post('awardNickList','Api\IndexController@awardNickList');//无限赏-中奖记录-昵称搜索
Route::post('awardNameList','Api\IndexController@awardNameList');//无限赏-中奖记录-赏品搜索
Route::post('eggList','Api\IndexController@eggList');//扭蛋机列表
Route::post('eggDetail','Api\IndexController@eggDetail');//扭蛋机详情
Route::group(['middleware' => ['api_auth']], function (){
    Route::post('userInfo','Api\UserController@userInfo');//个人中心
    Route::post('updateAvatar','Api\UserController@updateAvatar');//修改头像
    Route::post('updateNickname','Api\UserController@updateNickname');//修改昵称
    Route::post('addressList','Api\UserController@addressList');//地址列表
    Route::post('addressHandle','Api\UserController@addressHandle');//地址添加/修改
    Route::post('addressDel','Api\UserController@addressDel');//地址删除
    Route::post('couponList','Api\UserController@couponList');//潮玩券-我的券
    Route::post('couponMerge','Api\UserController@couponMerge');//潮玩券-合并
    Route::post('couponShare','Api\UserController@couponShare');//我的券-分享
    Route::post('couponDraw','Api\UserController@couponDraw');//我的券-领取
    Route::post('couponJoin','Api\UserController@couponJoin');//我的券-参与中
    Route::post('couponOpen','Api\UserController@couponOpen');//我的券-开券
    Route::post('scoreList','Api\UserController@scoreList');//潮玩币-明细
    Route::post('dealList','Api\UserController@dealList');//我的账单
    Route::post('userBox','Api\UserController@userBox');//我的赏袋
    Route::post('waitUserBox','Api\UserController@waitUserBox');//待操作赏袋
    Route::post('userBoxDeliver','Api\UserController@userBoxDeliver');//赏袋发货
    Route::post('addSafe','Api\UserController@addSafe');//添加到保险柜
    Route::post('removeSafe','Api\UserController@removeSafe');//移除保险柜
    Route::post('userBoxSale','Api\UserController@userBoxSale');//赏袋挂售
    Route::post('deliverList','Api\UserController@deliverList');//发货记录
    Route::post('saleList','Api\UserController@saleList');//挂售记录
    Route::post('collectionList','Api\UserController@collectionList');//收藏列表
    Route::post('collection','Api\UserController@collection');//收藏
    Route::post('collectionCancel','Api\UserController@collectionCancel');//取消收藏
    Route::post('logout','Api\UserController@logout');//退出登录

    Route::post('orderBox','Api\OrderController@orderBox');//盲盒下单
    Route::post('drawPrize','Api\OrderController@drawPrize');//开奖赏品查询
    Route::post('drawCoupon','Api\OrderController@drawCoupon');//开奖优惠券查询
});
