
create table `book_info`(
  `book_id` int(11) unsigned not null auto_increment comment '主键ID',
  `book_name` varchar(10) not null default '' comment '小说名称',
  `book_desc` varchar(100) not null default '' comment '描述',
  `book_cover` varchar(50) not null default '' comment '封面',
  `book_status` tinyint(1) not null default '1' comment '1: normal, 2: deleted, 3: not audit',
  `category_id` int(11) unsigned not null default 0 comment '',
  `author_id` int(11) unsigned not null default 0 comment '关联 users_id ',
  `area_id` int(11) unsigned not null default 0 comment '',
  `age_works` int(8) unsigned not null default 0 comment '',
  `update_time` int(10) unsigned not null default 0 comment '',
  `create_time` int(10) unsigned not null default 0 comment '',
  primary key (`book_id`),
  key area_id (area_id),
  key author_id(author_id),
  key category_id (category_id),
  key age_works(age_works)
) engine =innodb default charset = utf8 comment 'book info table';


create table `book_category_info`(
  `category_id` int(11) unsigned not null auto_increment comment 'category id 主键',
  `category_name` varchar(15) default '' comment 'category name',
  `category_status` tinyint(1) not null default '1' comment '1: normal, 2: deleted, 3: not audit',
  `create_time` int(10) unsigned not null default 0 comment '',
  `update_time` int(10) unsigned not null default 0 comment '',
  primary key (category_id)
) engine =innodb default charset = utf8 comment 'book category';


create table `book_extend_info`(

);
create table `book_tag_info`(

);
create table `book_tag_map`(

);

create table `book_num_info`(
  `num_id` int(11) unsigned not null auto_increment,
  `book_id` int(11) unsigned not null default 0 comment 'book id',
  `fav_num` int(10) unsigned not null default 0 comment '',
  `comment_num` int(10) unsigned not null default 0 comment '',
  `pv_num` int(10) unsigned not null default 0 comment '',
  `create_time` int(10) unsigned not null default 0 comment '',
  `update_time` int(10) unsigned not null default 0 comment '',
  primary key (num_id),
  unique key uniq_book_id(book_id)
);

create table `book_chapter_info`(
  `book_chapter_id` int(11) unsigned not null auto_increment,
  `book_id` int(11) not null default 0 comment 'book id',
  `chapter_name` varchar(20) not null default '' comment 'chapter name',
  `chapter_status` tinyint(1) not null default 1 comment '1: normal, 2: deleted, 3: not audit',
  `text_url` varchar(50) not null default '' comment 'text url paths',
  `create_time` int(10) unsigned not null default 0 comment '',
  `update_time` int(10) unsigned not null  default 0 comment '',
  primary key (book_chapter_id),
  key book_id(book_id)
) engine = innodb default charset =utf8 comment 'chapter info';

----------------------
Users table info
----------------------

create table `users_order_info`(
  `order_id` int(11) unsigned not null auto_increment,
  `order_sn` char(32) not null default '' comment '订单号',
  `users_id` int(11) unsigned not null default 0 comment 'users id',
  `object_id` int(11) unsigned not null default 0 comment '购买商品的上级ID 如 comic_chapter => comic id',
  `product_id` int(11) unsigned not null default 0 comment 'product id',
  `price` int(11)  unsigned not null default 0 comment '价格RMB(分)',
  `product_type` tinyint(2) unsigned not null default 0 comment '1:comic_chapter 2:video_chapter 3:book_chapter 4:music 5:v_property',
  `pay_type` tinyint(1) unsigned not null default 0 comment '1:v_coin,2:alipay,3:wechat',
  `product_total` int(5) unsigned not null default 1 comment '商品数量默认1个',
  `pay_status` tinyint(1) unsigned not null default 1 comment '1:未支付 2:已支付',
  `create_time` int(10) unsigned not null default 0 comment '',
  `update_time` int(10) unsigned not null default 0 comment '',
  primary key (order_id),
  key users_product_id(users_id, product_id),
  unique key order_sn(order_sn)
) engine = innodb default charset = utf8 comment 'users order info';


