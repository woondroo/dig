<?php
/**
 * This file defined some constant.
 * 
 * @author samson.zhou<sanson.zhou@newbiiz.com> 
 * @date 2010-08-13
 */
//网站根路径
define('WEB_ROOT',dirname(dirname(dirname(__FILE__))));

//网站域名
define('WEB_DOMAIN','http://localwww.qinxue.com');
define('UPLOAD_DOMAIN','http://localupload.qinxue.com');
define('TEACHER_DOMAIN','http://localteacher.qinxue.com');
define('ADMIN_DOMAIN','http://localadmin.qinxue.com');
define('DL_DOMAIN','http://localdl.qinxue.com');
define('IMAGE_DOMAIN','http://localimage.qinxue.com');

//是否开启地址重写
define('REWRITE_MODE',true);
//开始运行时间(秒)
define('NBT_BEGIN_TIME',time());
//开始运行时间(微秒)
define('NBT_BEGIN_MICROTIME',microtime(true));

//WEB数据库配置
define('DB_WEB_DSN','mysql:host=localhost;dbname=web.qinxue.com');
define('DB_WEB_USERNAME','root');
define('DB_WEB_PASSWORD','greenwen');
define('DB_WEB_CHARGSET','utf8');
//订单数据数据库配置
define('DB_ORDER_DSN','mysql:host=localhost;');
define('DB_ORDER_USERNAME','root');
define('DB_ORDER_PASSWORD','greenwen');
define('DB_ORDER_CHARGSET','utf8');
//用户数据数据库配置
define('DB_ACCOUNT_DSN','mysql:host=localhost;');
define('DB_ACCOUNT_USERNAME','root');
define('DB_ACCOUNT_PASSWORD','greenwen');
define('DB_ACCOUNT_CHARGSET','utf8');


//邮件地址
define('MAIL_TO_ZHOUYANG','samson.zhou@newbiiz.com');

//邮件地址
define('MAIL_TO_ZY','55090127@qq.com');
define('MAIL_TO_WGB','120694861@qq.com');
define('MAIL_TO_ZJY','729464633@qq.com');

//通信密钥
//通信密钥-与upload.qinxue.com进行通信的密钥
define('UPLOAD_DOMAIN','http://localupload.qinxue.com');
define('UPLOAD_DOMAIN_KEY','12345678');

//Redis连接
define( 'REDIS_CONNECT_ADD' , '127.0.0.1' );
define( 'REDIS_CONNECT_PORT' , '6379' );
//Redis存储域，比如 www 站点存储为 www 域开头的 key 中
define( 'REDIS_DISTRICT_NAME' , 'www' );
//是否开启缓存
define( 'CACHE_STATUS' , true );
