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
    public function add(){
        $this->display();
    }

    /**
     * 列表页
     */
    public function index(){
        $this->meta_title = '管理首页';
        $this->display();
    }

}
