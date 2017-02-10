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
use yii\base\Model;
use backend\models\SnakeUser;
use backend\models\UserType;

class UserModel extends \backend\models\SnakeUser
{



    /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getUsersByWhere($where, $offset, $limit)
    {
        /**
         * @return \yii\db\ActiveQuery
         */
        return (new \yii\db\Query())->select(['u.*','r.rolename'])
                             ->join('LEFT JOIN','{{%role}} r','u.typeid = r.id')
                             ->from('{{%user}} u')
                             ->where($where)
                            ->offset($offset)
                            ->limit($limit)
                            ->orderBy('u.id desc')
                            ->all();
    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAllUsers($where)
    {
        return static::find($where)->count();
    }

    /**
     * 插入管理员信息
     * @param $param
     */
    public function insertUser($param)
    {
        try{
            //表单验证信息
            $this->load($param);
            $result =  $this->validate();
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getErrors()];
            }else{
                //保存数据入库
                $connection = \Yii::$app->db;
                // 批量插入数据 一次插入多行
                $ret = $connection->createCommand()->batchInsert('snake_user', ['username', 'password','real_name','typeid'], [
                    [$this->username,md5($this->password),$this->real_name,$this->typeid],
                ])->execute();

                if($ret){
                    return ['code' => 1, 'data' => '', 'msg' => '添加用户成功'];
                }else{
                    return ['code' => -1, 'data' => '', 'msg' => '添加用户失败'];
                }

            }
        }catch(\PDOException $e){

            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑管理员信息
     * @param $param
     */
    public function editUser($param)
    {
        try{

            //表单验证信息
            $this->load($param);
            $result =  $this->validate();
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getErrors()];
            }else{
                //保存数据入库
                $connection = \Yii::$app->db;
                $uret = $connection->createCommand()->update('snake_user', $param['UserModel'],
                    "id=:id", [
                    ':id' => $param['UserModel']['id']
                ])->execute();

                if($uret){
                    return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
                }else{
                    return ['code' => -1, 'data' => '', 'msg' => '编辑用户失败'];
                }
            }
        }catch( \PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneUser($id)
    {
        return  (new \yii\db\Query())->from("{{%user}} u")->where(['id'=>$id])->one();
    }

    /**
     * 删除管理员
     * @param $id
     */
    public function delUser($id)
    {
        try{
            $connection = \Yii::$app->db;
            $connection->createCommand()->delete('snake_user', 'id='.$id)->execute();
            return ['code' => 1, 'data' => '', 'msg' => '删除管理员成功'];

        }catch(\PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}