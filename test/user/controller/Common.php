<?php
namespace app\user\controller;

use app\user\model\UserModel;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Exception;


/**
 * 日期: 2018-03-07
 * 用途: 公用控制器
 */
class Common extends Controller
{

    /**
     * 日期: 2018-03-07
     * 用途: 公用分页
     */
    public function common_page()
    {

        try {
            $pageIndex = 1;
            if (!empty($_POST['pageIndex'])) {
                $pageIndex = $_POST['pageIndex'];
            }
            $map = null;
            if (!empty($_POST['data'])) {
                $params = $_POST['data'];
                $map = array();
                foreach ($params as $k => $v) {
                    if ($v['op'] == 'eq') {
                        $map[$v['name']] = $v['value'];
                    } else {
                        $map[$v['name']] = [$v['op'], $v['value']];
                    }
                }
            }
            $order = null;
            if (!empty($_POST['order'])) {
                $order = $_POST['order'];
            }
            $table = Config('database.prefix') . $_POST['model'];
            $result['list'] = Db::table($table)->where($map)
                ->order($order)
                ->limit(($pageIndex - 1) * 12, 12)
                ->select();
            $result['pageIndex'] = $pageIndex;
            $result['totalSize'] = Db::table($table)->where($map)->count();
            $result['totalPage'] = ceil($result['totalSize'] / 12);
            $result['success'] = true;
            $result['message'] = '查询成功';
        } catch (Exception $e) {
            $result['success'] = false;
            $result['list'] = [];
            $result['pageIndex'] = 1;
            $result['totalSize'] = 0;
            $result['totalPage'] = 0;
            $result['message'] = $e->getMessage();
        } finally {
            return json($result);
        }
    }


    /**
     * 日期: 2018-03-07
     * 用途: 公用新增
     */
    public function common_add()
    {
        $result = null;
        try {
            if ($_POST['data'] && empty($_POST['data']['id'])) {
                $entity = $_POST['data'];
                $model = Db::table(Config('database.prefix').$_POST['model']);
                $entity['create_time'] = date('Y-m-d H:i:s');
                $int = $model->insert($entity);
                if ($int) {
                    $result['success'] = true;
                    $result['message'] = '新增成功';
                }
            } else {
                throw new Exception('新增请勿带入id');
            }
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        } finally {
            return json($result);
        }
    }

    /**
     * 日期: 2018-03-07
     * 用途: 公用修改
     */
    public function common_edit()
    {
        $result = null;
        try {
            $model = Db::table(Config('database.prefix') . $_POST['model']);
            $model->save($_POST, ['id' => $_POST['id']]);
            $result['success'] = true;
            $result['message'] = '修改成功';
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        } finally {
            return json($result);
        }
    }

    /**
     * 日期: 2018-03-07
     * 用途: 公用删除
     */
    public function common_delete()
    {
        $result = null;
        try {
            $model = Db::table(Config('database.prefix') . $_POST['model']);
            $int = $model->delete($_POST['id']);
            if ($int) {
                $result['success'] = true;
                $result['message'] = '删除成功';
            }
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        } finally {
            return json($result);
        }
    }

    /**
     * 日期: 2018-03-07
     * 用途: 公用查找单条数据
     */
    public function common_get_one()
    {
        $result = null;
        try {
            $model = Db::table(Config('database.prefix') . $_POST['model']);
            $history = $model::get($_POST['id']);
            $result['data'] = $history;
            $result['success'] = true;
            $result['message'] = '查询成功';
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        } finally {
            return json($result);
        }
    }

}
