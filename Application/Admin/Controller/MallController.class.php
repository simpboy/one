<?php
/**
 * 店商管理
 */
namespace Admin\Controller;
use User\Api\UserApi as UserApi;

class MallController extends AdminController {

    /**
     * 添加店商
     */
    public function add_page(){
        $mall_id = I('get.id',null,'intval');
        $Mall = M('mall','ngc_');
        if(IS_POST){
            $mall_name = I("post.name",'','trim');
            $mall_desc = I("post.desc",'','trim');
            $address = I("post.address",'','trim');
            $expire_time = I("post.expire_time",0,'trim');
            $mall_id = I("post.id",null,'intval');

            if(empty($mall_name)){
                $this->error("请填写电商名称");
            }
            if(!empty($expire_time)){
                $expire_time = strtotime($expire_time);
            }

            $count = $Mall->where(['name'=>$mall_name])->count();
            if($count>0){
                    $this->error("电商名称重复");
            }

            $data = [
                'name'          => $mall_name,
                'desc'          => $mall_desc,
                'address'       => $address,
                'expire_time'   => $expire_time
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
            }else{
                $this->error("操作失败");
            }
        }
        $this->display();
    }

    /**
     * 列表页
     */
    public function index(){
        $Mall = M('mall','ngc_');


        $this->meta_title = '电商列表';
        $this->display();
    }

}
