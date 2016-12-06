<?php

use app\models\Event;

/* @var $this yii\web\View */

$emptyEvent = new Event();

?>
<div class="event">
    <h3>{{view.event.name}}</h3>
    <div class="row">
        <div class="col-xs-6">
            <img class="img-responsive center-block img-thumbnail" ng-src="{{view.event.imageUrl}}" ng-show="view.event.imageUrl">
        </div>
        <div class="col-xs-6">
            <p>
                <span ng-repeat="dance in view.event.dances" ng-class="'ico-'+dance.name.toLowerCase()" title="{{dance.name}}">{{dance.name}}</span><br />
                <span ng-show="view.event.summary">{{view.event.summary}}</span>
            </p>

            <p>
                <span class="glyphicon glyphicon-road" aria-hidden="true"></span> {{view.event.address}}
            <div class="date">
                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> {{view.event.start_date}} - {{view.event.end_date}}
            </div>
            <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>  <a ng-show="view.event.website" ng-href="{{view.event.website}}" target="_blank"><?= $emptyEvent->getAttributeLabel('website') ?></a>
            </p>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-6">
            <label><?= Yii::t('app', "Featuring") ?>:</label>
            <div ng-repeat="group in view.event.groups">
                {{group.name}}
            </div>
        </div>

        <div class="col-xs-6" ng-show="view.event.links.length">
            <label><?= $emptyEvent->getAttributeLabel('links') ?>:</label><br />
            <a target="_blank" ng-repeat="link in view.event.links" ng-href="{{link.url}}">{{link.title}}</a>
        </div>
    </div>
    <hr />
    <div>
        <h3>{{view.event.price}}</h3>
        <small ng-show="view.event.price_change_date"><?= Yii::t('app', "This price is available until ") ?>{{view.event.price_change_date}}</small>
        <div class="more-info pull-right">
            <?= Yii::t('app', 'Close') ?>
        </div>
    </div>

</div>
