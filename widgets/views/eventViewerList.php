<?php

use yii\helpers\Html;

/* @var $model app\models\Event */
/* @var $controllerVar string */
/* @var $this yii\web\View */
?>

<div class="row event" ng-repeat="event in <?= $controllerVar ?>.selectedEvents">

    <div class="col-sm-3 col-xs-4 list-img">
     	<div class="img-price-block">
            <img ng-src="{{event.imageUrl}}" ng-show="event.imageUrl">
            <small><?= Yii::t('app', "from") ?></small>{{event.price}}
   		</div>
    </div>

    <div class="col-sm-9 col-xs-8 list-info">
        <h3>{{event.name}}</h3>
        <p>
            <span ng-show="event.summary">{{event.summary}}<br /></span>
            <b>{{event.city}}, <span class="country">{{event.country}}</span></b>
        </p>
        <div class="date">
            {{event.start_date || date}} - {{event.start_date || date}}
        </div>
        <span ng-repeat="dance in event.dances" ng-class="'ico-'+dance.name.toLowerCase()" title="{{dance.name}}">{{dance.name}}</span>
        <div class="more-info">
            Details
        </div>
    </div>


</div>
