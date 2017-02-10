<?php
namespace backend\controllers;

use yii;
use yii\web\Controller;
use backend\controllers\BaseController;
use common\helps\tools;

class DataController extends BaseController
{
    //取消main.php
    public $layout = false;

    //备份首页列表
    public function actionIndex()
    {
        $_db =Yii::$app->db;
        $tables = $_db->createCommand('show tables')->queryAll();
        $database = substr(strrchr(Yii::$app->db->dsn, '='), 1);
        foreach($tables as $key=>$vo){
            $sql = "select count(0) as alls from " . $vo['Tables_in_' .$database];
            $tables[$key]['alls'] = $_db->createCommand($sql)->queryAll()['0']['alls'];
            $operate = [
                '备份' => "javascript:importData('". $vo['Tables_in_' . $database]."', ".$tables[$key]['alls'].")",
                '还原' => "javascript:backData('" . $vo['Tables_in_' . $database] . "')"
            ];
            $tables[$key]['operate'] = tools::showOperate($operate);
            if(file_exists(Yii::$app->params['back_path'] . $vo['Tables_in_' . $database] . ".sql")){
                $tables[$key]['ctime'] = date('Y-m-d H:i:s', filemtime(Yii::$app->params['back_path'] . $vo['Tables_in_' . $database] . ".sql"));
            }else{
                $tables[$key]['ctime'] = '无';
            }
        }

       return $this->render('index',['tables' => $tables]);
    }

    //备份数据
    public function actionImportData()
    {
        $_db =Yii::$app->db;
       // $database = substr(strrchr(Yii::$app->db->dsn, '='), 1);
        set_time_limit(0);
        $table=  tools::isParams('table');
        $sqlStr = "SET FOREIGN_KEY_CHECKS=0;\r\n";
        $sqlStr .= "DROP TABLE IF EXISTS `$table`;\r\n";
        $create = $_db->createCommand('show create table ' . $table)->queryAll();
        $sqlStr .= $create['0']['Create Table'] . ";\r\n";
        $sqlStr .= "\r\n";

        $result = $_db->createCommand('select * from ' . $table)->queryAll();
        foreach($result as $key=>$vo){
            $keys = array_keys($vo);
            $keys = array_map('addslashes', $keys);
            $keys = join('`,`', $keys);
            $keys = "`" . $keys . "`";
            $vals = array_values($vo);
            $vals = array_map('addslashes', $vals);
            $vals = join("','", $vals);
            $vals = "'" . $vals . "'";
            $sqlStr .= "insert into `$table`($keys) values($vals);\r\n";
        }

        $filename = Yii::$app->params['back_path'] . $table . ".sql";
        $fp = fopen($filename, 'w');
        fputs($fp, $sqlStr);
        fclose($fp);

        return json_encode(['code' => 1, 'data' => '', 'msg' => 'success']);
    }

    //还原数据
    public function actionBackData()
    {
        $_db =Yii::$app->db;
        set_time_limit(0);
        $table = tools::isParams('table');

        if(!file_exists(Yii::$app->params['back_path'] . $table . ".sql")){
            return json_encode(['code' => -1, 'data' => '', 'msg' => '备份数据不存在!']);
        }

        $sqls = tools::analysisSql(Yii::$app->params['back_path'] . $table . ".sql");
        foreach($sqls as $key=>$sql){
             $_db->createCommand($sql)->execute();
        }
        return json_encode(['code' => 1, 'data' => '', 'msg' => 'success']);
    }

}
