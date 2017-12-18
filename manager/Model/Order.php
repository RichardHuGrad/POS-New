<?php

class Order extends AppModel {

    public $name = 'Order';
    public $validate = array();

    public $hasMany = array(
        'OrderItem' => array(
            'className' => 'OrderItem',
            'foreignKey' => 'order_id'
        ),
    );
    public $belongsTo = array(
        'Cashier' => array(
            'className' => 'Cashier',
            'foreignKey' => 'counter_id' 
        )
    );

    public function deleteByOrderNo($order_no) {
        // record the deleted order to the log system
        $order_detail = $this->find('first', array(
                'recursive' => -1,
                'conditions' => array(
                'order_no' => $order_no
            )
        ));
        
        $order_id = $order_detail['Order']['id'];

        $OrderLog = ClassRegistry::init('OrderLog');
        $OrderLog->insertLog($order_detail, 'delete');

        $this->delete(array('Order.id' => $order_id), false);
    }

    public function update($order_id,$post) {

        $orderDetail = $this->find('first', array(
            'recursive' => -1,
            'conditions' => array('Order.id' => $order_id)
        ));
        
        $para   = array('id'=>$order_id,'order_no'=>$orderDetail['Order']['order_no']);
        
        $logstr = '';
        
        foreach($post['Order'] as $key=>$value){
        	if($orderDetail['Order'][$key] != $value){
		  		$logstr .= "{$key}[".$orderDetail['Order'][$key]."]=>[".$value."],";
		  		$para[$key] = $value ;        		
        	}        	
        }
        $logstr = rtrim($logstr , ",");
                
        $ret = $this->save($para, false);
        
        /*              
        $OrderLog = ClassRegistry::init('OrderLog');       
        $log_detail = array('OrderLog' => array('order_no' => $post['Order']['order_no'], 'json' => json_encode($post['Order']), 'notes'=>$logstr, 'operation' => 'edit','last_update'=> date('Y-m-d H:i:s')));
        $OrderLog->create();
        $OrderLog->save($log_detail, false);
        */
        
        //$this->Session->read('Admin.id')
        //cashier_id =0, means operate by admin
        //here admin_id is restaurant_id
        $logArr = array('cashier_id' => 0, 'admin_id' => $orderDetail['Order']['cashier_id'],'operation'=>'Update order[OrderNo='.$para['order_no'].']', 'logs' => $logstr);
        
        $Log = ClassRegistry::init('Log'); 
        $Log->save($logArr, false);
                
        return $ret;
    }


}

?>