create table `users_order_trace_log`(
  `log_id` int(11) unsigned not null auto_increment,
  `order_id` int(11) unsigned not null default 0 comment '',
  `trace_order_sn` varchar(255) not null default '' comment '第三方订单流水号',
  `pay_type` tinyint(1) unsigned not null default 0 comment '1:v_coin,2:alipay,3:wechat',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  primary key (log_id),
  unique  key uniq_order_id(order_id)
) engine = innodb default  charset = utf8 comment 'users order trace log';

create table `users_fav_comic`(
  `fav_id` int(11) unsigned not null auto_increment,
  `comic_id` int(11) unsigned not null default 0 comment 'comic id',
  `users_id` int(11) unsigned not null default 0 comment 'users id',
  `fav_status` tinyint(1) unsigned not null default 1 comment '1: normal, 2: deleted, 3: not audit',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  primary key (fav_id),
  unique key uniq_users_comic_id(users_id, comic_id)
) engine = innodb default charset =utf8 comment 'users fav comic';

create table `users_fav_book`(
  `fav_id` int(11) unsigned not null auto_increment,
  `book_id` int(11) unsigned not null default 0 comment 'book id',
  `users_id` int(11) unsigned not null default 0 comment 'users id',
  `fav_status` tinyint(1) unsigned not null default 1 comment '1: normal, 2: deleted, 3: not audit',
  `update_time` int(10) unsigned not null default 0  comment 'update_time',
  `create_time` int(10) unsigned not null default 0 comment 'create_time',
  primary key (fav_id),
  unique key uniq_users_book_id(users_id, book_id)
) engine = innodb default charset =utf8 comment 'users fav book';

create table `users_fav_users`(
  `fav_id` int(11) unsigned not null auto_increment,
  `users_id` int(11) unsigned not null default 0 comment 'users id',
  `users_fav_id` int(11) unsigned not null default 0 comment 'users fav id',
  `fav_status` tinyint(1) unsigned not null default 1 comment '1: normal, 2: deleted, 3: not audit',
  `update_time` int(10) unsigned not null default 0  comment 'update_time',
  `create_time` int(10) unsigned not null default 0 comment 'create_time',
  primary key (fav_id),
  unique key uniq_users_fav_id(users_id, users_fav_id)
) engine =innodb default charset =utf8 comment 'users fav users';



create table `users_mobile`(
  `mobile_id` int(11) unsigned not null auto_increment,
  `mobile` char(11) not null default '',
  `users_id` int(11) not null default 0,
  `create_time` int(10) not null default 0,
  `update_time` int(10) not null default 0,
  primary key (`mobile_id`),
  unique key uniq_mobile (mobile)
) engine =innodb default charset =utf8 comment 'mobile users table';


create table `users_platform`(
  `platform_id` int(11) unsigned not null auto_increment comment '主键id',
  `platform_sign` char(32) not null default '' comment '第三方平台id',
  `platform` tinyint(1) not null default 0 comment '1: qq 2:微信 ...',
  `sign` char(32) not null default '' comment '查询标识',
  `users_id` int(11) not null default 0 comment 'users id',
  `create_time` int(10) not null default 0 comment 'create time unix time',
  primary key (platform_id),
  unique key uniq_sign (sign),
  unique key uniq_user_id (users_id)
) engine =innodb default charset =utf8 comment 'platform users table';

create table `users_mail`(
  `mail_id` int(11) unsigned not null auto_increment comment '主键id',
  `mail` varchar(50) not null default '' comment 'users mail',
  `sign` char(32) not null default '' comment '查询标识',
  `users_id` int(11) not null default 0 comment 'users id',
  `create_time` int(10) not null default 0 comment 'create time unix time',
  primary key (`mail_id`),
  unique key uniq_sign (sign),
  unique key uniq_user_id (users_id)
) engine =innodb default charset =utf8 comment 'mail users table';


#
create table `users_info_001`(
  `id` int(11) unsigned not null auto_increment comment '主键id',
  `users_id` int(11) unsigned not null default 0 comment 'users_id',
  `users_nickname` varchar(8) not null default '' comment '用户昵称',
  `users_password` char(32) not null default  '' comment 'password',
  `users_status` tinyint(1) unsigned not null default 1 comment '1: normal, 2: deleted, 3: not audit',
  `virtual_coin` int(10) unsigned not null default 0 comment '用户虚拟币余额',
  `credit_num` int(10) unsigned not null default 0 comment '积分值',
  `level_num` int(10) unsigned not null default 0 comment 'level num',
  `recently_active` int(10) not null default 0 comment '最近活跃时间',
  `create_time` int(10) not null default 0 comment '创建时间',
  `update_time` int(10) not null default 0 comment '数据更新时间',
  unique key uniq_users_id (users_id),
  primary key (`id`)
) engine =innodb default charset =utf8 comment 'users master table';


