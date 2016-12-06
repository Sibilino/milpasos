(function () {
    var app = angular.module('EventViewerApp', ['ngRoute']);

    app.factory('EventSource', function () {
        return {
            events: milpasos.events || []
        };
    });

    app.factory('MapSelector', function () {
        return {
            onSelectEvents: function (listener) {
                milpasos.addMapSelectListener(function (e) {
                    var eventIds = [];
                    var features = e.target.getFeatures();
                    if (features.getLength() > 0) {
                        features.forEach(function (cluster) {
                            // Get all features' eventIds
                            eventIds = cluster.get("features").map(function ($f) {return $f.get("eventId");});
                        });
                    }
                    return listener(eventIds);
                });
            }
        };
    });

    app.config(function($routeProvider) {
        $routeProvider
            .when("/", {
                templateUrl: "angular-view?viewName=viewerList",
                controller: "EventManager"
            })
            .when("/:id", {
                templateUrl: "angular-view?viewName=viewerDetail",
                controller: "DetailView"
            });
        $routeProvider.otherwise({
            redirectTo: '/'
        });
    });

    /**
     * Keeps track of available Events and the subset that has been selected by the user.
     * One of the Events can also be selected for detailed inspection.
     */
    app.controller('EventManager', ['EventSource', 'MapSelector', '$scope', function (EventSource, MapSelector, $scope) {
        $scope.selectedEvents = EventSource.events || [];

        $scope.selectEvents = function (eventIds) {
            $scope.selectedEvents = [];
            for (var i=0; i<eventIds.length; i++) {
                for (var j=0; j<EventSource.events.length; j++) {
                    if (EventSource.events[j].id == eventIds[i]) {
                        $scope.selectedEvents.push(EventSource.events[j]);
                    }
                }
            }
        };

        $scope.selectAll = function () {
            $scope.selectedEvents = EventSource.events;
        };

        MapSelector.onSelectEvents(function (eventIds) {
            $scope.$apply(function () {
                if (eventIds.length === 0) {
                    $scope.selectAll();
                } else {
                    $scope.selectEvents(eventIds);
                }
            });
        });
    }]);

    app.controller('DetailView', ['EventSource', '$routeParams', '$scope', function (EventSource, $routeParams, $scope) {
        $scope.event = null;
        for (var i = 0; i<EventSource.events.length; i++) {
            if (EventSource.events[i].id == $routeParams.id) {
                $scope.event = EventSource.events[i];
                break;
            }
        }
    }]);
})();
