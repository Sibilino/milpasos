(function () {
    /**
     * External dependency may be needed, see EventSource and MapSelector services.
     */

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
                return milpasos.eventViewer.events || []; // Depends on milpasos module, eventViewer widget.
            }
        };
    });

    /**
     * Service that allows assigning handlers to the select event of the Milpasos maps, via addMapSelectListener().
     * Usage: MapSelector.onSelectEvents(your_listener_func);
     */
    app.service('MapSelector', ['$rootScope', function ($rootScope) {
        this.lastSelection = [];
        var serviceObj = this;
        milpasos.eventViewer.onSelectEvents(function (eventIds) {
            serviceObj.lastSelection = eventIds;
            $rootScope.$apply(function () {
                $rootScope.$broadcast('MapSelector:selection-changed', { eventIds: eventIds });
            });
        });
    }]);

    /**
     * Route configuration.
     */
    app.config(function($routeProvider, $locationProvider) {
        $locationProvider.hashPrefix('!'); // For backwards compatibility if server cannot update angular
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
        /**
         * The currently selected Events.
         * @type {*|events|{}|Array}
         */
        $scope.selectedEvents = [];
        /**
         * Selects a subset of the available Events in the EventSource.
         * @param eventIds
         */
        $scope.selectEvents = function (eventIds) {
            $scope.selectedEvents = [];
            var events = EventSource.getEvents();
            for (var i=0; i<eventIds.length; i++) {
                for (var j=0; j<events.length; j++) {
                    if (events[j].id == eventIds[i]) {
                        $scope.selectedEvents.push(events[j]);
                    }
                }
            }
        };
        /**
         * Selects all available Events in the EventSource.
         */
        $scope.selectAll = function () {
            $scope.selectedEvents = EventSource.getEvents();
        };
        
        // Listen for event selection from the map and change selected Events accordingly
        $scope.$on('MapSelector:selection-changed', function (e, args) {
            if (args.eventIds.length === 0) {
                $scope.selectAll();
            } else {
                $scope.selectEvents(args.eventIds);
            }
        });

        // Load initial Events
        if (MapSelector.lastSelection.length > 0) {
            $scope.selectEvents(MapSelector.lastSelection);
        } else {
            $scope.selectAll();
        }
    }]);

    /**
     * Chooses an Event from the EventSource, based on the id route param, and exposes it to the $scope.
     */
    app.controller('DetailView', ['EventSource', 'MapSelector', '$routeParams', '$scope', '$location',
        function (EventSource, MapSelector, $routeParams, $scope, $location) {
        /**
         * Holds data of the chosen Event.
         * @type {Event}
         */
        $scope.event = null;
        // Choose the available id that corresponds to id route param
        var events = EventSource.getEvents();
        for (var i = 0; i<events.length; i++) {
            if (events[i].id == $routeParams.id) {
                $scope.event = events[i];
                break;
            }
        }
        
        /**
         * If the target of the $event is not a link, go back to root route.
         * @param $event
         */
        $scope.eventClick = function ($event) {
            var element = angular.element($event.target);
            if (!element.is('a')) {
                $location.path('/');
            }
        };
        
        // Listen for event selection from the map and close detail view if necessary
        $scope.$on('MapSelector:selection-changed', function (e, args) {
            if (args.eventIds.length == 1) {
                var id = args.eventIds.pop();
                if (id != $routeParams.id) {
                    $location.path('/'+id);
                }
            } else {
                $location.path('/');
            }
        });
    }]);
})();
