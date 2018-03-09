<?php
namespace app\user\controller;

use app\user\model\RsGroupModel;
use think\Controller;
use think\Db;


/**
 * 日期: 2018-03-03
 * 用途: 资源控制器
 */
class Resource extends Controller
{

    /**
     * 日期: 2018-03-03
     * 用途: 首页
     */
    public function index()
    {
        return $this -> fetch();
    }


    /**
     * 日期: 2018-03-03
     * 用途: 资源组树数据源
     */
    public function resource_group_tree()
    {
        $nodes = array();
        $root['id'] = 0;
        $root['name'] = '所有资源组';
        $root['open'] = true;
        array_push($nodes, $root);

        $rsGroupModel = new RsGroupModel();

        $groups = $rsGroupModel->get_batch(null);
        foreach ($groups as $k => $v) {
            $child['id'] = $v['id'];
            $child['pId'] = $v['pid'];
            $child['name'] = $v['group_name'];
            array_push($nodes, $child);
        }
        return json($nodes);
    }


    /**
     * 日期: 2018-03-07
     * 用途: 判断资源组下是否有资源
     */
    public function count_group()
    {
        $result['success'] = true;
        if (!empty($_POST['groupId'])) {
            $int = Db::table('test_resource')->where('group_id', $_POST['groupId'])->count();
            if ($int) {
                $result['success'] = false;
                $result['message'] = '该组下面有资源，不能删除';
            }
        }
        return json($result);
    }
}
