<?php

require __DIR__ . '/../vendor/autoload.php';

use think3\Db;
use think3\Think3;
use function think3\C;

Think3::start();

C([
    /* 数据库配置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => getenv('TESTS_DB_MYSQL_HOSTNAME'), // 服务器地址
    'DB_NAME' => getenv('TESTS_DB_MYSQL_DATABASE'), // 数据库名
    'DB_USER' => getenv('TESTS_DB_MYSQL_USERNAME'), // 用户名
    'DB_PWD' => getenv('TESTS_DB_MYSQL_PASSWORD'),  // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'test_', // 数据库表前缀
]);
Db::getInstance();


function array_column_ex(array $arr, array $column, ?string $key = null): array
{
    $result = array_map(function ($val) use ($column) {
        $item = [];
        foreach ($column as $index => $key) {
            if (is_callable($key)) {
                $item[$index] = call_user_func($key, $val);
            } elseif (is_int($index)) {
                $item[$key] = $val[$key];
            } else {
                $item[$key] = $val[$index];
            }
        }
        return $item;
    }, $arr);

    if (!empty($key)) {
        $result = array_combine(array_column($arr, 'id'), $result);
    }

    return $result;
}
