<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	private $paths;
	public function __construct() {
        parent::__construct();
        $this->paths = $this->config->item('CUSTOME_PATH');
    }

	public function index()
	{
		$data = array();
		$data['CUSTOME_PATH'] = $this->paths ;
		if(!is_loggedin())
		{
			redirect('user/login');
		}

		$this->load->view('layout/header',$data );
		$this->load->view('user/index',$data );
		$this->load->view('layout/footer',$data );
	}

	public function login()
	{
		$data = array();
		$data['message'] = '';
		if(is_loggedin())
		{
			redirect('user/index');
		}

		$this->load->library('form_validation');
		if($this->input->post('btn_submit'))
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if ($this->form_validation->run() == TRUE)
			{
				$ret = validate_login($email,$password);
				//validate combination
				if($ret['error'] ==  false)
				{
					//set session
					$sessData = array(
									'cib_userID' => $ret['data']['uid'],
									'cib_userName' => ucfirst($ret['data']['username']),
									);
					$this->session->set_userdata($sessData);
					redirect('user/index');
				}
				else
				{
					$data['message'] = $ret['message'];
				}
			}
			else
			{
				$data['message'] = validation_errors() ;
			}
		}

		$data['CUSTOME_PATH'] = $this->paths ;
		$this->load->view('layout/loginheader',$data);
		$this->load->view('user/login',$data);
		$this->load->view('layout/loginfooter',$data);
	}

	public function logout()
	{
		$sessData = array(
						'cib_userID' => '',
						'cib_userName' => '',
						);
		$this->session->set_userdata($sessData);
		redirect('user/login');
	}
}
