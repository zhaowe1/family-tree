<?php

namespace FamilyTree;

use FamilyTree\common\User;
use Medoo\Medoo;

class Model
{
    public static $dbDriver;

    public static $config = [
        'database_type' => 'mysql',
        'database_name' => 'family_tree',
        'server' => '127.0.0.1',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8mb4',
        'port' => 3305,
    ];

    public static function DB()
    {
        if (empty(static::$dbDriver)) {
            try {
                static::$dbDriver = new Medoo(static::$config);
            } catch (\Exception $e) {
                echo '<h3>数据库连接失败，请检查数据库连接。更多信息参考README文件。</h3>';
                exit;
            }
        }
        return static::$dbDriver;
    }

    public static function query()
    {
        return static::DB();
    }

    /**
     * 初始化数据
     * @return array
     */
    public static function initData()
    {
        return [
            ['id' => 1, 'name' => '王大锤', 'sex' => User::MAN, 'birthday' => '1990-01-01', 'f_id' => 3, 'm_id' => 4],
            ['id' => 2, 'name' => '王尼美', 'sex' => User::WOMAN, 'birthday' => '1990-02-01', 'f_id' => 3, 'm_id' => 4],
            ['id' => 3, 'name' => '王建国', 'sex' => User::MAN, 'birthday' => '1970-03-03', 'f_id' => 0, 'm_id' => 0],
            ['id' => 4, 'name' => '李秀英', 'sex' => User::WOMAN, 'birthday' => '1970-03-03', 'f_id' => 0, 'm_id' => 0],
            ['id' => 5, 'name' => '赵铁柱', 'sex' => User::MAN, 'birthday' => '2010-04-04', 'f_id' => 0, 'm_id' => 2],
            ['id' => 6, 'name' => '王小明', 'sex' => User::MAN, 'birthday' => '2010-05-05', 'f_id' => 1, 'm_id' => 0],
        ];
    }

    /**
     * 获取全部用户数据
     * @return array
     */
    public static function getAllUser()
    {
        return static::DB()->select('user', ['id', 'name', 'sex', 'birthday', 'f_id', 'm_id']);
    }

    /**
     * 获取单个用户数据
     * @param $uid
     * @return array
     */
    public static function getUser($uid)
    {
        $data = static::DB()->select('user', ['id', 'name', 'sex', 'birthday', 'f_id', 'm_id'], ['id' => $uid]);
        if (isset($data[0])) {
            return $data[0];
        } else {
            return [];
        }
    }
}
