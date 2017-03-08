alter table `ngc_goods` drop COLUMN `cate_id`;
alter table `ngc_goods` add COLUMN `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id' after mall_id;
alter table `ngc_mall` drop COLUMN `del`;
alter table `ngc_mall` add COLUMN `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1删除；0正常' after expire_time;
alter table `ngc_goods` MODIFY `imgs` varchar(1024) NOT NULL DEFAULT '' COMMENT '商品图片json';

alter table `ngc_order` CHANGE  no_pay_money not_pay_money decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '待支付金额';
alter table `ngc_order` CHANGE shopping_fee post_fee decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '快递费';
alter table `ngc_mall` modify `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-删除；0-未删除';
alter table `ngc_goods` add column `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-未删除，1-删除';
alter table `ngc_goods_detail` add column `unit` varchar(50) NOT NULL DEFAULT 'g' COMMENT '重量单位；g,kg';
alter table `ngc_area` COMMENT='地区表';
alter table `ngc_address` add column `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '收货人电话' after `address`;
alter table `ngc_address` add column `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '收货人姓名' after `address`;


alter table `ngc_order` add column   `express_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '快递状态；0-未发货，1-代发货，2-已发货，3-已收货'after `type`;
alter table `ngc_order` add column   `express_time` int(11) NOT NULL DEFAULT '0' COMMENT '快递发货时间;输入订单号的时间'after `type`;
alter table `ngc_order` add column   `express_sn` varchar(50) NOT NULL DEFAULT '' COMMENT '快递单号'after `type`;
alter table `ngc_order` add column `express_name` varchar(50) NOT NULL DEFAULT '' COMMENT '快递名称' after `type`;
