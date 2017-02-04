<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>




    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=  $form->field($model, 'cateid')->dropDownList(['1'=>'大学','2'=>'高中','3'=>'初中'], ['prompt'=>'请选择','style'=>'width:120px']) ?>

    <?= $form->field($model, 'flag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover')->fileInput() ?>




    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'description')->textarea(['rows'=>3]) ?>

    <?= $form->field($model, 'vieworder')->textInput() ?>

    <?= $form->field($model, 'hits')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linkurl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modify_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isrecycle')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



<!--<div class="article-form">-->
<!---->
<?php //$form = ActiveForm::begin(); ?>
<!--    <div class="table_full">-->
<!--        <table width="100%">-->
<!--            <colgroup>-->
<!--                <col class="th">-->
<!--                <col width="400">-->
<!--            </colgroup>-->
<!--            <tr>-->
<!--                <th>标题</th>-->
<!--                <td>-->
<!--                    <span class="must_red">*</span>-->
<!--                    <input name="title" type="text" class="input length_6 input_hd">-->
<!--                </td>-->
<!--                <td>标题最多输入100个字符</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>推荐位</th>-->
<!--                <td>-->
<!--                    --><?php //foreach($flags as $flag){?>
<!--                        <input name="flag[]" type="checkbox" class="input" value="--><?php //echo $flag['alias']?><!--"> --><?php //echo $flag['flagname']?>
<!--                    --><?php //}?>
<!--                </td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>关键词</th>-->
<!--                <td><input name="keywords" type="text" class="input length_6"></td>-->
<!--                <td>多关键词之间用空格或者“,”隔开</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>来源</th>-->
<!--                <td><input name="source" type="text" class="input length_6"></td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>所属类别</th>-->
<!--                <td>-->
<!--                    <select name="cateid">-->
<!--                        <option value="0">请选择分类</option>-->
<!--                        --><?php //echo $cateOptions?>
<!--                    </select>-->
<!--                </td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>缩略图</th>-->
<!--                <td>-->
<!--                    <input type="hidden" name="cover" class="J_cover_value" value="">-->
<!--                    <div class="up_thumb">-->
<!--                        <a href="--><?php //echo $this->createUrl('public/uploadThumb')?><!--" class="J_dialog" title="上传缩略图">-->
<!--                            <img src="--><?php //echo $this->_baseUrl?><!--/static/images/backend/content/upload-pic.png" width="135" height="113" class="J_cover_path">-->
<!--                        </a>-->
<!--                    </div>-->
<!--                </td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>摘要</th>-->
<!--                <td><textarea name="description" class="length_6" style="height:80px;"></textarea></td>-->
<!--                <td>摘要最多输入255个字符</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>正文</th>-->
<!--                <td colspan="2">-->
<!--                    --><?php //$this->widget('application.widget.ueditor.UEditor', array(
//                        'id' => 'content',
//                        'name' => 'content',
//                        'content' => '',
//                        'width' => '100%',
//                        'height' => '400px',
//                        'serverUrl' => $this->createUrl('ueditor')
//                    ));
//                    ?>
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>外链地址</th>-->
<!--                <td><input name="linkurl" type="text" value="" class="input length_3 mr20"></td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th>发布时间</th>-->
<!--                <td><input name="modify_time" type="text" value="--><?php //echo date('Y-m-d H:i', time())?><!--" class="input length_3 J_datetime mr20"></td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--    <div class="btn_wrap">-->
<!--        <div class="btn_wrap_pd">-->
<!--            <button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>-->
<!--        </div>-->
<!--    </div>-->
<!--    <input type="hidden" name="csrf_token" value="--><?php //echo $this->_csrfToken?><!--"/>-->
<!---->
<?php //ActiveForm::end(); ?>
<!---->
<!--</div>-->