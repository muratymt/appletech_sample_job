<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.03.2017
 * Time: 11:48
 */
use APPLE\forms\LoginForm;
use yii\bootstrap\ActiveForm;
use APPLE\components\SubmitButton;

/**  @var $this \yii\web\View */
/** @var $form  LoginForm */


?>

<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <p class="login-box-msg"><?= $this->title ?></p>
        <?php $activeForm = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-xs-4">
                <?= SubmitButton::widget(['title' => \Yii::t('app', 'Выйти'), 'icon' => 'sign-in']); ?>
            </div>
            <!-- /.col -->
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>