<?php

class Dictionary_model extends CI_Model {
    /*
     * Get All Vendor with pagination
     * @param limit int
     * @param offset int
     * @param getCount Bool
     * @param $str string
     * @return count or array
     */

    public function getAllDictionries($lan1,$lan2)
    {
        $this->db->select('*');
        $this->db->from('master_dictionary');
        $this->db->order_by('priority','asc');
        $query = $this->db->get();
        $result = $query->result_array();
          
        return $result;
    }
    
}
    