<?php
/**
 * 店商管理
 */
namespace Admin\Controller;
use Common\Controller;

class GoodsController extends AdminController {
    /**
     * 添加商品
     */
    /**
     * `goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品id',
    `mall_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
    `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
    `sub_title` varchar(255) NOT NULL DEFAULT '' COMMENT '副标题',
    `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
    `postage` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
    `main_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品主图',
    `imgs` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片json',
    `stock` int(5) NOT NULL DEFAULT '0' COMMENT '库存数量',
    `sell_reserve` tinyint(1) NOT NULL DEFAULT '0' COMMENT '预定还是销售；0-销售，1预定；\r\n预定不能修改城销售（后期可以复制成销售）',
    `buy_member_number` int(11) NOT NULL DEFAULT '0' COMMENT '购买过的人数',
    `sell_number` int(11) NOT NULL DEFAULT '0' COMMENT '售出数量',
    `comment_number` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
    `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '编辑时间',
    `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '生成时间',
    `online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-上线，-1下线',
    `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可以预定或购买；0可以，-1不可以',
    `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为正常数据；-1不是正常数据',
     */
    public function add_page(){
        $mall_id = I('get.id',null,'intval');
        $Mall = M('goods','ngc_');
        if(IS_POST){
            $post_data = I("post.");


            $title          = I("post.title",'','trim');
            $sub_title      = I("post.sub_title",'','trim');
            $price          = I("post.price",0,'trim');
            $postage        = I("post.postage",0,'trim');
            $main_img       = I("post.main_img",null,'');
            $mail_img       = I("post.imgs",'','trim');
            $stock          = I("post.stock",0,'intval');
            $sell_reserve   = I("post.sell_reserve",0,'intval');
            $create_time    = NOW_TIME;
            $online         = I("post.online",0,'intval');

            $Up 			= D('Admin/Up');
            $img_new 		= $Up->save_img();

            if(!empty($img_new)){
                $domain = C('DOMAIN')?C('DOMAIN'):'http://admin.one.loca/';
                $img    = $domain.$img_new[0];
            }elseif(!empty($img_old)){
                $img    = $img_old;
            }

            if(!empty($expire_time)){
                $expire_time = strtotime($expire_time);
            }
            if(empty($mall_name)){
                $this->error("请填写电商名称");
            }
            $where = ['name'=>$mall_name];
            !empty($mall_id)&&$where['mall_id'] = array('neq',$mall_id);
            $count = $Mall->where($where)->count();
            if($count>0){
                $this->error("电商名称重复");
            }

            $data = [
                'name'          => $mall_name,
                'desc'          => $mall_desc,
                'address'       => $address,
                'expire_time'   => $expire_time,
                'img'           => $img
            ];
            //后台可以修改电商名称，前台不可以，店商名称不能与其他电商重名，需要增加电商的地址字段
            if(empty($mall_id)){
                $data['create_time']    = NOW_TIME;
                $res = $Mall->add($data);
            }else{
                $res = $Mall->where(['mall_id'=>$mall_id])->save($data);
            }
            if($res!==false){
                $this->success("操作成功");
                return ;
            }else{
                $this->error("操作失败");
                return ;
            }
        }
        $mall_info = $Mall->where(['mall_id'=>$mall_id])->find();
        if(!empty($mall_info)){
            $mall_info['expire_time']=date('Y-m-d H:i',$mall_info['expire_time']);
        }
        $server_url = U('Admin/Mall/upload_img');
        $this->assign("server_url",'http://'.SITE_DOMAIN.$server_url);
        $this->assign('info',$mall_info);
        $this->display();
    }

    /**
     * 列表页
     */
    public function index(){
        $Mall = M('mall','ngc_');

        $list = $this->lists($Mall,['del'=>['neq',1]]);
        foreach($list as $k=>&$v){
            if($v['expire_time']>time()){
                $v['is_expire'] = 0;
            }else{
                $v['is_expire'] = 1;
            }
        }

        $this->assign("_list",$list);
        $this->meta_title = '电商列表';
        $this->display();
    }
    public function on_off()
    {
        $id = I('get.id');
        $Mall = M('mall','ngc_');
        $state = $Mall->where(['mall_id'=>$id])->getField('state');
        $data = [
            'state' => abs($state)-1
        ];
        $res = $Mall->where(['mall_id'=>$id])->save($data);
        if($res!==false){
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }

    public function del(){
        $id = I('get.id');
        $Mall = M('mall','ngc_');
        $data = [
            'del' => 1
        ];
        $res = $Mall->where(['mall_id'=>$id])->save($data);
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
