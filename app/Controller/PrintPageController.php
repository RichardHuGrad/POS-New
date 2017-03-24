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



        $this->set(compact('pageDetail'));
    }

    public function getAllLines() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('PrintPage');
        function removeIndex($item) {
            return $item['PrintPage'];
        }
        $pageDetail = array_map("removeIndex", $this->PrintPage->find('all'));


        return json_encode($pageDetail);
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
    public function updateLine() {
        $this->layout = false;
        $this->autoRender = NULL;

        print_r($this->data);

        $this->loadModel('PrintPage');
        $pageDetail = $this->PrintPage->find('first', array(
            'conditions' => array('id' => $this->data['id'])
        ));

        $pageDetail['PrintPage']['content'] = $this->data['content'];
        $pageDetail['PrintPage']['offset_x'] = $this->data['offset_x'];
        $pageDetail['PrintPage']['line_index'] = $this->data['line_index'];
        $pageDetail['PrintPage']['lang_code'] = $this->data['lang_code'];
        $pageDetail['PrintPage']['bold'] = $this->data['bold'];

        $this->PrintPage->save($pageDetail);
    }


    public function deleteLine() {
        $this->layout = false;
        $this->autoRender = NULL;

        print_r($this->data);

        $this->loadModel('PrintPage');
        $this->PrintPage->delete($this->data['id']);
    }
}

?>
