<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use backend\models\Node;
//使用session方法
use  yii\web\Session;

class IndexController extends BaseController{

    public $layout=false; //重写这个属性就可以了

    public function init()
    {
        //获取权限菜单
        $node = new Node();
        $session = Yii::$app->session;
        $row['username'] = $session->get('username');
        $row['menu'] = $node->getMenu($session->get('rule'));
        $row['rolename'] = $session->get('role');
        return $row;

    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        //渲染模板一定要加return
        return $this->render('index',$this->init());
    }

    /**
     * 后台默认首页
     * @return mixed
     */
    public function actionIndexPage()
    {
       return $this->render('indexpage');
    }


}
