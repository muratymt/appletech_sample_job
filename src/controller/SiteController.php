<?php

namespace APPLE\controller;

use APPLE\components\Alert;
use APPLE\forms\LoginForm;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.03.2017
 * Time: 10:14
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        return \Yii::$app->user->isGuest ? $this->redirect(Url::to('/login')) : $this->redirect(Url::to('/clients'));
    }

    public function actionLogin()
    {
        $request = \Yii::$app->request;

        if (!\Yii::$app->user->isGuest) {
            Alert::success(\Yii::t('app', 'Вы уже вошли'));
            return $this->goBack();
        }

        $form = new LoginForm();

        /** @noinspection NotOptimalIfConditionsInspection */
        if ($request->isPost && $form->load($request->post()) && $form->login()) {
            Alert::success(\Yii::t('app', 'Вы успешно вошли'));
            return $this->goBack('/clients');
        }

        \Yii::$app->view->title = \Yii::t('app', 'Вход');
        $form->password = '';
        return $this->render('login', ['form' => $form]);
    }

    /** выход
     * @throws BadRequestHttpException
     */
    public function actionLogout()
    {
        if (\Yii::$app->request->isPost) {
            if (\Yii::$app->user->logout()) {
                Alert::success(\Yii::t('app', 'Вы успешно вышли'));
                return $this->goHome();
            }
            Alert::error(\Yii::t('app', 'Ошибка выхода'));
        }

        $this->view->title = \Yii::t('app', 'Выход');
        return $this->render('logout');
    }
}