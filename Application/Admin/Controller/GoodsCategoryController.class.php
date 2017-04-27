<?php
/**
 * 商品分类管理
 */
namespace Admin\Controller;
use Common\Controller;

class GoodsCategoryController extends AdminController {

    public function _initialize(){
        parent::_initialize();
        $this->assign('_controller',CONTROLLER_NAME);
        $this->assign('_action',ACTION_NAME);
    }
    /**
     * 添加商品
     */
    public function add_page(){
        $cate_id            = I('get.id',null,'intval');
        $parent_id          = I('get.parent_id',0,'intval');
        $Category           = M('Category','ngc_');
        if(IS_POST){
            //基本字段
            $cate_id        = I('post.id',null,'intval');
            $cate_name      = I("post.cate_name",'','trim');
            $description    = I("post.description",'','trim');
            $parent_id      = I("post.parent_id",0,'trim');
            $online         = I("post.online",1,'intval');
            $img_old        = I("post.img_old",null,'trim');

            $Up 			= D('Admin/Up');
            $img_new 		= $Up->save_img();

            $img = $img_new['img'];
            if(!empty($img)){
                $domain      = C('DOMAIN')?C('DOMAIN'):'http://admin.one.loca/';
                $img         = $domain.$img;
            }elseif(!empty($img_old)){
                $img    = $img_old;
            }
            if(empty($cate_name)||empty($description)){
                $this->error("分类名称，或者描述为空");
            }
            $data = [
                'cate_name'         => $cate_name,
                'description'       => $description,
                'parent_id'         => $parent_id,
                'online'            => $online,
                'img'               => $img,
                'update_time'       => NOW_TIME
            ];
            if(empty($cate_id)){
                $data['create_time']    = NOW_TIME;
                $res                    = $Category->add($data);
            }else{
                $res                    = $Category->where(['cate_id'=>$cate_id])->save($data);
            }
            if($res!==false){
                $this->success("操作成功");
                return ;
            }else{
                $this->error("操作失败");
                return ;
            }
        }
        //当前分类
        $info           = $Category->where(['cate_id'=>$cate_id])->find();
        if(isset($info['parent_id'])){
            $parent_id      = $info['parent_id'];
        }
        $parent_name = "根分类";
        if($parent_id>0){
            $parent_name = $Category->where(['cate_id'=>$parent_id])->getField('cate_name');
        }


        $server_url     = U('Admin/Mall/upload_img');
        $this->assign("server_url",'http://'.SITE_DOMAIN.$server_url);
        $this->assign('info',$info);
        $this->assign('parent_id',$parent_id);
        $this->assign('parent_name',$parent_name);
        $this->assign('meta_title','添加商品分类');
        $this->display();
    }

    /**
     * 列表页
     */
    public function index(){
        $Category = M('Category','ngc_');
        $order = "display_order desc";
        $list = $this->lists($Category,[],$order);
        foreach($list as $k=>&$v){
            $v['parent_name'] = $v['parent_id']==0?'根分类':$Category->where(['cate_id'=>$v['parent_id']])->getField('cate_name');
        }
        $this->assign("_list",$list);
        $this->meta_title = '商品分类列表';
        $this->display();
    }
    /**
     * 更改上下线
     */
    public function on_off_online()
    {
        $id = I('get.id');
        $Category = M('Category','ngc_');
        $online = $Category->where(['goods_id'=>$id])->getField('online');
        $data = [
            'online' => abs(abs($online)-1)
        ];
        $res = $Category->where(['goods_id'=>$id])->save($data);
        if($res!==false){
            $this->success("操作成功");
        }else{
            $this->error("操作失败");
        }
    }

    public function del(){
        $id = I('get.id');
        $Category = M('Category','ngc_');
        $data = [
            'del' => 1
        ];
        $res = $Category->where(['cate_id'=>$id])->save($data);
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
