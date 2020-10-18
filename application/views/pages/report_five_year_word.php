<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=รายงานโครงการ5ปี.doc");
?>
<html>
<style>
@page Section1 {size:595.45pt 841.7pt; margin:1.0in 1.25in 1.0in 1.25in;mso-header-margin:.5in;mso-footer-margin:.5in;mso-paper-source:0;}
div.Section1 {page:Section1;}
@page Section2 {size:841.7pt 595.45pt;mso-page-orientation:landscape;margin:1.25in 1.0in 1.25in 1.0in;mso-header-margin:.5in;mso-footer-margin:.5in;mso-paper-source:0;}
div.Section2 {page:Section2;}
table#table_five_year td {
 border: 1px solid black;
 border-collapse: collapse;
 padding: 4px;
}
</style>
<body>
<div class=Section2>
  <table border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="15">
        <center>
          <strong>
            <?php echo thai_number("รายงานโครงการ5ปี"); ?>
          </strong>
        </center>
        <br/>
        <br/>
      </td>
    </tr>
    <tr>
      <td>
        <table  border="1" cellspacing="0" id="table_five_year" cellpadding="0" width="100%">
          <tr role="row">
            <th class="text-center start_no " width="70px" >ลำดับ</th>
            <th class="text-center " width="250px"  >ชื่อโครงการ</th>
            <th class="text-center" width="100px"  >ปีงบประมาณ</th>
            <th class="text-center" width="100px"  >น้ำหนักโครงการ</th>
            <?php
            for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
              ?>
              <th class="text-center" width="100px"  >เป้าหมายปี <?php echo $search_year_start+$i+543; ?></th>
              <th class="text-center" width="100px"  >ผลการประเมิน</th>
              <th class="text-center" width="100px"  >ร้อยละความสำเร็จ</th>
            <?php } ?>
            <th class="text-center" width="75px"  >ร้อยละความสำเร็จทั้งโครงการ</th>
          </tr>
          <?php
          if (isset($project_list) && !empty($project_list)) {
            foreach ($project_list as $key => $data) {
              ?>
              <tr class="odd" role="row">
                <td class="text-center">
                  <?php
                  echo number_format($key+1, 0);
                  ?>
                </td>
                <td class="text-left">
                  <?php echo $data->project_name; ?>
                </td>
                <td class="text-center" >
                  <?php
                  if($data->year_start == $data->year_end){
                    echo $data->year_start+543;
                  }else{
                    $year_start_show = $data->year_start+543;
                    $year_end_show = $data->year_end+543;
                    echo "$year_start_show - $year_end_show" ;
                  }
                  ?>
                </td>
                <td class="text-center" >
                  <span id="text_weight_<?php echo $data->id; ?>">
                    <?php echo $data->weight; ?>
                  </span>
                </td>

                <?php
                $result_all = 0;
                for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
                  if(isset($data_detail['score'][$data->id][$search_year_start+$i])){
                    $result_all += $data_detail['score'][$data->id][$search_year_start+$i];
                  }

                  if($i == 0){
                    ?>
                    <td class="text-center">
                      <span id="target_text_<?php echo "{$data->id}_".$i; ?>" class="save_data_text">
                        <?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>
                      </span>
                    </td>

                    <td class="text-center">
                      <span id="score_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
                        <?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>
                      </span>

                    </td>
                    <td class="text-center">
                      <span id="result_<?php echo "{$data->id}_".$i ; ?>" class="">
                        <?php echo isset($data_detail['result'][$data->id][$search_year_start+$i]) && $data_detail['result'][$data->id][$search_year_start+$i] != '' ?number_format($data_detail['result'][$data->id][$search_year_start+$i],2):''; ?>
                      </span>
                    </td>
                    <?php
                  }else{
                    ?>
                    <td class="text-center">
                      <span id="target_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
                        <?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>
                      </span>
                    </td>

                    <td class="text-center">
                      <span id="score_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
                        <?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <span id="result_<?php echo "{$data->id}_".$i ; ?>" class="">
                        <?php echo isset($data_detail['result'][$data->id][$search_year_start+$i]) && $data_detail['result'][$data->id][$search_year_start+$i] != ''?number_format($data_detail['result'][$data->id][$search_year_start+$i],2):''; ?>
                      </span>
                    </td>
                    <?php
                  }
                }
                ?>
                <td class="text_center">
                  <span id="result_all_<?php echo $data->id; ?>"><?php echo $result_all ?></span>
                </td>
              </tr>
              <?php
            }
          }
          ?>
        </table>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
