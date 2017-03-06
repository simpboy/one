<?php
/**
 * 店商管理
 */
namespace Admin\Controller;
use Common\Controller;

class OrderController extends AdminController {
    /**
     * 添加商品
     */
    public function add_page(){
        $goods_id       = I('get.id',null,'intval');
        $Goods          = M('goods','ngc_');
        $Goods_detail   = M('goods_detail','ngc_');
        if(IS_POST){
            //基本字段
            $goods_id       = I('post.id',null,'intval');
            $title          = I("post.title",'','trim');
            $sub_title      = I("post.sub_title",'','trim');
            $price          = I("post.price",0,'trim');
            $cate_id        = I("post.cate_id",0,'intval');
            $postage        = I("post.postage",0,'trim');
            $main_img_old   = I("post.main_img_old",null,'');
            $imgs_old       = I("post.imgs_old",'','trim');
            $stock          = I("post.stock",0,'intval');
            $sell_reserve   = I("post.sell_reserve",0,'intval');
            $online         = I("post.online",0,'intval');
            $state          = I("post.state",0,'intval');

            //详情字段
            $origin         = I("post.origin",'','trim');
            $weight         = I("post.weight",'','trim');
            $detail         = I("post.detail",'','trim');
            $unit           = I("post.unit",'','trim');

//            debug_output($imgs_old,'$imgs_old');
            $Up 			= D('Admin/Up');
            $img_new 		= $Up->save_img();
//            debug_output('aaa','aaa');
//            debug_output($_FILES,'$_FILES');
//            debug_output($img_new,'$img_new');
            $main_img_new   = $img_new['main_img'];

            $main_img = '';
            if(!empty($main_img_new)){
                $domain      = C('DOMAIN')?C('DOMAIN'):'http://admin.one.loca/';
                $main_img    = $domain.$main_img_new;
            }elseif(!empty($main_img_old)){
                $main_img    = $main_img_old;
            }
            $imgs_old = is_array($imgs_old)?$imgs_old:[];
            $imgs_add = !empty($img_new['imgs'])?$img_new['imgs']:[];
            $imgs_add = is_array($imgs_add)?$imgs_add:[$imgs_add];
            $imgs     = array_merge($imgs_old,$imgs_add);

            if(empty($title)||empty($price)||$stock<=0){
                $this->error("标题，价格为空，或者库存不大于0");
            }
            $where = ['title'=>$title,'sub_title'=>$sub_title];
            !empty($goods_id)&&$where['goods_id'] = array('neq',$goods_id);
            $count = $Goods->where($where)->count();
            if($count>0){
                $this->error("商品标题+商品副标题重复");
            }
            $Goods->startTrans();
            $data = [
                'title'             => $title,
                'sub_title'         => $sub_title,
                'price'             => $price,
                'postage'           => $postage,
                'main_img'          => $main_img,
                'imgs'              => json_encode($imgs),
                'sell_reserve'      => $sell_reserve,
                'online'            => $online,
                'state'             => $state,
                'stock'             => $stock,
                'cate_id'           => $cate_id,
                'update_time'       => NOW_TIME
            ];
            if(empty($goods_id)){
                $data['create_time']    = NOW_TIME;
                $res                    = $Goods->add($data);
                $goods_id               = $res;
            }else{
                $res                    = $Goods->where(['goods_id'=>$goods_id])->save($data);
            }


            $detail_data = [
                'origin'    => $origin,
                'weight'    => $weight,
                'detail'    => $detail,
                'unit'      => $unit,
                'goods_id'  => $goods_id,
            ];
            $count = $Goods_detail->where(['goods_id'=>$goods_id])->count();
            if($count<=0){
                $detail_data['goods_id']    = $goods_id;
                $res1 = $Goods_detail->add($detail_data);
            }else{
                $res1 = $Goods_detail->where(['goods_id'=>$goods_id])->save($detail_data);
            }
            if($res!==false&&$res1!==false){
                $Goods->commit();
                $this->success("操作成功");
                return ;
            }else{
                $Goods->rollback();
                $this->error("操作失败");
                return ;
            }
        }
        $info           = $Goods->where(['goods_id'=>$goods_id])->find();
        $info['imgs']   = json_decode($info['imgs'],true);
        $detail         = $Goods_detail->where(['goods_id'=>$goods_id])->find();
        $info           = array_merge($info,$detail);



        $server_url     = U('Admin/Mall/upload_img');
        $this->assign("server_url",'http://'.SITE_DOMAIN.$server_url);
        $this->assign('info',$info);
        $this->assign('meta_title','添加商品');
        $this->display();
    }

    /**
     * 列表页
     * lists2 ($model,$where=array(),$order='',$field=true, $join='',$alias='',$group= '',$target='',$page_id='')
     *
     *
     */
    public function index(){
        $Order = M('order','ngc_');
        $Order_goods = M('order_goods','ngc_');
        $where = [
            'o.status'    => 0
        ];
        $order = 'o.order_id desc';
        $list = $this->lists2($Order,$where,$order,$field=true, 'ngc_order_goods og on o.order_id=og.order_id',$alias='o');

        $this->assign("_list",$list);
        $this->meta_title = '列表';
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
