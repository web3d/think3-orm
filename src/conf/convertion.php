<?php

return [
    /* 系统变量名称设置 */
    'VAR_MODULE'             => 'm', // 默认模块获取变量
    'VAR_ADDON'              => 'addon', // 默认的插件控制器命名空间变量
    'VAR_CONTROLLER'         => 'c', // 默认控制器获取变量
    'VAR_ACTION'             => 'a', // 默认操作获取变量
    'VAR_AJAX_SUBMIT'        => 'ajax', // 默认的AJAX提交变量
    'VAR_JSONP_HANDLER'      => 'callback',
    'VAR_PATHINFO'           => 's', // 兼容模式PATHINFO获取变量例如 ?s=/module/action/id/1 后面的参数取决于URL_PATHINFO_DEPR
    'VAR_TEMPLATE'           => 't', // 默认模板切换变量
    'VAR_AUTO_STRING'        => false, // 输入变量是否自动强制转换为字符串 如果开启则数组变量需要手动传入变量修饰符获取变量

    /* 默认设定 */
    'DEFAULT_M_LAYER'        => 'Model', // 默认的模型层名称
    'DEFAULT_C_LAYER'        => 'Controller', // 默认的控制器层名称
    'DEFAULT_V_LAYER'        => 'View', // 默认的视图层名称
    'DEFAULT_LANG'           => 'zh-cn', // 默认语言
    'DEFAULT_THEME'          => '', // 默认模板主题名称
    'DEFAULT_MODULE'         => 'Home', // 默认模块
    'DEFAULT_CONTROLLER'     => 'Index', // 默认控制器名称
    'DEFAULT_ACTION'         => 'index', // 默认操作名称
    'DEFAULT_CHARSET'        => 'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE'       => 'PRC', // 默认时区
    'DEFAULT_AJAX_RETURN'    => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_JSONP_HANDLER'  => 'jsonpReturn', // 默认JSONP格式返回的处理方法
    'DEFAULT_FILTER'         => 'htmlspecialchars', // 默认参数过滤方法 用于I函数...

    /* 数据库设置 */
    'DB_TYPE'                => 'mysql', // 数据库类型
    'DB_HOST'                => '127.0.0.1', // 服务器地址
    'DB_NAME'                => '', // 数据库名
    'DB_USER'                => '', // 用户名
    'DB_PWD'                 => '', // 密码
    'DB_PORT'                => '', // 端口
    'DB_PREFIX'              => '', // 数据库表前缀
    'DB_PARAMS'              => array(), // 数据库连接参数
    'DB_DEBUG'               => true, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'        => true, // 启用字段缓存
    'DB_CHARSET'             => 'utf8', // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'         => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'         => false, // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'          => 1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'            => '', // 指定从服务器序号

    /* 数据缓存设置 */
    'DATA_CACHE_TIME'        => 0, // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'    => false, // 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK'       => false, // 数据缓存是否校验缓存
    'DATA_CACHE_PREFIX'      => '', // 缓存前缀
    'DATA_CACHE_TYPE'        => 'File', // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'        => TEMP_PATH, // 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_KEY'         => '', // 缓存文件KEY (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR'      => false, // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'        => 1, // 子目录缓存级别

    /* URL设置 */
    'URL_PATHINFO_DEPR'      => '/', // PATHINFO模式下，各参数之间的分割符号
];
