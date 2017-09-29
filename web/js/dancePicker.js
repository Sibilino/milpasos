(function () {
    /**
     * JavaScript representation of a Dance style object.
     * @param name
     * @param id
     * @param selected
     * @constructor
     */
    function DanceStyle (name, id, selected) {
        this.name = name;
        this.id = id;
        this.selected = selected;
        /**
         * The initial letter of this dance style's name.
         * @returns {string}
         */
        this.getInitial = function ()  {
            return this.name.charAt(0);
        };
        /**
         * Toggle the selected field of this object.
         */
        this.toggle = function () {
            this.selected = !this.selected;
        };
    }

    app.controller('DancePicker', [function () {
        this.dances = [];
        /**
         * Initializes the controller with initial data and selection.
         * @param dances Array of DanceStyle-like objects to be used as pool of dances to select from.
         * @param selection Array of the ids of the dances to be initially selected.
         */
        this.initDances = function (dances, selection) {
            var picker = this;
            angular.forEach(dances, function (dance) {
                picker.dances.push(new DanceStyle(dance.name, dance.id, selection.indexOf(dance.id) !== -1));
            });
        };
        /**
         * Whether all dances are currently selected.
         * @returns {boolean}
         */
        this.allSelected = function () {
            for (var i=0; i<this.dances.length; i++) {
                if (!this.dances[i].selected) {
                    return false;
                }
            }
            return true;
        };
        /**
         * Whether no dances are currently selected.
         * @returns {boolean}
         */
        this.noneSelected = function () {
            for (var i=0; i<this.dances.length; i++) {
                if (this.dances[i].selected) {
                    return false;
                }
            }
            return true;
        };
        /**
         * Returns the DanceStyles that are currently selected.
         * @returns {Array}
         */
        this.getSelectedDances = function () {
            return this.dances
                .filter(function (d) {
                    return d.selected;
                });
        };
        /**
         * Returns the names of the DanceStyles that are currently selected.
         * @returns {Array}
         */
        this.getSelectedDanceNames = function () {
            return this.getSelectedDances()
                .map(function (d) {
                    return d.name;
                } );
        };
    }]);
})();
