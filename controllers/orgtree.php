<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');

class Orgtree extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('org_tree_model','',true);
    }

    function index_get() {
        $data = $this->org_tree_model->getNode($this->get());
        $results = array();
        foreach($data AS $item){
            $results[]=array(
                'text'=>$item['text'],
                'nodeId'=>$item['id'],
                'iconCls'=>$item['iconCls'],
                'leaf'=>$item['leaf'],
                'nodeType'=>$item['type']
            );
        }
        $this->response($results,200);
    }

    function index_put() {
        $this->org_tree_model->saveNode($this->put());
    }

}
