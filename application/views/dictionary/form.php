<section class="content-header">
      <h1>
       Dictionary
        <small>You can view edit Dictionary information here.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url();?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?php echo site_url('dictionary/list');?>"><i class="fa fa-dashboard"></i> Dictionary</a></li>        
        <li class="active">Dictionary Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here | -->
<div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Horizontal Form</h3>
            </div>
            <!-- /.box-header -->
            
            <!-- form start -->
            <form class="form-horizontal" name="frmForm" id="frmForm" method="post" enctype="multipart/form-data">
              <input type="hidden" name="dic_id" id="dic_id" value="<?php echo $dicId;?>">
              <div class="box-body">
                <?php if($this->session->flashdata('error_document_upload')){
              echo '<div class="alert alert-danger">'.$this->session->flashdata('error_document_upload').'</div>';
            }?>
             <div class="form-group">
                  <label for="file" class="col-sm-2 control-label">Dictionary Name</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="dic_name" name="dic_name" value="<?php echo $details['dic_name'];?>">
                  </div>
                </div>

                <div class="form-group">
                  <label for="file" class="col-sm-2 control-label">csv File</label>

                  <div class="col-sm-2">
                    <input type="file" class="" id="file" name="file">
                  </div>
                  <div class="col-sm-10">
                  <label id="file-error" class="error" for="file"></label>
                </div>
                </div>
               <!-- textarea -->
                <div class="form-group">
                  <label  class="col-sm-2 control-label">Priority</label>
                  <div class="col-sm-4">
                   <input type="text" class="form-control" id="priority" name="priority" value="<?php echo $details['priority'];?>">
                </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info">Save</button>
                <button type="button" onclick="window.location='<?php echo site_url();?>dictionary/list'" class="btn btn-default">Cancel</button>
                
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
          <!-- /.box -->
        </div>
      </div>

      <!--  -------------------------->

    </section>
    <script type="text/javascript">
      var ext = '<?php echo $fileType;?>';
    </script>