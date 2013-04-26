<?php

class org_tree_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function saveNode($args){
        return $this->db->
            where('id',$args['nodeId'])->
            update('agents',array('office_id'=>$args['parentNodeId']));
    }
    
    function getNode($args) {

        //fetch list of offices
        if($args['nodeType']=='corp'){

            return $this->db->select("
                id,
                CONCAT(city, ', ', state_id) text,
                'office' type,
                'icon-office' iconCls,
                'false' leaf
            ", false)->
            from('offices')->
            get()->result_array();

        } else if($args['nodeType']=='office'){

            $this->db->where('office_id',$args['nodeId']);                
            return $this->db->select("
                id,
                CONCAT(first_name, ' ', last_name) text,
                'agent' type,
                'icon-agent' iconCls,
                'true' leaf
            ", false)->from('agents')->get()->result_array();
            
        }
    }

}

?>
