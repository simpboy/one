alter table `ngc_goods` drop COLUMN `cate_id`;
alter table `ngc_goods` add COLUMN `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id' after mall_id;