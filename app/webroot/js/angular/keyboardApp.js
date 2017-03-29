var keyboardApp = angular.module('keyboardApp', [])

keyboardApp.directive('keyboard', [function() {
    return {
        restrict: 'E',
        template: `
        <div class="container">
            <button class="btn btn-lg btn-info" type="button" name="button" ng-click="switchMode()">Switch</button>
            <input class="form-control text-center" type="text" name="" value="" ng-model="screen">
            <div class="col-md-12 count-mode" ng-show="mode=='count'">

                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(0.05)">$0.05 * {{count['$0.05']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(0.10)">$0.10 * {{count['$0.1']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(0.25)">$0.25 * {{count['$0.25']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-warning" class="col-md-3 keyboard" ng-click="revert()">Revert</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(1)">$1 * {{count['$1']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(2)">$2 * {{count['$2']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(5)">$5 * {{count['$5']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-danger" class="col-md-3 keyboard" ng-click="clear()">Clear</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(10)">$10 * {{count['$10']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(20)">$20 * {{count['$20']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(50)">$50 * {{count['$50']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-success" class="col-md-3 keyboard" ng-click="enter()">Enter</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="add(100)">$100 * {{count['$100']}}</button>
                </div>
            </div>
            <div class="col-md-12 normal-mode" ng-show="mode=='normal'">
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(1)">1</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(2)">2</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(3)">3</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-warning" class="col-md-3 keyboard" ng-click="back()">Back</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(4)">4</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(5)">5</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(6)">6</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-danger" class="col-md-3 keyboard" ng-click="clear()">Clear</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(7)">7</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(8)">8</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(9)">9</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-success" class="col-md-3 keyboard" ng-click="enter()">Enter</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="attach(0)">0</button>
                </div>
            </div>
        </div>

        `,
        link: function (scope, elm, attrs) {
        }
    }
}])
