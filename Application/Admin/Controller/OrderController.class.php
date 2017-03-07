<?php
/**
 * 店商管理
 */
namespace Admin\Controller;
use Common\Controller;

class OrderController extends AdminController {
    /**
     * 添加订单
     */
    public function view(){
        $order_id       = I('get.id',null,'intval');
        $Order          = M('order','ngc_');
        $Order_goods   	= M('order_goods','ngc_');
        $Address   		= M('address','ngc_');
        $Goods          = M('goods','ngc_');
        
        $order_info		= $Order->where(['order_id'=>$order_id])->find();
        $order_goods	= $Order_goods->where(['order_id'=>$order_id])->select();
        $address		= $Address->where(['order_id'=>$order_id])->find();
        
        var_dump($order_info);
        var_dump($order_goods);
        var_dump($address);
        
        



       
        $this->assign('meta_title','查看订单');
        $this->display();
    }

    /**
     * 列表页
     * lists2 ($model,$where=array(),$order='',$field=true, $join='',$alias='',$group= '',$target='',$page_id='')
     *
     *
     */
    public function index(){
        $Order 			= M('order','ngc_');
        $Order_goods 	= M('order_goods','ngc_');
        $Address 		= M('address','ngc_');
        $where = [
            'o.status'    => 0
        ];
        $order = 'o.order_id desc';
        $list = $this->lists2($Order,$where,$order,$field=true, 'ngc_order_goods og on o.order_id=og.order_id',$alias='o');
        foreach($list as $k=>&$v){
        	$address 		= $Address->where()->getField('address');
        	$v['address'] 	= $address;
        }

        $this->assign("_list",$list);
        $this->meta_title = '订单列表';
        $this->assign("title",$this->meta_title);
        $this->display();
    }

    /**
     * 更改上下线
     */
    public function on_off_online()
    {
        $id = I('get.id');
        $Goods = M('goods','ngc_');
        $online = $Goods->where(['goods_id'=>$id])->getField('online');
        $data = [
            'online' => abs($online)-1
        ];
        $res = $Goods->where(['goods_id'=>$id])->save($data);
        if($res!==false){
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }
    /*
     * 更改下单状态
     */
    public function on_off_state()
    {
        $id = I('get.id');
        $Goods = M('goods','ngc_');
        $state = $Goods->where(['goods_id'=>$id])->getField('state');
        $data = [
            'state' => abs($state)-1
        ];
        $res = $Goods->where(['goods_id'=>$id])->save($data);
        if($res!==false){
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }

    public function del(){
        $id = I('get.id');
        $Goods = M('goods','ngc_');
        $data = [
            'del' => 1
        ];
        $res = $Goods->where(['goods_id'=>$id])->save($data);
        if($res!==false){
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }
    public  function upload_img(){
        $action = I('get.action','','trim');
        $Up     = D('Admin/Up');
        $Up->editor_upload($action);
    }

}
