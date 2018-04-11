<?php if(count($files) > 0){

  foreach($files as $file)
  {
    echo "<tr>
                  <td>$counter</td>
                  <td>".$file['file_name']."</td>
                  <td><span class='label label-success'>".ucfirst($file['file_status'])."</span></td>
                  <td>".date("d-M-Y",strtotime($file['added_on']))."</td>
                  <td>".ucfirst($file['username'])."</td>
                  <td><a href='".site_url('translate/excel/edit/').$file['file_id']."'  class='edit-excel'>Edit</a> | <a href='javascript:void(0)' data-id='".$file['file_id']."' class='delete-excel'>Delete</a> | <a href='".site_url('translate/processExcel/'.base64_encode($file['file_id']))."' data-id='".$file['file_id']."' class='' target='_blank'>Process</a> | <a href='".site_url('translate/word/download/'.base64_encode($file['file_id']))."' data-id='".$file['file_id']."' class='' target='_blank'>Download</a></td>
                </tr>";
      $counter++;
  }

}
else
{
  echo "<tr><td colspan='6' style='text-align:center'><span class='label label-warning'>No files found.</span></td></tr>";
}
?>