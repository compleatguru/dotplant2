<?php

/* @var $this yii\web\View */
/* @var $model app\models\Currency */

use app\backend\widgets\BackendWidget;
use kartik\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\ActiveForm;

$this->title = $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update');
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app', 'Currencies'), 'url' => ['index']],
    $this->params['breadcrumbs'][] = $this->title,
];

?>
<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); ?>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

        <?php
            BackendWidget::begin(
                [
                    'icon' => 'gear',
                    'title'=> Yii::t('app', 'Currency'),
                    'footer' => Html::submitButton(
                        Icon::show('save') . Yii::t('app', 'Save'),
                        ['class' => 'btn btn-primary']
                    ),
                ]
            );
        ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'iso_code')->textInput(['maxlength' => 4]) ?>
            <?= $form->field($model, 'is_main')->textInput()->widget(\kartik\widgets\SwitchInput::className()) ?>
            <?= $form->field($model, 'convert_nominal') ?>
            <?= $form->field($model, 'currency_rate_provider_id')->dropDownList(
                [0=>'-']+app\components\Helper::getModelMap(\app\models\CurrencyRateProvider::className(), 'id', 'name')
            ) ?>
            <?= $form->field(
                $model,
                'convert_rate',
                [
                    'addon' => [
                        'append' => [
                            'content' =>
                                Html::a(
                                    Icon::show('question-circle'),
                                    '#',
                                    [
                                        'data-toggle' => 'popover',
                                        'data-trigger' => 'focus',
                                        'data-content' => Yii::t('app', 'Convert rate is updated automatically if currency rate provider is set and includes additional rate and nominal.'),
                                    ]
                                )
                        ],
                    ],
                ]
            ) ?>

            <?= $form->field($model, 'additional_rate') ?>
            <?= $form->field($model, 'additional_nominal') ?>

            <?= $form->field($model, 'sort_order') ?>


        <?php BackendWidget::end(); ?>

</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

<?php
BackendWidget::begin(
    [
        'icon' => 'gear',
        'title'=> Yii::t('app', 'Currency formatting'),
        'footer' => Html::submitButton(
            Icon::show('save') . Yii::t('app', 'Save'),
            ['class' => 'btn btn-primary']
        ),
    ]
);
?>
<?= $form->field($model, 'intl_formatting')->textInput()->widget(\kartik\widgets\SwitchInput::className()) ?>
<?= $form->field($model, 'min_fraction_digits') ?>
<?= $form->field($model, 'max_fraction_digits') ?>
<?= $form->field($model, 'dec_point') ?>
<?= $form->field($model, 'thousands_sep')->dropDownList([
    '' => 'Don\'t separate',
    ' ' => 'Space',
    '.' => 'Dot',
    ',' => 'Dash',
]) ?>
<?= $form->field($model, 'format_string') ?>
<?php BackendWidget::end(); ?>

</div>
<?php ActiveForm::end(); ?>


<div class="clearfix"></div>