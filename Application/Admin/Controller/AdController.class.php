<?php/** * 广告管理 */namespace Admin\Controller;use Common\Controller;class AdController extends AdminController{    /**     * 添加广告     */    public function add_page(){        if(IS_POST){            $img_old 			= I('post.img_old','','trim');            $Up 			    = D('Admin/Up');            $img_info 		    = $Up->save_img();            $img = '';            if(!empty($img_info)){                $site_url = C('UPLOADURL')?C('UPLOADURL'):'http://www.ngc.com/';                $img  = img_to_cdn($site_url.$img_info['img']);            }else{                $img = $img_old;            }            $id 			= I('post.id','','trim');            $url 			= I('post.url','','trim');            $position_id 	= I('post.position_id',0,'intval');            $display_order  = I('post.display_order',0,'intval');            $title 			= I('post.title','','trim');            $online 	    = I('post.online',0,'intval');            if(empty($img)||empty($title)||empty($url)){                $this->error("请填写完整");            }            $Ad = M('ad_ad','ngc_');            $Ad->startTrans();            if($online==1){                $res = $Ad->where(['position_id'=>$position_id])->save(['online'=>0]);            }            $data = [                'title'			=> $title,                'url'			=> $url,                'img'			=> $img,                'position_id'	=> $position_id,                'online'		=> $online,                'status'		=> 1,                'display_order' => $display_order,                'create_time'   => NOW_TIME            ];            if(!empty($id)){                $where = array(['ad_id'=>$id]);                $res1 = $Ad->where($where)->save($data);            }else{                $res1 = $Ad->add($data);            }            if($res!==false&&$res1!==false){                $Ad->commit();                $this->success("保存成功");                return ;            }else{                $Ad->rollback();                $this->error("保存失败");                return ;            }        }        $Position       = M('ad_position','ngc_');        $position_list  = $Position->where(['online'=>1,'status'=>1])->select();        $id = I('get.id','','intval');        if(is_numeric($id)){            $Ad         = M('ad_ad','ngc_');            $ad_info    = $Ad->where(['ad_id'=>$id])->find();            $this->assign('info',$ad_info);        }        $this->assign('position_list',$position_list);        $this->assign('title','添加广告');        $this->display();    }    /**     * 广告列表     */    public function index()    {        $position_id    = I('get.position_id',0,'intval');        $Ad             = M('ad_ad','ngc_');        $Ad_position    = M('ad_position','ngc_');        $position_array  = $Ad_position->where(['status'=>1])->getField('position_id,name',true);        $where = array(            'position_id'   => $position_id        );        $ad_list = $Ad->where(1)->select();        foreach($ad_list as $k=>&$v){            $v['online_op']         = $v['online']==1?"不显示":"显示";            $v['position_name']     = $position_array[$v['position_id']];        }        $this->assign('meta_title','广告列表');        $this->assign('_list',$ad_list);        $this->display();    }    /**     * 删除     */    public function del(){        $id = I('get.id','','intval');        if(!is_numeric($id)){            $this->error("参数错误");        }        $Ad     = M('ad_ad','ngc_');        $res    = $Ad->where(['ad_id'=>$id])->delete();        if($res!==false){            $this->success("删除成功");        }else{            $this->error("删除失败");        }    }    /**     * 上下线     */    public function on_off(){        $id = I('get.id','','intval');        if(!is_numeric($id)){            $this->error("参数错误");        }        $Ad         = M('ad_ad','ngc_');        $online     = $Ad->where(['ad_id'=>$id])->getField('online');        $Ad->startTrans();        $online = $online==1?0:1;        $res = $Ad->where(['ad_id'=>$id])->save(['online'=>$online]);        if($res!==false){            $Ad->commit();            $this->success("操作成功");        }else{            $Ad->rollback();            $this->error("操作失败");        }    }}