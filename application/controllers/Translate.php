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
		$this->load->model(array('files_model','dictionary_model'));
		$this->load->library(array('Ajax_pagination','javascript'));
    }


	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function word()
	{
		$data = array();
		$data['message'] = '';
        $data['fileType'] = 'docx';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'docx';
        $data['dictionaries'] = $this->dictionary_model->getAllDictionries();

        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "translate_list.js"),
            )
        );

		$this->load->view('layout/header',$data );
		$this->load->view('translate/index',$data );
		$this->load->view('layout/footer',$data );
	}

	public function wordAjax($page)
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
        $post['searchText'] = ($post['searchText'] == 'all') ? $post['searchText'] = NULL : $post['searchText'];
        $totalRow = $this->files_model->get_all_word(0, 0, TRUE, $post);

        $details['files'] = $this->files_model->get_all_word($post['perPage'], $offset, FALSE, $post);
       // print_r($details['files']);die;
        $details['counter'] = ($offset*$post['perPage'])+1;

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
		
   //     print_r($details);
//die('here');
        echo json_encode($data);
	}

    public function getInfo()
    {
        echo json_encode(array('error'=> false));



    }


    public function editWord($id=0)
    {
        if($id <= 0)
        {
            redirect('translate/word');
        }

        $data['details'] = $this->files_model->getDetails($id);
        if($data['details'] === false)
        {
            redirect('translate/word');
        }

        if($this->input->post())
        {

        }
        $data['details']['arrDicIds'] = explode(',',$data['details']['dic_applied']);
        $data['fileId'] = 0;
        $data['message'] = '';
        $data['fileType'] = 'docx';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'docx';
        $data['dictionaries'] = $this->dictionary_model->getAllDictionries();

        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "translate_list.js"),
            )
        );


        $this->load->view('layout/header',$data );
        $this->load->view('translate/form',$data );
        $this->load->view('layout/footer',$data );



    }

}


