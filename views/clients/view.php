<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model APPLE\models\Clients */

$this->title = $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="clients-view">
    <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Изменть'), ['update', 'id' => $model->clientId], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'clientId',
            'fio',
            'address',
            'email:email',
        ],
    ]) ?>

</div>
