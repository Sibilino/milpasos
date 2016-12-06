<?php

use app\models\Event;

/* @var $this yii\web\View */

$emptyEvent = new Event();

?>
<div class="event">
    <h3>{{event.name}}</h3>
    <div class="row">
        <div class="col-xs-6">
            <img class="img-responsive center-block img-thumbnail" ng-src="{{event.imageUrl}}" ng-show="event.imageUrl">
        </div>
        <div class="col-xs-6">
            <p>
                <span ng-repeat="dance in event.dances" ng-class="'ico-'+dance.name.toLowerCase()" title="{{dance.name}}">{{dance.name}}</span><br />
                <span ng-show="event.summary">{{event.summary}}</span>
            </p>

            <p>
                <span class="glyphicon glyphicon-road" aria-hidden="true"></span> {{event.address}}
            <div class="date">
                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> {{event.start_date}} - {{event.end_date}}
            </div>
            <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>  <a ng-show="event.website" ng-href="{{event.website}}" target="_blank"><?= $emptyEvent->getAttributeLabel('website') ?></a>
            </p>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-6">
            <label><?= Yii::t('app', "Featuring") ?>:</label>
            <div ng-repeat="group in event.groups">
                {{group.name}}
            </div>
        </div>

        <div class="col-xs-6" ng-show="event.links.length">
            <label><?= $emptyEvent->getAttributeLabel('links') ?>:</label><br />
            <a target="_blank" ng-repeat="link in event.links" ng-href="{{link.url}}">{{link.title}}</a>
        </div>
    </div>
    <hr />
    <div>
        <h3>{{event.price}}</h3>
        <small ng-show="event.price_change_date"><?= Yii::t('app', "This price is available until ") ?>{{event.price_change_date}}</small>
        <div class="more-info pull-right">
            <?= Yii::t('app', 'Close') ?>
        </div>
    </div>

</div>
