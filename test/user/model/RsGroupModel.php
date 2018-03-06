<?php
namespace app\user\model;

use think\Model;


/**
 * 日期: 2018-03-03
 * 用途: 资源组模型
 */
class RsGroupModel extends Model
{
    protected $table = 'test_resource_group';


    /**
     * 日期: 2018-03-03
     * 用途: 批量查询
     */
    public function get_batch($map, $field = '*')
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
