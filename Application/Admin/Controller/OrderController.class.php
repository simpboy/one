<?php
/**
 * 店商管理
 */
namespace Admin\Controller;
use Common\Controller;

class OrderController extends AdminController {
	// 0-未发货，1-待发货，2-已发货，3-已收货
	protected $express_state_map=[
			0	=> '未发货',
			1	=> '待发货',
			2	=> '已发货',
			3	=> '已收货',
	];
	protected function _initialize(){
		parent::_initialize();
		define('NOT_EXPRESS', 0);
		define('FOR_EXPRESS', 1);
		define('EXPRESSED', 2);
		define('RECEIVE', 3);
	}
    /**
     * 添加订单
     */
    public function view(){
        $order_id       = I('get.id',null,'intval');
        $Order          = M('order','ngc_');
        $Order_goods   	= M('order_goods','ngc_');
        $Address   		= M('address','ngc_');
        $Goods          = M('goods','ngc_');
        if($order_id<=0){
        	$this->error("参数错误！");
        }

        $order_info		= $Order->where(['order_id'=>$order_id])->find();
        $order_goods	= $Order_goods->where(['order_id'=>$order_id])->select();
        $address		= $Address->where(['order_id'=>$order_id])->find();

        $goods_id_array = array_column($order_goods,'goods_id');
        $goods          = $Goods->where(['goods_id'=>['in',$goods_id_array]])->select();

        foreach($order_goods as $k=>&$v){
            foreach($goods as $value){
                if($v['goods_id']==$value['goods_id']){
                    $v['goods_info'] = $value;
                }
            }
        }
        $this->assign('id',$order_id);

        $this->assign('order_info',$order_info);
        $this->assign('order_goods',$order_goods);
        $this->assign('address',$address);

        $this->assign('meta_title','查看订单');
        $this->display();
    }

    /**
     * 列表页
     */
    public function index(){
        $Order 			= M('order','ngc_');
        $Order_goods 	= M('order_goods','ngc_');
        $Address 		= M('address','ngc_');
        $where = [
            'status'    => 0
        ];
        $order = 'order_id desc';
//        $list = $this->lists2($Order,$where,$order,$field=true, 'ngc_order_goods og on o.order_id=og.order_id',$alias='o');
        $list = $this->lists($Order,$where,$order);
        foreach($list as $k=>&$v){
        	$address 		= $Address->where()->getField('address');
        	$v['address'] 	= $address;
        }
        $this->assign("_list",$list);
        $this->meta_title = '订单列表';
        $this->assign("title",$this->meta_title);
        $this->display();
    }

    /*
     * 
     */
    public function edit_post(){
    	$order_id = I('post.id',null,'intval');
    	if(empty($order_id)){
    		$this->error("参数错误");
    	}
    	$express_sn	 	= I("post.express_sn",'','trim');
    	$express_name 	= I("post.express_name",'','trim');
    	$update_express_time 	= I("post.update_express_time",0,'intval');
    	
    	$Order          = M('order','ngc_');
    	
    	$data = [
    			'express_sn'	=> $express_sn,
    			'express_name'  => $express_name,
    			'express_state'	=> EXPRESSED
    	];
    	if($update_express_time==1){
    		$data['express_time']	= NOW_TIME;
    	}
    	$res = $Order->where(['order_id'=>$order_id])->save($data);
    	if($res!==false){
    		$this->success("操作成功");
    	}else{
    		$this->error("操作失败");
    	}
    }

}
