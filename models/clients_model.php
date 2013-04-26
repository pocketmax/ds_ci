<?php

class clients_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function getComboList($query) {

        if( empty($query) ){
            die('getComboList requires search criteria');
        }

        return $this->db->select('
            id,
            first_name,
            last_name,
            company_name
        ')->
        from('clients')->
        like("CONCAT(first_name,' ',last_name,'',company_name)",$query,'both')->
        get()->result_array();
    }

}

?>
