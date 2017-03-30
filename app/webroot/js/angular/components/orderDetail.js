function orderDetailCtrl() {

}

paymentApp.component('orderDetail', {
    template: `
        <div class="container">
            <div class="row">
                <div class="col-md-6">小计</div>
                <div class="col-md-6">{{$ctrl.subtotal}}</div>
            </div>
            <div class="row">
                <div class="col-md-6">税({{$ctrl.taxRate}})</div>
                <div class="col-md-6">{{$ctrl.tax}}</div>
            </div>
            <div class="row">
                <div class="col-md-6">总计</div>
                <div class="col-md-6">{{$ctrl.total}}</div>
            </div>
            <div class="row">
                <div class="col-md-6">已收到</div>
                <div class="col-md-6">{{$ctrl.received.cash + $ctrl.received.card}}</div>
                <div class="col-md-6">现金:{{$ctrl.received.cash}}</div>
                <button>现金</button>
                <div class="col-md-6">卡:{{$ctrl.received.card}}</div>
                <button>卡</button>
            </div>
            <div class="row">
                <div class="col-md-6">剩余</div>
                <div class="col-md-6">{{$ctrl.left}}</div>
            </div>
            <div class="row">
                <div class="col-md-6">找零</div>
                <div class="col-md-6">{{$ctrl.change}}</div>
            </div>
            <div class="row">
                <div class="col-md-6">小费</div>
                <div class="col-md-6">{{$ctrl.tip}}</div>
            </div>
        </div>
    `,
    controller: orderDetailCtrl,
})
