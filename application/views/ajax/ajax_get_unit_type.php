<div class="col-md-12">
    	<?php
			if($kpi_standard_type=='1'){
		?>
      <table class="table">
        <tr>
           <th width="100" class="text-center">ระดับ</th>
              <th width="100" class="text-center"><b>ค่าคะแนนถ่วงน้ำหนัก</b></th>
              <th width="" class="text-center"><b>คำอธิบาย</b></th>
        </tr>

        <tr>
            <td align="center">1</td>

            <td align="center"><input type="number"  step="any" class="form-control" name="kpi_standard_label1" id="kpi_standard_label1" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_label1; }?>" style="width:100px"></td>
          <td><input type="text" class="form-control" name="kpi_standard_1" id="kpi_standard_1" value="<?php if(isset($data->kpi_standard_1)){ echo $data->kpi_standard_1; }?>"></td>
        </tr>
        <tr>
            <td align="center">2</td>
            <td align="center"><input type="number"  step="any" class="form-control" name="kpi_standard_label2" id="kpi_standard_label2" value="<?php if(isset($data->kpi_standard_label2)){ echo $data->kpi_standard_label2; }?>" style="width:100px"></td>
          <td><input type="text" class="form-control" name="kpi_standard_2" id="kpi_standard_2" value="<?php if(isset($data->kpi_standard_2)){ echo $data->kpi_standard_2; }?>"></td>

        </tr>
        <tr>
            <td align="center">3</td>
            <td align="center"><input type="number"  step="any" class="form-control" name="kpi_standard_label3" id="kpi_standard_label3" value="<?php if(isset($data->kpi_standard_label3)){ echo $data->kpi_standard_label3; } ?>" style="width:100px"></td>
          <td><input type="text" class="form-control" name="kpi_standard_3" id="kpi_standard_3" value="<?php if(isset($data->kpi_standard_3)){ echo $data->kpi_standard_3; }?>"></td>

        </tr>
        <tr>
            <td align="center">4</td>
            <td align="center"><input type="number"  step="any" class="form-control" name="kpi_standard_label4" id="kpi_standard_label4" value="<?php if(isset($data->kpi_standard_label4)){ echo $data->kpi_standard_label4; }?>" style="width:100px"></td>
          <td><input type="text" class="form-control" name="kpi_standard_4" id="kpi_standard_4" value="<?php if(isset($data->kpi_standard_4)){ echo $data->kpi_standard_4; }?>"></td>

        </tr>
        <tr>
            <td align="center">5</td>
            <td align="center"><input type="number" step="any" class="form-control" name="kpi_standard_label5" id="kpi_standard_label5" value="<?php if(isset($data->kpi_standard_label5)){ echo $data->kpi_standard_label5; }?>" style="width:100px"></td>
          <td><input type="text" class="form-control" name="kpi_standard_5" id="kpi_standard_5" value="<?php if(isset($data->kpi_standard_5)){ echo $data->kpi_standard_5; }?>"></td>

        </tr>

        </table>
        <?
			}else{
		?>
        <table class="table">
        <tr>
          <th width="25%" class="text-center">ระดับ</th>
          <th class="text-center">1</th>
          <th class="text-center">2</th>
          <th class="text-center">3</th>
          <th class="text-center">4</th>
          <th class="text-center"><b>5</b></th>
        </tr>

        <tr>
          <td align="center"><strong>ค่าคะแนนถ่วงน้ำหนัก</strong></td>
          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label1" id="kpi_standard_label1" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_label1; }?>"></td>
          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label2" id="kpi_standard_label2" value="<?php if(isset($data->kpi_standard_label2)){ echo $data->kpi_standard_label2; }?>"></td>
          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label3" id="kpi_standard_label3" value="<?php if(isset($data->kpi_standard_label3)){ echo $data->kpi_standard_label3; }?>"></td>
          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label4" id="kpi_standard_label4" value="<?php if(isset($data->kpi_standard_label4)){ echo $data->kpi_standard_label4; }?>"></td>
          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label5" id="kpi_standard_label5" value="<?php if(isset($data->kpi_standard_label5)){ echo $data->kpi_standard_label5; }?>"></td>
        </tr>
        <tr>
            <td align="center"><strong>เกณฑ์การให้คะแนน</strong></td>
            <td><input type="number" step="any" class="form-control" name="kpi_standard_1" id="kpi_standard_1" value="<?php if(isset($data->kpi_standard_1)){ echo $data->kpi_standard_1; }?>"></td>
            <td><input type="number" step="any" class="form-control" name="kpi_standard_2" id="kpi_standard_2" value="<?php if(isset($data->kpi_standard_2)){ echo $data->kpi_standard_2; }?>"></td>
            <td><input type="number" step="any" class="form-control" name="kpi_standard_3" id="kpi_standard_3" value="<?php if(isset($data->kpi_standard_3)){ echo $data->kpi_standard_3; }?>"></td>
            <td><input type="number" step="any" class="form-control" name="kpi_standard_4" id="kpi_standard_4" value="<?php if(isset($data->kpi_standard_4)){ echo $data->kpi_standard_4; }?>"></td>
            <td><input type="number" step="any" class="form-control" name="kpi_standard_5" id="kpi_standard_5" value="<?php if(isset($data->kpi_standard_5)){ echo $data->kpi_standard_5; }?>"></td>
        </tr>

        </table>
        <?
			}
		?>
        <br>
  </div>
