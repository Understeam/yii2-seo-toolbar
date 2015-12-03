<?php
/**
 * @var PageForm $model
 */
use understeam\seotoolbar\assets\ToolbarAssets;
use understeam\seotoolbar\models\PageForm;
use yii\widgets\ActiveForm;

ToolbarAssets::register($this);
?>
<div class="seo-toolbar">
    <?php $pjax = \yii\widgets\Pjax::begin([
        'id' => 'seo-toolbar-pjax',
        'enablePushState' => false,
        'linkSelector' => false,
        'formSelector' => '#seo-entity-form',
    ]) ?>
    <?php
    $flashes = Yii::$app->session->getFlash('seo-success', [], true);
    ?>
    <?php foreach ($flashes as $flash): ?>
        <div class="seo-success">
            <?=$flash ?>
        </div>
    <?php endforeach; ?>
    <?php
    $form = ActiveForm::begin([
        'id' => 'seo-entity-form',
        'action' => ['/seoToolbar/toolbar/index'],
        'enableAjaxValidation' => true,
    ]);
    ?>
    <?= $form->field($model, 'pattern')->textInput(); ?>
    <?= $form->field($model, 'title')->textInput(); ?>
    <?= $form->field($model, 'keywords')->textInput(); ?>
    <?= $form->field($model, 'description')->textInput(); ?>

    <div class="seo-status">
        <?=$model->isNewRecord ? 'Запись ещё не создана' : 'Имеется запись' ?>
    </div>
    <dl class="seo-attributes">
        <?php foreach($seoAttributes as $key => $value):  ?>
            <dt><?=$key?></dt>
            <dd><?=$value?></dd>
        <?php endforeach; ?>
    </dl>
    <?= \yii\helpers\Html::submitButton('Сохранить'); ?>
    <?php $form->end(); ?>
    <?php $pjax->end(); ?>
</div>