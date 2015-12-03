<?php
/**
 * @var \yii\widgets\ActiveForm $form
 * @var \understeam\seotoolbar\models\PageForm $model
 * @var string $property
 * @var string $content
 * @var string $index
 */
use yii\helpers\Html;

if (!isset($index)) {
    $index = 0;
}
?>
<div class="form-group">
    <?= Html::activeTextInput($model, "ogTags[{$index}][property]", [
        'id' => null,
        'class' => 'form-control og-name',
        'placeholder' => $model->getAttributeLabel('ogTags[property]'),
    ]) ?>
    <?= Html::activeTextInput($model, "ogTags[{$index}][content]", [
        'id' => null,
        'class' => 'form-control og-name',
        'placeholder' => $model->getAttributeLabel('ogTags[content]'),
    ]) ?>
</div>
