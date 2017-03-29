<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.03.2017
 * Time: 11:56
 */

namespace APPLE\models;

use yii\db\ActiveRecord;

/**
 * Class Clients
 * @package APPLE\models
 * @property integer $clientId
 * @property string $fio
 * @property string $address
 * @property string $email
 */
class Clients extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clients}}';
    }

    public function rules()
    {
        return [
            [['fio'], 'required'],
            [['fio'], 'string', 'max' => 128],
            [['fio'], 'unique'],
            [['address'], 'string', 'max' => 200],
            [['email'], 'default'],
            [['email'], 'string', 'max' => 128],
            [['email'], 'email', 'checkDNS' => !YII_ENV_TEST],
            [['email'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'clientId' => 'Id',
            'fio' => \Yii::t('app', 'ФИО'),
            'address' => \Yii::t('app', 'Адрес'),
            'email' => \Yii::t('app', 'E-mail'),
        ];
    }
}
