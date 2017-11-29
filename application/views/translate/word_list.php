<?php if(count($files) > 0){

  foreach($files as $file)
  {
    echo "<tr>
                  <td>$counter</td>
                  <td>".$file['file_name']."</td>
                  <td>".ucfirst($file['file_status'])."</td>
                  <td>".date("d-M-Y",strtotime($file['added_on']))."</td>
                  <td>".ucfirst($file['username'])."</td>
                  <!--<td><span class='label label-success'>Approved</span></td>-->
                  <td><a href='javascript:void(0)' data-id='".$file['file_id']."' class='edit-word'>Edit</a> | <a href='javascript:void(0)' data-id='".$file['file_id']."' class='delete-word'>Delete</a> | <a href='javascript:void(0)' data-id='".$file['file_id']."' class='process-word'>Process</a> | <a href='javascript:void(0)' data-id='".$file['file_id']."' class='download-word'>Download</a></td>
                </tr>";
      $counter++;
  }

}
else
{
  echo "<tr><td colspan='6' style='text-align:center'><span class='label label-warning'>No files found.</span></td></tr>";
}
?>