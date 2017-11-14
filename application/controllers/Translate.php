<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Translate extends CI_Controller {

	private $paths;
	public function __construct() {
        parent::__construct();
        $this->paths = $this->config->item('CUSTOME_PATH');
        if(!is_loggedin())
		{
			redirect('user/login');
		}
		$this->load->model('files_model');
		$this->load->library('Ajax_pagination');
    }


	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function word()
	{

		$data = array();
		$data['message'] = '';

		$this->load->view('layout/header',$data );
		$this->load->view('translate/index',$data );
		$this->load->view('layout/footer',$data );
	}

	public function wordAjax()
	{

		$data = array();
		$data['message'] = '';

		$post = $this->input->post();
        $page = $post['page'];
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }
        $str = ($str == 'all') ? $str = NULL : $str;
        $totalRow = $this->files_model->get_all_word(0, 0, TRUE, $post);

        $details['files'] = $this->files_model->get_all_word($post['perPage'], $offset, FALSE, $post);

        $config['base_url'] = base_url('translate/word/ajax');
        $config['uri_segment'] = 4;

        $config['total_rows'] = $totalRow;
        $config['per_page'] = $post['perPage'];
        $config['anchor_class'] = 'page-anchor';
        $config['cur_tag_open'] = '&nbsp;<a class="active">';
        $config['cur_tag_close'] = '</a>';
        $config['num_links'] = 3;
        $config['next_link'] = '>>';
        $config['prev_link'] = '<<';
        $config['first_link'] = 'FIRST';
        $config['last_link'] = 'LAST';

        $this->ajax_pagination->initialize($config);

         $data['pagination'] = $this->ajax_pagination->create_links() . "<br/> Total Records : $totalRow";
        $data['limit'] = $limit;
        $this->ProjectController = $this;

        $data['data'] = $this->load->view('translate/word_list', $details, TRUE);
		echo json_encode($data);


		$this->load->view('layout/header',$data );
		$this->load->view('user/index',$data );
		$this->load->view('layout/footer',$data );
	}
}
