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
        $this->autoRender = NULL;

        // $this->data;
        // print_r($this->data);
        // print_r($this->data['type']);
        print_r($this->data['bold']);

        $this->loadModel('PrintPage');
        $this->PrintPage->save($this->data);

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
        $this->autoRender = NULL;
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