# comic read log
create table `users_browsing_history_log_2019_01_001`(
  `log_id` int(11) unsigned not null auto_increment,
  `users_id` int(11) unsigned not null default 0 comment '用戶usersid',

  `object_id` int(11) unsigned not null default 0 comment '瀏覽對象的id',
  `object_brows_progress` int(11) unsigned not null default 0 comment '對象瀏覽進度',

  `parent_id` int(11) unsigned not null default 0 comment '瀏覽對象上級的id',
  `browsing_type` tinyint(1) unsigned not null  default 0 comment '瀏覽數據類型 1:comic 2:video 3:book 4:article',

  `create_time` int(10) unsigned not null  default 0 comment '',
  `update_time` int(10) unsigned not null default 0 comment '',
  primary key (`log_id`),
  key `users_id` (`users_id`),
  key `unio_users_parent_id` (`users_id`, `parent_id`),
  unique key `uniq_users_object_id` (`users_id`, `object_id`)

) engine =innodb default charset =utf8 comment 'browsing history log';



create table `users_article`(
  `article_id` int(11) unsigned not null auto_increment comment '主键id',
  `article_title` varchar(20) not null  default '' comment 'article title string',
  `content` text not null default '' comment 'article 内容',
  `article_status` tinyint(1) not null default '1' comment '1: normal, 2: deleted, 3: not audit',
  `users_id` int(11) not null default 0 comment 'users id',
  `category_id` int(3) not null default 0 comment '分类',
  `create_time` int(10) not null default 0 comment 'create time',
  `update_time` int(10) not null default 0 comment 'update time',
  primary key (`article_id`),
  key `users_id` (`users_id`),
  key `category_id` (`category_id`)
) engine=innodb default charset =utf8 comment 'users article submit';


create table `users_music`(
  `users_music_id` int(11) unsigned not null auto_increment comment 'users_music_id',
  `music_name` varchar(20) not null default '' comment 'music name',
  `music_status` tinyint(1) not null default 1 comment '1: normal, 2: deleted, 3: not audit',
  `users_id` int(11) unsigned not null default 0 comment 'users id 投稿人ID',
  `music_id` int(11) unsigned not null default 0 comment '审核捅过后music_info 表主键ID',
  `music_json` varchar(1000) not null  default '' comment '投稿音乐信息',
  `media_url` varchar(50) default '' comment '视频文件地址',
  `update_time` int(10) unsigned not null default 0 comment 'update time',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  primary key (users_music_id),
  unique key uniq_music_id(music_id),
  key users_id(users_id)
) engine = innodb default  charset =utf8 comment 'users music submit';



/***************Book 评论相关表******************/
create table `users_book_comment_info`(
    `comment_id` int(11) not null auto_increment,
    `book_id` int(11) unsigned not null default 0 comment '',
    `reply_num` int(5) unsigned not null default 0 comment '',
    `users_id` int(11) unsigned not null default 0 comment 'users_id',
    `like_num` int(10) unsigned not null default 0 comment '',
    `comment_status` tinyint(1) not null default '1' comment '1: normal, 2: deleted, 3: not audit',
    `new_reply_id_arr` varchar(60) not null default '' comment '最新回复ID JSON 格式',
    `create_time` int(10) unsigned not null default 0 comment '',
    `update_time` int(10) unsigned not null default 0 comment '',
    primary key (comment_id),
    key users_id(users_id),
    key book_id (book_id)
)engine =innodb default charset =utf8 comment 'book_comment_info';

create table `users_book_comment_content_info`(
    `content_id` int(11) unsigned not null auto_increment,
    `content` varchar(255) not null default '' comment '',
    `comment_id` int(11) unsigned not null default 0 comment '',
    `create_time` int(10) unsigned not null default 0  comment '',
    `update_time` int(10) unsigned not null default 0 comment '',
    primary key (content_id),
    unique key uniq_comment_id (comment_id)
)engine =innodb default charset =utf8 comment 'book_comment_content_info';

