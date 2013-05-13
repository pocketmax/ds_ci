<?php

class deals_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function update($args=array()) {
        //need a value
        if( empty($args['value']) ){
            return false;
        }
        print_r($args);
        switch($args['field']){
            case 'deal_stage':
                return $this->db->
                where('id',$args['deal_id'])->
                update('deals',array('deal_stage'=>$args['value']));
            
            case 'seller_price':
                return $this->db->
                where('id',$args['deal_id'])->
                update('deals',array('seller_price'=>$args['value']));

            case 'created_date':
                return $this->db->
                where('id',$args['deal_id'])->
                update('deals',array('created_date'=>$args['value']));

            case 'acres':
                return $this->db->query("UPDATE
                    deals 
                    LEFT JOIN properties ON (properties.id=deals.property_id)
                    SET acres = ?
                    WHERE deals.id = ?
                    ",array($args['value'],$args['deal_id']));
        
            case 'appraised_value':
                return $this->db->query("UPDATE
                    deals 
                    LEFT JOIN properties ON (properties.id=deals.property_id)
                    SET appraised_value = ?
                    WHERE deals.id = ?
                    ",array($args['value'],$args['deal_id']));
        }
        
    }

    function getMainGrid($args=array()) {

        //they selected a node from the tree, lets filter on that node
        if( isset($args['nodeType']) && isset($args['nodeId']) ){
            if($args['nodeType']=='agent'){
                $this->db->where('agents.id',$args['nodeId']);                
            } else if($args['nodeType']=='office'){
                $this->db->where('agents.office_id',$args['nodeId']);                
            }
        }

        //date filters have been applied. Lets filter those dates
        if( isset($args['filter']) ){
             $filters = json_decode($args['filter'],true);
            
             foreach($filters AS $filter){
                 $date = explode('/',$filter['value']);
                 
                 switch($filter['property']){
                     case 'createdFromDate':
                        $this->db->where('created_date >',"$date[2]-$date[1]-$date[0]");
                     break;
                     case 'createdToDate':
                        $this->db->where('created_date <',"$date[2]-$date[1]-$date[0]");
                     break;
                 }
             }
        }
        
        if( key_exists('to_created_date',$args) ){
            $this->db->where('created_date <',str_replace('-','',$args['to_created_date']));
        }

        if( key_exists('from_created_date',$args) ){
            $this->db->where('created_date >',str_replace('-','',$args['from_created_date']));
        }

        return $this->db->select("
            deals.id,
            deals.seller_price,
            deals.deal_stage,
            deals.created_date,

            buyer_id,
            buyers.first_name buyer_first_name,
            buyers.last_name buyer_last_name,
            buyers.company_name buyer_company_name,
            buyers.company_name buyer_label,

            owner_id,
            owners.first_name owner_first_name,
            owners.last_name owner_last_name,
            owners.company_name owner_company_name,
	
            agent_id,
            agents.first_name agent_first_name,
            agents.last_name agent_last_name,
            CONCAT(agents.first_name, ' ', agents.last_name) AS agent_label,
            agents.title agent_title,
            agents.phone_number agent_phone_number,
	
            properties.acres,
            properties.appraised_value appraised_value
        ", false)->
        from('deals')->
        join('agents', 'deals.agent_id=agents.id', 'left')->
        join('properties', 'deals.property_id=properties.id', 'left')->
        join('clients buyers', 'deals.buyer_id=buyers.id', 'left')->
        join('clients owners', 'properties.owner_id=owners.id', 'left')->
        get()->result_array();
    }

}

?>
