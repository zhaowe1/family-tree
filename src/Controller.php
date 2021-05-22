<?php

namespace FamilyTree;

use FamilyTree\common\Family;
use FamilyTree\common\User;

class Controller
{
    /**
     * 家谱数据
     */
    public $family;

    /**
     * 初始化家谱数据
     */
    protected function initFamily()
    {
        $this->family = new Family();
        foreach (Model::getAllUser() as $user) {
            $this->family->add(new User($user));
        }
    }

    /**
     * 获取可视化数据源
     * @return string
     */
    public function graphData()
    {
        $this->initFamily();
        return $this->family->getGraphData();
    }

    /**
     * 首页
     */
    public function index()
    {
        $this->initFamily();

        $rText = '';
        if (!empty($_GET['aid']) && !empty($_GET['bid'])) {
            $rText = $this->family->getRelation(intval($_GET['aid']), intval($_GET['bid']));
        }

        include VIEW . 'index.php';
    }

    /**
     * 用户添加/修改
     */
    public function edit()
    {
        $id = 0;
        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        }

        if (empty($_POST)) {
            $manArr = Model::query()->select('user', ['id', 'name'], ['sex' => User::MAN]);
            array_unshift($manArr, ['id' => 0, 'name' => '未知']);

            $womanArr = Model::query()->select('user', ['id', 'name'], ['sex' => User::WOMAN]);
            array_unshift($womanArr, ['id' => 0, 'name' => '未知']);

            $currentUser = Model::getUser($id);
            include VIEW . 'edit.php';
        } else {
            $data = [
                'name' => $_POST['name'],
                'sex' => $_POST['sex'],
                'birthday' => $_POST['birthday'],
                'f_id' => $_POST['f_id'],
                'm_id' => $_POST['m_id'],
            ];

            if ($id == 0) {
                Model::query()->insert('user', $data);
            } else {
                Model::query()->update('user', $data, ['id' => $id]);
            }

            header('Location: /');
        }
    }

    /**
     * 用户删除
     */
    public function del()
    {
        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
            Model::query()->delete('user', ['id' => $id]);
            Model::query()->update('user', ['f_id' => 0], ['f_id' => $id]);
            Model::query()->update('user', ['m_id' => 0], ['m_id' => $id]);
        }

        header('Location: /');
    }

    /**
     * 数据重置
     */
    public function reset()
    {
        Model::query()->query('TRUNCATE TABLE user');
        Model::query()->insert('user', Model::initData());

        header('Location: /');
    }
}