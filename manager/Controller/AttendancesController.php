<?php

/**
 * Class AttendancesController
 */
class AttendancesController extends AppController {

    public $uses = array('Attendance');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'attendances');
    }

    /**
     * admin_index For listing of orders
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Attendance', 'can_view');

        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Attendance.checkin DESC';

        $conditions = array();
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
          //  $conditions = array('is_hide'=>'N');
        }

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Attendance']) && !empty($this->request->data['Attendance'])) {
                $search_data = $this->request->data['Attendance'];
                $this->Session->write('attendance_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
        	  $limit = $this->Session->read('page_size');

            //$limit = strtolower($this->Session->read('page_size'))=='all'? 100000000 : $this->Session->read('page_size');
        }

        if($this->Session->check('attendance_search')){
            $search = $this->Session->read('attendance_search');

            if(!empty($search['search'])){
                $conditions['Attendance.userid'] = str_replace("#", "", $search['search']);
            }
            if(!empty($search['from_day'])){
                $conditions['Attendance.checkin >='] = $search['from_day']." 00:00:00";
            }
            if(!empty($search['to_day'])){
                $conditions['Attendance.checkin <='] = $search['to_day']." 23:59:59";
            }

        }

        $this->Attendance->virtualFields = array(
           //'working_hours' => 'round((TIME_TO_SEC(Attendance.checkout)-TIME_TO_SEC(Attendance.checkin))/3600,2)',
           'working_hours' => 'round(timestampdiff(minute,Attendance.checkin,Attendance.checkout)/60,2)', 
        );


        $this->Attendance->virtualFields['name'] = "Select concat(firstname,' ',lastname) as name from cashiers where cashiers.userid = Attendance.userid";
        
        $query = array(
            'conditions' => $conditions,
            'order' => $order,
            'recursive'=>-1
        );
        
        if('all' == $limit){
            $records = $this->Attendance->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records = $this->paginate();
        }
        
        $this->set(compact('records', 'limit', 'order', 'is_super_admin'));
    }


    public function admin_batch_delete() {

        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Attendance');
        
        $ids = $this->data['ids'];
        $ret = $this->Attendance->deleteAll(array('Attendance.id' => $ids), false);
        
        $this->Session->setFlash('Records have been deleted successfully', 'success');
        
        //should redirect in ajax success method
        //$this->redirect(array('plugin' => false, 'controller' => 'attendances', 'action' => 'index', 'admin' => true));

    }


}