create table `users_book_comment_like_log`(
    `log_id` int(11) unsigned not null auto_increment comment '主键ID',
    `comment_id` int(11) unsigned not null default 0 comment '评论ID',
    `users_id`  int(11) unsigned not null default 0 comment '点赞人ID',
    `create_time` int(10) unsigned not null default 0 comment '点赞时间',
    primary key (log_id),
    unique key uniq_comment_users_id(comment_id, users_id)
) engine = innodb default charset =utf8 comment '点赞日志信息表';


create table `users_book_reply_info`(
    `reply_id` int(11) unsigned not null auto_increment,
    `comment_id` int(11) unsigned not null default 0 comment '',
    `replay_status` tinyint(1) not null default '1' comment '1: normal, 2: deleted, 3: not audit',
    `users_id` int(11) unsigned not null default 0 comment 'users_id',
    `reply_parent_id` int(11) unsigned not null default 0 comment '',
    `like_num` int(10) unsigned not null default 0 comment '',
    `create_time` int(10) unsigned not null default 0 comment '',
    `update_time` int(10) unsigned not null default 0 comment '',
    primary key (reply_id),
    key comment_id(comment_id),
    key users_id(users_id),
    key reply_parent_id(reply_parent_id)

) engine =innodb default charset =utf8 comment 'reply info users book';

create table `users_book_reply_content_info`(
    `content_id` int(11) unsigned not null auto_increment,
    `content` varchar(255) not null  default '' comment 'reply content',
    `reply_id` int(11) not null default 0 comment 'reply_info table reply_id',
    `create_time` int(11) not null default 0 comment '',
    `update_time` int(11) not null default 0 comment '',
    primary key (content_id),
    unique key uniq_reply_id(reply_id)
) engine =innodb default charset =utf8 comment 'reply content users book';


create table `users_book_reply_like_log`(
  `log_id` int(11) unsigned not null auto_increment comment '主键ID',
  `reply_id` int(11) unsigned not null default 0 comment '评论ID',
  `users_id`  int(11) unsigned not null default 0 comment '点赞人ID',
  `create_time` int(10) unsigned not null default 0 comment '点赞时间',
  primary key (log_id),
  unique uniq_reply_users_id(reply_id, users_id)
) engine = innodb default charset =utf8 comment '点赞日志信息表';

/*****************Comic 评论相关内容**************************/
create table `users_comic_comment_info`(
    `comment_id` int(11) not null auto_increment,
    `comic_id` int(11) unsigned not null default 0 comment '',
    `reply_num` int(5) unsigned not null default 0 comment '',
    `users_id` int(11) unsigned not null default 0 comment 'users_id',
    `like_num` int(10) unsigned not null default 0 comment '',
    `comment_status` tinyint(1) not null default '1' comment '1: normal, 2: deleted, 3: not audit',
    `new_reply_id_arr` varchar(60) not null default '' comment '最新回复ID JSON 格式',
    `create_time` int(10) unsigned not null default 0 comment '',
    `update_time` int(10) unsigned not null default 0 comment '',
    primary key (comment_id),
    key users_id(users_id),
    key book_id (comic_id)
)engine =innodb default charset =utf8 comment 'comic_comment_info';

create table `users_comic_comment_content_info`(
    `content_id` int(11) unsigned not null auto_increment,
    `content` varchar(255) not null default '' comment '',
    `comment_id` int(11) unsigned not null default 0 comment '',
    `create_time` int(10) unsigned not null default 0  comment '',
    `update_time` int(10) unsigned not null default 0 comment '',
    primary key (content_id),
    unique key uniq_comment_id (comment_id)
)engine =innodb default charset =utf8 comment 'comic_comment_content_info';

