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

use Yii;
use yii\web\Controller;


use yii\helpers\Url;

class BaseController extends Controller
{



    public function init()
    {

//        if(empty(session('username'))){
//
//            $this->redirect(Url::to('login/index'));
//        }

//        //检测权限
//        $control = lcfirst( request()->controller() );
//        $action = lcfirst( request()->action() );
//
//        //跳过登录系列的检测以及主页权限
//        if(!in_array($control, ['login', 'index'])){
//
//            if(!in_array($control . '/' . $action, session('action'))){
//                $this->error('没有权限');
//            }
//        }

    }
}