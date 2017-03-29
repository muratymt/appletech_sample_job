<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 30.06.2015
 * Time: 15:25
 */

namespace APPLE\forms;

use APPLE\models\Users;
use yii\base\Model;

/**
 * Class LoginForm форма входа
 * @package yiicms\modules\users\models
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login', 'password'], 'string', 'max' => 255],
            [['rememberMe'], 'boolean'],
            [
                ['password'],
                function ($attribute) {
                    if ($this->hasErrors()) {
                        return;
                    }
                    $user = $this->getUser();
                    if ($user === null || !$user->validatePassword($this->password)) {
                        $this->addError($attribute, \Yii::t('app', 'Неверный пароль или логин'));
                    }
                },
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => \Yii::t('app', 'Логин'),
            'password' => \Yii::t('app', 'Пароль'),
            'rememberMe' => \Yii::t('app', 'Запомнить меня'),
        ];
    }

    public function login()
    {
        if (!$this->validate()) {
            return false;
        }
        return \Yii::$app->user->login(
            $this->getUser(),
            $this->rememberMe ? 86400 : 0
        );
    }

    private $_user = false;

    /**
     * @return Users|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = null;
            if (null !== ($user = Users::findOne(['login' => $this->login]))) {
                $this->_user = $user;
            }
        }

        return $this->_user;
    }
}
