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

class UserModel extends Model
{

    /**
     * 操作指定数据库表
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getUsersByWhere($where, $offset, $limit)
    {

 //        $rows = (new \yii\db\Query())
//            ->select(['id', 'email'])
//            ->from('user')
//            ->where(['last_name' => 'Smith'])
//            ->limit(10)
//            ->all();

//
//        $query = (new \yii\db\Query())
//            ->select(['f.id', 'f.key', 'f.fnum', 'f.fdate', 'f.dep_code', 'f.arr_code','f.dep_city', 'f.arr_city', 'f.fstatus', 'f.status', 'f.boarding_status','u.mobile','p.pkey','p.ptoken','up.puid'])
//            ->join('LEFT JOIN', '{{%user}} u', 'u.id = f.uid')
//            ->join('LEFT JOIN', '{{%platform}} p', 'p.id = f.pid')
//            ->join('LEFT JOIN', '{{%user_platform}} up', 'up.uid = u.id')
//            ->from('{{%user_flight}} f')
//            ->where(['f.status' => 1])
//            ->limit(1000);



        $row= (new \yii\db\Query())->select(['u.*','r.rolename'])
                             ->join('LEFT JOIN','{{%role}} r','u.typeid = r.id')
                             ->from('{{%user}} u')
                             ->where($where)
                            ->limit($offset.$limit)
                            ->orderBy('u.id desc')
                            ->all();

        return $row;
    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAllUsers($where)
    {
        return  (new \yii\db\Query())->from("{{%user}} u")->where($where)->count();
    }

    /**
     * 插入管理员信息
     * @param $param
     */
    public function insertUser($param)
    {
        try{

            $result =  $this->validate('UserValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '添加用户成功'];
            }
        }catch( \PDOException $e){

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

            $result =  $this->validate('UserValidate')->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{

                return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneUser($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除管理员
     * @param $id
     */
    public function delUser($id)
    {
        try{

            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除管理员成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}