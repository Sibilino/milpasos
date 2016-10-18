(function () {
    var app = angular.module('mapEventViewer', []);

    milpasos.EventViewer = app.controller('EventViewer', function () {
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
                    if (this.availableEvents[j] == eventIds[i]) {
                        selection.push(this.availableEvents[j]);
                        found++;
                    }
                }
            }
            this.selectedEvents = selection;
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
