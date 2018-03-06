<?php
namespace app\user\model;

use think\Model;


/**
 * 日期: 2018-03-03
 * 用途: 资源模型
 */
class RsModel extends Model
{
    protected $table = 'test_resource';


    /**
     * 日期: 2018-03-03
     * 用途: 分页查询
     */
    public function get_page($map, $field = '*', $order = 'id desc', $begin, $size)
    {
        return $this->where($map)->field($field)->order($order)->limit($begin, $size)->select();
    }

    /**
     * 日期: 2018-03-03
     * 用途: 批量查询
     */
    public function get_batch($map='1=1', $field = '*')
    {
        return $this->where($map)->field($field)->select();
    }

    /**
     * 日期: 2018-03-03
     * 用途: 批量查询
     */
    public function get_one($map, $field = '*')
    {
        return $this->where($map)->field($field)->find();
    }
}
