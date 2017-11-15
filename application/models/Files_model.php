<?php

class Files_model extends CI_Model {
    /*
     * Get All Vendor with pagination
     * @param limit int
     * @param offset int
     * @param getCount Bool
     * @param $str string
     * @return count or array
     */

public function get_all_word($limit, $offset, $getCount = FALSE, $post)
{
    return 10;
}
    public function getAllVendors($limit, $offset, $getCount = FALSE, $str = NULL) {
        $this->db->select('vendor.vendor_id,vendor.name,vendor.email,vendor.phone,country_information.country_name,currency_information.currency_name, users.first_name as created_by_fname, users.last_name  as created_by_lname, vendor.creation_date as vendor_datetime, vendor.last_modified_date as vendor_mod_date, modified_user.first_name as mod_fname,modified_user.last_name as mod_lname ');
        $this->db->from('vendor');
        $this->db->join('country_information', 'vendor.country = country_information.id');
        $this->db->join('currency_information', 'vendor.currency = currency_information.currency_id');
        $this->db->join('users', 'users.ID = vendor.created_by');
        $this->db->join('users as modified_user', 'modified_user.ID = vendor.last_modified_by', 'left');
        $this->db->order_by("vendor.name", "asc");

        if ($str != NULL) {
            $this->db->like('vendor.name', $str);
        }
        if ($getCount === FALSE) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        if ($getCount === TRUE) {
            $result = count($query->result_array());
        }
        else {
            $result = $query->result_array();
        }
      
        return $result;
    }
}
    