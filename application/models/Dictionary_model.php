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

public function save_csv($csvFile,$from_lang_id,$to_lang_id,$newDicId)
    {
        $this->db->cache_delete_all();
        $query = <<<eof
                LOAD DATA LOCAL INFILE '$csvFile'
                 INTO TABLE master_translate
                 CHARACTER SET utf8
                 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
                 LINES TERMINATED BY '\r\n'
                (from_lang,to_lang)
                set dic_id = $newDicId,from_lang_id=$from_lang_id,to_lang_id=$to_lang_id
eof;
        return $this->db->query($query);
    }
    
    public function get_all_dic($limit, $offset, $getCount = FALSE, $post)
    {
        $this->db->select('d.*,username,l1.lan_name as from_language,l2.lan_name as to_language');
        $this->db->from('master_dictionary as d');
        $this->db->join('master_users', 'master_users.uid = d.added_by');
        $this->db->join('master_languages l1', 'l1.lang_id = d.from_lang_id');
        $this->db->join('master_languages l2', 'l2.lang_id = d.to_lang_id');
        if ($post['searchText'] != NULL) {
                $this->db->like('dic_name', $post['searchText']);
            }
            if ($getCount === FALSE) {
                $this->db->limit($limit, $offset);
            }
            $query = $this->db->get();
        //    echo $this->db->last_query();
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
        $this->db->from('master_dictionary');
        $this->db->where('dic_id',$id);
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

    public function save($data,$id)
    {
        if($id == 0)
        {
            if($this->db->insert('master_dictionary',$data))
                return $this->db->insert_id();
            else return false;
        }
        else
        {
            $this->db->where('dic_id',$id);
            return $response = $this->db->update('master_dictionary',$data);
        }
    }

    public function get_all_dic_words($limit, $offset, $getCount = FALSE, $post)
    {
        $this->db->select('*');
        $this->db->from('master_translate');
        $this->db->where('dic_id',$post['dicId']);
        if ($post['searchText'] != NULL) {
                $this->db->where('(from_lang like "%'.$post['searchText'].'%" OR to_lang like "%'.$post['searchText'].'%")');
            }
            if ($getCount === FALSE) {
                $this->db->limit($limit, $offset);
            }
            $query = $this->db->get();
        //    echo $this->db->last_query();
            if ($getCount === TRUE) {
                $result = $query->num_rows();
            }
            else {
                $result = $query->result_array();
            }
          
            return $result;
    }

    public function save_word($data,$id)
    {
        if($id == 0)
        {
            if($this->db->insert('master_translate',$data))
                return $this->db->insert_id();
            else return false;
        }
        else
        {
            $this->db->where('word_id',$id);
            return $response = $this->db->update('master_translate',$data);
        }
    }

    public function getDetailsWord($id)
    {
        $this->db->select('*');
        $this->db->from('master_translate');
        $this->db->where('word_id',$id);
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

    public function check_word($fromLang,$dicId,$wordId)
    {
        $this->db->select('*');
        $this->db->from('master_translate');
        if($wordId>0)
            $this->db->where('word_id !=',$wordId);

        $this->db->where('from_lang',$fromLang);
        $this->db->where('dic_id',$dicId);

        $query = $this->db->get();
        // echo $this->db->last_query();die;
        if($query->num_rows() == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function delete_words($id)
    {
        $this->db->where('word_id',$id);
        $this->db->limit(1);
        $this->db->delete('master_translate');
        if($this->db->affected_rows()> 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_dictionary($id)
    {
        $this->db->where('dic_id',$id);
        $this->db->limit(1);
        $this->db->delete('master_dictionary');
        if($this->db->affected_rows()> 0)
        {
            $this->db->where('dic_id',$id);
            $this->db->delete('master_translate');
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getWords($dicId)
    {
        $this->db->cache_on();
        $this->db->select('from_lang,to_lang');
        $this->db->from('master_translate');
        $this->db->where('dic_id',$dicId);
        $this->db->order_by('CHAR_LENGTH(from_lang)','desc');
        $query = $this->db->get();
        $this->db->cache_off();
        return $query->result_array();
    }

    public function save_xls($data)
    {
        $this->db->cache_delete_all();
        return $this->db->insert_batch('master_translate',$data);
    }
    
}
    