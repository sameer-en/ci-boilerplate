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
		
        echo json_encode($data);
	}

    public function editWord($id=0)
    {
        $data['details']['arrDic'] =  $data['details']['doc_file'] = $data['details']['fileStatus'] = $data['details']['comments'] = '';
        $data['details']['file_id'] = 0;
        if($id > 0)
        {
            $data['details'] = $this->files_model->getDetails($id);
            if($data['details'] === false)
            {
                redirect('translate/word');
            }
        }

        if($this->input->post('arrDic'))
        {
            $postData = $this->input->post();
            $error = false;
            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0 && $_FILES['file']['size'] > 0)
                {
                    $path = "./assets/uploads/documents";
                    if(!is_dir($path)) //create the folder if it's not already exists
                    {
                      mkdir($path,0777,TRUE);
                    }
                    //upload file
                    $fileuploadConfig = $this->config->item('fileupload_config');
                    $fileuploadConfig['upload_path'] = './assets/uploads/documents/';
                    $fileuploadConfig['allowed_types'] = array('docx');
                    $fileuploadConfig['file_name'] = "doc_".$id."_".date("YmdHis");

                    $this->load->library('upload', $fileuploadConfig);
                    if ( $this->upload->do_upload('file'))
                    {
                       $postData['doc_file'] = $this->upload->data('file_name');

                       //delete old file
                       if($postData['doc_file'] != '')
                       {
                        // delete
                          unlink($path.'/'.$data['details']['file_name']);
                       }
                    }
                    else
                    {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('error_document_upload',  $this->upload->display_errors());
                    }
                }

                if($error == false)
                {
                        //save to db here
                    $datatToSave['file_name'] = $postData['doc_file'];
                    $datatToSave['file_status'] = $postData['fileStatus'];
                    $datatToSave['dic_applied'] = implode(',', $postData['arrDic']);
                    $datatToSave['comments'] = implode(',', $postData['comments']);
                    $datatToSave['added_by'] = $this->session->userdata('cib_userID'); 

                    if($postData['file_name']!='')
                    {
                        $datatToSave['file_name'] = $postData['file_name'];   
                    }
                    if($doc_id == 0)
                    {
                        $datatToSave['added_on'] = date('Y-m-d H:i:s');
                    }
                    else
                    {
                        $datatToSave['last_modified_on'] = date('Y-m-d H:i:s');
                    }

                     if($this->files_model->save_document($datatToSave,$data['details']['file_id']))
                     {
                       redirect('translate/word');
                     }
                     else
                     {
                         $this->session->set_flashdata('error_document_upload','Error in upload');
                     }

                }

        }
        
        $data['details']['arrDicIds'] = explode(',',$data['details']['dic_applied']);
        $data['fileId'] =  $data['details']['file_id'];
        $data['message'] = '';
        $data['fileType'] = 'docx';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'docx';
        $data['dictionaries'] = $this->dictionary_model->getAllDictionries();

        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "jquery.validate.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "additional-methods.min.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "translate_validate.js"),
            )
        );


        $this->load->view('layout/header',$data );
        $this->load->view('translate/form',$data );
        $this->load->view('layout/footer',$data );
    }

    public function download($id='')
    {
        $id = base64_decode($id);
        if($id > 0)
        {
            //get details of document
            $data['document_details'] = $this->files_model->getDetails($id);
            if($data['document_details'] == false)
            {
                redirect('translate/word');
            }
            $fileName = $data['document_details']['file_name'];
            $this->load->helper('download');
            $path = "./assets/uploads/documents/";
            force_download($path.$fileName, NULL);
        }
    }

    public function processWord($id)
    {
        $this->load->library('TranslateLib','','TranslateLib');
        $id = base64_decode($id);
        if($id > 0)
        {
            $details = $this->files_model->getDetails($id);
            if($details === false)
            {
                $this->session->set_flashdata('error_document_upload',  'File not found.');
                redirect('translate/word');
            }
            else
            {
                //get and process
                if($this->TranslateLib->processFile($details))
                {
                    $this->session->set_flashdata('success_document_upload',  'File proccessed successfully.');
                    redirect('translate/word');
                }
                else
                {
                    $this->session->set_flashdata('error_document_upload',  'File proccessed successfully.');
                    redirect('translate/word');
                }
            }
        }
    }


    /*---------------excel -------------------*/
    public function excel()
    {
        $data = array();
        $data['message'] = '';
        $data['fileType'] = 'xls';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'excel';
        $data['dictionaries'] = $this->dictionary_model->getAllDictionries();

        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "translate_list.js"),
            )
        );

        $this->load->view('layout/header',$data );
        $this->load->view('translate/excel_index',$data );
        $this->load->view('layout/footer',$data );
    }

    public function excelAjax($page)
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
        $totalRow = $this->files_model->get_all_excel(0, 0, TRUE, $post);

        $details['files'] = $this->files_model->get_all_excel($post['perPage'], $offset, FALSE, $post);
       // print_r($details['files']);die;
        $details['counter'] = ($offset*$post['perPage'])+1;

        $config['base_url'] = base_url('translate/excel/ajax');
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

        $data['data'] = $this->load->view('translate/excel_list', $details, TRUE);
        
        echo json_encode($data);
    }

    public function editExcel($id=0)
    {
        $data['details']['arrDic'] =  $data['details']['doc_file'] = $data['details']['fileStatus'] = $data['details']['comments'] = '';
        $data['details']['file_id'] = 0;
        if($id > 0)
        {
            $data['details'] = $this->files_model->getDetails($id);
            if($data['details'] === false)
            {
                redirect('translate/excel');
            }
        }

        if($this->input->post('arrDic'))
        {
            $postData = $this->input->post();
            $error = false;
            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0 && $_FILES['file']['size'] > 0)
                {
                    $path = "./assets/uploads/excel";
                    if(!is_dir($path)) //create the folder if it's not already exists
                    {
                      mkdir($path,0777,TRUE);
                    }
                    //upload file
                    $fileuploadConfig = $this->config->item('fileupload_config');
                    $fileuploadConfig['upload_path'] = './assets/uploads/excel/';
                    $fileuploadConfig['allowed_types'] = array('xls');
                    $fileuploadConfig['file_name'] = "xls_".$id."_".date("YmdHis");

                    $this->load->library('upload', $fileuploadConfig);
                    if ( $this->upload->do_upload('file'))
                    {
                       $postData['doc_file'] = $this->upload->data('file_name');

                       //delete old file
                       if($postData['doc_file'] != '')
                       {
                        // delete
                          unlink($path.'/'.$data['details']['file_name']);
                       }
                    }
                    else
                    {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('error_document_upload',  $this->upload->display_errors());
                    }
                }

                if($error == false)
                {
                        //save to db here
                    $datatToSave['file_name'] = $postData['doc_file'];
                    $datatToSave['file_status'] = $postData['fileStatus'];
                    $datatToSave['dic_applied'] = implode(',', $postData['arrDic']);
                    $datatToSave['comments'] = implode(',', $postData['comments']);
                    $datatToSave['added_by'] = $this->session->userdata('cib_userID'); 
                    $datatToSave['file_type'] = 'xls'; 

                    if($postData['file_name']!='')
                    {
                        $datatToSave['file_name'] = $postData['file_name'];   
                    }
                    if($doc_id == 0)
                    {
                        $datatToSave['added_on'] = date('Y-m-d H:i:s');
                    }
                    else
                    {
                        $datatToSave['last_modified_on'] = date('Y-m-d H:i:s');
                    }

                     if($this->files_model->save_document($datatToSave,$data['details']['file_id']))
                     {
                       redirect('translate/excel');
                     }
                     else
                     {
                         $this->session->set_flashdata('error_document_upload','Error in upload');
                     }

                }

        }
        
        $data['details']['arrDicIds'] = explode(',',$data['details']['dic_applied']);
        $data['fileId'] =  $data['details']['file_id'];
        $data['message'] = '';
        $data['fileType'] = 'xls';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'xls';
        $data['dictionaries'] = $this->dictionary_model->getAllDictionries();

        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "jquery.validate.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "additional-methods.min.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "translate_validate.js"),
            )
        );


        $this->load->view('layout/header',$data );
        $this->load->view('translate/form_excel',$data );
        $this->load->view('layout/footer',$data );
    }

    public function processExcel($id)
    {
        $id = base64_decode($id);
        if($id > 0)
        {
            //get and process
        }
       
    }
}


