<?php
/**
 * @author wangxl
 */
namespace Admin\Widget;
use Think\Controller;
class TaginputWidget extends Controller {
    /**
     * @param array $param
     * @param suffix 后缀׺
     */
    public function input($param = array()){
        $data = array(
            'suffix'        => $param['suffix'],
            'input_name'    => $param['input_name'],
            'search_url'    => $param['search_url'],
            'required'      => $param['required'],
            'last_item'     => !empty($param['last_item'])?$param['last_item']:'',
            'data'          => json_decode($param['data'],true),
            'radio'          => $param['radio'],
        );
        $this->assign($data);
        $this->display('Taginput:input');
    }

    /**
     * @param array $param
     */
    public function editor($param = array()){
        $data = array(
            'input_name'            => $param['input_name'],
            'server_url'            => $param['server_url'],
            'content'               => $param['content'],
        );
        $this->assign($data);
        $this->display('Taginput:editor');
    }
}