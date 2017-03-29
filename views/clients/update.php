<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model APPLE\models\Clients */

$this->title = 'Редактировать клиента: ' . $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->clientId, 'url' => ['view', 'id' => $model->clientId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clients-update">
    <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
