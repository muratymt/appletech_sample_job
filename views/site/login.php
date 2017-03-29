<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 01.07.2015
 * Time: 15:17
 */

use APPLE\components\SubmitButton;
use APPLE\forms\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**  @var $this \yii\web\View */
/** @var $form  LoginForm */

?>

<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <p class="login-box-msg"><?= $this->title ?></p>

        <?php $activeForm = ActiveForm::begin(); ?>

        <?= $activeForm->field($form, 'login')
            ->label(false)
            ->textInput(['placeholder' => $form->getAttributeLabel('login')]); ?>

        <?= $activeForm->field($form, 'password')
            ->label(false)
            ->passwordInput(['placeholder' => $form->getAttributeLabel('password')]); ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $activeForm->field($form, 'rememberMe')
                    ->checkbox(); ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= SubmitButton::widget(['title' => \Yii::t('app', 'Войти'), 'icon' => 'sign-in']); ?>
            </div>
            <!-- /.col -->
        </div>
        <?php ActiveForm::end(); ?>

        <?= Html::a(\Yii::t('app', 'Регистрация нового пользователя'), ['/registration']) ?>
        <br>
        <?= Html::a(\Yii::t('app', 'Забыли пароль?'), ['/reset-password']) ?>

    </div>
    <!-- /.login-box-body -->
</div>

