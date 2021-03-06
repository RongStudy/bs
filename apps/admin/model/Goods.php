<?php 
namespace app\admin\model;
use think\Model;

class Goods extends Model{
    /**
     * 表名
     */
    protected $table = 'bs_goods';

    /**
     * 添加商品
     */
    public function addGoods($map){
        $data = \think\Db::table($this->table)->insert($map);
        return $data;
    }

    /**
     * 获取所有商品
     */
    public function getAll($page=10, $field='*', $map){
        $data = \think\Db::table($this->table)->field($field)->order('gSort desc')->where($map)->paginate($page);
        return $data;
    }

    /**
     * 获取商品详情
     */
    public function getOne($map){
        return \think\Db::table($this->table)->find($map);
    }

    /**
     * 修改商品
     */
    public function editGoods($where, $map){
        $data = \think\Db::table($this->table)->where($where)->update($map);
        return $data;
    }

    /**
     * 停用启用商品
     */
    public function stopOrStartGoods($where, $map){
        $list = \think\Db::table($this->table)->where($where)->update($map);
        return $list;
    }

    /**
     * 删除商品
     */
    public function delGoods($map){
        return \think\Db::table($this->table)->where($map)->delete();
    }
    
}


 ?>