app.controller('ToggleMore',[function () {
    this.open = false;
    this.init = function (open) {
        this.open = open ? true : false;
    };
    this.toggle = function () {
        this.open = !this.open;
    };
}]);