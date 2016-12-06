<?php

use app\models\Event;

/* @var $this yii\web\View */

$emptyEvent = new Event();

?>
<div class="row event" ng-repeat="event in manager.selectedEvents">

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
            {{event.start_date}} - {{event.end_date}}
        </div>
        <span ng-repeat="dance in event.dances" ng-class="'ico-'+dance.name.toLowerCase()" title="{{dance.name}}">{{dance.name}}</span>

        <div class="more-info">
            <?= Yii::t('app', 'See details') ?>
        </div>
    </div>

</div>