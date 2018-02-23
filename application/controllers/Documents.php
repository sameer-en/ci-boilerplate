<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends MY_Controller {

  public function __construct() {
        parent::__construct();
        $this->load->model('documents_model');
         $this->load->library('form_validation');
    }

  /*
     * 
     * @Author : sameer k
     * index function
     * @return  view
     * 
     */
  public function index() {
  		$data = array();
		$data['footer'] = array($this->javascript->external(base_url() . $this->paths['assets_js']. "documents.js"));
        $this->render('admin/documents/list','admin',$data);
	}

    /*
     * 
     * @Author : sameer k
     * pagination function for ajax loading
     * @return  json formated date
     * 
     */

	public function getDataAjax()
	{
		$post = $this->input->post();
        $page = $post['page'];
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }
        $totalRow = $this->documents_model->get_all_documents(0, 0, TRUE, trim($post['searchText']), $post);
        $details['supplier_details'] = $this->documents_model->get_all_documents($post['perPage'], $offset, FALSE, trim($post['searchText']), $post);
        $this->load->library('Ajax_pagination');
        //pagination configuration
        $config = $this->config->item('pagination_config');
        $config['base_url'] = base_url('admin/documents/manage');
        $config['uri_segment'] = 4;
        $config['total_rows'] = $totalRow;
        $config['per_page'] = $post['perPage'];
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links() . "";
        $data['data'] = $this->load->view('admin/documents/ajax_list', $details, TRUE);
        echo json_encode($data);
	}


    /*
     * 
     * @Author : sameer k
     * create/update document function
     * params : base64 encoded string id
     * @return  view
     * 
     */

	public function add_document($id = '')
	{
        $this->load->model('venues_model');
		$data = array();
        $data['document_details']['doc_id']  = $data['document_details']['venue_id'] = 0;
        $data['document_details']['doc_name']  = $data['document_details']['file_name'] = $data['document_details']['doc_description'] ='';
        $id = base64_decode($id);
        if($id > 0)
        {

            //get details of document
            $data['document_details'] = $this->documents_model->get_document_details($id);
            $data['document_details']['doc_id'] = $data['document_details']['document_id'];
            $data['document_details']['doc_name'] = $data['document_details']['document_name'];
            if($data['document_details'] == false)
            {
                redirect('admin/documents/manage');
            }
        }
        $doc_id = $data['document_details']['doc_id'] ;
		$data['footer'] = array(
            $this->javascript->external(base_url() . $this->paths['assets_js']. "documents.js")
            );

        //Insert suppliers posted data into db.
        if ($this->input->post('doc_name') != "") {
            $postData = $this->input->post();
            if($this->form_validation->run('document_add') == FALSE)//validate
            {
                $error = false;
                if(isset($_FILES['file_name']) && $_FILES['file_name']['error'] == 0 && $_FILES['file_name']['size'] > 0)
                {
                    $path = "./assets/uploads/documents";
                    if(!is_dir($path)) //create the folder if it's not already exists
                    {
                      mkdir($path,0777,TRUE);
                    }
                    //upload file
                    $fileuploadConfig = $this->config->item('fileupload_config');
                    $fileuploadConfig['upload_path'] = './assets/uploads/documents/';
                    $fileuploadConfig['allowed_types'] = array('xls','XLS','PDF','pdf','xlsx','XLSX');
                    $fileuploadConfig['file_name'] = "doc_".$id."_".date("YmdHis");

                    $this->load->library('upload', $fileuploadConfig);
                    if ( $this->upload->do_upload('file_name'))
                    {
                       $postData['file_name'] = $this->upload->data('file_name');

                       //delete old file
                       if($postData['doc_file'] != '')
                       {
                        // delete
                          unlink($path.'/'.$data['document_details']['file_name']);
                       }
                    }
                    else
                    {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error_document_upload', ERROR_DOCUMENT_UPLOAD . $this->upload->display_errors());
                    }
                }
                if($_FILES['file_name']['name'] !='' && $_FILES['file_name']['error'] > 0)
                {
                    //file upload error
                    $error = true;
                     $this->session->set_flashdata('error_document_upload', ERROR_DOCUMENT_UPLOAD );
                }

                if($error == false)
                {
                    //save to db here
                    $datatToSave['document_name'] = $postData['doc_name'];
                    $datatToSave['venue_id'] = $postData['venue_id'];
                    $datatToSave['doc_description'] = $postData['doc_description'];
                    if($postData['file_name']!='')
                    {
                        $datatToSave['file_name'] = $postData['file_name'];   
                    }
                    if($doc_id == 0)
                    {
                        $datatToSave['created_date'] = date('Y-m-d H:i:s');
                    }
                    if($this->documents_model->save_document($datatToSave,$doc_id))
                    {
                        if($doc_id > 0)
                        {
                            $this->session->set_flashdata('success_document_upload', SUCCESS_DOCUMENT_UPLOAD_UPDATE);
                        }
                        else
                        {
                            $this->session->set_flashdata('success_document_upload', SUCCESS_DOCUMENT_UPLOAD_ADD);
                        }
                        
                        redirect('admin/documents/manage');
                    }
                    else
                    {
                        $this->session->set_flashdata('error_document_upload', ERROR_DOCUMENT_UPLOAD . validation_errors());
                       // redirect('admin/documents/manage');
                    }
                }
            }
            else
            {
                 $this->session->set_flashdata('error_document_upload', ERROR_DOCUMENT_UPLOAD . validation_errors());
            }
        }

        //get all venue venues_model
        $data['venues'] = $this->venues_model->get_venues_dropdown();
        $data['footer'] = array($this->javascript->external(base_url() . $this->paths['assets_js']. "validation/document_validations.js"));

        $this->render('admin/documents/add','admin',$data);
	}

     /*
     * 
     * @Author : sameer k
     * download document function
     * params : base64 encoded string id
     * @return  file download
     * 
     */

    public function download($id='')
    {
        $id = base64_decode($id);
        if($id > 0)
        {
            //get details of document
            $data['document_details'] = $this->documents_model->get_document_details($id);
            if($data['document_details'] == false)
            {
                redirect('admin/documents/manage');
            }
            $fileName = $data['document_details']['file_name'];
            $this->load->helper('download');
            $path = "./assets/uploads/documents/";
            force_download($path.$fileName, NULL);
        }
    }

    /*
     * 
     * @Author : sameer k
     * delete document function
     * params :IN POST base64 encoded string id
     * @return  view
     * 
     */

    public function delete_document()
    {
        $ret = array('err'=>true,'message'=>ERROR_DOCUMENT_DELETE);
        $id = $this->input->post('id');
        $id = base64_decode($id);
        if($id > 0)
        {
            //get details of document
            $data['document_details'] = $this->documents_model->get_document_details($id);
            if($data['document_details'] == false)
            {
                $ret['message'] = 'No document found';
            }
            $fileName = $data['document_details']['file_name'];
            $this->load->helper('download');
            $path = "./assets/uploads/documents/";
            @unlink($path.$fileName);
            if( $this->documents_model->delete_document_details($id))
            {
                $ret = array('err'=>false,'message'=>SUCCESS_DOCUMENT_DELETE);
            }
            else
            {
                $ret = array('message'=>ERROR_DOCUMENT_DELETE);
            }

        }
        echo json_encode($ret);
    }
}