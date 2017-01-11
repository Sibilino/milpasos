<?php

use app\models\Event;

/* @var $this yii\web\View */

$emptyEvent = new Event();

?>
<div ng-repeat="event in selectedEvents">
    <a ng-href="#!/{{event.id}}">
        <div class="row event">

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
                <p class="date">
                    {{event.start_date}} - {{event.end_date}}
                </p>
                <span ng-repeat="dance in event.dances" ng-class="'ico-'+dance.name.toLowerCase()" title="{{dance.name}}">{{dance.name}}</span>

                <p class="more-info">
                    <?= Yii::t('app', 'See details') ?>
                </p>
            </div>

        </div>
    </a>
</div>