create table `users_comic_reply_info`(
    `reply_id` int(11) unsigned not null auto_increment,
    `comment_id` int(11) unsigned not null default 0 comment '',
    `replay_status` tinyint(1) not null default '1' comment '1: normal, 2: deleted, 3: not audit',
    `users_id` int(11) unsigned not null default 0 comment 'users_id',
    `reply_parent_id` int(11) unsigned not null default 0 comment '',
    `like_num` int(10) unsigned not null default 0 comment '',
    `create_time` int(10) unsigned not null default 0 comment '',
    `update_time` int(10) unsigned not null default 0 comment '',
    primary key (reply_id),
    key comment_id(comment_id),
    key users_id(users_id),
    key reply_parent_id(reply_parent_id)

) engine =innodb default charset =utf8 comment 'reply info users comic';


create table `users_comic_reply_content_info`(
    `content_id` int(11) unsigned not null auto_increment,
    `content` varchar(255) not null  default '' comment 'reply content',
    `reply_id` int(11) not null default 0 comment 'reply_info table reply_id',
    `create_time` int(11) not null default 0 comment '',
    `update_time` int(11) not null default 0 comment '',
    primary key (content_id),
    unique key uniq_reply_id(reply_id)
) engine =innodb default charset =utf8 comment 'reply content users comic';



/*******************music*********************************/

create table `music_info`(
  `music_id` int(11) unsigned not null auto_increment comment 'music id',
  `music_name` varchar(20) not null default '' comment 'name',
  `music_status` tinyint(1) unsigned not null default 1  comment '1: normal, 2: deleted, 3: not audit',
  `area_id` int(5) unsigned not null default 0  comment 'anime 发行地区',
  `users_id` int(11) unsigned not null default 0 comment 'users id 投稿人ID',
  `free_platform` tinyint(1) unsigned not null  comment '免费平台类型 1:pc 2: h5 3:android 4:ios 5:mobile(android+ios) 6:all',
  `payment_type` tinyint(1) unsigned not null comment 'payment type desc 1:anime payment 2:chapter payment 3: free',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  `update_time` int(10) unsigned not null default 0 comment 'update time',
  primary key (music_id),
  key users_id(users_id)
) engine = innodb default  charset = utf8 comment 'music info table';


create table `music_tag_map` (
  `tag_map_id` int(11) unsigned not null auto_increment comment '主键ID',
  `music_id` int(11) unsigned not null default 0 comment 'anime id',
  `tag_id`  int(11) unsigned not null default 0 comment 'tag id',
  `create_time` int(10) unsigned not null default 0 comment 'create_time',
  primary key (tag_map_id),
  key music_id (music_id),
  key tag_id(tag_id)
) engine = innodb default charset =utf8 comment 'tag map table';


create table `music_extend_info`(
  `extend_id` int(11) unsigned not null auto_increment comment '主键ID',
  `music_id`  int(11) unsigned not null default 0 comment 'music id',
  `music_cover` varchar(50) not null default '' comment 'cover',
  `music_desc` varchar(255) not null default '' comment 'desc',
  `music_price` int(11) unsigned not null default 0 comment 'current comic payment price (points)',
  `update_time` int(10) unsigned not null default 0 comment 'update time',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  primary key (extend_id),
  unique key uniq_music_id(music_id)
) engine = innodb default charset = utf8 comment 'extend info';


create table `music_author_map`(
  `map_id` int(11) unsigned not null auto_increment comment 'map id',
  `author_id` int(11) unsigned not null default 0 comment 'users id',
  `music_id` int(11) unsigned not null default 0 comment 'music_id',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  primary key (map_id),
  key author_id (author_id),
  key music_id (music_id)
) engine = innodb default charset =utf8 comment 'author music map';


create table `music_album_map`(
  `map_id` int(11) unsigned not null auto_increment comment '主键ID',
  `music_id` int(11) unsigned not null default 0 comment 'music id',
  `album_id` int(11) unsigned not null default 0 comment 'album_id',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  primary key (map_id),
  key music_id (music_id),
  key album_id (album_id)
) engine = innodb default  charset = utf8 comment '关联music info 与album 专辑表';


create table `music_album`(
  `album_id` int(11) unsigned not null auto_increment comment 'album id',
  `album_name` varchar(20) not null default '' comment 'name',
  `album_status` tinyint(1) unsigned not null default 1  comment '1: normal, 2: deleted, 3: not audit',
  `desc` varchar(255) not null default '' comment 'desc info',
  `author_id` int(11) not null default 0 comment 'users id',
  `update_time` int(10) unsigned not null default 0 comment 'update time',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  primary key (album_id),
  key author_id(author_id)
) engine = innodb default charset = utf8 comment 'music album table';

