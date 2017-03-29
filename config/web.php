<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.06.2015
 * Time: 9:04
 */

use APPLE\models\Users;
use yii\web\DbSession;
use yii\web\Request;
use yii\web\User;
use yii\web\View;

return [
    'components' => [
        'user' => [
            'class' => User::class,
            'identityClass' => Users::class,
            'enableAutoLogin' => true,
            'loginUrl' => '/login',
        ],
        'request' => [
            'class' => Request::class,
            'cookieValidationKey' => 'sdi---&Dnjiofjsdidsfgd$23oifgu',
        ],
        'session' => [
            'class' => DbSession::class,
            'sessionTable' => '{{%sessions}}',
        ],
        'view' => [
            'class' => View::class,
        ],
    ],
];
