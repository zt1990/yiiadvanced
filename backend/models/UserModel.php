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
use yii\db\ActiveRecord;

class UserModel extends ActiveRecord
{

    public $username;
    public $password;
    public $real_name;
    public $typeid;


    /**
     * 操作指定数据库表
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    /**
     * @inheritdoc
     * 插入数据验证
     */
    public function rules()
    {
        return [
            //用户名验证
            //trim一下输入信息
            ['username', 'trim'],
            //必填项
            ['username', 'required'],
            //唯一性,自定义查询数据是否存在
            ['username', 'checkusername','on'=>'insertUser'],
            //输入类型，大小范围
            ['username', 'string', 'length' => [2, 255]],


            //必填项
            ['password', 'required'],
            //输入类型，大小范围(由于密码已经加密无需验证长度)
            //['password', 'string','min'=>6],

            //验证是否选择分类信息
            ['typeid','required'],

            //验证真实姓名
            ['real_name','trim']
        ];
    }



    //查询用户名子否存在
    public function checkusername($attribute,$params){
        $oldtag = UserModel::find()->where(array('username'=>$this->username))->one();
        if($oldtag->id > 0){
            $this->addError($attribute, $this->username.'用户名已经存在!');
        }
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
                            ->offset($offset)
                            ->limit($limit)
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
                $uret = $connection->createCommand()->update('snake_user', ['username'=>"sfsdgdfgdfgdf"],
                    "id=:id", [
                    ':id' => 48
                ]);

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
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除管理员成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}