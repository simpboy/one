<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	//系统首页
    public function index(){

//        $Mall = M('mall','ngc_');
//        var_dump($Mall);

        $Ad_ad = M('ad_ad','ngc_');
        $ad_list = $Ad_ad->where(['position_id'=>1,'status'=>1,'online'=>1])->order("display_order desc")->select();
        /**
         *  `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-未删除，1-删除',
         *  `online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-上线，-1下线',
         *  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可以预定或购买；0可以，-1不可以',
         *  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为正常数据；-1不是正常数据',
         */
        $Goods = M('goods','ngc_');
        $where = [
            'del'   => 0,
            'online'    => 1,
            'state'     => ['in',[1]],
            'status'    => 0
        ];
        $order = "sell_number desc,update_time desc";
        $goods_list = $Goods->where($where)->order($order)->limit(30)->select();

        $this->assign('goods_list',$goods_list);
        $this->assign('ad_list',$ad_list);
        $this->display();
    }

}