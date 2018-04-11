<section class="content-header">
      <h1>
        Word Translator
        <small>You can view all your Word files here.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <!-- <li class="active">Here</li> -->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here | -->
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Word Files</h3>
              <input type="hidden" name="fileType" id="fileType" value="<?php echo $fileType;?>">
              <div class="box-tools">
                <a  href="<?php echo site_url('translate/word/add')?>" class="btn pull-left">Add</a>

                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search" id="searchText">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <?php 
              if($this->session->flashdata('success_document_upload')){
                echo '<div class="col-md-12"><div class="alert alert-success">'.$this->session->flashdata('success_document_upload').'</div></div>';
              }
              if($this->session->flashdata('error_document_upload')){
                echo '<div class="col-md-12"><div class="alert alert-danger">'.$this->session->flashdata('error_document_upload').'</div></div>';
              }
            ?>
              <table class="table table-hover" id="tbl-word">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>File Name</th>
                  <th>Status</th>
                  <th>Created On</th>
                  <th>Added By</th>
                  <th>Action</th>
                </tr>
              </thead>
               <tbody></tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix paginationdiv">
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">&laquo;</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>

      <!--  -------------------------->

    </section>

     <!-- Modal -->
<div class="modal fade" id="filePopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="filePopupTitle">Title</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add/Edit word file from here</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                <div class="form-group">
                  <label for="fileUpload">Upload File</label>
                  <input type="file" class="form-control1" id="fileUpload" name="fileUpload">
                </div>
                <div class="form-group">
                  <label for="dictionaries">Select dictionaries</label>
                  <p class="help-block">Example block-level help text here.</p>
                  <?php if(count($dictionaries) > 0)
                  {
                    echo '<table><tr><th>Dictionary</th><th></th><th>Priority</th></tr>';
                      foreach($dictionaries as $dictionry)
                      {
                        echo '<tr>
                              <td>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox"  name="chkDic['.$dictionry['dic_id'].']" id="chkDic_'.$dictionry['dic_id'].'"> '.$dictionry['dic_name'].'
                                </label>
                              </div>
                              </td>
                              <td>&nbsp;</td>
                              <td><input type="textbox" name="chkDic['.$dictionry['dic_id'].']" id="txtDic_'.$dictionry['dic_id'].'" size="2" class="form-control"/></td>
                              </tr>';
                      }

                      echo '</table>';
                  }?>
                </div>
                <div class="form-group">
                  <label for="Comments">Comments</label>
                  <textarea class="form-control" id="Comments" name="Comments" placeholder="Enter comments"></textarea>
                </div>
              </div>
              <input type="hidden" name="fileId" id="fileId" value="0">

              </form> 
              <!-- /.box-body -->
         
        </div>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
