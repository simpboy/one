<?php/** * 广告管理 */namespace Admin\Controller;class AdpositionController extends AdminController{    /**     * 添加广告位     */    public function add_page(){        if(IS_POST){            $id 			= I('post.id','','trim');            $name 			= I('post.name','','trim');            $online 	    = I('post.online',null,'intval');            if(empty($name)||is_null($online)){                $this->error("请填写完整");            }            $Ad_position = M('ad_position','ngc_');            $data = [                'name'			=> $name,                'online'		=> $online,                'create_time'   => NOW_TIME            ];            if(!empty($id)){                $where = array(['position_id'=>$id]);                $res = $Ad_position->where($where)->save($data);            }else{                $res = $Ad_position->add($data);            }            if($res!==false){                $this->success("保存成功");                return ;            }else{                $this->error("保存失败");                return ;            }        }        $id = I('get.id','','intval');        if(is_numeric($id)){            $Ad_position     = M('ad_position','ngc_');            $info            = $Ad_position->where(['position_id'=>$id])->find();            $this->assign('info',$info);        }        $this->assign('meta_title','添加广告位');        $this->display();    }    /**     * 广告位列表     */    public function index()    {        $position_id    = I('request.id',0,'intval');        $Ad_position    = M('ad_position','ngc_');        $position_array  = $Ad_position->where(['status'=>1])->getField('position_id,name',true);        $where = 1;        if(!empty($position_id)){            $where = [                'position_id'   =>$position_id            ];            $this->assign('id',$position_id);        }        $list = $Ad_position->where($where)->select();        foreach($list as $k=>&$v){            $v['online_op']         = $v['online']==1?"不显示":"显示";            $v['position_name']     = $position_array[$v['position_id']];        }        $this->assign('meta_title','广告位列表');        $this->assign('_list',$list);        $this->display();    }    /**     * 删除     */    public function del(){        $id = I('get.id','','intval');        if(!is_numeric($id)){            $this->error("参数错误");        }        $Ad_position    = M('ad_position','ngc_');        $res            = $Ad_position->where(['position_id'=>$id])->delete();        if($res!==false){            $this->success("删除成功");        }else{            $this->error("删除失败");        }    }    /**     * 上下线     */    public function on_off(){        $id = I('get.id','','intval');        if(!is_numeric($id)){            $this->error("参数错误");        }        $Ad_position    = M('ad_position','ngc_');        $online         = $Ad_position->where(['position_id'=>$id])->getField('online');        $Ad_position->startTrans();        $online = $online==1?0:1;        $res = $Ad_position->where(['position_id'=>$id])->save(['online'=>$online]);        debug_output($Ad_position->getLastSql());        if($res!==false){            $Ad_position->commit();            $this->success("操作成功");        }else{            $Ad_position->rollback();            $this->error("操作失败");        }    }}