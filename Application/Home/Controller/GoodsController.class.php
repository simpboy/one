<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
/**
 * 前台商品
 */
class GoodsController extends HomeController {
	//全部商品
    public function lists(){
        $Category = M('category','ngc_');
        $where = [
            'status'    => 0,
            'del'       => 0,
            'online'    => 1,
            'pid'       => 0
        ];
        $root_category = $Category->where($where)->order("display_order desc")->select();

        /**
         * CREATE TABLE `ngc_category` (
        `cate_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
        `cate_name` varchar(200) NOT NULL DEFAULT '' COMMENT '分类名称',
        `description` text NOT NULL COMMENT '描述',
        `img` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图片',
        `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父级分类id',
        `display_order` int(5) NOT NULL DEFAULT '0' COMMENT '展示顺序desc',
        `goods_number` int(10) NOT NULL DEFAULT '0' COMMENT '分类的商品数量',
        `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间；',
        `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
        `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为异常数据；0-正常，-1-异常',
        `online` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-上线；0-下线',
        `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-没有删除；1-删除',
        PRIMARY KEY (`cate_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
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

        $this->assign('root_category',$root_category);
        $this->assign('goods_list',$goods_list);
        $this->display();
    }

}