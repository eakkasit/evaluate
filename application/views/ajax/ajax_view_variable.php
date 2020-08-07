<?php
  if(isset($datas) && !empty($datas)){

    foreach ($datas as $key => $data) {
    ?>
    <tr>
      <td class="center"><?php echo $key+1; ?></td>
      <td>
        <input type="hidden" name="variable_data[<?php echo $key; ?>][criteria_id]" value="<?php echo $data->criteria_id ?>">
        <input type="text" class="form-control" name="variable_data[<?php echo $key; ?>][variable_name]" value="<?php echo $data->variable_name ?>" id="variable_name_<?php echo $key+1; ?>">
      </td>
      <td>
        <input type="hidden"  name="variable_data[<?php echo $key; ?>][variable_id]" value="<?php echo $data->variable_id; ?>" id="variable_id_<?php echo $key+1; ?>">
        <?php echo $variable_lists[$data->variable_id]; ?>
      </td>
      <!-- <td>1</td> -->
      <td><input type="text" class="form-control" name="variable_data[<?php echo $key; ?>][variable_prepend]" value="<?php echo $data->variable_prepend; ?>"  id="variable_prepend_<?php echo $key+1; ?>"></td>
      <td>
        <a href="#" class="table-link" onclick="delete_variable('<?php echo $key; ?>');" title="ลบ">
          <button type="button" class="btn btn-xs btn-danger">
            <i class="fa fa-trash-o"></i> ลบ
          </button>
        </a>
      </td>
    </tr>
    <?php
    }

  }
?>
