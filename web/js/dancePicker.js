(function () {
    function DanceStyle (name, selected) {
        this.name = name;
        this.selected = selected;
        this.getInitial = function ()  {
            return this.name.charAt(0);
        };
        this.toggle = function () {
            this.selected = !this.selected;
        };
    }

    app.controller('DancePicker', [function () {
        this.dances = [
            new DanceStyle("Salsa", true),
            new DanceStyle("Bachata", true),
            new DanceStyle("Kizomba", true)
        ];
        this.allSelected = function () {
            for (var i=0; i<this.dances.length; i++) {
                if (!this.dances[i].selected) {
                    return false;
                }
            }
            return true;
        };
        this.noneSelected = function () {
            for (var i=0; i<this.dances.length; i++) {
                if (this.dances[i].selected) {
                    return false;
                }
            }
            return true;
        };
        this.getSelectedDanceNames = function () {
            return this.dances
                .filter(function (d) {
                    return d.selected;
                })
                .map(function (d) {
                    return d.name;
                } );
        }
    }]);
})();
