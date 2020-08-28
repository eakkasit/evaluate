<?php

class Kpi_model extends CI_Model
{
	// private $default_user_type_id = 5;
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
	 }

	public function countKpi($cond = array())
	{
		$this->db->select('*');
		$this->db->from('data');
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

	public function getKpi($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('data');
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

	public function getKpiLists()
	{
		$list = array();
		$kpi_list = $this->db->select('*')->from('data')->order_by('kpi_id')->get()->result();
		if(!empty($kpi_list)){
			foreach($kpi_list as $val){
				$list[$val->kpi_id] = $val->kpi_name;
			}
		}
		return $list;
	}

	public function insertKpi($data = array())
	{
		$this->db->set('kpi_name',$data['kpi_name']);
		$this->db->set('unit_id',$data['unit_id']);
		$this->db->set('level_id',$data['level_id']);
		$this->db->set('kpi_detail',$data['kpi_detail']);
		$this->db->set('kpi_formula',$data['kpi_formula']);
		$this->db->set('kpi_note',$data['kpi_note']);
		$this->db->set('kpi_condition',$data['kpi_condition']);
		$this->db->set('kpi_source',$data['kpi_source']);
		$this->db->set('kpi_standard_type',$data['kpi_standard_type']);
		$this->db->set('kpi_standard_1',$data['kpi_standard_1']);
		$this->db->set('kpi_standard_2',$data['kpi_standard_2']);
		$this->db->set('kpi_standard_3',$data['kpi_standard_3']);
		$this->db->set('kpi_standard_4',$data['kpi_standard_4']);
		$this->db->set('kpi_standard_5',$data['kpi_standard_5']);
		$this->db->set('kpi_standard_label1',$data['kpi_standard_label1']);
		$this->db->set('kpi_standard_label2',$data['kpi_standard_label2']);
		$this->db->set('kpi_standard_label3',$data['kpi_standard_label3']);
		$this->db->set('kpi_standard_label4',$data['kpi_standard_label4']);
		$this->db->set('kpi_standard_label5',$data['kpi_standard_label5']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('data');
		return $this->db->insert_id();
	}

	public function updateKpi($data_id = null, $data = array())
	{
		$this->db->set('kpi_name',$data['kpi_name']);
		$this->db->set('unit_id',$data['unit_id']);
		$this->db->set('level_id',$data['level_id']);
		$this->db->set('kpi_detail',$data['kpi_detail']);
		$this->db->set('kpi_formula',$data['kpi_formula']);
		$this->db->set('kpi_note',$data['kpi_note']);
		$this->db->set('kpi_condition',$data['kpi_condition']);
		$this->db->set('kpi_source',$data['kpi_source']);
		$this->db->set('kpi_standard_type',$data['kpi_standard_type']);
		$this->db->set('kpi_standard_1',$data['kpi_standard_1']);
		$this->db->set('kpi_standard_2',$data['kpi_standard_2']);
		$this->db->set('kpi_standard_3',$data['kpi_standard_3']);
		$this->db->set('kpi_standard_4',$data['kpi_standard_4']);
		$this->db->set('kpi_standard_5',$data['kpi_standard_5']);
		$this->db->set('kpi_standard_label1',$data['kpi_standard_label1']);
		$this->db->set('kpi_standard_label2',$data['kpi_standard_label2']);
		$this->db->set('kpi_standard_label3',$data['kpi_standard_label3']);
		$this->db->set('kpi_standard_label4',$data['kpi_standard_label4']);
		$this->db->set('kpi_standard_label5',$data['kpi_standard_label5']);
		$this->db->where('kpi_id', $data_id);
		$this->db->update('data');
		return $data_id;
	}

	public function deleteKpi($data_id = null)
	{
		$this->db->where('kpi_id', $data_id);
		$this->db->delete('data');
		return $data_id;
	}

	public function saveKpiVardata($data)
	{
		$datecreate = date("Y-m-d H:i:s");
		$this->db->set('var_id',$data['var_id']);
		$this->db->set('var_data',$data['var_data']);
		$this->db->set('kpi_id',$data['kpi_id']);
		$this->db->set('date_var',$data['date_var']);
		$this->db->set('structure_id',$data['structure_id']);
		$this->db->set('org_id',$data['org_id']);
		$this->db->set('user_id',$data['user_id']);
		$this->db->set('time_count',$data['time_count']);
		$this->db->set('user_owner',$data['user_owner']);
		$this->db->set('datecreate',$datecreate);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('var_data');
		return $this->db->insert_id();

	}

	public function saveKpiFomulardata($data)
	{
		$datecreate = date("Y-m-d H:i:s");
		$this->db->set('formula_data_id',$data['formula_data_id']);
		$this->db->set('kpi_id',$data['kpi_id']);
		$this->db->set('structure_id',$data['structure_id']);
		$this->db->set('org_id',$data['org_id']);
		$this->db->set('user_id',$data['user_id']);
		$this->db->set('formula_data',$data['formula_data']);
		$this->db->set('formula_value',$data['formula_value']);
		$this->db->set('formula_score',$data['formula_score']);
		$this->db->set('grade_map',$data['grade_map']);
		$this->db->set('user_owner',$data['user_owner']);
		$this->db->set('datecreate',$datecreate);
		$this->db->set('score_real',$data['score_real']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('formula_data');
		return $this->db->insert_id();
	}

	public function deleteVarData($structure_id,$kpi_id)
	{
		$this->db->where('structure_id', $structure_id);
		$this->db->where('kpi_id', $kpi_id);
		$this->db->delete('var_data');
	}

	public function deleteFormulaData($structure_id,$kpi_id)
	{
		$this->db->where('structure_id', $structure_id);
		$this->db->where('kpi_id', $kpi_id);
		$this->db->delete('formula_data');
	}

	// public function getFormula($var_id='',$kpi_id)
	// {
	// 	$value_fomular = $this->db->query("SELECT formula_value FROM kpi_formula WHERE var_id='$var_id' AND kpi_id='$kpi_id' ")->row()->formula_value;
	// 	return $value_fomular;
	// }
	public function getVarData($kpi_id='',$var_id)
	{
		$this->db->select('*');
		$this->db->where('kpi_id',$kpi_id);
		$this->db->where('var_id',$var_id);
		$this->db->from('var_data');
		$var_data = $this->db->get()->result();
		return $var_data;
	}

	public function queryData($query)
	{
		$query_data = $this->db->query($query);
		$var_data = $query_data->result_array();
		$data = array();
		if(isset($var_data)){
			foreach ($var_data as $key => $value) {
				// $data[$key] =
				$index = 0;
				foreach ($value as $key_data => $value_data) {
						$data[$key][$index] = $value_data;
						$index++;
				}
			}
		}
		// echo "<pre>";
		// print_r($data);
		// die();
		// foreach ($variable as $key => $value) {
		// 	// code...
		// }
		return $data;
	}
}
