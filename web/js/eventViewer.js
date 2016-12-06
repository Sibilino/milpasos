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
                controller: "EventManager as manager"
            })
            .when("/:id", {
                templateUrl: "angular-view?viewName=viewerDetail",
                controller: "DetailView as view"
            });
        $routeProvider.otherwise({
            redirectTo: '/'
        });
    });

    /**
     * Keeps track of available Events and the subset that has been selected by the user.
     * One of the Events can also be selected for detailed inspection.
     */
    app.controller('EventManager', ['EventSource',function (eventSource) {
        this.selectedEvents = eventSource.events || [];

        this.selectEvents = function (eventIds) {
            this.selectedEvents = [];
            for (var i=0; i<eventIds.length; i++) {
                for (var j=0; j<eventSource.events.length; j++) {
                    if (eventSource.events[j].id == eventIds[i]) {
                        this.selectedEvents.push(eventSource.events[j]);
                    }
                }
            }
        };

        this.selectAll = function () {
            this.selectedEvents = eventSource.events;
        };
    }]);

    app.controller('DetailView', ['EventSource', '$routeParams', function (eventSource, $routeParams) {
        this.event = null;
        for (var i = 0; i<eventSource.events.length; i++) {
            if (eventSource.events[i].id == $routeParams.id) {
                this.event = eventSource.events[i];
                break;
            }
        }
    }]);
})();
