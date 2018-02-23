  <div class="content-wrapper">
    <section class="content-header">
        <h1>
            Document Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Document Management</li>
        </ol>
    </section>
<section class="content">
<?php  $edit_success_message = $this->session->flashdata('success_document_upload');
       if (isset($edit_success_message) && $edit_success_message) { ?>
                    <div class="alert alert-success fade in">
                    <a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
                        <?php echo $edit_success_message; ?>
                    </div>
        <?php } ?>
        <?php  $error_document_upload = $this->session->flashdata('error_document_upload');
                if (isset($error_document_upload) && $error_document_upload) { ?>
                    <div class="alert alert-danger fade in">
                    <a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
                    <?php echo $error_document_upload; ?>
                    </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="row table-header no-margin">
                            <div class="col-md-6">
                                <div class="form-inline">
                                    <div class="form-group"> 
                                        <label>Show</label>
                                        <select id="pagination_offset" name="pagination_offset" class="form-control input-sm selectInput">
                                            <?php for ($i = 5; $i <= 20; $i += 5) { ?>
                                                <?php if (($this->session->userdata('Pagination_project') != '' && $this->session->userdata('Pagination_project') == $i) || ($this->session->userdata('Pagination_project') == '' && $i == 10 )) {//|| $i == 10) //by default 10 will be selected on load   
                                                    ?>
                                                    <option selected="selected" value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <label>entries</label>
                                    </div> </div>
                            </div>
                            <div class="col-md-6"> 
                            <div class="form-inline pull-right">
                                <a href="<?php echo base_url('admin/documents/add') ?>" class="btn btn-flat btn-primary"> <i class="fa fa-plus"></i> <b>Add Document</b></a>
                                    <div class="form-group">                           
                                        <input type="text" id="searchText" name="searchText" class="form-control pull-right" placeholder="Search">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row no-margin">
                            <div class="col-md-12">
                                <table class="table table-hover" id="tableSearchResults">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Document Name</th>
                                            <th>Venue Name</th>
                                            <th>File Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer clearfix ">
                        <div class="paginationDiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  </div>
  <input type="hidden" id="doc_confirmation_message" name="doc_confirmation_message" value="<?php echo DOC_CONFIRM_MESSAGE; ?>" />
