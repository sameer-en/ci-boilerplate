<section class="content-header">
      <h1>
        Dictionary
        <small>You can view all your Dictionaries files here.</small>
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
              <h3 class="box-title">Dictionary</h3>
              <div class="box-tools">
                <a  href="<?php echo site_url('dictionary/add')?>" class="btn pull-left">Add</a>

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
                  <th>Dictionary Name</th>
                  <th>From Language</th>
                  <th>To Language</th>
                  <th>Priority</th>
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
   