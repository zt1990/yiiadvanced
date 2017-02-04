<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       // 'css/site.css',
        'admin/css/bootstrap.min.css',
        'admin/css/font-awesome.min.css',
        'admin/css/animate.min.css',
        'admin/css/style.min.css.css',
    ];
    public $js = [
        'admin/js/jquery.min.js',
        'admin/js/bootstrap.min.js',
        'admin/js/plugins/metisMenu/jquery.metisMenu.js',
        'admin/js/plugins/slimscroll/jquery.slimscroll.min.js',
        'admin/js/plugins/layer/layer.min.js',
        'admin/js/hplus.min.js',
        'admin/js/contabs.js',
        'admin/js/pace.min.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
