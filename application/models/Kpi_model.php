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

	public function saveKpiVardata($var_id='',$var_data,$kpi_id)
	{
		$datecreate = date("Y-m-d H:i:s");
		$this->db->query("INSERT INTO kpi_var_data SET
		var_id='$var_id' ,
		var_data='$var_data' ,
		kpi_id='$kpi_id' ,
		date_var='' ,
		structure_id='19' ,
		org_id='1' ,
		user_id='1' ,
		time_count='1' ,
		user_owner='1' ,
		datecreate='$datecreate'
		");
	}

	public function getFormula($var_id='',$kpi_id)
	{
		$value_fomular = $this->db->query("SELECT formula_value FROM kpi_formula WHERE var_id='$var_id' AND kpi_id='$kpi_id' ")->row()->formula_value;
		return $value_fomular;
	}
}