/*******************anime**********************************/

create table `anime_info`(
  `anime_id` int(11) unsigned not null auto_increment comment '主键ID',
  `anime_name` varchar(15) not null default ''  comment '动漫名称',
  `anime_status` tinyint(1) unsigned not null default 1  comment '1: normal, 2: deleted, 3: not audit',
  `area_id` int(5) unsigned not null default 0  comment 'anime 发行地区',
  `author_id` int(11) unsigned not null default 0 comment '关联users_id',
  `free_platform` tinyint(1) unsigned not null  comment '免费平台类型 1:pc 2: h5 3:android 4:ios 5:mobile(android+ios) 6:all',
  `payment_type` tinyint(1) unsigned not null comment 'payment type desc 1:anime payment 2:chapter payment 3: free',
  `update_time` int(10) unsigned not null default 0 comment 'update_time',
  `create_time` int(10) unsigned not null default 0 comment 'create_time',
  primary key (anime_id),
  key author_id(`author_id`)
) engine = innodb default charset = utf8 comment '动漫信息表';


create table `anime_extend_info`(
  `anime_extend_id` int(11) unsigned not null auto_increment comment '主键ID',
  `anime_id` int(11) unsigned not null default 0 comment 'anime_id',
  `anime_cover` varchar(50) not null default '' comment 'cover',
  `anime_desc` varchar(255) not null default '' comment 'desc',
  `new_chapter_id` int(11) unsigned not null default 0 comment 'current anime new chapter id',
  `anime_price` int(11) unsigned not null default 0 comment 'current comic payment price (points)',
  `chapter_default_price` int(11) unsigned not null default 0 comment 'current anime chapter default price (points)',
  `create_time` int(10) unsigned not null default 0 comment 'current comic add database create time',
  `update_time` int(10) unsigned not null default 0 comment 'current comic update time',
  primary key (anime_extend_id),
  key anime_id (anime_id)
) engine = innodb default charset = utf8 comment '动漫附加信息表';


create table `anime_chapter_info`(
  `anime_chapter_id` int(11) unsigned not null auto_increment comment '主键ID',
  `anime_id` int(11) unsigned not null default 0 comment 'anime info table pk',
  `chapter_name` varchar(15) default '' comment 'chapter name',
  `media_url` varchar(50) default '' comment '视频文件地址',
  `chapter_status` tinyint(1) not null default 1 comment '1: normal, 2: deleted, 3: not audit',
  `is_payment` tinyint(1) unsigned not null default 1 comment '1: free 2:payment',
  `chapter_price` int(11) unsigned not null default 0 comment 'current chapter price',
  `chapter_sort` int(3) unsigned not null default 0 comment '排序规则',
  `update_time` int(10) unsigned not null default 0 comment 'update_time',
  `create_time` int(10) unsigned not null default 0 comment 'create_time',
  primary key (anime_chapter_id),
  key anime_id(anime_id)
) engine = innodb default  charset = utf8 comment '动漫章节(话)信息表';


create table `anime_tag_map` (
  `tag_map_id` int(11) unsigned not null auto_increment comment '主键ID',
  `anime_id` int(11) unsigned not null default 0 comment 'anime id',
  `tag_id`  int(11) unsigned not null default 0 comment 'tag id',
  `create_time` int(10) unsigned not null default 0 comment 'create_time',
  primary key (tag_map_id),
  key anime_id (anime_id),
  key tag_id(tag_id)
) engine = innodb default charset =utf8 comment 'tag map table';



-----------------
----漫画主信息表
-----------------
create table `comic_info` (
  `comic_id` int(11) unsigned not null auto_increment comment '主键ID',
  `comic_name` varchar(50) not null default ''  comment 'comic name',
  `free_platform` tinyint(1) not null default 0 comment '免费平台类型 0: default 无免费平台 1:pc 2: h5 3:android 4:ios 5:mobile(android+ios)',
  `is_payment` tinyint(1) not null default 3  comment 'payment type desc 1:comic payment 2:chapter payment 3: free',
  `area_id` int(5) not null default 0 comment 'comic 发行地区',
  `is_end` tinyint(1) unsigned not null default 1 comment '1: 连载, 2: 完结',
  `comic_status` tinyint(1) unsigned not null default 3 comment '1: normal 2: deleted 3: not audit',
  `create_time` int(10) unsigned not null default 0 comment 'current comic add database create time',
  `update_time` int(10) unsigned not null default 0 comment 'current comic update time',
  primary key (`comic_id`)
) engine=innodb default charset=utf8;


