<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');

class Clientscombo extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('clients_model','',true);
    }

    function index_get() {
        $data = $this->clients_model->getComboList($this->get('query'));
        if(empty($data)){
            $data[]=array();
        }
        $this->response($data,200);
    }

}
