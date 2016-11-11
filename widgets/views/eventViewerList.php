<?php

use app\models\Event;

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
            {{event.start_date}} - {{event.start_date}}
        </div>
        <span ng-repeat="dance in event.dances" ng-class="'ico-'+dance.name.toLowerCase()" title="{{dance.name}}">{{dance.name}}</span>

        <div class="more-info">
            <?= Yii::t('app', 'See details') ?>
        </div>
    </div>

</div>

<div class="row event" ng-show="<?= $controllerVar ?>.detailedEvent" ng-click="<?= $controllerVar ?>.closeDetails()">
    <h3>{{<?= $controllerVar ?>.detailedEvent.name}}</h3>
    <div>
        <img ng-src="{{<?= $controllerVar ?>.detailedEvent.imageUrl}}" ng-show="<?= $controllerVar ?>.detailedEvent.imageUrl">
        <p>
            <span ng-show="<?= $controllerVar ?>.detailedEvent.summary">{{<?= $controllerVar ?>.detailedEvent.summary}}</span><br />
            <b>{{<?= $controllerVar ?>.detailedEvent.address}}</b>
        </p>
        <div class="date">
            {{<?= $controllerVar ?>.detailedEvent.start_date}} - {{<?= $controllerVar ?>.detailedEvent.start_date}}
        </div>
        <span ng-repeat="dance in <?= $controllerVar ?>.detailedEvent.dances" ng-class="'ico-'+dance.name.toLowerCase()" title="{{dance.name}}">{{dance.name}}</span>
    </div>
    <a ng-show="<?= $controllerVar ?>.detailedEvent.website" ng-href="<?= $controllerVar ?>.detailedEvent.website" target="_blank">{{<?= $controllerVar ?>.detailedEvent.website}}</a>

    <div>
        <label><?= $emptyEvent->getAttributeLabel('groupIds') ?>:</label>
        <div ng-repeat="group in <?= $controllerVar ?>.detailedEvent.groups">
            <img ng-show="group.imageUrl" ng-src="{{group.imageUrl}}">{{group.name}}
        </div>
    </div>

    <div ng-show="<?= $controllerVar ?>.detailedEvent.links.length">
        <label><?= $emptyEvent->getAttributeLabel('links') ?>:</label>
        <a ng-repeat="link in <?= $controllerVar ?>.detailedEvent.links" ng-href="{{link.url}}">{{link.title}}</a>
    </div>

    <h3>{{<?= $controllerVar ?>.detailedEvent.price}}</h3>
    <small ng-show="<?= $controllerVar ?>.detailedEvent.price_change_date"><?= Yii::t('app', "Price lasts until ") ?>{{<?= $controllerVar ?>.detailedEvent.price_change_date}}</small>
    <div class="more-info pull-right">
        <?= Yii::t('app', 'Close') ?>
    </div>
</div>