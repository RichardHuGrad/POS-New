<?php
    class UserController extends AppController {
        public function beforeFilter() {

            parent::beforeFilter();
            $this->Auth->allow('index', 'forgot_password');
            $this->layout = "default";

        }

        public function tableHisdetail() {
            // get cashier details
            $this->loadModel('Cashier');
            $cashier_detail = $this->Cashier->find("first", array(
                'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
                'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                    )
            );

            $table_no = $this->params['named']['table_no'];
            $order_id = $this->params['named']['order_id'];

            $this->loadModel('Order');
            $this->loadModel('OrderItem');

            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.id' => $order_id,
            	'Order.table_no' => $table_no,
                'Order.is_completed' => 'Y',
                'Order.order_type' => 'D',
                'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
            );

            $Order_detail = $this->Order->find("first", array(
                'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
                'conditions' => $conditions
                    )
            );
            if (empty($Order_detail)) {
                $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
                return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
            }

            $today = date('Y-m-d H:i', strtotime($Order_detail['Order']['created']));

            $this->set(compact('Order_detail', 'cashier_detail', 'table_no', 'order_id', 'today'));
        }

        public function tableHisupdate() {
            $this->layout = false;
            $this->autoRender = NULL;

        	// get cashier details
            $this->loadModel('Cashier');
            $cashier_detail = $this->Cashier->find("first", array(
                'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
                'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                    )
            );

            $table_no = $this->data['table_no'];
            $order_id = $this->data['order_id'];

            $this->loadModel('Order');
            $this->loadModel('Log');

            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.id' => $order_id,
            	'Order.table_no' => $table_no,
                'Order.is_completed' => 'Y',
                'Order.order_type' => 'D',
                'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
            );

            $Order_detail = $this->Order->find("first", array(
                'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
                'conditions' => $conditions
                    )
            );
            if (empty($Order_detail)) {
                $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
                return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
            }

            $subtotal = $this->data['subtotal'];
            $discount_value = $this->data['discount_value'];
            $total = $this->data['total'];
            $paid = $this->data['paid'];
            $cash_val = $this->data['cash_val'];
            $card_val = $this->data['card_val'];
            $change = $this->data['change'];
            $tip = $this->data['tip'];

            $logs = '';
            $data = array();
            if ($subtotal != number_format($Order_detail['Order']['subtotal'],2)) {
            	$logs .= 'subtotal[' . $subtotal . ' <= ' . $Order_detail['Order']['subtotal'] . "]";
            	$data['subtotal'] = $subtotal;
            	$data['tax_amount'] = $subtotal *  $Order_detail['Order']['tax'] / 100;
            }
            if ($discount_value != number_format($Order_detail['Order']['discount_value'],2)) {
            	$logs .= 'discount_value[' . $discount_value . ' <= ' . $Order_detail['Order']['discount_value'] . "]";
            	$data['discount_value'] = $discount_value;
            }
            if ($total != number_format($Order_detail['Order']['total'],2)) {
            	$logs .= 'total[' . $total . ' <= ' . $Order_detail['Order']['total'] . "]";
            	$data['total'] = $total;
            }
            if ($paid != number_format($Order_detail['Order']['paid'],2)) {
            	$logs .= 'paid[' . $paid . ' <= ' . $Order_detail['Order']['paid'] . "]";
            	$data['paid'] = $paid;
            }
            if ($cash_val != number_format($Order_detail['Order']['cash_val'],2)) {
            	$logs .= 'cash_val[' . $cash_val . ' <= ' . $Order_detail['Order']['cash_val'] . "]";
            	$data['cash_val'] = $cash_val;
            }
            if ($card_val != number_format($Order_detail['Order']['card_val'],2)) {
            	$logs .= 'card_val[' . $card_val . ' <= ' . $Order_detail['Order']['card_val'] . "]";
            	$data['card_val'] = $card_val;
            }
            if ($change != number_format($Order_detail['Order']['change'],2)) {
            	$logs .= 'change[' . $change . ' <= ' . $Order_detail['Order']['change'] . "]";
            	$data['change'] = $change;
            }
            if ($tip != number_format($Order_detail['Order']['tip'],2)) {
            	$logs .= 'tip[' . $tip . ' <= ' . $Order_detail['Order']['tip'] . "]";
            	$data['tip'] = $tip;
            }

            if ($logs != '') {
            	$logArr = array('cashier_id' => $cashier_detail['Cashier']['id'], 'admin_id' => $cashier_detail['Admin']['id'], 'logs' => $logs);
            	$this->Log->save($logArr);
            	$data['id'] = $order_id;
            	$this->Order->save($data);
            }

            $r['status'] = 'OK';
            $r['url'] = Router::url(array('controller' => 'homes', 'action' => 'tableHistory',  'table_no' => $table_no, 'order_id' => $order_id));
            echo json_encode($r);
        }

        public function tableHistory() {
            // get cashier details
            $this->loadModel('Cashier');
            $cashier_detail = $this->Cashier->find("first", array(
                'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
                'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                    )
            );

            $table_no = $this->params['named']['table_no'];

            $this->loadModel('Order');
            $this->loadModel('OrderItem');

            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table_no,
                'Order.is_completed' => 'Y',
                'Order.order_type' => 'D',
                'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
            );

            $Order_detail = $this->Order->find("all", array(
                'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
                'conditions' => $conditions
                    )
            );
            if (empty($Order_detail)) {
                $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
                return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
            }

            $this->paginate = array(
                'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
                'conditions' => $conditions,
                'limit' => 10,
                'order' => array('Order.created' => 'desc')
            );

            $Order_detail = $this->paginate('Order');
            $today = date('Y-m-d H:i', strtotime($Order_detail[0]['Order']['created']));

            $this->set(compact('Order_detail', 'cashier_detail', 'table_no', 'today'));
        }
    }
?>
