(function () {
    var app = angular.module('EventViewerApp', ['ngRoute']);

    app.factory('EventSource', function () {
        return {
            events: milpasos.events || []
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
    app.controller('EventManager', ['EventSource', '$scope', function (eventSource, $scope) {
        $scope.selectedEvents = eventSource.events || [];

        $scope.selectEvents = function (eventIds) {
            $scope.selectedEvents = [];
            for (var i=0; i<eventIds.length; i++) {
                for (var j=0; j<eventSource.events.length; j++) {
                    if (eventSource.events[j].id == eventIds[i]) {
                        $scope.selectedEvents.push(eventSource.events[j]);
                    }
                }
            }
        };

        $scope.selectAll = function () {
            $scope.selectedEvents = eventSource.events;
        };

        milpasos.addMapSelectListener(function (e) {
            // TODO: Refactor this into an injected service that broadcasts event from map listener.
            var features = e.target.getFeatures();
            if (features.getLength() == 0) {
                $scope.$apply(function () {
                    $scope.selectAll();
                });
            } else {
                features.forEach(function (cluster) {
                    // Get all features' eventIds
                    var eventIds = cluster.get("features").map(function ($f) {return $f.get("eventId");});
                    $scope.$apply(function () {
                        $scope.selectEvents(eventIds);
                    });
                });
            }
        });
    }]);

    app.controller('DetailView', ['EventSource', '$routeParams', '$scope', function (eventSource, $routeParams, $scope) {
        $scope.event = null;
        for (var i = 0; i<eventSource.events.length; i++) {
            if (eventSource.events[i].id == $routeParams.id) {
                $scope.event = eventSource.events[i];
                break;
            }
        }
    }]);
})();
