    function keyboardCtrl () {
        this.screen = 0
        var mode = ['normal', 'count']
        this.mode = mode[0]
        var initCount =
        this.count = {
            '$0.05': 0,
            '$0.1': 0,
            '$0.25': 0,
            '$1': 0,
            '$2': 0,
            '$5': 0,
            '$10': 0,
            '$20': 0,
            '$50': 0,
            '$100': 0,
        }
        this.prev = []
        this.attach = function(num) {
            this.screen = _.round(this.screen * 10 + num * 0.01, 2)
        }

        this.add = function(num) {
            this.prev.push(num)
            console.log('$' + num.toString())
            ++this.count['$' + num.toString()]
            this.screen = _.round(this.screen + num, 2)
        }

        this.back = function() {
            this.prev.push(this.screen)
            this.screen = _.floor(this.screen / 10, 2)
        }


        this.clear = function() {
            this.prev.push(this.screen)
            this.screen = 0
            _this = this
            _.forEach(this.count, function(value, index) {
                _this.count[index] = 0
            })
            this.prev = []
        }

        this.minus = function(num) {
            --this.count['$' + num.toString()]
            this.screen = _.round(this.screen - num, 2)
        }

        // only for count mode
        this.revert = function() {
            // this.screen = _.last(this.prev)
            console.log(this.prev)
            if (this.prev.length > 0){
                var num = _.last(this.prev)
                this.prev.splice(-1, 1)
                this.minus(num)
            }
        }

        this.switchMode = function() {
            if (this.mode == 'count') {
                this.mode = 'normal'
            } else if (this.mode == 'normal') {
                this.mode = 'count'
            }
            // clear screen
            this.clear()
        }
    }

paymentApp.component('keyboard', {
        template: `
        <div class="container">
            <button class="btn btn-lg btn-info" type="button" name="button" ng-click="$ctrl.switchMode()">Switch</button>
            <input class="form-control text-center" type="text" name="" value="" ng-model="$ctrl.screen">
            <div class="col-md-12 count-mode" ng-show="$ctrl.mode=='count'">

                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(0.05)">$0.05 * {{$ctrl.count['$0.05']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(0.10)">$0.10 * {{$ctrl.count['$0.1']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(0.25)">$0.25 * {{$ctrl.count['$0.25']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-warning" class="col-md-3 keyboard" ng-click="$ctrl.revert()">Revert</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(1)">$1 * {{$ctrl.count['$1']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(2)">$2 * {{$ctrl.count['$2']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(5)">$5 * {{$ctrl.count['$5']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-danger" class="col-md-3 keyboard" ng-click="$ctrl.clear()">Clear</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(10)">$10 * {{$ctrl.count['$10']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(20)">$20 * {{$ctrl.count['$20']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(50)">$50 * {{$ctrl.count['$50']}}</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-success" class="$ctrl.col-md-3 keyboard" ng-click="$ctrl.enter()">Enter</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-info" ng-click="$ctrl.add(100)">$100 * {{$ctrl.count['$100']}}</button>
                </div>
            </div>
            <div class="col-md-12 normal-mode" ng-show="$ctrl.mode=='normal'">
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(1)">1</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(2)">2</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(3)">3</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-warning" class="col-md-3 keyboard" ng-click="$ctrl.back()">Back</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(4)">4</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(5)">5</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(6)">6</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-danger" class="col-md-3 keyboard" ng-click="$ctrl.clear()">Clear</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(7)">7</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(8)">8</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(9)">9</button>
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-success" class="col-md-3 keyboard" ng-click="$ctrl.enter()">Enter</button>
                </div>
                <div class="col-md-12">
                    <button type="button" name="button" class="col-md-3 keyboard btn-lg btn-default" class="col-md-3 keyboard" ng-click="$ctrl.attach(0)">0</button>
                </div>
            </div>
        </div>
        `,
        controller: keyboardCtrl,
        bindings: {

        }
})
