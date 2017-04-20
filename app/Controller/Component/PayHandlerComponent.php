<?php
App::uses('Component', 'Controller');
App::uses('ApiHelperComponent', 'Component');
App::uses('MergeController', 'Controller');

class PayHandlerComponent extends Component {
    public $status = 'success';

    public function __construct() {
        // register model
        $this->Order = ClassRegistry::init('Order');
    }

    public function completeOrder($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['order_id', 'table', 'type', 'paid_by', 'pay', 'change', 'card_val', 'cash_val', 'tip_paid_by', 'tip']);
        // get all params
        $order_id = $args['order_id'];
        $table = $args['table'];
        $type = $args['type'];
        $paid_by = strtoupper($args['paid_by']);

        $pay = $args['pay'];
        $change = $args['change'];

        // save order to database
        $data['Order']['id'] = $order_id;
        if ($args['card_val'] > 0 and $args['cash_val'] > 0)
            $data['Order']['paid_by'] = "MIXED";
        elseif ($args['card_val'] > 0)
            $data['Order']['paid_by'] = "CARD";
        elseif ($args['cash_val'] > 0)
            $data['Order']['paid_by'] = "CASH";

        $data['Order']['table_status'] = 'P';

        $data['Order']['paid'] = $pay;
        $data['Order']['change'] = $change;
        $data['Order']['is_kitchen'] = 'Y';

        $data['Order']['is_completed'] = 'Y';

        $data['Order']['card_val'] = $args['card_val'];
        $data['Order']['cash_val'] = $args['cash_val'];
        $data['Order']['tip_paid_by'] = $args['tip_paid_by'];
        $data['Order']['tip'] = $args['tip'];

        $this->Order->save($data, false);

        // update popularity status
        // $this->loadModel('Cousine');
        // $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");
    }


    public function completeMergeOrder($args) {
    	
        ApiHelperComponent::verifyRequiredParams($args, ['order_ids','main_order_id','table','table_merge','pay','change','card_val','cash_val','tip_val','tip_paid_by']);


        // pr($args); die;
        // get all params
        $order_id = explode(",", $args['order_ids']);
        $table = $args['table'];
        $table_merge = explode(",", $args['table_merge']);
        $main_order_id = $args['main_order_id'];

        $paid = $args['pay'];
        $change = $args['change'];

        // save order to database
        for ($i = 0; $i < count($order_id); $i++) {
            $data['Order']['id'] = $order_id[$i];
            $table_detail = $this->Order->find("first", array('fields' => array('Order.table_no', 'total'), 'conditions' => array('Order.id' => $data['Order']['id']), 'recursive' => false));

            if ($args['card_val'] and $args['cash_val']) {
                $data['Order']['paid_by'] = "MIXED";
            } elseif ($args['card_val']) {
                $data['Order']['paid_by'] = "CARD";
            } elseif ($args['cash_val']) {
                $data['Order']['paid_by'] = "CASH";
            };
            $data['Order']['table_status'] = 'P';
            $data['Order']['is_kitchen'] = 'Y';
            $data['Order']['is_completed'] = 'Y';


            if ($table_detail['Order']['table_no'] == $table) {
                $data['Order']['paid'] = $paid;
                $data['Order']['change'] = $change;

                $data['Order']['card_val'] = $args['card_val'];
                $data['Order']['cash_val'] = $args['cash_val'];
                $data['Order']['tip_paid_by'] = $args['tip_paid_by'];
                $data['Order']['tip'] = $args['tip_val'];
                $data['Order']['merge_id'] = 0; 
            } else {
                $data['Order']['paid'] = 0;
                $data['Order']['change'] = 0;
                $data['Order']['card_val'] = 0;
                $data['Order']['cash_val'] = 0;
                $data['Order']['tip'] = 0;
                $data['Order']['merge_id'] = $main_order_id; 
            };

            $this->Order->save($data, false);

            //$this->loadModel('Cousine');
            //$this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id[$i]')");
        };

        return true;
    }

    
}

?>
