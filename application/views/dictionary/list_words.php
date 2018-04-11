<?php if(count($files) > 0){

  foreach($files as $file)
  {
    echo "<tr>
                  <td>$counter</td>
                  <td>".$file['from_lang']."</td>
                  <td>".$file['to_lang']."</td>
                  <td><a href='".site_url('dictionary/words_edit/').$file['word_id']."'  class='edit-word'>Edit</a> | <a href='javascript:void(0)' data-id='".$file['word_id']."' class='delete-word'>Delete</a></td>
                </tr>";
      $counter++;
  }
}
else
{
  echo "<tr><td colspan='6' style='text-align:center'><span class='label label-warning'>No words found.</span></td></tr>";
}
?>