<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//When we creating new stort then should get status from process of milestones
function is_loggedin() 
    {
        $CI = & get_instance();
        if($CI->session->userdata('cib_userID') != '' && $CI->session->userdata('cib_userID')  >0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

function validate_login($email,$password)
{
    $CI = & get_instance();
    $CI->db->select('uid,username,user_status');
    $CI->db->from('master_users');
    $CI->db->where('user_email', $email);
    $CI->db->where('user_password', md5($password));
    $query = $CI->db->get();
    if($query->num_rows() > 0)
    {
        $result = $query->row_array();
        if($result['user_status'] != 1)
        {
            $ret['error'] = true;
            $ret['message'] = 'Account is blocked.Contact administrator';
        }
        else
        {
            $ret['error'] = false;
            $ret['data'] = $result;
        }
    }
    else
    {
        $ret['error'] = true;
        $ret['message'] = 'Invalid details';
    }
    return $ret;
}