<?php
App::uses('Component', 'Controller');

class ExampleComponent extends Component {

  public $status = 'success';

  public function hello($args) {
    return 'Hello World';
  }

  public function say($args) {
    if (empty($args['text'])) {
      throw new Exception('Missing argument: text');
    }
    return 'You said: ' . $args['text'];
  }
}

?>