<?php
namespace app\user\controller;

use app\user\model\RsGroupModel;
use app\user\model\RsModel;
use think\Controller;


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
     * 用途: 资源分页
     */
    public function get_by_page()
    {
        $rsModel = new RsModel();
        $pageIndex = 1;
        if (!empty($_POST['pageIndex'])) {
            $pageIndex = $_POST['pageIndex'];
        }
        $map = null;
        if(!empty($_POST['data'])){
            $params = $_POST['data'];
            foreach ($params as $k => $v) {
                $map[$v['name']] = array($v['op'], $v['value']);
            }
        }

        $order = null;
        if(!empty($_POST['order'])){
            $order = $_POST['order'];
        }
        $result['list'] = $rsModel->where($map)
            ->order($order)
            ->limit(($pageIndex - 1) * 12, 12)
            ->select();
        $result['pageIndex'] = $pageIndex;
        $result['totalSize'] = $rsModel->where($map)->count();
        $result['totalPage'] = ceil($result['totalSize'] / 12);
        $result['success'] = true;
        $result['message'] = '查询成功';
        return json($result);
    }


    /**
     * 日期: 2018-03-03
     * 用途: 资源组树数据源
     */
    public function resource_group_tree(){
        $nodes = array();
        $root['id'] = 0;
        $root['name'] = '所有资源组';
        $root['open'] = true;
        array_push($nodes,$root);

        $rsGroupModel = new RsGroupModel();
        $map['id'] = 1;
        $groups = $rsGroupModel -> get_batch($map);
        foreach($groups as $k => $v){
            $child['id'] = $v['id'];
            $child['pId'] = 0;
            $child['name'] = $v['group_name'];
            array_push($nodes,$child);
        }
        return json($nodes);
    }


    /**
     * 日期: 2018-03-06
     * 用途: 新增资源组
     */
    public function add_group(){
        $result['success'] = false;
        $result['message'] = '新增失败';
        try {
            if ($_POST && empty($_POST['id'])) {
                $rsGroupModel = new RsGroupModel();
                $_POST['create_time'] = date('Y-m-d H:i:s');
                $int = $rsGroupModel->save($_POST);
                if ($int) {
                    $result['success'] = true;
                    $result['message'] = '新增成功';
                }
            }
        } catch (\Exception $e) {
            $result['error'] = $e ->getMessage();
        } finally {
            return json($result);
        }
    }


    /**
     * 日期: 2018-03-06
     * 用途: 修改资源组
     */
    public function edit_group(){
        $result['success'] = false;
        $result['message'] = '修改失败';
        try {
            if ($_POST && $_POST['id']) {
                $rsGroupModel = new RsGroupModel();
                $rsGroupModel->save($_POST);
                $result['success'] = true;
                $result['message'] = '修改成功';
            }
        } catch (\Exception $e) {

        } finally {
            return json($result);
        }
    }

    /**
     * 日期: 2018-03-06
     * 用途: 删除资源组
     */
    public function remove_group(){
        $result['success'] = false;
        $result['message'] = '删除失败';
        try {
            if ($_POST['id']) {
                $history = RsGroupModel::get($_POST['id']);
                $int = $history -> delete();
                if($int){
                    $result['success'] = true;
                    $result['message'] = '删除成功';
                }
            }
        } catch (\Exception $e) {

        } finally {
            return json($result);
        }
    }
}