-----------------
----漫画附加信息表
-----------------
create table `comic_extend_info` (
  `comic_extend_id` int(11) unsigned not null auto_increment comment '主键ID',
  `comic_id` int(11) unsigned not null default 0 comment 'relevance comic id',
  `comic_desc` varchar(255) not null  default '' comment 'comic desc',
  `comic_cover` varchar(100) not null default '' comment 'save cover pic files name url ps: comic/cover/{200_200}/date(ymd, update_time)/xxx.jpg',
  `new_chapter_id` int(11) unsigned not null default 0 comment 'current comic  new chapter id',
  `comic_price` int(11) unsigned not null default 0 comment 'current comic payment price (points)',
  `chapter_default_price` int(11) unsigned not null default 0 comment 'current comic chapter default price (points)',
  `create_time` int(10) unsigned not null default 0 comment 'current comic add database create time',
  `update_time` int(10) unsigned not null default 0 comment 'current comic update time',
  primary key (`comic_extend_id`)
) engine=innodb default charset=utf8;



create table `comic_tag_map`(
  `id` int(11) unsigned not null auto_increment,
  `comic_id` int(11) unsigned not null default 0 comment '',
  `tag_id` int(11) unsigned not null default  0 comment '',
  `status` tinyint(1) unsigned not null default 1 comment '1:正常 2:删除',
  `create_time` int(10) unsigned not null default  0 comment '',
  primary key (`id`)
)engine =innodb default charset =utf8;
---------------
---漫画章节信息
---------------
create table `comic_chapter_info`(
 `chapter_id` int(11) unsigned not null auto_increment,
 `comic_id` int(11) unsigned not null default 0 comment 'relevance comic id',
 `chapter_name` varchar(20)  not null default '' comment 'chapter name',
 `chapter_sort` int(3) unsigned not null default 0 comment 'chapter sort',
 `is_payment` tinyint(1) unsigned not null default 1 comment '1:payment 3: free',
 `chapter_price` int(11) unsigned not null default 0 comment 'current chapter price',
 `chapter_status` tinyint(1) unsigned not null default 3 comment '1: normal 2: deleted 3: not audit',
 `create_time` int(10) unsigned not null default 0 comment 'create time',
 `update_time` int(10) unsigned not null default 0 comment 'update time',
 primary key (`chapter_id`)
) engine=innodb default charset=utf8;


-------------------
---- 章节图片信息表 chapter id % 100
-------------------
create table `chapter_images_info_000`(
 `images_id` int(11) unsigned not null auto_increment,
 `chapter_id` int(11) unsigned not null default 0 comment 'relevance comic id',
 `images_width` int(4) unsigned not null default 0 comment 'chapter original image width',
 `images_height` int(4) unsigned not null default 0 comment 'chapter original image height',
 `images_sort` tinyint(2) unsigned not null default 0 comment 'chapter images list order',
 `images_status` tinyint(1) unsigned not null default 3 comment '1: normal 2: deleted 3: not audit',
 `create_time` int(10) unsigned not null default 0 comment 'create_time',
 `update_time` int(10) unsigned not null default 0  comment 'update_time',
 primary key (`images_id`)
) engine=innodb default charset=utf8 comment 'chapter id mod 100';


create table `comic_num_info`(
 `num_id` int(11) unsigned not null auto_increment,
 `fav_num` int(11) unsigned default 0 comment '',
 `comment_num` int(11) unsigned default 0 comment '',
 `pv_num` int(11) unsigned default 0 comment '',
 `create_time` int(10) unsigned default 0 comment '',
 primary key (`num_id`)
) engine = innodb default charset = utf8 comment '';



--------------------------
网站配置信息表
--------------------------

