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
use backend\models\Node;
use backend\models\UserType;
use common\helps\tools;
class RoleController extends BaseController
{
    public $layout = false;
    //角色列表
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $request = Yii::$app->request;
            $param = $request->get();
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['rolename'] = ['like', '%' . $param['searchText'] . '%'];
            }
            $user = new UserType();
            $selectResult = $user->getRoleByWhere($where, $offset, $limit);
            foreach($selectResult as $key=>$vo){

                if(1 == $vo['id']){
                    $selectResult[$key]['operate'] = '';
                    continue;
                }

                $operate = [
                    '编辑' => \Yii::$app->urlManager->createUrl(['role/role-edit', 'id' => $vo['id']]),
                    '删除' => "javascript:roleDel('".$vo['id']."')",
                    '分配权限' => "javascript:giveQx('".$vo['id']."')"
                ];
                $tools = new tools();
                $selectResult[$key]['operate'] = $tools->showOperate($operate);
            }
            $return['total'] = $user->getAllRole($where);  //总数据
            $return['rows'] = $selectResult;
            return json_encode($return);
        }

        return $this->render('index');
    }

    //添加角色
    public function actionRoleAdd()
    {
        if(Yii::$app->request->isPost){

           // $request = Yii::$app->request;
            $params = Yii::$app->request->post();
            $param['UserType'] = tools::parseParams($params['data']);
            //剔除csrf验证码
            unset($param['UserType']['_csrf']);
            $role = new UserType();
            $flag = $role->insertRole($param);

            return json_encode(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return $this->render('roleadd');
    }

    //编辑角色
    public function actionRoleEdit()
    {
        $role = new UserType();
        if(Yii::$app->request->isPost){
            $params = Yii::$app->request->post();

            $param['UserType'] = tools::parseParams($params['data']);
            //剔除csrf验证码
            unset($param['UserType']['_csrf']);
            $flag = $role->editRole($param);
            return json_encode(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = Yii::$app->request->get('id');
        return $this->render('roleedit',['id'=>$id,'role'=>$role->getOneRole($id)]);
    }

    //删除角色
    public function actionRoleDel()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $role = new UserType();
        $flag = $role->delRole($id);
        return json_encode(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    //分配权限
    public function actionGiveAccess()
    {
        $param = tools::isParams();
        $node = new Node();
        //获取现在的权限
        if('get' == $param['type']){
            $nodeStr = $node->getNodeInfo($param['id']);
            return json_encode(['code' => 1, 'data' => $nodeStr, 'msg' => 'success']);
        }
        //分配新权限
        if('give' == $param['type']){
            $doparam = [
                'id' => $param['id'],
                'rule' => $param['rule']
            ];
            $user = new UserType();
            $flag = $user->editAccess($doparam);
            return json_encode(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


}