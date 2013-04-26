<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');

class Dealgrid extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('deals_model','',true);
    }

    function index_get() {
        $data = $this->deals_model->getMainGrid($this->get());
        $this->response($data,200);
    }

    function index_put() {
        $data = $this->deals_model->update($this->put());
        $this->response(array(),200);
    }

}
