<?php

use app\models\Event;
use yii\helpers\Html;

/* @var $model app\models\Event */
/* @var $controllerVar string */
/* @var $this yii\web\View */

$emptyEvent = new Event();

?>
<div class="row event" ng-repeat="event in <?= $controllerVar ?>.selectedEvents"
      ng-hide="<?= $controllerVar ?>.detailedEvent"
      ng-click="<?= $controllerVar ?>.openDetails(event)"
>

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
    </div>
</div>

<div class="row event" ng-show="<?= $controllerVar ?>.detailedEvent">
    <h3>{{<?= $controllerVar ?>.detailedEvent.name}}</h3>
    <img ng-src="{{event.imageUrl}}" ng-show="event.imageUrl">

    <div>
        <label><?= $emptyEvent->getAttributeLabel('website') ?>:</label>
        <a ng-show="<?= $controllerVar ?>.detailedEvent.website" ng-href="">{{<?= $controllerVar ?>.detailedEvent.website}}</a>
    </div>

    <div>
        <label><?= $emptyEvent->getAttributeLabel('groupIds') ?>:</label>
        <div ng-repeat="group in <?= $controllerVar ?>.detailedEvent.groups">{{group.name}}</div>
    </div>

    <div ng-show="<?= $controllerVar ?>.detailedEvent.links.length">
        <label><?= $emptyEvent->getAttributeLabel('links') ?>:</label>
        <a ng-repeat="link in <?= $controllerVar ?>.detailedEvent.links" ng-href="{{link.url}}">{{link.url}}</a>
    </div>

    <div class="more-info" ng-click="<?= $controllerVar ?>.closeDetails()">
        <?= Yii::t('app', 'Close') ?>
    </div>
</div>