<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\DataColumn;
/* @var $this yii\web\View */
/* @var $searchModel common\models\KeywordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Keywords');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keyword-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //Html::a(Yii::t('app', 'Create Keyword'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'keyword',
            // 'user_id',
            [
                'class' => DataColumn::className(), // this line is optional
                'headerOptions' => ['style' => 'width:15%'],
                'attribute' => 'user.email',
                'format' => 'text',
                'label' => 'User',
            ],
            'created_at:datetime',
            // 'updated_at',
                
            [   'class' => 'yii\grid\ActionColumn',
                'template'=>'{my_button}',
                'buttons' => [
                    'my_button' => function ($url, $model, $key) {
                        return Html::a('View Tweets', ['/tweet?TweetSearch[keyword_id]', 'id'=>$model->id]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
