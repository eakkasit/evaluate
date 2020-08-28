<?php

class KpiTree_model extends CI_Model
{
	// private $default_user_type_id = 5;
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
			 $this->db2 = $this->load->database('db_activity', TRUE);
	 }

	public function countKpiTree($cond = array())
	{
		$this->db->select('*');
		$this->db->from('tree');
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if (is_string($k)) {
					if (is_array($v)) {
						$this->db->where_in($k, $v);
					} else {
						$this->db->where($k, $v);
					}
				} else {
					$this->db->where($v);
				}
			}
		}
		return $this->db->get()->num_rows();
	}

	public function getKpiTree($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('tree');
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if (is_string($k)) {
					if (is_array($v)) {
						$this->db->where_in($k, $v);
					} else {
						$this->db->where($k, $v);
					}
				} else {
					$this->db->where($v);
				}
			}
		}
		if (!empty($order)) {
			foreach ($order as $k => $v) {
				if (is_string($k)) {
					$this->db->order_by($k, $v);
				} else {
					$this->db->order_by($v);
				}
			}
		} else {//default order
			$this->db->order_by('create_date', 'desc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function getKpiTreeLists()
	{
		$list = array();
		$tree_list = $this->db->select('*')->from('tree')->order_by('tree_id')->get()->result();
		if(!empty($tree_list)){
			foreach($tree_list as $val){
				$list[$val->tree_id] = $val->tree_value;
			}
		}
		return $list;
	}

	public function insertKpiTree($data = array())
	{
		$this->db->set('tree_parent',$data['tree_parent']);
		if(isset($data['kpi_id'])){
			$this->db->set('kpi_id',$data['kpi_id']);
		}

		if(isset($data['structure_id'])){
			$this->db->set('structure_id',$data['structure_id']);
		}

		$this->db->set('tree_number',$data['tree_number']);

		if(isset($data['tree_name'])){
			$this->db->set('tree_name',$data['tree_name']);
		}

		$this->db->set('tree_type',$data['tree_type']);

		if(isset($data['unit_id'])){
			$this->db->set('tree_type',$data['tree_type']);
		}

		if(isset($data['tree_weight'])){
			$this->db->set('tree_weight',$data['tree_weight']);
		}

		if(isset($data['tree_target'])){
			$this->db->set('tree_target',$data['tree_target']);
		}

		if(isset($data['short_name'])){
			$this->db->set('short_name',$data['short_name']);
		}
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('tree');
		$id = $this->db->insert_id();
		$this->treeweightsum($id);
		return $id;
	}

	public function updateKpiTree($data_id = null, $data = array())
	{
		$this->db->set('tree_parent',$data['tree_parent']);
		$this->db->set('kpi_id',$data['kpi_id']);
		$this->db->set('structure_id',$data['structure_id']);
		$this->db->set('tree_number',$data['tree_number']);
		$this->db->set('tree_type',$data['tree_type']);
		if(isset($data['tree_name'])){
			$this->db->set('tree_name',$data['tree_name']);
		}
		if(isset($data['unit_id'])){
			$this->db->set('unit_id',$data['unit_id']);
		}
		if(isset($data['unit_id'])){
			$this->db->set('tree_type',$data['tree_type']);
		}

		if(isset($data['tree_weight'])){
			$this->db->set('tree_weight',$data['tree_weight']);
		}

		if(isset($data['tree_target'])){
			$this->db->set('tree_target',$data['tree_target']);
		}

		if(isset($data['short_name'])){
			$this->db->set('short_name',$data['short_name']);
		}
		$this->db->where('tree_id', $data_id);
		$this->db->update('tree');
		$this->treeweightsum($data_id);
		return $data_id;
	}

	public function deleteKpiTree($data_id = null)
	{

		$checkparent = $this->db->query("SELECT tree_parent FROM kpi_tree WHERE tree_id='$data_id' ")->row()->tree_parent;
		if($checkparent != '0'){
			$this->treeweightsumDel($data_id);
		}
		$this->db->where('tree_id', $data_id);
		$this->db->delete('tree');

		return $data_id;
	}

	public function loopTreeSelect($tree_id,$structure_id,$lock,$level,$html){
		$level = $level.'---';

		$this->db->select('*');
		$this->db->where('structure_id',$structure_id);
		$this->db->where('tree_parent',$tree_id);
		$this->db->from('tree');
		$data = $this->db->get()->result();
			if(count($data)>0){
				foreach( $data as $op ){
					if($op->tree_type=='1'){
						$name = $op->tree_name;
					}else{
						$name = $this->db->query("SELECT * FROM kpi_data WHERE kpi_id='$op->kpi_id'")->row()->kpi_name;
					}
					$disable = "";
					if(($op->tree_type!='1')){
						$disable = 'disabled';
					}
					$html .= '<option value="'.$op->tree_id.'" '.$disable.'>'.$level.$name.'</option>';
					$html .= $this->loopTreeSelect($op->tree_id,$structure_id,$lock,$level,'');

				}

			}
			return $html;
	}


	public function getTree($structure_id,$tree_id,$html)
	{
		$this->db->select('*');
		$this->db->where('structure_id',$structure_id);
		$this->db->where('tree_parent',$tree_id);
		$this->db->from('tree');
		$data = $this->db->get()->result();
		foreach( $data as $op ){
			if($op->tree_type=='1'){
				$name = $op->tree_name;
			}else{
				$name = $this->db->query("SELECT * FROM kpi_data WHERE kpi_id='$op->kpi_id'")->row()->kpi_name;

			}
		  $html .= '<option value="'.$op->tree_id.'" >'.$name.'</option>';
			$html .= $this->loopTreeSelect($op->tree_id,$structure_id,'','','');

		}

		return $html;
	}

	public function loopTreeListSub($tree_id,$structure_id,$html){
		$this->db->select('*');
		$this->db->where('structure_id',$structure_id);
		$this->db->where('tree_parent',$tree_id);
		$this->db->from('tree');
		$this->db->order_by('tree_number','ASC');
		$data = $this->db->get()->result();
			if(count($data)>0){
				$html .= '<ol class="dd-list">';
				foreach( $data as $key => $value ){
					if($value->tree_type=='1'){
						$name = $value->tree_name;
					}else{
						$name = $this->db->query("SELECT * FROM kpi_data WHERE kpi_id='$value->kpi_id'")->row()->kpi_name;

					}
					$html .= '<li class="dd-item" data-id="'.($key+1).'">';
					$html .= '	<div class="dd-handle">';
					$html .= $value->tree_number.' '.$name;
					$html .= '		<div class="pull-right action-buttons">';
					$html .= '			<a class="light-blue" href="#" onclick="showData(\''.$structure_id.'\',\''.$value->tree_id.'\')">';
					$html .= '				<i class="ace-icon fa fa-eye bigger-130"></i>';
					$html .= '			</a>';
					$html .= '			<a class="blue" href="#" onclick="editData(\''.$structure_id.'\',\''.$value->tree_id.'\')">';
					$html .= '				<i class="ace-icon fa fa-pencil bigger-130"></i>';
					$html .= '			</a>';
					$html .= '			<a class="red" href="#" onclick="deleteData(\''.$structure_id.'\',\''.$value->tree_id.'\')">';
					$html .= '				<i class="ace-icon fa fa-trash-o bigger-130"></i>';
					$html .= '			</a>';
					$html .= '		</div>';
					$html .= '	</div>';
					$html .= '</li>';
					$html .= $this->loopTreeListSub($value->tree_id,$structure_id,'');

				}
				$html .= '</ol>';
		}
		return $html;
			// }
	}


	public function getTreeList($structure_id,$tree_id,$html)
	{
		$this->db->select('*');
		$this->db->where('structure_id',$structure_id);
		$this->db->where('tree_parent',$tree_id);
		$this->db->from('tree');
		$this->db->order_by('tree_number','ASC');
		$data = $this->db->get()->result();

		$html .= '<div class="dd" id="nestable"><ol class="dd-list">';
		foreach( $data as $key => $value ){
			if($value->tree_type=='1'){
				$name = $value->tree_name;
			}else{
				$name = $this->db->query("SELECT * FROM kpi_data WHERE kpi_id='$value->kpi_id'")->row()->kpi_name;

			}
			$html .= '<li class="dd-item" data-id="'.($key+1).'">';
			$html .= '	<div class="dd-handle">';
			$html .= $value->tree_number.' '.$name;
			$html .= '		<div class="pull-right action-buttons">';
			$html .= '			<a class="light-blue" href="#" onclick="showData(\''.$structure_id.'\',\''.$value->tree_id.'\')">';
			$html .= '				<i class="ace-icon fa fa-eye bigger-130"></i>';
			$html .= '			</a>';
			$html .= '			<a class="blue" href="#" onclick="editData(\''.$structure_id.'\',\''.$value->tree_id.'\')">';
			$html .= '				<i class="ace-icon fa fa-pencil bigger-130"></i>';
			$html .= '			</a>';
			$html .= '			<a class="red" href="#" onclick="deleteData(\''.$structure_id.'\',\''.$value->tree_id.'\')">';
			$html .= '				<i class="ace-icon fa fa-trash-o bigger-130"></i>';
			$html .= '			</a>';
			$html .= '		</div>';
			$html .= '	</div>';
			$html .= '</li>';
			$html .= $this->loopTreeListSub($value->tree_id,$structure_id,'');

		}
		$html .= '</ol></div>';

		return $html;
	}

	public function treeweightsum($tree_id){
		$tree_parent = $this->db->query("SELECT tree_parent FROM kpi_tree WHERE tree_id='$tree_id' ")->row()->tree_parent;
    $tree_parent_weight = 0;
		$chk_tree = $this->db->query("SELECT * FROM kpi_tree WHERE tree_parent='$tree_parent' ")->result();

		if(count($chk_tree)>0){
			foreach( $chk_tree as $key => $chk ){
				$tree_parent_weight = $tree_parent_weight+$chk->tree_weight;
			}
		}
		$this->db->set('tree_weight',$tree_parent_weight);
		$this->db->where('tree_id', $tree_parent);
		$this->db->update('tree');

		if($tree_parent!='0'){
			$this->treeweightsum($tree_parent);
		}
	}

	function treeweightsumDel($tree_id){
		$tree_parent = $this->db->query("SELECT tree_parent FROM kpi_tree WHERE tree_id='$tree_id' ")->row()->tree_parent;
    $tree_parent_weight = 0;
		$chk_tree = $this->db->query("SELECT * FROM kpi_tree WHERE tree_parent='$tree_id' ")->result();
		if(count($chk_tree)>0){
			foreach( $chk_tree as $key => $chk ){
				$tree_parent_weight = $tree_parent_weight+$chk->tree_weight;
			}
		}
		$this->db->set('tree_weight',$tree_parent_weight);
		$this->db->where('tree_id', $tree_id);
		$this->db->update('tree');

		if($tree_parent!='0'){
			$this->treeweightsumDel($tree_parent);
		}
	}



	public function loopTreeFormListSub($tree_id,$structure_id,$html){
		$this->db->select('*');
		$this->db->where('structure_id',$structure_id);
		$this->db->where('tree_parent',$tree_id);
		$this->db->from('tree');
		$data = $this->db->get()->result();
			if(count($data)>0){
				$html .= '<ol class="dd-list">';
				foreach( $data as $key => $value ){
					$html .= '<li class="dd-item">';
					if($value->tree_type=='1'){
						$name = $value->tree_name;
						$html .= '<div class="row">';
						$html .= '	<label class="col-md-2">หมวด</label>';
						$html .= '	<div class="col-md-4">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][criteria_id]" value="">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][criteria_parent_id]" value="'.$value->tree_parent.'">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][project_id]" value="">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][result]" value="">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][percent]" value="">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][weight]" value="">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][project_id]" value="">';
						$html .= '		<input type="text" name="criteria_data['.$key.'][criteria_name]" title="หมวดเกณฑ์การประเมิน" alt="หมวดเกณฑ์การประเมิน" class="form-control"  value="'.$value->tree_number.' '.$name.'">';
						$html .= '	</div>';
						$html .= '</div>';
					}else{
						$kpi = $this->db->query("SELECT * FROM kpi_data WHERE kpi_id='$value->kpi_id'")->row();
						$fomular_value =  $this->db->query("SELECT * FROM kpi_formula_data WHERE structure_id='$structure_id' AND kpi_id='$value->kpi_id'")->row();
						$result = '';
						$target = '';
						if(isset($fomular_value)){
							$target = $fomular_value->grade_map;
							$result = $fomular_value->formula_value;
						}
						// $number = rand(1,5);
						// $percent = $number*10;
						$html .= '<div class="row">';
						$html .= '	<label class="col-md-2">เกณฑ์การประเมิน</label>';
						$html .= '	<div class="col-md-3">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][criteria_id]" value="">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][criteria_parent_id]" value="">';
						$html .= '		<input type="hidden" name="criteria_data['.$key.'][total]" value="">';
						$html .= '		<input type="text" class="form-control" name="criteria_data['.$key.'][criteria_name]" title="เกณฑ์การประเมิน" alt="เกณฑ์การประเมิน" value="'.$value->tree_number.' '.$kpi->kpi_name.'" >';
						$html .= '		</div>';
						$html .= '	<div class="col-md-3">';
						// $html .= '		<select class="form-control" title="โครงการ / กิจจกรรม" name="criteria_data['.$key.'][project_id]" >';
						// $html .= '		</select>';
						$html .= '<a href="#" onClick="show_variable(\''.$value->kpi_id.'\',\''.$value->tree_id.'\',\''.$kpi->kpi_standard_type.'\')" class="btn btn-success">บันทึกค่าตัวแปร</a>';
						$html .= '	</div>';
						$html .= '	<div class="form-group col-md-2">';
						$html .= '		<div class="input-group ">';
						$html .= '			<input type="text" class="form-control mini-box target_value_'.$value->kpi_id.'" title="ผลลัพธ์" alt="ผลลัพธ์" name="criteria_data['.$value->kpi_id.'][result]" value="'.$target.'" >';
						$html .= '			<input type="text" class="form-control mini-box result_value_'.$value->kpi_id.' percent_'.$value->tree_parent.'" title="เปอร์เซนต์ผลลัพธ์ของโครงการที่ได้" alt="เปอร์เซนต์ผลลัพธ์ของโครงการที่ได้" name="criteria_data['.$value->kpi_id.'][percent]" value="'.$result.'" >';
						$html .= '			<input type="text" class="form-control mini-box" title="ค่าน้ำหนัก" alt="ค่าน้ำหนัก" name="criteria_data['.$value->kpi_id.'][weight]" value="'.$value->tree_weight.'" >';
						$html .= '		</div>';
						$html .= '	</div>';
						$html .= '	<div class="col-md-2">';
						$html .= '		<div class="pull-right action-buttons">';
						$html .= '			<a class="orange" href="#" title="เอกสารแนบ"  onclick="addtData(\'\')">';
						$html .= '				<i class="ace-icon fa fa-paperclip bigger-130"></i>';
						$html .= '			</a>';
						// $html .= '			<a class="green" href="#" title="เพิ่มโครงการ" onclick="insert_row(\'\')">';
						// $html .= '				<i class="ace-icon fa fa-plus bigger-130"></i>';
						// $html .= '			</a>';
						$html .= '		</div>';
						$html .= '	</div>';
						$html .= '</div>';

						// $this->db->select('*');
						// $this->db->where('kpi_id',$value->kpi_id);
						// $this->db->from('formula');
						// $this->db->join('variable','formula.var_id = variable.var_id','inner');
						// $fomular = $this->db->get()->result();
						// if(count($data)>0){
						// 	$html .= '<ol class="dd-list">';
						// 	$html .= '<li class="dd-item">ป้อนค่าสูตร</li>';
						// 	foreach ($fomular as $key_fomular => $value_fomular) {
						// 		$html .= '<li class="dd-item">';
						// 		$html .= '<div class="row">';
						// 		// $html .= '	<label class="col-md-2"></label>';
						// 		// $html .= '	<div class="col-md-3">';
						// 		// $html .= '		<input type="hidden" name="criteria_data['.$key_fomular.'][criteria_id]" value="">';
						// 		// $html .= '		<input type="hidden" name="criteria_data['.$key_fomular.'][criteria_parent_id]" value="">';
						// 		// $html .= '		<input type="hidden" name="criteria_data['.$key_fomular.'][total]" value="">';
						// 		// $html .= '		<input type="text" class="form-control" name="criteria_data['.$key_fomular.'][criteria_name]" title="เกณฑ์การประเมิน" alt="เกณฑ์การประเมิน" value=" '.$value_fomular->var_name.'" >';
						// 		// $html .= '		</div>';
						// 		// $html .= $this->getForm($value_fomular);
						// 		// $html .= '	<div class="col-md-3">';
						// 		// $html .= '		<select class="form-control" title="โครงการ / กิจจกรรม" name="criteria_data['.$key_fomular.'][project_id]" >';
						// 		// $html .= '		</select>';
						// 		// $html .= '	</div>';
						// 		// $html .= '	<div class="form-group col-md-2">';
						// 		// $html .= '		<div class="input-group ">';
						// 		// $html .= '			<input type="text" class="form-control mini-box" title="ผลลัพธ์" alt="ผลลัพธ์" name="criteria_data['.$key_fomular.'][result]" value="" >';
						// 		// $html .= '			<input type="text" class="form-control mini-box" title="เปอร์เซนต์ผลลัพธ์ของโครงการที่ได้" alt="เปอร์เซนต์ผลลัพธ์ของโครงการที่ได้" name="criteria_data['.$key_fomular.'][percent]" value="" >';
						// 		// $html .= '			<input type="text" class="form-control mini-box" title="ค่าน้ำหนัก" alt="ค่าน้ำหนัก" name="criteria_data['.$key_fomular.'][weight]" value="" readonly>';
						// 		// $html .= '		</div>';
						// 		// $html .= '	</div>';
						// 		// $html .= '	<div class="col-md-2">';
						// 		// $html .= '		<div class="pull-right action-buttons">';
						// 		// $html .= '			<a class="orange" href="#" title="เอกสารแนบ"  onclick="addtData(\'\')">';
						// 		// $html .= '				<i class="ace-icon fa fa-paperclip bigger-130"></i>';
						// 		// $html .= '			</a>';
						// 		// $html .= '			<a class="green" href="#" title="เพิ่มโครงการ" onclick="insert_row(\'\')">';
						// 		// $html .= '				<i class="ace-icon fa fa-plus bigger-130"></i>';
						// 		// $html .= '			</a>';
						// 		// $html .= '		</div>';
						// 		// $html .= '	</div>';
						// 		$html .= '</div>';
						// 		$html .= '</li>';
						// 	}
						// 		$html .= '</ol>';
						//
						// }

					}
					$html .= '</li>';
					$html .= $this->loopTreeFormListSub($value->tree_id,$structure_id,'');
					if($value->tree_type=='1'){
						$html .= '<li class="dd-item">';
						$html .= '<div class="row">';
						$html .= '	<label class="col-md-4 text-right">คะแนนเฉลี่ยรายหมวด</label>';
						$html .= '	<div class="col-md-4">';
						$html .= '		<input type="text" name="criteria_data['.$key.'][criteria_point]" data-percent="'.$value->tree_id.'" title="คะแนนเฉลี่ยรายหมวด" alt="คะแนนเฉลี่ยรายหมวด" class="form-control percent_total_'.$value->tree_id.'"  value="">';
						$html .= '	</div>';
						$html .= '</div>';
						$html .= '</li>';
					}

				}
				$html .= '</ol>';
		}
		return $html;
			// }
	}

	private function getForm($data){
		$input_data = '';
		$where_var = '';
		$var_data = $this->db->query("SELECT * FROM kpi_var_data WHERE kpi_id='$data->kpi_id' AND var_id='$data->var_id' $where_var  ")->row();
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
          $input_data .= '<div class="col-md-1"></div></div>';
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
              $var_value = $kpi->get_results($data->var_sql,ARRAY_N);

              if(count($var_value)>0){
                  foreach( $var_value as $keys => $vals ){
                      $var_data_value .= '<option value="'.$var_value[$keys][0].'"';
                      if($var_data->var_data==$var_value[$keys][0]){
                          $var_data_value .= 'selected';
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
          $input_data .= '<div class="col-md-12 ">';
          $input_data .= '    <div class="col-md-9">';
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
          $input_data .= '    </div>';
          $input_data .= '</div>';
          $dp_value = $ck_val;
      }

			$input_data .= "<script>";
			if($dp_value) {
					$input_data .= "dependvar['" . $data->formula_value . "']=1;";
			}else{
					$input_data .= "dependvar['" . $data->formula_value . "']=0;";
			}
			$input_data .= "dependcond['"  . $data->formula_value . "']='".$data->depend."';";
			$input_data .= "</script>";
			return $input_data;
	}


	public function getTreeFormList($structure_id,$tree_id,$html)
	{
		$this->db->select('*');
		$this->db->where('structure_id',$structure_id);
		$this->db->where('tree_parent',$tree_id);
		$this->db->from('tree');
		$data = $this->db->get()->result();

		$html .= '<div class="criteria_data_list"><ol class="dd-list">';
		foreach( $data as $key => $value ){
			if($value->tree_type=='1'){
				$name = $value->tree_name;
			}else{
				$name = $this->db->query("SELECT * FROM kpi_data WHERE kpi_id='$value->kpi_id'")->row()->kpi_name;

			}
			$html .= '<li class="dd-item">';
			$html .= '<div class="row">';
			$html .= '	<label class="col-md-2">หมวด</label>';
			$html .= '	<div class="col-md-4">';
			$html .= '		<input type="hidden" name="criteria_data['.$key.'][criteria_id]" value="">';
			$html .= '		<input type="hidden" name="criteria_data['.$key.'][criteria_parent_id]" value="'.$value->tree_parent.'">';
			$html .= '		<input type="hidden" name="criteria_data['.$key.'][project_id]" value="">';
			$html .= '		<input type="hidden" name="criteria_data['.$key.'][result]" value="">';
			$html .= '		<input type="hidden" name="criteria_data['.$key.'][percent]" value="">';
			$html .= '		<input type="hidden" name="criteria_data['.$key.'][weight]" value="">';
			$html .= '		<input type="hidden" name="criteria_data['.$key.'][project_id]" value="">';
			$html .= '		<input type="text" name="criteria_data['.$key.'][criteria_name]" title="หมวดเกณฑ์การประเมิน" alt="หมวดเกณฑ์การประเมิน" class="form-control"  value="'.$value->tree_number.' '.$name.'">';
			$html .= '	</div>';
			$html .= '</div>';
			$html .= '</li>';
			$html .= $this->loopTreeFormListSub($value->tree_id,$structure_id,'');
			// $html .= '<li class="dd-item">';
			// $html .= '<div class="row">';
			// $html .= '	<label class="col-md-4 text-right">คะแนนเฉลี่ยรายหมวด</label>';
			// $html .= '	<div class="col-md-4">';
			// $html .= '		<input type="text" name="criteria_data['.$key.'][criteria_point]" title="คะแนนเฉลี่ยรายหมวด" alt="คะแนนเฉลี่ยรายหมวด" class="form-control"  value="">';
			// $html .= '	</div>';
			// $html .= '</div>';
			// $html .= '</li>';

		}
		$html .= '</ol></div>';

		return $html;
	}

}
