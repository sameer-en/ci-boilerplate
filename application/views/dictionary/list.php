<?php if(count($files) > 0){

  foreach($files as $file)
  {
    echo "<tr>
                  <td>$counter</td>
                  <td>".$file['dic_name']."</td>
                  <td>".$file['from_language']."</td>
                  <td>".$file['to_language']."</td>
                  <td>".$file['priority']."</td>
                  <td>".ucfirst($file['username'])."</td>
                  <td><a href='".site_url('dictionary/edit/').$file['dic_id']."'  class='edit-word'>Edit</a> | <a href='javascript:void(0)' data-id='".$file['dic_id']."' class='delete-word'>Delete</a> | <a href='".site_url('dictionary/word-list/'.base64_encode($file['dic_id']))."' data-id='".$file['dic_id']."' class='' target='_blank'>List</a></td>
                </tr>";
      $counter++;
  }

}
else
{
  echo "<tr><td colspan='6' style='text-align:center'><span class='label label-warning'>No dictionary found.</span></td></tr>";
}
?>