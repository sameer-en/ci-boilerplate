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

    public function getAllVendorsCount() {
        $this->db->select('vendor.vendor_id,vendor.name,vendor.email,vendor.phone,country_information.country_name,currency_information.currency_name, users.first_name as created_by_fname, users.last_name  as created_by_lname, vendor.creation_date as vendor_datetime, vendor.last_modified_date as vendor_mod_date, modified_user.first_name as mod_fname,modified_user.last_name as mod_lname ');
        $this->db->from('vendor');
        $this->db->join('country_information', 'vendor.country = country_information.id');
        $this->db->join('currency_information', 'vendor.currency = currency_information.currency_id');
        $this->db->join('users', 'users.ID = vendor.created_by');
        $this->db->join('users as modified_user', 'modified_user.ID = vendor.last_modified_by', 'left');
        $this->db->order_by("vendor.name", "asc");
        $query = $this->db->get();
        $result = count($query->result_array());
        return $result;
    }

    //add vendor
    public function createVendor() {

        $data = array(
                            'name' => $this->input->post('vendor_name'),
                            'email' => $this->input->post('email'),
                            'phone' => $this->input->post('phone'),
                            'country' => $this->input->post('billing_country'),
                            'currency' => $this->input->post('billing_currency'),
                            'created_by' => $this->session->userdata('user_id'),
                            'last_modified_by' => '',
                            'last_modified_date' => ''
        );
        $this->db->set('creation_date', 'NOW()', FALSE);
        $insert = $this->db->insert('vendor', $data);
        $vendor_id = $this->db->insert_id();
        //FOR SENDING EMAIL TO KEYA
        $vendor_details = $this->getVendorDataForEdit($vendor_id);
        $vendor_details = $vendor_details[0];
        $email_data = array(
                            'FIRST_NAME' => 'Keya',
                            'LAST_NAME' => 'Thomas',
                            'EMAIL' => 'keya.thomas@enyotalearning.com',
                            'VENDOR_NAME' => $vendor_details['name'],
                            'VENDOR_EMAIL' => $vendor_details['email'],
                            'PHONE' => $vendor_details['phone'],
                            'COUNTRY' => $vendor_details['country_name'],
                            'CURRENCY' => $vendor_details['currency_name'],
                            'CREATED_BY' => $vendor_details['first_name'] . ' ' . $vendor_details['last_name'],
        );
        /* --------------------------------------------------------------------------*/
        $userRole = $this->session->userdata('user_role_id');
        $buId = $this->session->userdata('bu_id');
        $arrToUsers[] = 90;
        // add finanace persion as well
        $this->load->model('Project_payment_model');
        $send_to_details = $this->Project_payment_model->get_finance_person_info();
        $finance_persons = $this->Project_payment_model->get_finance_persons_ids();
        $arrToUsers = $finance_persons;

        $arrToUsers[] = $send_to_details['id'];
            
            switch($userRole)
            {
                case  ROLE_BULEAD: 
                                 //send to keya only
                                 break;
                case ROLE_ACCLEAD:
                                  $this->load->model('Bu_model');
                                  $arrBuLData =  $this->Bu_model->get_bu_Lead_data($buId);
                                  $arrToUsers[] = $arrBuLData[0]['ID'];
                                 //send to keya and its bul
                                 break;
                case ROLE_SALES_MANAGER :
                                  // send to its bul. for now for sales bu we send to andy : user id 125 
                                if($this->session->userdata('bu_category') == 3)
                                {
                                     $arrToUsers[] = 125;
                                }
                                else 
                                {
                                  $this->load->model('Bu_model');
                                  $arrBuLData =  $this->Bu_model->get_bu_Lead_data($buId);
                                  $arrToUsers[] = $arrBuLData[0]['ID'];
                                }
                                 break;
                default : break;
            }
        
            //remove the one who created the task
            $this->load->library('EmailLib');
            $EmailLib = new EmailLib();
            $arrToUsers = $EmailLib->remove_from_array_by_value($arrToUsers,$this->session->userdata('user_id')); 
         
            if(count($arrToUsers) > 0)
            {
                $ret = $EmailLib->sendMail($arrToUsers,'','',true,'vendor_add','',$email_data);
            }
        /*----------------------------*/
        return $insert;
    }

    /*
     * Auther : Vikram
     * 
     * Get vendor details
     * 
     * @Param1:  vendor_id
     *
     * return :Array
     */

    public function getVendorDataForEdit($vendor_id) {

        $this->db->select('vendor_id,name,email,phone,country,currency,country_name,currency_name,users.first_name,users.last_name');
        $this->db->where('vendor_id', $vendor_id);
        $this->db->from('vendor');
        $this->db->join('country_information', 'vendor.country = country_information.id');
        $this->db->join('currency_information', 'vendor.currency = currency_information.currency_id');
        $this->db->join('users', 'users.ID = vendor.created_by');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    /*
     * Auther : Vikram
     * 
     * Get vendor project list
     * 
     * @Param1:  vendor_id
     *
     * return :Array
     */

    public function getVendorProjectList($vendor_id) {

        $this->db->select('project_name,project_code,project_expenses.project_id');
        $this->db->from('project_expenses');
        $this->db->join('projects', 'project_expenses.project_id = projects.project_id');
        $this->db->where('project_expenses.vendor_id', $vendor_id);
        $this->db->order_by("project_name", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    /*
     * Auther : Vikram
     * 
     * Edit vendor
     * 
     * @Param1:  vendor_id
     *
     * return :true
     */

    public function editVendorAction($vendor_id) {

        $data = array(
                            'name' => $this->input->post('vendor_name'),
                            'email' => $this->input->post('email'),
                            'phone' => $this->input->post('phone'),
                            'country' => $this->input->post('billing_country'),
                            'currency' => $this->input->post('billing_currency'),
                            'last_modified_by' => $this->session->userdata('user_id'),
        );

        $vendor_before_update = $this->getVendorDataForEdit($vendor_id);
        $this->db->set('last_modified_date', 'NOW()', FALSE);
        $this->db->where('vendor_id', $vendor_id);
        $this->db->update('vendor', $data);

        /*-----------------------------------------------------------------------------------------------*/

        //FOR SENDING EMAIL TO KEYA
        $vendor_after_update = $this->getVendorDataForEdit($vendor_id);
        $vendor_diff = array_diff($vendor_after_update[0], $vendor_before_update[0]);

        $this->load->model('Users_model');
        $user_data = $this->Users_model->get_user_by_id($this->session->userdata('user_id'));
        $user_data = $user_data[0];
        $email_data = array(
                            'FIRST_NAME' => 'Keya',
                            'LAST_NAME' => 'Thomas',
                            'EMAIL' => 'keya.thomas@enyotalearning.com',
                            'UPDATED_BY' => $user_data['first_name'] . ' ' . $user_data['last_name'],
        );

        $email_data['VENDOR_NAME_BGCOLOR'] = $email_data['PHONE_BGCOLOR'] = $email_data['VENDOR_EMAIL_BGCOLOR']   =  $email_data['COUNTRY_BGCOLOR'] =  $email_data['CURRENCY_BGCOLOR'] = $this->config->item('EMAIL_NOT_UPDATED_BGCOLOR');

        $updated = 0;
        $arrReplaceMap = array(
                            'name' => 'VENDOR_NAME_BGCOLOR',
                            'phone' => 'PHONE_BGCOLOR',
                            'email' => 'VENDOR_EMAIL_BGCOLOR',
                            'country_name' => 'COUNTRY_BGCOLOR',
                            'currency_name' => 'CURRENCY_BGCOLOR',
                        );
        foreach($vendor_diff as $updatedKey => $updatedValue)
        {
            $updated = 1;
            $email_data[$arrReplaceMap[$updatedKey]] = $this->config->item('EMAIL_UPDATED_BGCOLOR');
        }

        $vendor_after_update = $vendor_after_update[0];
        /*-----------------------------------------------------------------------------------------------*/
        $email_data['VENDOR_NAME'] = $vendor_after_update['name'];
        $email_data['PHONE'] = $vendor_after_update['phone'];
        $email_data['VENDOR_EMAIL'] = $vendor_after_update['email'];
        $email_data['COUNTRY'] = $vendor_after_update['country_name'];
        $email_data['CURRENCY'] = $vendor_after_update['currency_name'];
         /* --------------------------------------------------------------------------*/
            $userRole = $this->session->userdata('user_role_id');
            $buId = $this->session->userdata('bu_id');
            $arrToUsers[] = 90;
            // add finanace persion as well
            $this->load->model('Project_payment_model');
            $send_to_details = $this->Project_payment_model->get_finance_person_info();
            $finance_persons = $this->Project_payment_model->get_finance_persons_ids();
            $arrToUsers = $finance_persons;
            
            $arrToUsers[] = $send_to_details['id'];
        
            switch($userRole)
            {
                case  ROLE_BULEAD: 
                                 //send to keya only
                                 break;
                case ROLE_ACCLEAD:
                                  $this->load->model('Bu_model');
                                  $arrBuLData =  $this->Bu_model->get_bu_Lead_data($buId);
                                  $arrToUsers[] = $arrBuLData[0]['ID'];
                                 //send to keya and its bul
                                 break;
                case ROLE_SALES_MANAGER :
                                  // send to its bul. for now for sales bu we send to andy : user id 125 
                                if($this->session->userdata('bu_category') == 3)
                                {
                                     $arrToUsers[] = 125;
                                }
                                else 
                                {
                                  $this->load->model('Bu_model');
                                  $arrBuLData =  $this->Bu_model->get_bu_Lead_data($buId);
                                  $arrToUsers[] = $arrBuLData[0]['ID'];
                                }
                                 break;
                default : break;
            }
        
            //remove the one who created the task
            $this->load->library('EmailLib');
            $EmailLib = new EmailLib();
            $arrToUsers = $EmailLib->remove_from_array_by_value($arrToUsers,$this->session->userdata('user_id')); 
            
            if($updated == 1 && count($arrToUsers) > 0)
            {
                $ret = $EmailLib->sendMail($arrToUsers,'','',true,'vendor_edit','',$email_data);
            }
        
        return $this->db->last_query();
    }

    /*
     * Auther : Vikram
     * 
     * get vendor Contacts details for mail to keya
     * 
     * @Param1:  vendor_id
     *
     * return :vendor_contacts array
     */

    public function getVendorContactsDataForEmail($vendor_id) {
        $this->db->select('vendor_contacts.name,vendor_contacts.email,vendor_contacts.phone,country_name,currency_name');
        $this->db->from('vendor_contacts');
        $this->db->join('vendor', 'vendor_contacts.vendor_id = vendor.vendor_id');
        $this->db->join('country_information', 'vendor.country = country_information.id');
        $this->db->join('currency_information', 'vendor.currency = currency_information.currency_id');
        $this->db->where('vendor_contacts.id', $vendor_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /*
     * Auther : Vikram
     * 
     * Add vendor Contacts
     * 
     * @Param1:  vendor_id
     *
     * return :true
     */

    public function addVendorContacts() {

        $data = array(
                            'vendor_id' => $this->input->post('vendor_id'),
                            'name' => $this->input->post('vendor_contact_name'),
                            'email' => $this->input->post('vendor_contact_email'),
                            'phone' => $this->input->post('vendor_contact_number'),
        );
//        $this->db->set('creation_date', 'NOW()', FALSE);
        $insert = $this->db->insert('vendor_contacts', $data);
        $vendor_id = $this->db->insert_id();

        //FOR SENDING EMAIL TO KEYA
        $vendor_details = $this->getVendorContactsDataForEmail($vendor_id);
        $vendor_details = $vendor_details[0];
        $email_data = array(
                            'FIRST_NAME' => 'Keya',
                            'LAST_NAME' => 'Thomas',
                            'EMAIL' => 'keya.thomas@enyotalearning.com',
                            'VENDOR_NAME' => $vendor_details['name'],
                            'VENDOR_EMAIL' => $vendor_details['email'],
                            'PHONE' => $vendor_details['phone'],
                            'COUNTRY' => $vendor_details['country_name'],
                            'CURRENCY' => $vendor_details['currency_name'],
        );
        
        $this->load->library('EmailLib');
        $EmailLib = new EmailLib();
       // $ret = $EmailLib->sendMail(90,'','',false,'vendor_contacts_add','',$email_data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /*
     * Auther : Vikram
     * 
     * Add vendor Contacts
     * 
     * @Param1:  vendor_id
     *
     * return :true
     */

    public function getVendorListData($vendor_id) {
        $this->db->select('vendor_contacts.id,vendor_contacts.name,vendor_contacts.email,vendor_contacts.phone');
        $this->db->from('vendor');
        $this->db->join('vendor_contacts', 'vendor.vendor_id = vendor_contacts.vendor_id');
        $this->db->where('vendor.vendor_id', $vendor_id);
        $this->db->order_by("vendor_contacts.name", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    /*
     * Auther : Vikram
     * 
     * Check email exist or not
     * 
     * @Param1:  vendor_id
     *
     * return :true
     */

    public function check_email_exists($email, $is_add) {
        $this->db->select('*');
        $this->db->from('vendor');
        if ($is_add == 1) {
            $this->db->where('vendor.email', $email);
        }
        else {
            $vendor_id = $this->input->post('vendor_id');
            $this->db->where('vendor.email', $email);
            $this->db->where('vendor.vendor_id<>', $vendor_id);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if (!(empty($result))) {
            return 'exist';
        }
        else {
            return 'not_exist';
        }
    }

    /*
     * Auther : Vikram
     * 
     * Check email exist or not for vendor Contacts
     * 
     * @Param1:  vendor_id
     *
     * return :true
     */

    public function checkVendorsContactsEmailExists($email, $is_add) {
        $this->db->select('*');
        $this->db->from('vendor_contacts');
        if ($is_add == 1) {
            $this->db->where('vendor_contacts.email', $email);
        }
        else {
            $vendor_id = $this->input->post('vendor_id');
            $this->db->where('vendor_contacts.email', $email);
            $this->db->where('vendor_contacts.id<>', $vendor_id);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if (!(empty($result))) {
            return 'exist';
        }
        else {
            return 'not_exist';
        }
    }

    
    public function get_country_and_currency() {
        $this->db->select('id,country_name,country_currency');
        $this->db->from('country_information');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function get_vendor_currency_type($vendor_id) {
        $this->db->select('currency_information.currency_name');
        $this->db->from('vendor');
        $this->db->join('currency_information', 'currency_information.currency_id = vendor.currency');
        $this->db->where('vendor_id', $vendor_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /*
     * Auther : Vikram
     * 
     * Delete vendor contacts
     * 
     * @Param1:  vendor_id
     *
     * return :true
     */
    public function deleteVendorContacts($vendor_id) {
        if ($vendor_id != "") {
            $this->db->where('id', $vendor_id);
            $this->db->delete('vendor_contacts');
            return 'true';
        }else{
            return 'false';
        }
    }
}
    