<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 16.02.2017
 * Time: 11:54
 */

use yii\caching\FileCache;
use yii\console\controllers\MigrateController;
use yii\db\Connection;
use yii\rbac\DbManager;
use yii\rbac\PhpManager;
use yii\web\UrlManager;

$appPath = dirname(__DIR__);

return [
    'id' => 'applesample',
    'basePath' => $appPath,
    'vendorPath' => $appPath . '/vendor',
    'runtimePath' => $appPath . '/runtime',
    'controllerNamespace' => 'APPLE\controller',
    'timeZone' => 'UTC',
    'name' => 'Apple Sample Job',
    'bootstrap' => ['log'],
    'language' => 'ru',
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => null,
            'migrationNamespaces' => [
                'APPLE\migrations',
            ],
        ],
    ],
    'modules' => [
    ],
    'components' => [
        'urlManager' => [
            'class' => UrlManager::class,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '<action:(login|logout)>' => 'site/<action>',
            ],
        ],
        'authManager' => [
            'class' => DbManager::class,
            'cache' => 'cache',
            'itemTable' => '{{%rbacItem}}',
            'itemChildTable' => '{{%rbacItemChild}}',
            'assignmentTable' => '{{%rbacAssignment}}',
            'ruleTable' => '{{%rbacRule}}',
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=appletech',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
    ],
];