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
        $this->db->select('file_id,file_name ,file_status,added_on,added_by,username');
        $this->db->from('master_files');
        $this->db->join('master_users', 'master_users.uid = master_files.added_by');
        $this->db->where('file_type','doc');
        if ($post['searchText'] != NULL) {
                $this->db->like('file_name', $post['searchText']);
            }
            if ($getCount === FALSE) {
                $this->db->limit($limit, $offset);
            }
            $query = $this->db->get();
            if ($getCount === TRUE) {
                $result = $query->num_rows();
            }
            else {
                $result = $query->result_array();
            }
          
            return $result;
    }

    public function get_all_excel($limit, $offset, $getCount = FALSE, $post)
    {
        $this->db->select('file_id,file_name ,file_status,added_on,added_by,username');
        $this->db->from('master_files');
        $this->db->join('master_users', 'master_users.uid = master_files.added_by');
        $this->db->where('file_type','xls');
        if ($post['searchText'] != NULL) {
                $this->db->like('file_name', $post['searchText']);
            }
            if ($getCount === FALSE) {
                $this->db->limit($limit, $offset);
            }
            $query = $this->db->get();
            if ($getCount === TRUE) {
                $result = $query->num_rows();
            }
            else {
                $result = $query->result_array();
            }
          
            return $result;
    }
    
    public function getDetails($id)
    {
        $this->db->select('*');
        $this->db->from('master_files');
        $this->db->where('file_id',$id);
        $query = $this->db->get();
        if($query->num_rows() == 0)
        {
            return false;
        }
        else
        {
            $result = $query->result_array();
            return $result[0];
        }
    }

    public function save_document($data,$id)
    {
        if($id == 0)
        {
            if($this->db->insert('master_files',$data))
                return $this->db->insert_id();
            else return false;
        }
        else
        {
            $this->db->where('file_id',$id);
            return $response = $this->db->update('master_files',$data);
        }
    }

    public function updateStatus($fileId,$data)
    {
        $this->db->where('file_id',$fileId);
        return $response = $this->db->update('master_files',$data);
        
    }
}
    