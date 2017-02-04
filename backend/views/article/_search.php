<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'aid') ?>

    <?= $form->field($model, 'cateid') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'flag') ?>

    <?= $form->field($model, 'cover') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'vieworder') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'linkurl') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'modify_time') ?>

    <?php // echo $form->field($model, 'isrecycle') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
