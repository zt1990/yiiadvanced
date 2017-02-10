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

class UserType extends SnakeRole
{

    /**
     * 操作指定数据库表
     */
    public static function tableName()
    {
        return '{{%role}}';
    }



    /**
     * 根据搜索条件获取角色列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getRoleByWhere($where, $offset, $limit)
    {

        return (new \yii\db\Query())
            ->from('{{%role}} u')
            ->where($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('id desc')
            ->all();
    }

    /**
     * 根据搜索条件获取所有的角色数量
     * @param $where
     */
    public function getAllRole($where)
    {
        return UserType::find()->where($where)->count();
    }

    /**
     * 插入角色信息
     * @param $param
     */
    public function insertRole($param)
    {
        try{

            //表单验证信息
            $this->load($param);
            if (!$this->validate()) {
                return ['code' => -1, 'data' => '', 'msg' => $this->getErrors()];
            }else{

                //保存数据入库
                $connection = \Yii::$app->db;
                // 批量插入数据 一次插入多行
                $ret = $connection->createCommand()->batchInsert('snake_role', ['rolename'], [
                    [$this->rolename],
                ])->execute();

                if($ret){
                    return ['code' => 1, 'data' => '', 'msg' => '添加角色成功'];
                }else{
                    return ['code' => -1, 'data' => '', 'msg' => '添加角色失败'];
                }
            }

        }catch(\Exception $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑角色信息
     * @param $param
     */
    public function editRole($param)
    {
        try{
            //表单验证信息
            $this->load($param);
            if (!$this->validate()) {
                return ['code' => -1, 'data' => '', 'msg' => $this->getErrors()];
            }else{
                //保存数据入库
                $connection = \Yii::$app->db;
                $uret = $connection->createCommand()->update('snake_role', $param['UserType'],
                    "id=:id", [
                        ':id' => $param['UserType']['id']
                    ])->execute();
                if($uret){
                    return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
                }else{
                    return ['code' => -1, 'data' => '', 'msg' => '编辑用户失败'];
                }
            }

        }catch(\Exception $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 根据角色id获取角色信息
     * @param $id
     */
    public function getOneRole($id)
    {
        return self::find()->where(['id'=>$id])->one();
    }

    /**
     * 删除角色
     * @param $id
     */
    public function delRole($id)
    {
        try{
            $connection = \Yii::$app->db;
            $connection->createCommand()->delete('snake_role', 'id='.$id)->execute();
            return ['code' => 1, 'data' => '', 'msg' => '删除角色成功'];
        }catch(\Exception $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    //获取所有的角色信息
    public function getRole()
    {
        return UserType::find()->all();
    }

    //获取角色的权限节点
    public function getRuleById($id)
    {
        $res =  SnakeRole::find()->select('rule')->where(['id'=>$id])->one();
        return $res['rule'];
    }

    /**
     * 分配权限
     * @param $param
     */
    public function editAccess($param)
    {
        try{
            //保存数据入库
            $connection = \Yii::$app->db;
            $connection->createCommand()->update('snake_role', $param,
                "id=:id", [
                    ':id' => $param['id']
                ])->execute();
            return ['code' => 1, 'data' => '', 'msg' => '分配权限成功'];

        }catch(\Exception $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 获取角色信息
     * @param $id
     */
    public function getRoleInfo($id){

        $result = db('role')->where('id', $id)->find();
        if(empty($result['rule'])){
            $where = '';
        }else{
            $where = 'id in('.$result['rule'].')';
        }
        $res = db('node')->field('control_name,action_name')->where($where)->select();
        foreach($res as $key=>$vo){
            if('#' != $vo['action_name']){
                $result['action'][] = $vo['control_name'] . '/' . $vo['action_name'];
            }
        }

        return $result;
    }
}