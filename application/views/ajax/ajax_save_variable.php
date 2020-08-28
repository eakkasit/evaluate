<?php
if(isset($fomular)){
  foreach ($fomular as $key => $data) {
    $input_data = '';
    $input_data .= '<div class="row">';


// $input_data = '';
$where_var = '';
$var_data_list = $kpi->getVarData($data->kpi_id,$data->var_id);
if(isset($var_data_list[0])){
  $var_data = $var_data_list[0];
}
// echo "<pre>";
// print_r($var_data);
// die();
if($data->var_type_id=='1'){ //Number
      if(!isset($var_data->var_data)){
          if($data->var_sql!=''){
              $var_value = $kpi->get_var($data->var_sql);
          }else if($data->var_value!=''){
              $var_value = $data->var_value;
          }
      }else{
          $var_value = $var_data->var_data;
      }

      $readonly ='';
      if($data->var_import_id!='1'){
          $readonly = 'readonly';
      }
      $input_data .= '<div class="col-md-12 ">';
      $input_data .= '<div class="col-md-9"><div class="col-md-8">'.$data->var_name.'</div>';
      $input_data .= '<div class="col-md-3"> <input onchange="depend(this,\''.$data->formula_value.'\')" type="number" name="VAR['.$data->kpi_id.']['.$data->var_id.']" value="'.$var_value.'"  class="form-control depend_'.$data->formula_value.'" required '.$readonly.'> </div>';
      $input_data .= '<div class="col-md-1"></div></div></div></div>';
      $dp_value = $var_value;
  }
  if($data->var_type_id=='2'){ //Float
    if(!isset($var_data->var_data)){
      if($data->var_sql!=''){
          $var_value = $kpi->get_var($data->var_sql);
      }else if($data->var_value!=''){
          $var_value = $data->var_value;
      }
    }else{
      $var_value = $var_data->var_data;
    }
    $readonly ='';
    if($data->var_import_id!='1'){
      $readonly = 'readonly';
    }
    $input_data .= '<div class="col-md-12 ">';
    $input_data .= '<div class="col-md-9"><div class="col-md-8">'.$data->var_name.'</div>';
    $input_data .= '<div class="col-md-3"> <input onchange="depend(this,\''.$data->formula_value.'\')" type="number" name="VAR['.$data->kpi_id.']['.$data->var_id.']" value="'.$var_value.'"    class="form-control depend_'.$data->formula_value.'" step="any" '.$readonly.'> </div>';
    $input_data .= '<div class="col-md-1"></div></div>';
    $input_data .= '</div>';
    $dp_value = $var_value;
  }

  if($data->var_type_id=='3'){ //Text
      if(!isset($var_data->var_data)){
          if($data->var_sql!=''){
              $var_value = $kpi->get_var($data->var_sql);
          }else if($data->var_value!=''){
              $var_value = $data->var_value;
          }
      }else{
          $var_value = $var_data->var_data;
      }
      $readonly ='';
      if($data->var_import_id!='1'){
          $readonly = 'readonly';
      }
      $input_data .= '<div class="col-md-12 ">';
      $input_data .= '<div class="col-md-9"><div class="col-md-7">'.$data->var_name.'</div>';
      $input_data .= '<div class="col-md-4"> <input type="text" onchange="depend(this,\''.$data->formula_value.'\')" name="VAR['.$data->kpi_id.']['.$data->var_id.']"  class="form-control depend_'.$data->formula_value.'" value="'.$var_value.'" onblur="checkLength(this,'.$data->var_max_length.')"  '.$readonly.'> </div>';
      $input_data .= '<div class="col-md-1"></div></div>';
      $input_data .= '</div>';
      $dp_value = $var_value;
  }

  if($data->var_type_id=='4'){ //Radio
      $var_data_value = '';
      $readonly ='';
      if($data->var_import_id!='1'){
          $readonly = 'readonly';
      }
      if($data->var_sql!=''){
          $var_value = $kpi->get_results($data->var_sql,ARRAY_N);

          if(count($var_value)>0){
              foreach( $var_value as $keys => $vals ){
                  $var_data_value .= '<label><input type="radio" onchange="depend(this,\''.$data->formula_value.'\')" class="depend_'.$data->formula_value.'" name="VAR['.$data->kpi_id.']['.$data->var_id.']" value="'.$var_value[$keys][0].'" ';
                  if($var_data->var_data==$var_value[$keys][0]){
                      $var_data_value .= 'checked';
                  }
                  //echo $readonly;
                  $var_data_value .= '> '.$var_value[$keys][1].'</label><br> ';
              }
          }

      }else if($data->var_value!=''){
          eval("\$var_value = array".$data->var_value.";");

          if(count($var_value)>0){
              foreach( $var_value as $keys => $vals ){
                  $var_data_value .= '<label><input type="radio" onchange="depend(this,\''.$data->formula_value.'\')" class="depend_'.$data->formula_value.'" name="VAR['.$data->kpi_id.']['.$data->var_id.']" value="'.$keys.'"';
                  if(isset($var_data->var_data)){
                    if($var_data->var_data==$keys){
                        $var_data_value .= 'checked';
                    }
                  }

                  //echo $readonly;
                  $var_data_value .= '> '.$vals.'</label><br> ';
              }
          }
      }
      $input_data .= '<div class="col-md-12 ">';
      $input_data .= '<div class="col-md-9"><div class="col-md-5">'.$data->var_name.'</div>';
      $input_data .= '<div class="col-md-5"> ';
      $input_data .= $var_data_value;
      $input_data .= '</div>';
      $input_data .= '<div class="col-md-2"> ';
      $input_data .= '</div>';
      $input_data .= '</div>';
      $input_data .= '</div>';
      $dp_value = isset($var_data->var_data)?$var_data->var_data:'';
  }

  if($data->var_type_id=='5'){ //Select
      $var_data_value = '';
      $readonly ='';
      if($data->var_import_id!='1'){
          $readonly = 'readonly';
      }
      if($data->var_sql!=''){
          $var_value = $kpi->queryData($data->var_sql);

          if(count($var_value)>0){
              foreach( $var_value as $keys => $vals ){
                  $var_data_value .= '<option value="'.$var_value[$keys][0].'"';
                  if(isset($var_data->var_data)){
                    if($var_data->var_data==$var_value[$keys][0]){
                        $var_data_value .= 'selected';
                    }
                  }
                  //echo $readonly;
                  $var_data_value .= '>  '.$var_value[$keys][1].'</option> ';
              }
          }

      }else if($data->var_value!=''){
          eval("\$var_value = array".$data->var_value.";");

          if(count($var_value)>0){
              foreach( $var_value as $keys => $vals ){
                  $var_data_value .= '<option value="'.$keys.'"';
                  if(isset($var_data->var_data)){
                    if($var_data->var_data==$keys){
                        $var_data_value .= 'selected';
                    }
                  }

                  //echo $readonly;
                  $var_data_value .= '>  '.$vals.'</option> ';
              }
          }
      }
      $input_data .= '<div class="col-md-12 ">';
      $input_data .= '<div class="col-md-9"><div class="col-md-5">'.$data->var_name.'</div>';
      $input_data .= '<div class="col-md-5"> ';
      $input_data .= '<select onchange="depend(this,\''.$data->formula_value.'\')" name="VAR['.$data->kpi_id.']['.$data->var_id.']" class="form-control depend_'.$data->formula_value.'">';
      $input_data .= $var_data_value;
      $input_data .= '</select>';

      $input_data .= '</div>';
      $input_data .= '<div class="col-md-2"> ';
      $input_data .= '</div>';
      $input_data .= '</div>';
      $input_data .= '</div>';
      $dp_value = isset($var_data->var_data)?$var_data->var_data:'';
  }

  if($data->var_type_id=='6'){ //Calenda
      $readonly ='';
      if($data->var_import_id!='1'){
          $readonly = 'readonly';
      }
      if(!isset($var_data->var_data)){
          if($data->var_sql!=''){
              $var_value = $kpi->get_var($data->var_sql);
              if($var_value!='' or $var_value!='0000-00-00'){

                  // $value_calenda = $mn->dateFormat($var_value,'thaifull');
              }
          }else if($data->var_value!='' or $data->var_value!='0000-00-00'){

              $var_value = $data->var_value;
              // $value_calenda = $mn->dateFormat($data->var_value,'thaifull');
          }
      }else{

          // $value_calenda = $mn->dateFormat($var_data->var_data,'thaifull');
          $var_value = $var_data->var_data;
      }
      $value_calenda = '';

      $input_data .= '<div class="col-md-12 ">';
      $input_data .= '<div class="col-md-9"><div class="col-md-5">'.$data->var_name.'</div>';
      $input_data .= '<div class="col-md-5">
<div class="form-group">
<input type="text" onchange="depend(this,\''.$data->formula_value.'\')" name="VAR_'.$data->kpi_id.'_'.$data->var_id.'_Joker" id="VAR_'.$data->kpi_id.'_'.$data->var_id.'_Joker" value="'.$value_calenda.'" class="form-control datethai col-md-8 depend_'.$data->formula_value.'" style="width:90% !important" '.$readonly.'>
<input type="hidden" class="depend_'.$data->formula_value.'" name="VAR['.$data->kpi_id.']['.$data->var_id.']" id="VAR_'.$data->kpi_id.'_'.$data->var_id.'" value="'.$var_value.'">
</div>
</div>';
      $input_data .= '<div class="col-md-2"></div></div>';
      $input_data .= '</div>';
      $dp_value = $var_value;
  }

  if($data->var_type_id=='7'){ //Boolean
      $var_value='';
      if($data->var_sql!=''){
          $var_value = $kpi->get_var($data->var_sql);
      }else if($data->var_value!=''){
          $var_value = $data->var_value;
      }
      $readonly ='';
      if($data->var_import_id!='1'){
          $readonly = 'readonly checked';
      }
      $checked ='';
      if(isset($var_data->var_data) && $var_value==$var_data->var_data ){
        $checked = 'checked';
        $ck_val = $var_value;
      }else{
        $ck_val = 0;
      }
      // $input_data .= '<div class="col-md-12 ">';
      // $input_data .= '    <div class="col-md-9">';
      $input_data .= '        <div class="col-md-1"></div>';
      $input_data .= '        <div class="col-md-9"> ';
      $input_data .= '            <div class="col-md-1">';
      $input_data .= '                <input type="checkbox" onclick="setval(\''.'VAR['.$data->kpi_id.']['.$data->var_id.']'.'\',this,'.$var_value.');depend(this,\''.$data->formula_value.'\');" id="VAR_CTRL['.$data->kpi_id.']['.$data->var_id.']" name="VAR_CTRL['.$data->kpi_id.']['.$data->var_id.']" value="'.$var_value.'" ';
      $input_data .= '                class="depend_'.$data->formula_value.'" '.$checked.' '.$readonly.' >';
      $input_data .= '                <input type="hidden" class="depend_'.$data->formula_value.'" name="VAR['.$data->kpi_id.']['.$data->var_id.']" value="'.$ck_val.'" >';
      $input_data .= '            </div>';
      $input_data .= '            <div class="col-md-11"><label for="VAR_CTRL['.$data->kpi_id.']['.$data->var_id.']">'.$data->var_name.'</label></div>';
      $input_data .= '        </div>';
      $input_data .= '        <div class="col-md-2"> </div>';
      // $input_data .= '    </div>';
      // $input_data .= '</div>';
      $dp_value = $ck_val;
  }
  $input_data .= '</div>';

  $input_data .= "<script>";
  if($dp_value) {
      $input_data .= "dependvar['" . $data->formula_value . "']=1;";
  }else{
      $input_data .= "dependvar['" . $data->formula_value . "']=0;";
  }
  $input_data .= "dependcond['"  . $data->formula_value . "']='".$data->depend."';";
  $input_data .= "</script>";
  echo  $input_data;
}
}
?>
