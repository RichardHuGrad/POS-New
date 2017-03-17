<?php

class PrintPageController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        $this->layout = "default";
    }

    public function index() {
        $this->loadModel('PrintPage');

        $pageDetail = $this->PrintPage->find('all');
        print_r($pageDetail);



        $this->set(compact('pageDetail'));
    }

    public function insertType() {
        $this->layout = false;

        // $this->data;

        $this->loadModel('PrintPage');
        $this->PrintPage->save($this->data, false);

    }

    /**
     * type
     * offset_x
     * content
     * line_index
     * lang_code
     */
    public function insertLine() {
        $this->layout = false;
    }

    /**
     * type
     * offset_x
     * content
     * line_index
     * lang_code
     */
    public function updateLine() {
        $this->layout = false;
    }
}

?>
