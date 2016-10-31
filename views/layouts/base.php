<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\assets\MilpasosAsset;

AppAsset::register($this);
MilpasosAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="icon" href="../img/common/fav.ico" type="image/x-icon"/>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
    	'brandLabel' => Html::img('@web/img/common/milpasos.png', ['alt'=>'Milpasos.com']),
        'brandUrl' => Yii::$app->homeUrl,
    	'innerContainerOptions' => [
    				'class'=>'container-fluid'
    	],
        'options' => [
            'class' => 'navbar-inverse col-xs-12 main-navbar',
        ],

    ]);
    $items = [
        ['label' => Yii::t('app', "Home"), 'url' => ['/event/map']],
        ['label' => Yii::t('app', "Events"), 'url' => ['/event/index']],
        ['label' => Yii::t('app', "Passes"), 'url' => ['/pass/index']],
        ['label' => Yii::t('app', "Artists"), 'url' => ['/artist/index']],
        ['label' => Yii::t('app', "Groups"), 'url' => ['/group/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $items []= ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $items []= ['label' => Yii::t('app', "Sign up"), 'url' => ['/site/signup']];
        $items []= [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Luis Hernández <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
