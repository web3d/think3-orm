<?php

namespace think3;

class Think3
{
    public static function start()
    {
        // 记录开始运行时间
        $GLOBALS['_beginTime'] = microtime(true);
        // 记录内存初始使用
        define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));
        if (MEMORY_LIMIT_ON) {
            $GLOBALS['_startUseMems'] = memory_get_usage();
        }

        defined('APP_DEBUG') or define('APP_DEBUG', false); // 是否调试模式
        define('NOW_TIME', $_SERVER['REQUEST_TIME']);

        defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
        defined('RUNTIME_PATH') or define('RUNTIME_PATH', APP_PATH . 'Runtime/'); // 系统运行时目录
        // 应用缓存目录
        defined('DATA_PATH') or define('DATA_PATH', RUNTIME_PATH . 'Data/');
        defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'Temp/'); // 应用缓存目录

        defined('STORAGE_TYPE') or define('STORAGE_TYPE', 'File'); // 存储类型 默认为File
        // 初始化文件存储方式
        Storage::connect(STORAGE_TYPE);

        require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';

        // 加载默认配置
        C(require __DIR__ . '/conf/convertion.php');

    }
}
