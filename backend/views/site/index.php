<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <?php if ($guest): ?>
            <h1>Congratulations!</h1>    
            <p class="lead">You have successfully reach Tweet Crisis admin panel.</p>
            <p><a class="btn btn-lg btn-success" href="/site/login">Login to manage Tweet Crisis</a></p>
        <?php else: ?>
            <h2>Welcome to Tweet Crisis admin panel.</h2>
        <?php endif ?>
        
    </div>

    <div class="body-content">

    </div>
</div>
