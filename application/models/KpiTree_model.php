<?php

class KpiTree_model extends CI_Model
{
	// private $default_user_type_id = 5;
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
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
		$this->db->set('tree_name',$data['tree_name']);
		$this->db->set('unit_id',$data['unit_id']);
		$this->db->set('tree_type',$data['tree_type']);
		$this->db->set('tree_weight',$data['tree_weight']);
		$this->db->set('tree_target',$data['tree_target']);

		if(isset($data['short_name'])){
			$this->db->set('short_name',$data['short_name']);
		}
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('tree');
		return $this->db->insert_id();
	}

	public function updateKpiTree($data_id = null, $data = array())
	{
		$this->db->set('tree_parent',$data['tree_parent']);
		$this->db->set('kpi_id',$data['kpi_id']);
		$this->db->set('structure_id',$data['structure_id']);
		$this->db->set('tree_number',$data['tree_number']);
		$this->db->set('tree_name',$data['tree_name']);
		$this->db->set('unit_id',$data['unit_id']);
		$this->db->set('tree_type',$data['tree_type']);
		$this->db->set('tree_weight',$data['tree_weight']);
		$this->db->set('tree_target',$data['tree_target']);
		$this->db->set('short_name',$data['short_name']);
		$this->db->where('tree_id', $data_id);
		$this->db->update('tree');
		return $data_id;
	}

	public function deleteKpiTree($data_id = null)
	{
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
			// if(count($get)>0){
				foreach( $data as $op ){
					if($op->tree_type=='1'){
						$name = $op->tree_name;
					}else{
						$name = $this->db->query("SELECT * FROM kpi_data WHERE kpi_id='$op->kpi_id'")->row()->kpi_name;

						// $kpi->get_var("SELECT kpi_name FROM kpi_data WHERE kpi_id='$op->kpi_id' ");

					}
					$html .= '<option value="'.$op->tree_id.'" >'.$level.$name.'</option>';
					$html .= $this->loopTreeSelect($op->tree_id,$structure_id,$lock,$level,'');

				}
		return $html;
			// }
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
				$name = 'ss';//$kpi->get_var("SELECT kpi_name FROM kpi_data WHERE kpi_id='$op->kpi_id' ");

			}
		  $html .= '<option value="'.$op->tree_id.'" >'.$name.'</option>';
			$html .= $this->loopTreeSelect($op->tree_id,$structure_id,'','','');

		}

			return $html;
	}


}