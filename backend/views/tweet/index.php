<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\DataColumn;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TweetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tweets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tweet-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //Html::a(Yii::t('app', 'Create Tweet'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => DataColumn::className(), // this line is optional
                'headerOptions' => ['style' => 'width:5%'],
                'attribute' => 'id',
                'format' => 'text',
                'label' => 'ID',
            ],
            // 'id',
            /*[
                'class' => DataColumn::className(), // this line is optional
                'headerOptions' => ['style' => 'width:15%'],
                'attribute' => 'keyword.keyword',
                'format' => 'text',
                'label' => 'Keyword',
            ],*/
            // 'keyword_id',
            // 'user_id',
            //'country_id',
            'description',
            'tweet_owner',
            'country_name',
            'city_name',
            'location',
            'coordinates',
            //'altitude',
            //'longtitude',
            //'profile_image',
            'created_at:datetime',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
