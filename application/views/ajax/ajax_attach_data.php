<div class="row">
  <div class="col-md-12">
    <input type="file" id="attaches_file" name="attaches_file[]"
         class="form-control"
         style="display: none;" multiple/>

    <button type="button" onclick="attaches_file.click();" class="btn btn-sm btn-primary">
      <i class="fa fa-paperclip"></i> เพิ่มเอกสารแนบ
    </button>
  </div>
  <label
    class="col-md-12 text-danger"><?php if (isset($upload_msg)) {
      echo $upload_msg;
    } ?></label>
</div>
<table role="grid" id="table-example"
     class="table table-bordered table-hover dataTable no-footer">
  <thead>
  <tr role="row">
    <th class="text-center" width="10%">ลำดับ</th>
    <th class="text-center" width="70%">เอกสารแนบ</th>
    <th class="text-center" width="20%"></th>
  </tr>
  </thead>
  <tbody>
  <?php
  if (isset($attach) && !empty($attach)) {
    $i = 0;
    foreach ($attach as $key => $value) {
      $i++;
      ?>
      <tr class="odd" role="row">
        <td class="text-center">
          <?php echo $i; ?>
        </td>
        <td class="text-left">
          <a href="<?php echo base_url('assets/attaches/'.$value->structure_id.'_'.$value->tree_id.'/'.$value->filename); ?>" target="_blank"><?php echo $value->detail; ?></a>
        </td>
        <td class="text-center">
          <a href="#" class="text-danger" onclick="delete_file('<?php echo $value->structure_id; ?>','<?php echo $value->id; ?>')" ><i class="ace-icon fa fa-trash-o"></i></a>
        </td>
      </tr>
      <?php
    }
  } else {
    ?>
    <tr class="odd" role="row">
      <td class="text-center" colspan="3">ไม่มีข้อมูล</td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<script>
  function delete_file(structure_id,id) {
      swal({
              title: "แจ้งเตือน",
              text: "ต้องการลบเอกสารแนบใช่หรือไม่",
              type: "warning",
              showCancelButton: true,
              confirmButtonText: "ลบ",
              cancelButtonText: "ยกเลิก",
              closeOnConfirm: false,
              closeOnCancel: true
          },
          function (isConfirm) {
              if (isConfirm) {
                  location.href = '<?php echo base_url("criteria/ajax_delete_file/"); ?>' + structure_id+'/'+id;
              }
          });
  }
</script>
