(function () {
    var app = angular.module('EventViewerApp', ['ngRoute']);

    /**
     * Service that provides events through the getEvents() method.
     */
    app.factory('EventSource', function () {
        return {
            /**
             * Returns the Events currently available.
             * @returns {events|{}|*|Array}
             */
            getEvents: function () {
                return milpasos.events || [];
            }
        };
    });

    /**
     * Service that allows assigning handlers to the select event of the Milpasos maps, via addMapSelectListener().
     */
    app.factory('MapSelector', function () {
        return {
            /**
             * The given listener will receive a list of Event ids when the "select" event on the map is fired.
             * @param listener function(eventIds){} (void return)
             */
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
        $scope.selectedEvents = EventSource.getEvents() || [];

        $scope.selectEvents = function (eventIds) {
            $scope.selectedEvents = [];
            for (var i=0; i<eventIds.length; i++) {
                for (var j=0; j<EventSource.getEvents().length; j++) {
                    if (EventSource.getEvents()[j].id == eventIds[i]) {
                        $scope.selectedEvents.push(EventSource.getEvents()[j]);
                    }
                }
            }
        };

        $scope.selectAll = function () {
            $scope.selectedEvents = EventSource.getEvents();
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
        for (var i = 0; i<EventSource.getEvents().length; i++) {
            if (EventSource.getEvents()[i].id == $routeParams.id) {
                $scope.event = EventSource.getEvents()[i];
                break;
            }
        }
    }]);
})();
