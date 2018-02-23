  <div class="content-wrapper">
    <section class="content-header">
        <h1>
            Document Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>admin/documents/manage"><i class=""></i> Document Management</a></li>
            <li class="active"><?php echo ($document_details['doc_id'] > 0) ? 'Update ': 'Add '?> Document</li>
        </ol>
    </section>
    <section class="content">
         <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo ($document_details['doc_id'] > 0) ? 'Update ': 'Add '?> Document</h3>
<!--          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>-->
        </div>
        <div class="box-body">
        <?php if($this->session->flashdata('error_document_upload')!='')
        {
          echo $this->session->flashdata('error_document_upload');
        }
          ?>
        <form action="" id="frm_documents" name = "frm_documents" method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="doc_id" id="doc_id" value="<?php echo $document_details['doc_id']?>" />
        <input type="hidden" name="doc_file" id="doc_file" value="<?php echo $document_details['file_name']?>" />
          <div class="row">
          <div class="col-md-6">
                <div class="form-group">
                  <label for="doc_name">Document Name <span class="required" aria-required="true">*</span></label>
                  <input class="form-control" id="doc_name" name="doc_name" placeholder="Document name" type="text"  value="<?php echo $document_details['doc_name']?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="venue_id">Select Venue <span class="required" aria-required="true">*</span></label>
                  <select class="form-control select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="venue_id" id="venue_id">
                  <option value="">Select Venue</option>
                  <?php if(count($venues) > 0){
                     foreach ($venues as $key => $value) {
                     if($value['venue_id'] == $document_details['venue_id'])
                     {
                        $seleted = 'selected';
                     }
                     else
                        $seleted = '';
                     ?>
                          <option value="<?php echo $value['venue_id']?>" <?php echo $seleted;?>><?php echo $value['venue_name']?></option>
                      <?php } 
                      }
                  ?>
                </select>
              </div>
              </div>
              <div class="col-md-6">
              <div class="form-group">
                  <label for="file_name">Upload document <span class="required" aria-required="true">*</span></label>
                  <input type="file" id="file_name" name="file_name">
                    <div class="pull-left">
                    <?php if($document_details['file_name'] != ''){
                    echo "<a href='".site_url('admin/documents/download/'.base64_encode($document_details['doc_id']))."'>Download:".$document_details['file_name']."</a>";
                    }?>
                    <label for="file_name" class="error" style="width:100%"></label><p>Upload file only in excel/pdf formats</p></div>
              </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                  <label for="doc_description">Description</label>
                  <textarea class="form-control" id="doc_description" name="doc_description" ><?php echo $document_details['doc_description']?></textarea>
              </div>
            </div>
            </div>
            </div>
          <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat" id="save_document" name="save_document"><?php echo ($document_details['doc_id'] > 0 ? 'Update' : 'Save');?></button>
                <button type="reset" class="btn btn-default btn-flat" onclick="window.location = '<?php echo site_url('admin/documents/manage')?>'">Cancel</button>
            </div>
          </form>
        </div>
        </div>
    </section>
  </div>
