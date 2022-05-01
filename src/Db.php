<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think3;

use think3\Db\Driver;

/**
 * ThinkPHP 数据库中间层实现类
 * @method connect($config = '', $linkNum = 0, $autoConnection = false)
 * @method query($str, $fetchSql = false, $master = false)
 * @method execute($str, $fetchSql = false)
 * @method startTrans()
 * @method commit()
 * @method rollback()
 * @method getQueryTimes($execute = false)
 * @method getExecuteTimes()
 * @method close()
 * @method error()
 * @method insert($data, $options = array(), $replace = false)
 * @method insertAll($dataSet, $options = array(), $replace = false)
 * @method selectInsert($fields, $table, $options = array())
 * @method update($data, $options)
 * @method delete($options = array())
 * @method select($options = array())
 * @method buildSelectSql($options = array())
 * @method getLastSql($model = '')
 * @method getLastInsID()
 * @method getError()
 * @method setModel($model)
 */
class Db
{

    private static $instance  = array(); //  数据库连接实例
    private static $_instance = null; //  当前数据库连接实例

    /**
     * 取得数据库类实例
     * @static
     * @access public
     * @param mixed $config 连接配置
     * @return Driver 返回数据库驱动类
     */
    public static function getInstance($config = array())
    {
        $md5 = md5(serialize($config));
        if (!isset(self::$instance[$md5])) {
            // 解析连接参数 支持数组和字符串
            $options = self::parseConfig($config);
            // 兼容mysqli
            if ('mysqli' == $options['type']) {
                $options['type'] = 'mysql';
            }

            // 如果采用lite方式 仅支持原生SQL 包括query和execute方法
            $class = !empty($options['lite']) ? 'think3\db\Lite' : 'think3\\db\\driver\\' . ucwords(strtolower($options['type']));
            if (class_exists($class)) {
                self::$instance[$md5] = new $class($options);
            } else {
                // 类没有定义
                throw new Exception(L('_NO_DB_DRIVER_') . ': ' . $class);
            }
        }
        self::$_instance = self::$instance[$md5];
        return self::$_instance;
    }

    /**
     * 数据库连接参数解析
     * @static
     * @access private
     * @param mixed $config
     * @return array
     */
    private static function parseConfig($config)
    {
        if (!empty($config)) {
            if (is_string($config)) {
                return self::parseDsn($config);
            }
            $config = array_change_key_case($config);
            $config = array(
                'type'        => $config['db_type'],
                'username'    => $config['db_user'],
                'password'    => $config['db_pwd'],
                'hostname'    => $config['db_host'],
                'hostport'    => $config['db_port'],
                'database'    => $config['db_name'],
                'dsn'         => isset($config['db_dsn']) ? $config['db_dsn'] : null,
                'params'      => isset($config['db_params']) ? $config['db_params'] : null,
                'charset'     => isset($config['db_charset']) ? $config['db_charset'] : 'utf8',
                'deploy'      => isset($config['db_deploy_type']) ? $config['db_deploy_type'] : 0,
                'rw_separate' => isset($config['db_rw_separate']) ? $config['db_rw_separate'] : false,
                'master_num'  => isset($config['db_master_num']) ? $config['db_master_num'] : 1,
                'slave_no'    => isset($config['db_slave_no']) ? $config['db_slave_no'] : '',
                'debug'       => isset($config['db_debug']) ? $config['db_debug'] : APP_DEBUG,
                'lite'        => isset($config['db_lite']) ? $config['db_lite'] : false,
            );
        } else {
            $config = array(
                'type'        => C('DB_TYPE'),
                'username'    => C('DB_USER'),
                'password'    => C('DB_PWD'),
                'hostname'    => C('DB_HOST'),
                'hostport'    => C('DB_PORT'),
                'database'    => C('DB_NAME'),
                'dsn'         => C('DB_DSN'),
                'params'      => C('DB_PARAMS'),
                'charset'     => C('DB_CHARSET'),
                'deploy'      => C('DB_DEPLOY_TYPE'),
                'rw_separate' => C('DB_RW_SEPARATE'),
                'master_num'  => C('DB_MASTER_NUM'),
                'slave_no'    => C('DB_SLAVE_NO'),
                'debug'       => C('DB_DEBUG', null, APP_DEBUG),
                'lite'        => C('DB_LITE'),
            );
        }
        return $config;
    }

    /**
     * DSN解析
     * 格式： mysql://username:passwd@localhost:3306/DbName?param1=val1&param2=val2#utf8
     * @static
     * @access private
     * @param string $dsnStr
     * @return array
     */
    private static function parseDsn($dsnStr)
    {
        if (empty($dsnStr)) {
            return false;
        }
        $info = parse_url($dsnStr);
        if (!$info) {
            return false;
        }
        $dsn = array(
            'type'     => $info['scheme'],
            'username' => isset($info['user']) ? $info['user'] : '',
            'password' => isset($info['pass']) ? $info['pass'] : '',
            'hostname' => isset($info['host']) ? $info['host'] : '',
            'hostport' => isset($info['port']) ? $info['port'] : '',
            'database' => isset($info['path']) ? ltrim($info['path'], '/') : '',
            'charset'  => isset($info['fragment']) ? $info['fragment'] : 'utf8',
        );

        if (isset($info['query'])) {
            parse_str($info['query'], $dsn['params']);
        } else {
            $dsn['params'] = array();
        }
        return $dsn;
    }

    // 调用驱动类的方法
    public static function __callStatic($method, $params)
    {
        return call_user_func_array(array(self::$_instance, $method), $params);
    }
}
