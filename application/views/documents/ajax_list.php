<?php
if($supplier_details){
foreach ($supplier_details as $key=>$row) {?>
    <tr>
        <td><?php echo $key+1;?></td>
        <td><?php echo $row['document_name'] ?></td>
        <td><?php echo $row['venue_name']; ?></td>
        <td><?php  echo "<a href='".site_url('admin/documents/download/'.base64_encode($row['document_id']))."'>".$row['file_name']."</a>"; ?></td>
        <td class="actions">
            <a href="<?php echo site_url('admin/documents/add/' . base64_encode($row['document_id'])) ?>" class="tooltips actionLink"><i class="glyphicon glyphicon-edit" title="Edit"></i>
                 
            </a> 
            <a href="javascript:void(0)" id="del_<?php echo site_url('admin/documents/delete/' . base64_encode($row['document_id'])) ?>" class="delete-document tooltips actionLink" data-id="<?php echo base64_encode($row['document_id']) ?>"><i class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete"></i>
                 
            </a>
        </td>
    </tr>
<?php }} else {
    echo "<tr><td colspan='6' style='text-align: center;'>No Documents Found!</td></tr>";
}?>