(function () {
    var app = angular.module('EventViewerApp', []);

    /**
     * Keeps track of available Events and the subset that has been selected by the user.
     * One of the Events can also be selected for detailed inspection.
     */
    app.controller('EventViewer', function () {

        this.availableEvents = [];
        this.selectedEvents = [];
        this.detailedEvent = null;

        this.loadEvents = function (events) {
            this.availableEvents = events;
            this.selectAll();
        };

        this.selectEvents = function (eventIds) {
            var selection = [];
            var found = 0;
            for (var i=0; i<eventIds.length; i++) {
                for (var j=0; j<this.availableEvents.length; j++) {
                    if (this.availableEvents[j].id == eventIds[i]) {
                        selection.push(this.availableEvents[j]);
                        found++;
                    }
                }
            }
            this.selectedEvents = selection;
            if (found == 1) {
                this.openDetails(selection[0]);
            } else if (found > 1) {
                this.closeDetails();
            }
            return found;
        };

        this.selectAll = function () {
            this.selectedEvents = this.availableEvents;
        };

        this.openDetails = function (event) {
            this.detailedEvent = event;
        };

        this.closeDetails = function () {
            this.detailedEvent = null;
        };
    });
})();
