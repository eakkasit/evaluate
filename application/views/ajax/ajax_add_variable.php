<tr>
  <td class="center"><?php echo $data->row+1; ?></td>
  <td>
    <input type="hidden" name="variable_data[<?php echo $data->row; ?>][criteria_id]" value="<?php echo $data->modal_criteria_id ?>">
    <input type="text" class="form-control" name="variable_data[<?php echo $data->row; ?>][variable_name]" value="<?php echo $data->variable_name ?>" id="variable_name_<?php echo $data->row+1; ?>">
  </td>
  <td>
    <input type="hidden"  name="variable_data[<?php echo $data->row; ?>][variable_id]" value="<?php echo $data->variable_id; ?>" id="variable_id_<?php echo $data->row+1; ?>">
    <?php echo $variable_lists[$data->variable_id]; ?>
  </td>
  <!-- <td>1</td> -->
  <td><input type="text" class="form-control" name="variable_data[<?php echo $data->row; ?>][variable_prepend]"  id="variable_prepend_<?php echo $data->row+1; ?>"></td>
  <td>
    <a href="#" class="table-link" onclick="delete_variable('<?php echo $data->row; ?>');" title="ลบ">
      <button type="button" class="btn btn-xs btn-danger">
        <i class="fa fa-trash-o"></i> ลบ
      </button>
    </a>
  </td>
</tr>
