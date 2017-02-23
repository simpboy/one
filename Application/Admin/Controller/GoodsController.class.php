<?php
/**
 * 店商管理
 */
namespace Admin\Controller;
use User\Api\UserApi as UserApi;
use Common\Controller;

class GoodsController extends AdminController {
    /**
     * 添加店商
     */
    public function add_page(){
        $mall_id = I('get.id',null,'intval');
        $Mall = M('mall','ngc_');
        if(IS_POST){
            $post_data = I("post.");

            $mall_name      = I("post.name",'','trim');
            $mall_desc      = I("post.desc",'','trim');
            $address        = I("post.address",'','trim');
            $expire_time    = I("post.expire_time",0,'trim');
            $mall_id        = I("post.id",null,'intval');
            $img_old        = I("post.img_old",null,'trim');

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