create table `product_info`(
  `product_id` int(11) unsigned not null auto_increment,
  `product_name` varchar(20) not null  default '' comment '商品名称',
  `product_type` tinyint(1) unsigned not null default 1 comment '1:vcoin 2:vcoin券 3:优惠券 4:vip',
  `product_status` tinyint(1) unsigned not null default '1' comment '1: normal, 2: deleted, 3: not audit',
  `virtual_value` int(9) unsigned not null default 1 comment '商品虚拟价值',
  `sort` int(3) unsigned not null default 1 comment '排序规则 desc',
  `price` int(3) unsigned not null default 0 comment '价格(分)',
  `create_time` int(10) unsigned not null default 0 comment 'create time',
  `update_time` int(10) unsigned not null default 0 comment 'update time',
  primary key (product_id)
)engine=innodb default charset 'utf8' comment '商品基本信息表';

create table `recomment_content_info`(
   `content_id` int(11) unsigned not null auto_increment,
   `locaction_id` int(11) unsigned not null ,
   `content` varchar(255) not null default '',
   `link` varchar(255) not null default '',
   `title` varchar(100) not null default '',
   `type` tinyint(1) not null default '',
   `atter` varchar(255) not null default '',
   `create_time` int(10) not null default ''

)engine=innodb default charset=utf8;


create table `recomment_locaction_info`(
  `locaction_id` int(11) unsigned not null auto_increment,
  `locaction_name` varchar(20) not null default '',
  `page_id` int(11) unsigned not null ,
  `location_status` tinyint(1) not null,

  `create_time` int(10) unsigned not null
)engine=innodb default charset = utf8;

create table `recomment_page_info`(
  `page_id` int(11) unsigned not null auto_increment,
  `platfrom` int(1) unsigned not null,
  `mca` varchar(100)  not null,
  `create_time` int(10) unsigned not null,
  `update_time` int(10) unsigned not null

)engine=innodb default charset = utf8;

create table `tag_info`(
  `tag_id` int(11) unsigned not null auto_increment,
  `tag_name` varchar(10) not null default '' comment '',
  `tag_status` tinyint(1) unsigned not null default '1' comment '1: normal, 2: deleted, 3: not audit',
  `tag_type` tinyint(1) unsigned not null default 1 comment '1: comic, 2: 轻小说, 3: music, 4: anime',
  `create_time` int(10) not null default 0 comment '',
  `update_time` int(10) not null  default 0 comment '',
  primary key (`tag_id`)
)engine =innodb default  charset =utf8;

create table `area_info`(
  `area_id` int(11) unsigned not null auto_increment,
  `area_name` varchar(10) not null default '' comment '',
  `area_status` tinyint(1) unsigned not null default '1' comment '1: normal, 2: deleted, 3: not audit',
  `area_type` tinyint(1) unsigned not null default 1 comment '1: comic, 2: 轻小说, 3: music, 4: anime',
  `create_time` int(10) unsigned not null  default 0 comment '',
  `update_time` int(10) not null  default 0 comment '',
  primary key (area_id)
) engine =innodb default charset =utf8 comment '';

create table `credit_rules_info`(
  `rules_id` int(11) unsigned not null auto_increment,
  `rules_name` varchar(20) not null default '' comment '规则名称',
  `credit_val` int(3) not null default 0 comment '积分值',
  `rules_type` tinyint(1) unsigned not null default 1 comment '规则类型 1: comic, 2: novel, 3: music, 4: anime',
  `credit_type` varchar(9) not null default '' comment '积分类型 read, comments, like, fav',
  `rules_cycle` int(2) not null default 0 comment '周期(天) 0: 一次性积分增加规则, 1 ~ 30 天',
  `rules_status` tinyint(1) unsigned not null default '1' comment '1: normal, 2: deleted, 3: not audit',
  `rules_json`  varchar(255) not null default '' comment '附加规则配置 {"read_id_arr":[], }',
  `create_time` int(10) not null default 0 comment 'create_time',
  `update_time` int(10) not null default 0 comment 'update_time',
  primary key (rules_id),
  key rules_credit_type(rules_type, credit_type)
) engine = innodb default charset =utf8 comment 'credit rules 规则信息表';

create table `level_rules_info`(
  `rules_id` int(11) unsigned not null auto_increment,
  `rules`
) engine = innodb default charset =utf8 comment 'credit rules 规则信息表';
