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

namespace backend\controllers;

use yii;
use yii\web\Controller;
use backend\controllers\BaseController;

use backend\models\UserModel;
use backend\models\UserType;
use common\helps\tools;

class UserController extends BaseController
{
    public $layout = false;
    //csrf验证关闭
    public $enableCsrfValidation = false;


    //用户列表
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){

            $request = Yii::$app->request;
            $param = $request->get();
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where = 'u.username like "%'. $param["searchText"] . '%"';
            }
            $user = new UserModel();
            $selectResult = $user->getUsersByWhere($where, $offset, $limit);

            $status = Yii::$app->params['user_status'];

            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['last_login_time'] = date('Y-m-d H:i:s', $vo['last_login_time']);
                $selectResult[$key]['status'] = $status[$vo['status']];

                $operate = [
                    //传参
                    '编辑' => Yii::$app->urlManager->createUrl(['user/user-edit', 'id' => $vo['id']]),
                    '删除' => "javascript:userDel('".$vo['id']."')"
                ];

                $tools = new tools();
                $selectResult[$key]['operate'] = $tools->showOperate($operate);

                if( 1 == $vo['id'] ){
                	$selectResult[$key]['operate'] = '';
                }
            }

            $return['total'] = $user->getAllUsers($where);  //总数据
            $return['rows'] = $selectResult;

            return json_encode($return);
        }

        return $this->render('index');
    }



    //添加用户
    public function actionUserAdd()
    {

        if(Yii::$app->request->isPost){
            $params =  Yii::$app->request->post();
            //实例化工具类
            $tools = new tools();
            $param['UserModel'] = $tools->parseParams($params['data']);

            $user = new UserModel();
            $flag = $user->insertUser($param);
            return json_encode(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $role = new UserType();
        return $this->render('useradd',['role'=>$role->getRole(),'status'=>Yii::$app->params['user_status']]);
    }



    //编辑角色
    public function actionUserEdit()
    {
        $user = new UserModel();

        if(Yii::$app->request->isPost){

            $params =  Yii::$app->request->post();
            //实例化工具类
            $tools = new tools();
            $param['UserModel'] = $tools->parseParams($params['data']);
            if(empty($param['UserModel']['password'])){
                unset($param['UserModel']['password']);
            }else{
                $param['UserModel']['password'] = md5($param['UserModel']['password']);
            }
            $flag = $user->editUser($param);

            return json_encode(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = Yii::$app->request->get('id');
        $role = new UserType();
        return $this->render('useredit',['user'=>$user->getOneUser($id),'role'=>$role->getRole(),'status'=>Yii::$app->params['user_status']]);
    }

    //删除角色
    public function actionUserDel()
    {
        $id = Yii::$app->request->get('id');

        $role = new UserModel();
        $flag = $role->delUser($id);
        return json_encode(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}
