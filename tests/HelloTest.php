<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use think3\Exception;
use think3\Db;
use think3\Model;
use function think3\M;

class HelloTest extends TestCase
{

    protected static $testUserData;

    public static function setUpBeforeClass(): void
    {
        Db::execute('DROP TABLE IF EXISTS `test_user`;');
        Db::execute(<<<SQL
CREATE TABLE `test_user` (
     `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     `type` tinyint(4) NOT NULL DEFAULT '0',
     `username` varchar(32) NOT NULL,
     `nickname` varchar(32) NOT NULL,
     `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
        );
    }

    public function setUp(): void
    {
        Db::execute('TRUNCATE TABLE `test_user`;');
        self::$testUserData = [
            ['id' => 1, 'type' => 3, 'username' => 'qweqwe', 'nickname' => 'asdasd', 'password' => '123123'],
            ['id' => 2, 'type' => 2, 'username' => 'rtyrty', 'nickname' => 'fghfgh', 'password' => '456456'],
            ['id' => 3, 'type' => 1, 'username' => 'uiouio', 'nickname' => 'jkljkl', 'password' => '789789'],
            ['id' => 5, 'type' => 2, 'username' => 'qazqaz', 'nickname' => 'wsxwsx', 'password' => '098098'],
            ['id' => 7, 'type' => 2, 'username' => 'rfvrfv', 'nickname' => 'tgbtgb', 'password' => '765765'],
        ];
        M('user')->addAll(self::$testUserData);
    }

    public function testColumn()
    {
        $users = self::$testUserData;

        // 获取全部列
        $result = M('user')->select();

        $this->assertCount(5, $result);
        $this->assertEquals($users, array_values($result));
        $this->assertEquals(array_column($users, 'id'), array_column($result, 'id'));

        // 获取某一个字段
        $result = M('user')->getField('username', true);
        $this->assertEquals(array_column($users, 'username'), $result);

        // 获取某字段唯一
        $result = M('user')->getField('DISTINCT type', true);
        $expected = array_unique(array_column($users, 'type'));
        $this->assertEquals($expected, $result);

        // 字段别名
        $result = M('user')->getField('username as name2', true);
        $expected = array_column($users, 'username');
        $this->assertEquals($expected, $result);

        // 获取若干列
        $result = M('user')->getField('id,username,nickname', true);
        $expected = array_column_ex($users, ['username', 'nickname', 'id'], 'id');
        $this->assertEquals($expected, $result);


        // 获取若干列不指定key时不报错
        $result = M('user')->getField('username,nickname,id');
        $expected = array_column_ex($users, ['username', 'nickname', 'id']);
        $this->assertNotEquals($expected, $result);

    }

    public function testTable()
    {
        $users = self::$testUserData;
        // 表别名
        $result = M('user')->alias('test2')->getField('test2.username', true);
        $expected = array_column($users, 'username');
        $this->assertEquals($expected, $result);

//
//        // 数组方式获取
//        $result = Db::table('test_user')->column(['username', 'nickname', 'type'], 'id');
//        $expected = array_column_ex($users, ['username', 'nickname', 'type', 'id'], 'id');
//        $this->assertEquals($expected, $result);
//
//        // 数组方式获取（重命名字段）
//        $result = Db::table('test_user')->column(['username' => 'my_name', 'nickname'], 'id');
//        $expected = array_column_ex($users, ['username' => 'my_name', 'nickname', 'id'], 'id');
//        array_value_sort($result);
//        array_value_sort($expected);
//        $this->assertEquals($expected, $result);
//
//        // 数组方式获取（定义表达式）
//        $result = Db::table('test_user')
//            ->column([
//                'username' => 'my_name',
//                'nickname',
//                new Raw('`type`+1000 as type2'),
//            ], 'id');
//        $expected = array_column_ex(
//            $users,
//            [
//                'username' => 'my_name',
//                'nickname',
//                'type2' => function ($value) {
//                    return $value['type'] + 1000;
//                },
//                'id'
//            ],
//            'id'
//        );
//        array_value_sort($result);
//        array_value_sort($expected);
//        $this->assertEquals($expected, $result);
    }

    public function testWhereIn()
    {
//        $sqlLogs = [];
//        Db::listen(function ($sql) use (&$sqlLogs) {
//            $sqlLogs[] = $sql;
//        });
//
        $expected = [];
        $expectedTypes = [1, 3];
        foreach (self::$testUserData as $user) {
            if (in_array($user['type'], $expectedTypes)) {
                $expected[] = $user;
            }
        }
        $result = M('user')
            ->where(['type' => ['in', [1, 3]]])
            ->field('*')->select();
        $this->assertEquals($expected, $result);

        $expected = [];
        $expectedTypes = [1, ''];
        foreach (self::$testUserData as $user) {
            if (in_array($user['type'], $expectedTypes)) {
                $expected[] = $user;
            }
        }
        $result = M('user')
            ->where(['type' => ['in', [1, '']]])
            ->field('*')->select();
        $this->assertEquals($expected, $result);

    }

    public function testUpdate()
    {
        $id = 2;
        $expected = 'abc';
        M('user')->where(['id' => $id])->save(['nickname' => $expected]);

        $actual = M('user')->where(['id' => $id])->getField('nickname');
        $this->assertEquals($expected, $actual);
    }

    public function test_1st()
    {
        $rows = M('user')->select();
        $this->assertEquals(count($rows), 5, 'fake true');

        $users = self::$testUserData;
        $this->assertEquals(array_column($users, 'username'), array_column($rows, 'username'));
    }

    public function testCache()
    {
        $expected = [];
        $expectedTypes = [1, 3];
        foreach (self::$testUserData as $user) {
            if (in_array($user['type'], $expectedTypes)) {
                $expected[] = $user;
            }
        }
        $result = M('user')
            ->where(['type' =>  ['in', $expectedTypes]])
            ->cache('some_users', 6000)->select();

        $this->assertEquals($expected, $result);
    }
}
