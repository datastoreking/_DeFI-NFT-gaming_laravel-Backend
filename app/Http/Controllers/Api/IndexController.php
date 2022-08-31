<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Box;
use App\Models\BoxLevel;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Config;
use App\Models\Egg;
use App\Models\Goods;
use App\Models\Level;
use App\Models\Prize;
use App\Models\Suit;
use App\Models\SuitGoods;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Recharge;
use App\Models\User;
use App\Models\UserCoupon;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    //NewAPI2
    public function getBlindBoxesList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('pageSize',2);
        $c_id = $request->input('blindBoxesType');

        $query = Box::query()->select('id', 'name','image','cover_image','price', 'create_time')
            ->where('state',1)->where('is_del',0)->where('c_id',$c_id);
        $query = $query->whereIn('type',[1])->orderBy('sort','asc')->paginate($limit);
        foreach ($query as &$item){
            $item->blindBoxesType = $c_id;
            if($c_id == 2){
                $item->surplus = 10 - Box::query()->select('bid_count')->where('id', $item->id)->first()->bid_count;
                $item->totalNumber = 10;
            }
        }
        return $this->ajax(0,'sucess',[
            'count'=>$query->total(),
            'pageNumber'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //NewAPI3
    public function getBlindBoxe(Request $request){
        $box_id = $request->input('box_id');
        $query = Box::query()->select('id', 'name','image','price','create_time', 'c_id')->where('state',1)->where('id',$box_id)->first();
        if($query == null) {
            return $this->ajax(1, 'failed', 'That box is not existed');
        }
        $suit = Suit::query()->where('box_id',$box_id)->where('is_end',0)->orderBy('id','asc')->first();
        $goods = DB::table('suit_goods')->where('suit_id',$suit->id)->select('num','surplus');
        $surplus = 0;
        $totalNumber = 0;
        for($i = 0; $i < count($goods->get()); $i ++){
            $surplus += $goods->get()[$i]->surplus;
            $totalNumber += $goods->get()[$i]->num;
        }
        return $this->ajax(0,'sucess',[
            'data'=>[
                'id'=>$query->id,
                'price'=>$query->price,
                'image'=>$query->image,
                'name'=>$query->name,
                'releaseTime'=>$query->create_time,
                'blindBoxesType'=>$query->c_id,
                'surplus'=>$surplus,
                'total_number'=>$totalNumber,
            ],
        ]);
    }

    //NewAPI4
    public function blindBoxeDetailList(Request $request){
        $id = $request->input('id');
        $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
        if($suit == null){
            return $this->ajax(1, 'failed', 'That box is not existed');
        }
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.goods_id', 's.num','s.surplus','s.level','g.name','s.create_time','g.image','g.price','g.content')->where('s.suit_id',$suit->id);
        $result = array();
        for($i = 0; $i < count($goods->get()); $i ++){
            $result[$i]['id'] = $goods->get()[$i]->goods_id;
            $result[$i]['price'] = $goods->get()[$i]->price;
            $result[$i]['img'] = $goods->get()[$i]->image;
            $result[$i]['name'] = $goods->get()[$i]->name;
            $result[$i]['releaseTime'] = $goods->get()[$i]->create_time;
            $result[$i]['surplus'] = $goods->get()[$i]->surplus;
            $result[$i]['totalNumber'] = $goods->get()[$i]->num;
            $result[$i]['description'] = $goods->get()[$i]->content;
            $result[$i]['level'] = $goods->get()[$i]->level;
        }
        return $this->ajax(0,'success', $result);
    }

    //NewAPI5 -
    public function blindBoxeRankingList(Request $request){
        $pageNumber = $request->input('page',1);
        $pageSize = $request->input('pageSize');
        $box_id = $request->input('box_id');
        $user_idQuery = DB::table('user_box')->where('box_id', $box_id)->select('uid')->paginate($pageSize);
        // if(!($user_idQuery->count())){return('No user played in this box');}
        if(!($user_idQuery->count())){return $this->ajax(1,'failed', "No user played in this box");}
        $data = [];
        $i = 0;
        foreach ($user_idQuery as &$item){
            $query = User::query()->where('id',$item->uid)->select('nickname', 'avatar')->first();
            $data[$i]['id'] = $item->uid;
            $data[$i]['name'] = $query->nickname;
            $data[$i]['img'] = $query->avatar;
            $data[$i]['rank'] = DB::table('user_box')->where('uid', $item->uid)->select('reward_rank')->first()->reward_rank;
            $i ++;         
            $sorts = array_column($data,'rank');
            array_multisort($sorts, SORT_DESC, $data);
        }
        return $this->ajax(0,'success', $data);
    }

    //NewAPI6 -
    public function blindBoxeRecordList(Request $request){
        $pageNumber = $request->input('page',1);
        $pageSize = $request->input('pageSize');
        $box_id = $request->input('box_id');
        $query = DB::table('user_nft')->where('box_id', $box_id)->select('uid', 'NFTname', 'level', 'time')->paginate($pageSize);
        if(!($query->count())){return $this->ajax(1,'failed', "No user played in this box");}
        $data = [];
        $i = 0;
        foreach ($query as &$item){
            $queryUser = User::query()->where('id',$item->uid)->select('nickname', 'avatar')->first();
            $data[$i]['id'] = $item->uid;
            $data[$i]['name'] = $queryUser->nickname;
            $data[$i]['img'] = $queryUser->avatar;
            $data[$i]['nftName'] = $item->NFTname;
            $data[$i]['level'] = $item->level;
            $data[$i]['time'] = $item->time;
            $i ++;         
        }
        return $this->ajax(0,'success',$data);
    }

    //NewAPI7
    public function getChangeBoxList(Request $request){
        $id = $request->input('id');
        $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
        if($suit != null){
            $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','g.name', 'g.id')->where('s.suit_id',$suit->id);
            $surplus = 0;
            $result = array();
            for($i = 0; $i < count($goods->get()); $i ++){
                $result[$i]['id'] = $goods->get()[$i]->id;
                $result[$i]['name'] = $goods->get()[$i]->name;
                $result[$i]['surplus'] = $goods->get()[$i]->surplus;
                $surplus += $result[$i]['surplus'];
                $result[$i]['total'] = $goods->get()[$i]->num;
            }
            $data = [];
            $data[0]['id'] = $id;
            $data[0]['surplus'] = $surplus;
            $data[0]['nftList'] = $result;
            $data[1]= [];
            return $this->ajax(0,'sucess', $data);
        }
        return $this->ajax(1,'failed', 'That box is not exsted.');
    }

    //NewAPI8
    public function buyNFT(Request $request) {
        $id = $request->input('id');
        $accessToken = $request->input('accessToken');
        $buyNumber = $request->input('buyNumber');
        $query = Box::query()->where('id',$id)->select('c_id', 'price')->first();
        if($query == null){
            return $this->ajax(1, 'failed', 'That box is not existed');
        }
        $c_id = $query->c_id;
        $price = $query->price;
        $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
        $user_id = User::query()->where('token',$accessToken)->select('id')->first()->id;
        //get goods in selected box
        $goods = DB::table('suit_goods as s')->select('s.num','s.surplus', 's.sales', 's.ratio','s.goods_id','s.id')->where('s.suit_id',$suit->id);
        $calcAmount = 0;
        switch($c_id){
            case 1: 
                for($j = 0; $j < $buyNumber; $j++){
                    // get goodsId, suitid according to random number
                    $randomNumber = rand(1,100000);
                    for($i = 0; $i < count($goods->get()); $i ++){
                        $calcAmount += $goods->get()[$i]->ratio * 1000;
                        if($randomNumber > $calcAmount) continue;
                        else{
                            $suitid = $goods->get()[$i]->id;
                            $getGoodsId = $goods->get()[$i]->goods_id;
                            $getCurrentSurplus = $goods->get()[$i]->surplus;
                            $getCurrentSales = $goods->get()[$i]->sales;
                            break;
                        }
                    }

                    //pay to get reward
                    $payResult = $this->payAmount($accessToken, $user_id, $price);
                    if($payResult['hash']){
                        $contract_address = Goods::query()->where('id',$getGoodsId)->select('contract_address')->first()->contract_address;
                        $reward_type = Goods::query()->where('id',$getGoodsId)->select('reward_type')->first()->reward_type;
                        $selling_price = Goods::query()->where('id',$getGoodsId)->select('price')->first()->price;
                        //var_dump("reward type:",$reward_type);

                        // get tokenId
                        if($reward_type == 0){
                            $query_surplusNFTidArray = DB::table('suit_goods')->where('id', $suitid)->select('surplusNFTidArray')->first()->surplusNFTidArray;
                            $surplusNFTidArray = json_decode($query_surplusNFTidArray, true);
                            $tokenId = array_shift($surplusNFTidArray);
                        }
                        else{
                            $tokenId = 1; //This is for not NFT reward, it can be any value;
                        }
                        //get reward 
                        $params1=['user_id'=>$user_id, 'type'=>$reward_type, 'amount'=>$selling_price, 'contract_address'=>$contract_address, 'token_id'=>$tokenId];
                        $ch = curl_init('https://app.gamifly.co:3001/api/sendBlindReward');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $params1);
                        $res = curl_exec($ch);
                        curl_close($ch);
                        $result = json_decode($res, true);

                        if($result['result'] == "success"){
                            //update surplus
                            if($getCurrentSurplus > 0){
                                $sales = $getCurrentSales + 1;
                                $surplus = $getCurrentSurplus - 1;
                                DB::table('suit_goods')->where('id', $suitid)->update(['sales' => $sales, 'surplus' => $surplus]);
                                if($surplus == 0){   
                                    DB::table('box')->where('id', $id)->update(['is_del' => 1]);
                                    DB::table('suit')->where('box_id', $id)->update(['is_end' => 1]);
                                    return $this->ajax(1,'failed',"Box is ended!");
                                } 
                            } else {
                                DB::table('box')->where('id', $id)->update(['is_del' => 1]);
                                DB::table('suit')->where('box_id', $id)->update(['is_end' => 1]);
                                return $this->ajax(1,'failed',"Box is ended!");
                            }
                            //assign tokenid to user
                            if($reward_type == 0){
                                SuitGoods::where('id', $suitid)->update(['surplusNFTidArray'=>$surplusNFTidArray]); 
                                $is_user = DB::table('user_nft')->where('uid', $user_id)->select('uid')->count();
                                if($is_user == 0){
                                    // For record API
                                    $NFTname = Goods::query()->where('id', $getGoodsId)->select('name')->first()->name;
                                    $time = time();
                                    $level = SuitGoods::query()->where('id', $suitid)->select('level')->first()->level;
                                    $token_idArray = [];
                                    array_push($token_idArray, $tokenId);
                                    $data_array = [
                                        'uid'=>$user_id,
                                        'box_id'=>$id,
                                        'goods_id'=>$getGoodsId,
                                        'NFTname'=>$NFTname,
                                        'level'=>$level,
                                        'time'=>$time,
                                        'token_id'=>json_encode($token_idArray)
                                    ];
                                    DB::table('user_nft')->insert($data_array);
                                }
                                else{
                                    $current_tokenidArray = DB::table('user_nft')->where('uid', $user_id)->where('box_id', $id)->select('token_id')->first()->token_id;
                                    $str_array = json_decode($current_tokenidArray, true);
                                    array_push($str_array, $tokenId);
                                    DB::table('user_nft')->where('uid', $user_id)->where('box_id', $id)->update(['token_id' => json_encode($str_array)]);
                                }
                            }// For rank API
                            else{
                                $is_user = DB::table('user_box')->where('uid', $user_id)->select('reward_rank')->first();
                                if($is_user != null){
                                    //get current rank amount
                                    $getCurrentrank = DB::table('user_box')->where('uid', $user_id)->select('reward_rank')->first()->reward_rank;
                                    $updatedrank = $getCurrentrank + 1;
                                    DB::table('user_box')->where('uid', $user_id)->update(['reward_rank' => $updatedrank]);
                                }
                                else{
                                    $data_array = [
                                        'uid'=>$user_id,
                                        'box_id'=>$id,
                                        'reward_rank'=>1
                                    ];
                                    DB::table('user_box')->insert($data_array);
                                }
                            }
                            return $this->ajax(0,'success', $result['result']);
                        }
                        return $this->ajax(1,'failed', $result['result']);
                    }
                    else{
                        return $this->ajax(1,'failed', $payResult['result']);
                    }
                }
                break;
            case 2: 
                $data = [
                    'box_id'=>$id,
                    'uid'=>$user_id,
                    'buyNumber'=>$buyNumber
                ];
                DB::table('user_bid')->insert($data);
                $bidder_count = DB::table('user_bid')->where('box_id', $id)->select('uid')->count();
                if($bidder_count == 10){
                    $userBid = DB::table('user_bid')->where('box_id',$id)->select('uid','buyNumber');
                    for($i = 0; $i < 10; $i ++){
                        $user_id = $userBid->get()[$i]->uid;
                        $buyNumber = $userBid->get()[$i]->buyNumber;
                        for($j = 0; $j < $buyNumber; $j++){
                            // get goodsId, suitid according to random number
                            $randomNumber = rand(1,100000);
                            for($i = 0; $i < count($goods->get()); $i ++){
                                $calcAmount += $goods->get()[$i]->ratio * 1000;
                                if($randomNumber > $calcAmount) continue;
                                else{
                                    $suitid = $goods->get()[$i]->id;
                                    $getGoodsId = $goods->get()[$i]->goods_id;
                                    $getCurrentSurplus = $goods->get()[$i]->surplus;
                                    $getCurrentSales = $goods->get()[$i]->sales;
                                    break;
                                }
                            }
        
                            //pay to get reward
                            $payResult = $this->payAmount($accessToken, $user_id, $price);
                            if($payResult['hash']){
                                $contract_address = Goods::query()->where('id',$getGoodsId)->select('contract_address')->first()->contract_address;
                                $reward_type = Goods::query()->where('id',$getGoodsId)->select('reward_type')->first()->reward_type;
                                $selling_price = Goods::query()->where('id',$getGoodsId)->select('price')->first()->price;
                                //var_dump("reward type:",$reward_type);
        
                                // get tokenId
                                if($reward_type == 0){
                                    $query_surplusNFTidArray = DB::table('suit_goods')->where('id', $suitid)->select('surplusNFTidArray')->first()->surplusNFTidArray;
                                    $surplusNFTidArray = json_decode($query_surplusNFTidArray, true);
                                    $tokenId = array_shift($surplusNFTidArray);
                                }
                                else{
                                    $tokenId = 1; //This is for not NFT reward, it can be any value;
                                }
                                //get reward 
                                $params1=['user_id'=>$user_id, 'type'=>$reward_type, 'amount'=>$selling_price, 'contract_address'=>$contract_address, 'token_id'=>$tokenId];
                                $ch = curl_init('https://app.gamifly.co:3001/api/sendBlindReward');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $params1);
                                $res = curl_exec($ch);
                                curl_close($ch);
                                $result = json_decode($res, true);
        
                                if($result['result'] == "success"){
                                    //update surplus
                                    if($getCurrentSurplus > 0){
                                        $sales = $getCurrentSales + 1;
                                        $surplus = $getCurrentSurplus - 1;
                                        DB::table('suit_goods')->where('id', $suitid)->update(['sales' => $sales, 'surplus' => $surplus]);
                                        if($surplus == 0){   
                                            DB::table('box')->where('id', $id)->update(['is_del' => 1]);
                                            DB::table('suit')->where('box_id', $id)->update(['is_end' => 1]);
                                            return $this->ajax(1,'failed',"Box is ended!");
                                        } 
                                    } else {
                                        DB::table('box')->where('id', $id)->update(['is_del' => 1]);
                                        DB::table('suit')->where('box_id', $id)->update(['is_end' => 1]);
                                        return $this->ajax(1,'failed',"Box is ended!");
                                    }
                                    //assign tokenid to user
                                    if($reward_type == 0){
                                        SuitGoods::where('id', $suitid)->update(['surplusNFTidArray'=>$surplusNFTidArray]); 
                                        $is_user = DB::table('user_nft')->where('uid', $user_id)->select('uid')->count();
                                        if($is_user == 0){
                                            // For record API
                                            $NFTname = Goods::query()->where('id', $getGoodsId)->select('name')->first()->name;
                                            $time = time();
                                            $level = SuitGoods::query()->where('id', $suitid)->select('level')->first()->level;
                                            $token_idArray = [];
                                            array_push($token_idArray, $tokenId);
                                            $data_array = [
                                                'uid'=>$user_id,
                                                'box_id'=>$id,
                                                'goods_id'=>$getGoodsId,
                                                'NFTname'=>$NFTname,
                                                'level'=>$level,
                                                'time'=>$time,
                                                'token_id'=>json_encode($token_idArray)
                                            ];
                                            DB::table('user_nft')->insert($data_array);
                                        }
                                        else{
                                            $current_tokenidArray = DB::table('user_nft')->where('uid', $user_id)->where('box_id', $id)->select('token_id')->first()->token_id;
                                            $str_array = json_decode($current_tokenidArray, true);
                                            array_push($str_array, $tokenId);
                                            DB::table('user_nft')->where('uid', $user_id)->where('box_id', $id)->update(['token_id' => json_encode($str_array)]);
                                        }
                                    }// For rank API
                                    else{
                                        $is_user = DB::table('user_box')->where('uid', $user_id)->select('reward_rank')->first();
                                        if($is_user != null){
                                            //get current rank amount
                                            $getCurrentrank = DB::table('user_box')->where('uid', $user_id)->select('reward_rank')->first()->reward_rank;
                                            $updatedrank = $getCurrentrank + 1;
                                            DB::table('user_box')->where('uid', $user_id)->update(['reward_rank' => $updatedrank]);
                                        }
                                        else{
                                            $data_array = [
                                                'uid'=>$user_id,
                                                'box_id'=>$id,
                                                'reward_rank'=>1
                                            ];
                                            DB::table('user_box')->insert($data_array);
                                        }
                                    }
                                    return $this->ajax(0,'sucess', $result['result']);
                                }
                                return $this->ajax(1,'failed', $result['result']);
                            }
                            else{
                                return $this->ajax(1,'failed', $payResult['result']);
                            }
                        }
                    }
                    DB::table('user_bid')->where('box_id', $id)->delete();
                    return $this->ajax(0,'success', null);
                }
                return $this->ajax(1,'failed', 'Bidders should be up to 10');
                break;
            default: break;
        }
    }

    //NewAPI9
    public function getUserInfo(Request $request) {
        $accessToken = $request->input('accessToken');
        $query = User::query()->where('token', $accessToken)->select('id', 'balance')->first();
        $gamiflyToken = $query->balance;
        $user_id = $query->id;
        $transactions = DB::table('user_nft')->where('uid', $user_id)->get()->count();
        return $this->ajax(0,'sucess',[
            'myBox'=>'',
            'gamiflyToken'=>$gamiflyToken,
            'transactions'=>$transactions
        ]);
    }

    //NewAPI10
    public function getUserTransactions(Request $request) {
        $page = $request->input('page');
        $limit = $request->input('pageSize');
        $accessToken = $request->input('accessToken');
        $user_id = User::query()->where('token', $accessToken)->select('id')->first()->id;
        $query = DB::table('user_nft')->where('uid', $user_id)->paginate($limit);
        $result = array();
        for($i = 0; $i < count($query->items()); $i ++){
            $queryGoods = DB::table('goods')->where('id', $query->items()[$i]->goods_id)->select('price', 'content')->first();
            $result[$i]['id'] = $query->items()[$i]->goods_id;
            $result[$i]['price'] = $queryGoods->price;
            $result[$i]['time'] = $query->items()[$i]->time;
            $result[$i]['description'] = $queryGoods->content;
        }
        return $this->ajax(0,'success', $result);
    }

    //NewAPI12
    public function getUserReturnList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('pageSize',2);
        $accessToken = $request->input('accessToken');
        $user_id = User::query()->where('token',$accessToken)->select('id')->first()->id;
        $query = DB::table('nft_returnList')->where('uid', $user_id)->select('goods_id', 'price', 'img', 'name', 'returnTime', 'quantity', 'integral', 'description', 'level')->paginate($limit);
        return $this->ajax(0,'success', $query->items());
    }

    //NewAPI13
    public function getUserTransactionsList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('pageSize',2);
        $accessToken = $request->input('accessToken');
        $user_address = User::query()->where('token', $accessToken)->select('external_wallet_address')->first()->external_wallet_address;
        $query = DB::table('nft_withdrawList')->where('address', $user_address)->select('goods_id', 'img', 'name', 'address')->paginate($limit);
        return $this->ajax(0,'success', $query->items());
    }

    //NewAPI14
    public function returnNFTs(Request $request) {
        $goodsIdArray = json_decode($request->input('ids'),true);
        $accessToken = $request->input('accessToken');
        $user_id = User::query()->where('token',$accessToken)->select('id')->first()->id;
        for($i = 0; $i < count($goodsIdArray); $i ++){
            $goods_id = $goodsIdArray[$i];

            $suit_id = SuitGoods::query()->where('goods_id', $goods_id)->select('suit_id')->first()->suit_id;
            $id = Suit::query()->where('id', $suit_id)->select('box_id')->first()->box_id;
            $box_price = Box::query()->where('id',$id)->select('price')->first()->price;
            $price = $box_price * 0.7;

            $contractAddress = Goods::query()->where('id',$goods_id)->select('contract_address')->first()->contract_address;
            $quantity = array_count_values($goodsIdArray)[$goods_id];

            //get NFT id
            $query = DB::table('user_nft')->where('uid', $user_id)->where('goods_id', $goods_id)->select('token_id')->first()->token_id;
            $query_array = json_decode($query, true);
            $idToreturn = array_shift($query_array);

            //return NFT and refund money to user's balance
            $params=['user_id'=>$user_id, 'contract_address'=>$contractAddress, 'token_id'=>$idToreturn, 'refund_amount'=>$price];
            $ch = curl_init('https://app.gamifly.co:3001/api/returnblindNFT');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $response = curl_exec($ch);
            curl_close($ch);
            $res_array = json_decode($response, true);

            if($res_array['hash']){
                DB::table('user_nft')->where('uid', $user_id)->where('goods_id', $goods_id)->update(['token_id'=>json_encode($query_array)]);
                $surplusNFTidArray = SuitGoods::where('goods_id', $goods_id)->select('surplusNFTidArray')->first()->surplusNFTidArray;
                $surplusNFT = json_decode($surplusNFTidArray, true);
                array_push($surplusNFT, $idToreturn);
                SuitGoods::where('goods_id', $goods_id)->update(['surplusNFTidArray'=>json_encode($surplusNFT)]);

                //remove 1 sales  add 1 surplus
                $querySales = DB::table('suit_goods')->where('goods_id', $goods_id)->select('sales', 'surplus')->first();
                $getCurrentSales = $querySales->sales;
                $getCurrentSurplus = $querySales->surplus;
                if($getCurrentSales > 0){
                    $sales = $getCurrentSales - 1;
                    $surplus = $getCurrentSurplus + 1;
                    SuitGoods::where('goods_id', $goods_id)->update(['sales' => $sales, 'surplus' => $surplus]);
                } 

                //update user's balance in db
                $getCurrentBalance = User::query()->where('id', $user_id)->select('balance')->first()->balance;
                $updatedBalance = $getCurrentBalance + $price;
                DB::table('user')->where('id', $user_id)->update(['balance' => $updatedBalance]);

                //get returnNFT list
                $listQuery = Goods::query()->where('id',$goods_id)->select('image', 'name', 'content')->first();
                $returnTime = time();
                $level = SuitGoods::query()->where('goods_id',$goods_id)->select('level')->first()->level;
                $data = [
                    'goods_id'=>$goods_id,
                    'price'=>$price,
                    'img'=>$listQuery->image,
                    'name'=>$listQuery->name,
                    'returnTime'=>$returnTime,
                    'quantity'=>$quantity,
                    'integral'=>$quantity*($price),
                    'description'=>$listQuery->content,
                    'level'=>$level,
                    'uid'=>$user_id
                ];
                DB::table('nft_returnList')->insert($data);
                
                return $this->ajax(0,'success', $data = $res_array['hash']);
            }
            return $this->ajax(1,'failed', $data = $res_array['result']);
        }
    }

    //NewAPI15
    public function withdrawalNFTs(Request $request) {
        $goodsIdArray = json_decode($request->input('ids'),true);
        $accessToken = $request->input('accessToken');
        $user_id = User::query()->where('token',$accessToken)->select('id')->first()->id;
        for($i = 0; $i < count($goodsIdArray); $i ++){
            $goods_id = $goodsIdArray[$i];
            $query = DB::table('user_nft')->where('uid', $user_id)->where('goods_id', $goods_id)->select('token_id')->first();
            $boughtNFTidArray = json_decode($query->token_id, true);
            if(count($boughtNFTidArray) != 0){
                $idTowithdraw = array_shift($boughtNFTidArray);
            }
            $contractAddress = Goods::query()->where('id',$goods_id)->select('contract_address')->first()->contract_address;
            //withdraw NFT
            $params=['user_id'=>$user_id, 'token_id'=>$idTowithdraw,'contract_address'=>$contractAddress];
            $ch = curl_init('https://app.gamifly.co:3001/api/withdrawblindNFT');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $response = curl_exec($ch);
            curl_close($ch);
            $res_array = json_decode($response, true);
            if($res_array['hash']){
                //update token_id array in user_nft table
                DB::table('user_nft')->where('uid', $user_id)->where('goods_id', $goods_id)->update(['token_id'=>json_encode($boughtNFTidArray)]);

                //get withdrawNFT list
                $listQuery = Goods::query()->where('id',$goods_id)->select('image', 'name')->first();
                $user_address = User::query()->where('id', $user_id)->select('external_wallet_address')->first()->external_wallet_address;
                $data = [
                    'goods_id'=>$goods_id,
                    'img'=>$listQuery->image,
                    'name'=>$listQuery->name,
                    'address'=>$user_address
                ];
                DB::table('nft_withdrawList')->insert($data);
                return $this->ajax(0,'success', $data = $res_array['hash']);
            }
            return $this->ajax(1,'failed', $data = $res_array['result']);
        }
    }

    //NewAPI16
    public function getAllNFTs(Request $request) {
        $accessToken = $request->input('accessToken');
        $user_id = User::query()->where('token',$accessToken)->select('id')->first()->id;
        $query = DB::table('user_nft')->where('uid', $user_id)->select('goods_id', 'NFTname', 'token_id')->first();
        // $goods_id = $query->goods_id;
        // $img = Goods::query()->where('id', $goods_id)->select('image')->first()->image;
        // $NFTname = $query->NFTname;
        // $tokenIdarray = $query->token_id;
        // $quantity = count(json_decode($tokenIdarray, true));
        // return $this->ajax(0,'success', $data = [
        //     'id'=>$goods_id,
        //     'img'=>$img,
        //     'name'=>$NFTname,
        //     'quantity'=>$quantity,
        //     'returnPrice'=> '30%'
        // ]);
        $result = array();
        for($i = 0; $i < count($query->items()); $i ++){
            $goods_id = $$query->items()[$i]->goods_id;
            $img = Goods::query()->where('id', $goods_id)->select('image')->first()->image;
            $NFTname = $$query->items()[$i]->NFTname;
            $tokenIdarray = $$query->items()[$i]->token_id;
            $quantity = count(json_decode($tokenIdarray, true));

            $result[$i]['id'] = $goods_id;
            $result[$i]['img'] = $img;
            $result[$i]['name'] = $NFTname;
            $result[$i]['quantity'] = $quantity;
            $result[$i]['returnPrice'] = '30%';
        }
        return $this->ajax(0, 'success', $result);
    }

    //NewAPI17
    public function getRewardTransaction(Request $request) {
        $accessToken = $request->input('accessToken');
        $user_id = User::query()->where('token',$accessToken)->select('id')->first()->id;
        $params1=['user_id'=>$user_id];
        $ch = curl_init('https://app.gamifly.co:3001/api/getblindRewardTransaction');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params1);
        $res = curl_exec($ch); 
        curl_close($ch);
        $result = json_decode($res, true);
        return $this->ajax(0, 'success', $result);
    }

    public function payAmount($accessToken, $user_id, $price){
        $params=['accessToken'=>$accessToken, 'user_id'=>$user_id, 'reason'=>'buyNFT', 'amount'=>$price];
        $ch = curl_init('https://app.gamifly.co:3001/api/decrease');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        curl_close($ch);
        $getCurrentBalance = User::query()->where('id', $user_id)->select('balance')->first()->balance;
        if($getCurrentBalance > $price){
            $updatedBalance = $getCurrentBalance - $price;
            DB::table('user')->where('id', $user_id)->update(['balance' => $updatedBalance]);
        }
        return(json_decode($response, true));
    }

    public function ad(Request $request){
        $type = $request->input('type',1);
        $ad = Ad::query()->where('type',$type)->select('image','url')->orderBy('id','asc')->get();
        return $this->ajax(1,'请求成功',$ad);
    }

    //盲盒类型
    public function category(Request $request){
        $type = $request->input('type',1);
        $category = Category::query()->where('type',$type)->where('is_del',0)->select('id','name')->get()->toArray();
        if($type == 2){
            array_unshift($category,['id'=>0,'name'=>'推荐']);
        }
        return $this->ajax(1,'请求成功',$category);
    }

    //盲盒等级
    public function level(){
        $level = Level::query()->select('name','level')->orderBy('sort','asc')->get();
        return $this->ajax(1,'请求成功',$level);
    }

    //盲盒列表
    public function boxList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',2);
        $c_id = $request->input('c_id',1);
        $name = $request->input('name');

        $query = Box::query()->select('id','name','image','cover_image','price','type','create_time')
            ->where('state',1)->where('is_del',0)->where('c_id',$c_id);
        if($name){
            $query = $query->where('name','like','%'.$name.'%');
        }
        $query = $query->whereIn('type',[1,2])->orderBy('sort','asc')->paginate($limit);
        foreach ($query as &$item){
            $item->total = Suit::query()->where('box_id',$item->id)->count();
            $item->surplus = Suit::query()->where('box_id',$item->id)->where('is_end',0)->count();
        }
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //盲盒详情
    public function boxDetail(Request $request){
        $id = $request->input('id');
        $suit_id = $request->input('suit_id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $box = Box::query()->where('id',$id)->select('id','name','image','cover_image','price','create_time','sale','type')->first();
        if($suit_id){
            $suit = Suit::query()->where('box_id',$id)->where('id',$suit_id)->first();
        }else{
            $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
            if(!$suit){
                $suit = Suit::query()->where('box_id',$id)->orderBy('id','asc')->first();
            }
        }

        $box->suit_id = $suit->id;
        $box->is_free = 0;
        $box->free_first = 0;
        $box->free_end = 0;
        $free = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'Free'])->first();
        if($free){
            $box->is_free = 1;
            $box->free_first = 1;
            $box->free_end = $suit->num * Config::getValue('box_free');
        }
        $box->is_w = 0;
        $box->w_first = 0;
        $box->w_end = 0;
        $w = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'W'])->first();
        if($w){
            $box->is_w = 1;
            $box->w_first = 1;
            $box->w_end = $suit->num;
        }
        $box->is_first = 0;
        $box->first_first = 0;
        $box->first_end = 0;
        $first = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'First'])->first();
        if($first){
            $box->is_first = 1;
            $box->first_first = 1;
            $box->first_end = $suit->num * 0.5;
        }

        $box->is_last = 0;
        $box->last_first = 0;
        $box->last_end = 0;
        $last = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'Last'])->first();
        if($last){
            $box->is_last = 1;
            $box->last_first = $suit->num * 0.5 + 1;
            $box->last_end = $suit->num;
        }

        $box->is_war = 0;
        $box->war_first = 0;
        $box->war_end = 0;
        $war = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'War'])->first();
        if($war){
            $box->is_war = 1;
            $box->war_first = 1;
            $box->war_end = $suit->num;
        }

        $box->total_suit = Suit::query()->where('box_id',$id)->count();
        $box->current_suit = $suit->no_key;

        $box->is_collection = 0;
        $collection = Collection::query()->where(['uid'=>$uid,'box_id'=>$id])->first();
        if($collection){
            $box->is_collection = 1;
        }
        //已购买数量
        $buy_num = OrderGoods::query()->where('uid',$uid)->where('box_id',$id)->where('suit_id',$suit->id)->count();
        $box->buy_num = $buy_num;
        $box->total_num = $suit->num;
        $box->total_surplus = $suit->surplus;
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','s.is_special','g.name','g.image','g.price','g.is_book')->where('s.suit_id',$suit->id)->get()->each(function($item) use ($suit){
                $item->level_name = DB::table('level')->where('level',$item->level)->value('name');
                if($item->is_special == 0 && $suit->surplus > 0){
                    $ratio = bcdiv($item->surplus,$suit->surplus,5);
                    $item->ratio = sprintf('%.2f',bcmul($ratio,100,3)); 
                }else{
                    $item->ratio = 0;
                }
            });
        $box->goods = $goods;
        return $this->ajax(1,'请求成功',$box);
    }

    //当前箱子余量
    public function suitNum(Request $request){
        $suit_id = $request->input('suit_id');
        if(empty($suit_id)) return $this->ajax(0,'参数错误');
        $total = DB::table('suit')->where('id',$suit_id)->value('surplus');
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','s.is_special','g.name')->where('s.suit_id',$suit_id)->get()->each(function($item) use ($total){
                $item->level_name = DB::table('level')->where('level',$item->level)->value('name');
                if($item->is_special == 0 && $total > 0){
                    $ratio = bcdiv($item->surplus,$total,5);
                    $item->ratio = sprintf('%.2f',bcmul($ratio,100,3));
                }else{
                    $item->ratio = 0;
                }
            });
        return $this->ajax(1,'请求成功',$goods);
    }

    //当前箱子记录
    public function boxGoods(Request $request){
        $box_id = $request->input('box_id');
        $suit_id = $request->input('suit_id');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $number = $request->input('number');
        $query = OrderGoods::query()->from('order_goods as o')
            ->leftJoin('user as u','o.uid','=','u.id')
            ->select('u.nickname','u.avatar','o.level','o.level_name','o.is_special','o.number','o.name','o.create_time')
            ->where('o.box_id',$box_id)->where('o.suit_id',$suit_id);
        if($number){
            $query = $query->where('o.number','<=',$number);
        }
        $query = $query->orderBy('o.number','desc')->paginate($limit);
        $suit = Suit::query()->find($suit_id);
        if(!$number){
            if($suit->is_end == 1){
                $number = OrderGoods::query()->where('box_id',$box_id)->where('suit_id',$suit_id)->orderBy('number','desc')->value('number');
            }else{
                $number = OrderGoods::query()->where('box_id',$box_id)->where('suit_id',$suit_id)->where('is_special',0)->orderBy('number','desc')->value('number');
            }
        }
        return $this->ajax(1,'请求成功',[
            'number'=>$number,
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //换箱-箱子列表
    public function suit(Request $request){
        $id = $request->input('id');
        if(empty($id)) return $this->ajax(0,'参数错误');
        $suitList = Suit::query()->select('id','is_end','no_key','surplus')->where('box_id',$id)->get()->toArray();
        $data = Common::arrSplit($suitList,10);
        $result = [];
        foreach ($data as $key=>$value){
            $first = reset($value);
            $end = end($value);
            $result[$key]['first_key'] = $first['no_key'];
            $result[$key]['end_key'] = $end['no_key'];
            $result[$key]['first_id'] = $first['id'];
            $result[$key]['end_id'] = $end['id'];
        }
        foreach ($result as $k=>$v){
            $suit = Suit::query()->where('box_id',$id)->whereBetween('id',[$v['first_id'],$v['end_id']])->select('id','no_key','is_end','surplus')->get()->each(function ($item){
                $goods = SuitGoods::query()->where('suit_id',$item->id)->select('num','surplus','level')->get();
                foreach ($goods as &$map){
                    $map->level_name = Level::query()->where('level',$map->level)->value('name');
                }
                $item->goods = $goods;
            });
            $result[$k]['suit'] = $suit;
        }
        return $this->ajax(1,'请求成功',$result);
    }

    //换箱子-选择箱子
    public function choiceSuit(Request $request){
        $id = $request->input('id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $suit = Suit::query()->where('id',$id)->first();
        //已购买数量
        $buy_num = OrderGoods::query()->where('uid',$uid)->where('box_id',$suit->box_id)->where('suit_id',$id)->count();
        $data['suit_id'] = $suit->id;
        $data['current_suit'] = $suit->no_key;
        $data['total_num'] = $suit->num;
        $data['total_surplus'] = $suit->surplus;
        $data['buy_num'] = $buy_num;
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','s.is_special','g.name','g.image','g.price','g.is_book')->where('s.suit_id',$suit->id)->get()->each(function($item) use ($suit){
                $item->level_name = DB::table('level')->where('level',$item->level)->value('name');
                if($item->is_special == 0 && $item->surplus > 0){
                    $ratio = bcdiv($item->surplus,$suit->surplus,5);
                    $item->ratio = sprintf('%.2f',bcmul($ratio,100,3));
                }else{
                    $item->ratio = 0;
                }
            });
        $data['goods'] = $goods;
        return $this->ajax(1,'请求成功',$data);
    }

    //无限赏列表
    public function goodsList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $c_id = $request->input('c_id',0);
        $query = Box::query()->select('id','name','image','cover_image','price')->where(['type'=>3,'is_del'=>0,'state'=>1]);
        if($c_id){
            $query = $query->where('c_id',$c_id);
        }
        $query = $query->orderBy('sort','asc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //无限赏详情
    public function goodsDetail(Request $request){
        $id = $request->input('id');
        $token = $request->input('token');
        $uid = 0;
        if($token){
            $uid = User::query()->where('token',$token)->value('id');
        }
        $box = Box::query()->where('id',$id)->select('id','name','image','cover_image','price','type','hot')->first();
        $box->is_collection = 0;
        $collection = Collection::query()->where(['uid'=>$uid,'box_id'=>$id])->first();
        if($collection){
            $box->is_collection = 1;
        }
        $level = BoxLevel::query()->where('box_id',$id)->select('level','ratio')->get();
        $goods = Prize::query()->from('prize as p')->leftJoin('goods as g','p.goods_id','=','g.id')
            ->leftJoin('level as l','p.level','=','l.level')
            ->select('p.ratio','p.level','p.goods_id','g.name','g.image','g.price','g.is_book')->where('p.box_id',$id)
            ->orderBy('l.sort','asc')->orderBy('p.goods_id','asc')->get()->each(function ($item) use ($id){
                $item->ratio = BoxLevel::query()->where('box_id',$id)->where('level',$item->level)->value('ratio');
            });
        return $this->ajax(1,'请求成功',['box'=>$box,'level'=>$level,'goods'=>$goods]);
    }

    //无限赏-中奖记录
    public function awardList(Request $request){
        $box_id = $request->input('box_id');
        if(empty($box_id)) return $this->ajax(0,'参数错误');
        $lastLevel = Prize::query()->where('box_id',$box_id)->orderBy('id','desc')->value('level');
        $query = OrderGoods::query()->from('order_goods as o')->leftJoin('level as l','o.level','=','l.level')
            ->where('o.box_id',$box_id)->select('o.level','l.sort')->distinct('o.level')->orderBy('l.sort','asc')->get();
        foreach ($query as &$item){
            $item->is_end = 0;
            if($item->level == $lastLevel){
                $item->is_end = 1;
            }
            $item->goods = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
                ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
                ->where('o.box_id',$box_id)->where('o.level',$item->level)->orderBy('o.number','desc')->first();
            $item->state = 0;
        }
        return $this->ajax(1,'请求成功',$query);
    }

    //无限赏-中奖记录-展开
    public function awardListLog(Request $request){
        $box_id = $request->input('box_id');
        $level = $request->input('level');
        $goods = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
            ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
            ->where('o.box_id',$box_id)->where('o.level',$level)->orderBy('o.number','desc')->skip(1)->take(49)->get();
        return $this->ajax(1,'请求成功',$goods);
    }

    //无限赏-中奖记录-昵称搜索
    public function awardNickList(Request $request){
        $box_id = $request->input('box_id');
        $nickname = $request->input('nickname');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        if(empty($box_id)) return $this->ajax(0,'参数错误');
        $query = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
                ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
                ->where('o.box_id',$box_id)->where('u.nickname','like','%'.$nickname.'%')->orderBy('o.number','desc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //无限赏-中奖记录-赏品搜索
    public function awardNameList(Request $request){
        $box_id = $request->input('box_id');
        $name = $request->input('name');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        if(empty($box_id)) return $this->ajax(0,'参数错误');
        $query = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
            ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
            ->where('o.box_id',$box_id)->where('o.name','like','%'.$name.'%')->orderBy('o.number','desc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //扭蛋机列表
    public function eggList(Request $request){
        $limit = $request->input('limit',10);
        $page = $request->input('page',1);
        $query = DB::table('box')->select('id','name','image','cover_image','price')->where('type',4)->where('is_del',0)->where('state',1)
            ->orderBy('sort','asc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //扭蛋机详情
    public function eggDetail(Request $request){
        $id = $request->input('id');
        $box = DB::table('box')->where('id',$id)->select('id','name','image','cover_image','price')->first();
        $box->special = [];
        $special = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
            ->where('o.box_id',$id)->where('o.is_special',1)->select('u.id','u.nickname','u.avatar')->first();
        if($special){
            $box->special = $special;
        }
        $box->goods = Egg::query()->from('egg as e')->leftJoin('goods as g','e.goods_id','=','g.id')
            ->select('e.is_special','e.goods_id','g.name','g.image','g.price','g.is_book','g.content')->where('e.box_id',$id)
            ->orderBy('e.is_special','desc')->orderBy('e.goods_id','asc')->get();
        return $this->ajax(1,'请求成功',$box);
    }
}
