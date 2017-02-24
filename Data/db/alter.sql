alter table `ngc_goods` drop COLUMN `cate_id`;
alter table `ngc_goods` add COLUMN `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id' after mall_id;
alter table `ngc_mall` drop COLUMN `del`;
alter table `ngc_mall` add COLUMN `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1删除；0正常' after expire_time;