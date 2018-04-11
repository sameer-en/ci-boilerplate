<section class="content-header">
      <h1>
        Word Translator
        <small>You can view edit Word files here.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url();?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?php echo site_url('translate/word');?>"><i class="fa fa-dashboard"></i> Word Translate</a></li>        
        <li class="active">Word Edit</li>
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
              <input type="hidden" name="file_id" id="file_id" value="<?php echo $fileId;?>">
              <input type="hidden" name="doc_file" id="doc_file" value="<?php echo $details['file_name'];?>">
              <div class="box-body">
                <?php if($this->session->flashdata('error_document_upload')){
              echo '<div class="alert alert-danger">'.$this->session->flashdata('error_document_upload').'</div>';
            }?>
                <div class="form-group">
                  <label for="file" class="col-sm-2 control-label">File</label>

                  <div class="col-sm-2">
                    <input type="file" class="" id="file" name="file">
                  </div>
                  <div class="col-sm-4">
                   File: <?php echo $details['file_name']?>
                  </div>
                  <div class="col-sm-10">
                  <label id="file-error" class="error" for="file"></label>
                </div>
                </div>
                <div class="form-group">
                  <label for="fileStatus" class="col-sm-2 control-label">File Status</label>

                  <div class="col-sm-4">
                     <select class="form-control" name="fileStatus" id="fileStatus">
                      <option value="">select</option>
                    <option value="pending" <?php if($details['file_status'] == 'pending') echo " selected='selected'";?>>Pending</option>
                    <option value="completed" <?php if($details['file_status'] == 'completed') echo " selected='selected'";?>>Completed</option>
                  </select>
                  </div>
                </div>
               <!-- textarea -->
                <div class="form-group">
                  <label  class="col-sm-2 control-label">Comments</label>
                  <div class="col-sm-4">
                  <textarea class="form-control" rows="3" placeholder="Enter comments" name="comments" id="comments"><?php echo $details['comments'];?></textarea>
                </div>
                </div>
                
                 <div class="form-group">
                  <label  class="col-sm-2 control-label">Select Dictionries to be applied</label>
                
                  <div class="col-sm-10">
                  <?php foreach($dictionaries as $dictionary){ 
                      $checked = '';
                      if(in_array($dictionary['dic_id'],$details['arrDicIds']))
                         $checked = ' checked';
                    ?>
                    <div class="checkbox">
                      <label  class="col-sm-2 "> 
                 <input type="checkbox" name="arrDic[]" <?php echo $checked;?> value="<?php echo $dictionary['dic_id']?>" id="arrDic<?php echo $dictionary['dic_id']?>"><?php echo $dictionary['dic_name']?></label>
                    </div>
                 <?php  }?>
             </div>
               <div class="col-sm-6 col-sm-offset-2">
               <label id="arrDic[]-error" class="error" for="arrDic[]"></label>
             </div>
             </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info">Save</button>
                <button type="button" onclick="window.location='<?php echo site_url();?>translate/word'" class="btn btn-default">Cancel</button>
                
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