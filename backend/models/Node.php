<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace backend\models;
use Codeception\Lib\Interfaces\ActiveRecord;
use yii\base\Model;
use common\helps\tools;

class Node extends Model
{


    /**
     * 操作指定数据库表
     */
    public static function tableName()
    {
        return '{{%node}}';
    }


    /**
     * 获取节点数据
     */
    public function getNodeInfo($id)
    {
        $query = (new \yii\db\Query())
            ->from('{{%node}}');

        $result = $query->select('id,node_name,typeid')->all();
        $str = "";

        $role = new UserType();
        $rule = $role->getRuleById($id);

        if(!empty($rule)){
            $rule = explode(',', $rule);
        }
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['typeid'] . '", "name":"' . $vo['node_name'].'"';

            if(!empty($rule) && in_array($vo['id'], $rule)){
                $str .= ' ,"checked":1';
            }
            $str .= '},';
        }

        return "[" . substr($str, 0, -1) . "]";
    }

    /**
     * 根据节点数据获取对应的菜单
     * @param $nodeStr
     */
    public function getMenu($nodeStr = '')
    {
        //超级管理员没有节点数组
        $where = empty($nodeStr) ? 'is_menu = 2' : 'is_menu = 2 and id in('.$nodeStr.')';
        $result = (new \yii\db\Query())
        ->select('id,node_name,typeid,control_name,action_name,style')
        ->from('{{%node}}')
        ->where($where)
        ->all();

        $tools = new tools();
        $menu = $tools->prepareMenu($result);

        return $menu;
    }
}