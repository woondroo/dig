<?php
/**
 * This file defined some constant.
 * 
 * @author wengebin<wengebin@hotmail.com> 
 */
//网站根路径
define('WEB_ROOT',dirname(dirname(dirname(__FILE__))));

//网站域名
define('WEB_DOMAIN','http://localwww.eiodesign.com');
define('UPLOAD_DOMAIN','http://localupload.eiodesign.com');
define('IMAGE_DOMAIN','http://localimage.eiodesign.com');

//是否开启地址重写
define('REWRITE_MODE',true);
//开始运行时间(秒)
define('NBT_BEGIN_TIME',time());
//开始运行时间(微秒)
define('NBT_BEGIN_MICROTIME',microtime(true));

//WEB数据库配置
define('DB_WEB_DSN','mysql:host=localhost;dbname=web.db');
define('DB_WEB_USERNAME','root');
define('DB_WEB_PASSWORD','');
define('DB_WEB_CHARGSET','utf8');

//邮件地址
define('MAIL_TO_WGB','wengebin@hotmail.com');

//通信密钥
define('UPLOAD_DOMAIN','http://localupload.eiodesign.com');
define('UPLOAD_DOMAIN_KEY','12345678');

//Redis连接
define( 'REDIS_CONNECT_ADD' , '127.0.0.1' );
define( 'REDIS_CONNECT_PORT' , '6379' );
//Redis存储域，比如 www 站点存储为 www 域开头的 key 中
define( 'REDIS_DISTRICT_NAME' , 'www' );
//是否开启缓存
define( 'CACHE_STATUS' , true );
