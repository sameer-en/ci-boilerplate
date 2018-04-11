<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

// Include Spout library 
require_once APPPATH.'/libraries/spout-2.4.3/src/Spout/Autoloader/autoload.php';

class Dictionary extends CI_Controller {

	private $paths;
	public function __construct() {
        parent::__construct();
        $this->paths = $this->config->item('CUSTOME_PATH');
        if(!is_loggedin())
		{
			redirect('user/login');
		}
		$this->load->model(array('dictionary_model'));
		$this->load->library(array('Ajax_pagination','javascript'));
    }


	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function list_dic()
	{
		$data = array();
		$data['message'] = '';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'dictionaries';
       // $data['dictionaries'] = $this->dictionary_model->getAllDictionries();

        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "dictionary_list.js"),
            )
        );

		$this->load->view('layout/header',$data );
		$this->load->view('dictionary/index',$data );
		$this->load->view('layout/footer',$data );
	}

	public function dicAjax($page)
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
        $totalRow = $this->dictionary_model->get_all_dic(0, 0, TRUE, $post);

        $details['files'] = $this->dictionary_model->get_all_dic($post['perPage'], $offset, FALSE, $post);
       // print_r($details['files']);die;
        $details['counter'] = ($offset*$post['perPage'])+1;

        $config['base_url'] = base_url('dictionary/list/ajax');
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

        $data['data'] = $this->load->view('dictionary/list', $details, TRUE);
		
        echo json_encode($data);
	}

    public function edit($id=0)
    {
        $data['details']['dic_name'] =  '';
        $data['details']['dic_id'] =  $data['details']['priority'] = 0;
        if($id > 0)
        {
            $data['details'] = $this->dictionary_model->getDetails($id);
            if($data['details'] === false)
            {
                redirect('dictionary/list');
            }
        }

        if($this->input->post('dic_name'))
        {
            $postData = $this->input->post();
            $error = false;
            if($error == false)
            {
                    //save to db here
                $datatToSave['dic_name'] = $postData['dic_name'];
                $datatToSave['from_lang_id'] = 1;
                $datatToSave['to_lang_id'] = 2;
                $datatToSave['priority'] = $postData['priority'];
                $datatToSave['added_by'] = $this->session->userdata('cib_userID');
                $newDicId = $this->dictionary_model->save($datatToSave,$data['details']['dic_id']);
                 if($newDicId != false)
                 {
                    //upload words to dictionry if csv file is uploaded
                    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0 && $_FILES['file']['size'] > 0)
                    {
                        $path = "./assets/uploads/dictionary";
                        if(!is_dir($path)) //create the folder if it's not already exists
                        {
                          mkdir($path,0777,TRUE);
                        }
                        //upload file
                        $fileuploadConfig = $this->config->item('fileupload_config');
                        $fileuploadConfig['upload_path'] = './assets/uploads/dictionary/';
                        $fileuploadConfig['allowed_types'] = array('csv','xls','xlsx');
                        $fileuploadConfig['file_name'] = "dic_".$newDicId;

                        $this->load->library('upload', $fileuploadConfig);
                        if ( $this->upload->do_upload('file'))
                        {
                            //process
                             if($id > 0){$newDicId = $id;}
                            $csvFile =  $fileuploadConfig['upload_path'].$this->upload->data('file_name');
                            $ext = $this->upload->data('file_ext');
                            if($ext == '.xls' || $ext == '.xlsx')
                            {

                                $insertData = $this->readXls($csvFile,$datatToSave['from_lang_id'],$datatToSave['to_lang_id'],$newDicId);
                                if(count($insertData) > 0)
                                    $this->dictionary_model->save_xls($insertData);
                            }
                            else
                            {
                                $this->dictionary_model->save_csv($csvFile,$datatToSave['from_lang_id'],$datatToSave['to_lang_id'],$newDicId);
                            }
                        }
                    }
                    redirect('dictionary/list');
                 }
                 else
                 {
                     $this->session->set_flashdata('error_document_upload','Error in dictionary save');
                 }

            }

        }
        
        $data['dicId'] =  $data['details']['dic_id'];
        $data['message'] = '';
        $data['fileType'] = 'csv|xls|xlsx';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'dictionary';

        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "jquery.validate.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "additional-methods.min.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "dictionary_validate.js"),
            )
        );


        $this->load->view('layout/header',$data );
        $this->load->view('dictionary/form',$data );
        $this->load->view('layout/footer',$data );
    }

    public function list_words($id)
    {
        $id = base64_decode($id);

        $data = array();
        $data['message'] = '';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'dictionaries';
        $data['details'] = $this->dictionary_model->getDetails($id);
        if($data['details'] === false)
        {
            redirect('dictionary/list');
        }

        
        $data['assets'] = array(
        'head' => array(
           
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "dictionary_words_list.js"),
            )
        );

        $this->load->view('layout/header',$data );
        $this->load->view('dictionary/index_words',$data );
        $this->load->view('layout/footer',$data );
    }

    public function wordsAjax($page)
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
        $totalRow = $this->dictionary_model->get_all_dic_words(0, 0, TRUE, $post);

        $details['files'] = $this->dictionary_model->get_all_dic_words($post['perPage'], $offset, FALSE, $post);
       // print_r($details['files']);die;
        $details['counter'] = ($offset*$post['perPage'])+1;

        $config['base_url'] = base_url('dictionary/word-list/ajax');
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

        $data['data'] = $this->load->view('dictionary/list_words', $details, TRUE);
        
        echo json_encode($data);
    }

    public function add_words($id)
    {
        $data['details']['from_lang'] =  $data['details']['to_lang'] = '';
        $data['details']['word_id'] = 0;

        if($id != '')
        {
            $data['details'] = $this->dictionary_model->getDetails(base64_decode($id));
            if($data['details'] === false)
            {
                redirect('dictionary/word-list/'.$id);
            }
             $data['details']['from_lang_id'] = $data['details']['from_lang_id'];
             $data['details']['to_lang_id'] = $data['details']['to_lang_id'];
             $data['details']['dic_id'] = $data['details']['dic_id'];
             $data['dic_details'] = $data['details'];
        }

        if($this->input->post('from_lang') != '' && $this->input->post('to_lang') != '')
        {
            $postData = $this->input->post();
            $error = false;

            //check before insert
            if($this->dictionary_model->check_word($this->input->post('from_lang'),$data['details']['dic_id'],$data['details']['word_id']))
            {
                $error = true;
                $this->session->set_flashdata('error_document_upload','Error: Duplicate word/sentence');
            }

            if($error == false)
            {
                    //save to db here
                $datatToSave['from_lang'] = $postData['from_lang'];
                $datatToSave['to_lang'] = $postData['to_lang'];
                $datatToSave['from_lang_id'] = $data['details']['from_lang_id'];
                $datatToSave['to_lang_id'] = $data['details']['to_lang_id'];
                $datatToSave['dic_id'] = $data['details']['dic_id'];

                $newDicId = $this->dictionary_model->save_word($datatToSave,$data['details']['word_id']);
                 if($newDicId != false)
                 {
                    $this->session->set_flashdata('success_document_upload','Word/sentense saved successfully');
                    redirect('dictionary/word-list/'.$id);
                 }
                 else
                 {
                     $this->session->set_flashdata('error_document_upload','Error in word/sentense save');
                 }
            }
        }
        
        $data['message'] = '';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'dictionary';

        $data['assets'] = array(
        'head' => array(
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "jquery.validate.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "additional-methods.min.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "dictionary_words_validate.js"),
            )
        );

        $this->load->view('layout/header',$data );
        $this->load->view('dictionary/form_words',$data );
        $this->load->view('layout/footer',$data );
    }

    public function words_edit($id)
    {
        if($id > 0)
        {
            $data['details'] = $this->dictionary_model->getDetailsWord($id);
            if($data['details'] === false)
            {
                redirect('dictionary/word-list/'.base64_encode($id));
            }
             $data['details']['from_lang_id'] = $data['details']['from_lang_id'];
             $data['details']['to_lang_id'] = $data['details']['to_lang_id'];
             $data['details']['word_id'] = $data['details']['word_id'];

             $data['dic_details'] = $this->dictionary_model->getDetails($data['details']['dic_id']);
        }

        if($this->input->post('from_lang') != '' && $this->input->post('to_lang') != '')
        {
            $postData = $this->input->post();
            $error = false;

            // check before insert
            if($this->dictionary_model->check_word($this->input->post('from_lang'),$data['details']['dic_id'],$data['details']['word_id']))
            {
                $this->session->set_flashdata('error_document_upload','Error: Duplicate word/sentence');
                $error = true;
            }

            if($error == false)
            {
                    //save to db here
                $datatToSave['from_lang'] = $postData['from_lang'];
                $datatToSave['to_lang'] = $postData['to_lang'];
                $datatToSave['from_lang_id'] = $data['details']['from_lang_id'];
                $datatToSave['to_lang_id'] = $data['details']['to_lang_id'];
                $datatToSave['dic_id'] = $data['details']['dic_id'];

                $newDicId = $this->dictionary_model->save_word($datatToSave,$data['details']['word_id']);
                 if($newDicId != false)
                 {
                    $this->session->set_flashdata('success_document_upload','Word/sentense updated successfully');
                    redirect('dictionary/word-list/'.base64_encode($data['details']['dic_id']));
                 }
                 else
                 {
                     $this->session->set_flashdata('error_document_upload','Error in word/sentense save');
                 }
            }
        }
        
        $data['message'] = '';
        $data['CUSTOME_PATH'] = $this->paths ;
        $data['menuActive'] = 'dictionary';

        $data['assets'] = array(
        'head' => array(
            ),
        'footer' => array(
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "jquery.validate.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "additional-methods.min.js"),
            $this->javascript->external(base_url() . $this->paths['javascripts'] . "dictionary_words_validate.js"),
            )
        );

        $this->load->view('layout/header',$data );
        $this->load->view('dictionary/form_words',$data );
        $this->load->view('layout/footer',$data );
    }

    public function delete_words()
    {
        $ret = array('error' => 1,'message' => 'Please enter ID');
        $id = $this->input->post('id');
        if($id > 0)
        {
            if($this->dictionary_model->delete_words($id))
            {
                $ret = array('error' => 0,'message' => 'Word deleted successfully');
            }
            else
            {
                $ret = array('error' => 1,'message' => 'Error in word deletion');
            }
        }
       echo json_encode($ret);
    }

    public function delete_dictionary()
    {
        $ret = array('error' => 1,'message' => 'Please enter ID');
        $id = $this->input->post('id');
        if($id > 0)
        {
            if($this->dictionary_model->delete_dictionary($id))
            {
                $ret = array('error' => 0,'message' => 'Dictionary deleted successfully');
            }
            else
            {
                $ret = array('error' => 1,'message' => 'Error in dictionary deletion');
            }
        }
       echo json_encode($ret);
    }

    private function readXls($filePath,$from_lang_id,$to_lang_id,$newDicId)
    {
        $return = array();
         
        $filePath = str_replace('./assets/','/assets/',$filePath);
        $inputFileName = FCPATH.$filePath;    
        // Read excel file by using ReadFactory object.
        $reader = ReaderFactory::create(Type::XLSX);
 
        // Open file
        $reader->open($inputFileName);
        $count = 1;
        // Number of sheet in excel file
        foreach ($reader->getSheetIterator() as $sheet) {
             
            // Number of Rows in Excel sheet
            foreach ($sheet->getRowIterator() as $row) {
 
                // It reads data after header. In the my excel sheet, 
                // header is in the first row. 
                //if ($count > 1) { 
                    if(trim($row[0]) != '' && trim($row[1]) != '')
                    {
                         $return[] = array(
                                            'from_lang' => $row[0],
                                            'to_lang' => $row[1],
                                            'dic_id' => $newDicId,
                                            'from_lang_id' => $from_lang_id,
                                            'to_lang_id' => $to_lang_id,
                                    
                                        );
                    }
                //}
                $count++;
            }
        }
 
        // Close excel file
        $reader->close();
        return $return;
    }
}


