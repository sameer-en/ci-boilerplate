<section class="content-header">
      <h1>
       Dictionary Words
        <small>You can view edit Dictionary words here.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('dictionary/list');?>">Dictionary</a></li>
        <li><a href="<?php echo site_url('dictionary/word-list/'.base64_encode($dic_details['dic_id']));?>"><?php echo $dic_details['dic_name']?></a></li>
        <li class="active">Edit Words</li>
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
              <input type="hidden" name="dic_id" id="dic_id" value="<?php echo $dic_id;?>">
              <input type="hidden" name="word_id" id="word_id" value="<?php echo $word_id;?>">
              <div class="box-body">
                <?php if($this->session->flashdata('error_document_upload')){
              echo '<div class="alert alert-danger">'.$this->session->flashdata('error_document_upload').'</div>';
            }?>
             <div class="form-group">
                  <label for="file" class="col-sm-2 control-label">From Language</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="from_lang" name="from_lang" value="<?php echo $details['from_lang'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="file" class="col-sm-2 control-label">To Language</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="to_lang" name="to_lang" value="<?php echo $details['to_lang'];?>">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info">Save</button>
                <button type="button" onclick="window.location='<?php echo site_url('dictionary/word-list/'.base64_encode($dic_details['dic_id']));?>'" class="btn btn-default">Cancel</button>
                
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