<?php
/**
 * @var PageForm $model
 * @var array $seoAttributes
 */
use understeam\seotoolbar\assets\ToolbarAssets;
use understeam\seotoolbar\models\PageForm;
use yii\widgets\ActiveForm;

ToolbarAssets::register($this);
?>
<div class="seo-toolbar">
    <div class="toolbar-inner collapsed">
        <a class="yii-seo-toolbar-logo" href="#">
            <span>&gt;</span>
        </a>
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

        <div class="yii-seo-og">
            <label>OG tags</label>
            <div class="yii-seo-og-list">
                <?php foreach($model->ogTags as $property => $content): ?>
                    <?php $index = isset($index) ? $index + 1 : 0; ?>
                    <?= $this->render('_ogTag', compact('form', 'model', 'property', 'content', 'index')); ?>
                <?php endforeach; ?>
                <?php if (!count($model->ogTags)): ?>
                    <?= $this->render('_ogTag', compact('form', 'model')); ?>
                <?php endif; ?>
            </div>
            <a href="#" class="addTag">Add OG tag</a>
        </div>


        <?= \yii\helpers\Html::submitButton('Save', ['class' => 'btn']); ?>
        <?php $form->end(); ?>
        <?php if (count($seoAttributes)): ?>
        <div class="seo-toolbar_help">
            <div class="seo-toolbar_help__inner">
                <?php foreach($seoAttributes as $name => $value): ?>
                    <p><?=$name?> <b><?=$value ?></b></p>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php $pjax->end(); ?>
    </div>
</div>