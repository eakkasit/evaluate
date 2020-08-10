<?php

class Formula_model extends CI_Model
{
	// private $default_user_type_id = 5;
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
	 }

	public function countFormula($cond = array())
	{
		$this->db->select('*');
		$this->db->from('formula');
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

	public function getFormula($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('formula');
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

	public function getFormulaLists()
	{
		$list = array();
		$formula_list = $this->db->select('*')->from('formula')->order_by('formula_id')->get()->result();
		if(!empty($formula_list)){
			foreach($formula_list as $val){
				$list[$val->formula_id] = $val->formula_value;
			}
		}
		return $list;
	}

	public function insertFormula($data = array())
	{
		$this->db->set('kpi_id',$data['kpi_id']);
		$this->db->set('formula_value',$data['formula_value']);
		$this->db->set('var_id',$data['var_id']);
		$this->db->set('depend',$data['depend']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('formula');
		return $this->db->insert_id();
	}

	public function updateFormula($data_id = null, $data = array())
	{
		$this->db->set('kpi_id',$data['kpi_id']);
		$this->db->set('formula_value',$data['formula_value']);
		$this->db->set('var_id',$data['var_id']);
		$this->db->set('depend',$data['depend']);
		$this->db->where('formula_id', $data_id);
		$this->db->update('formula');
		return $data_id;
	}

	public function deleteFormula($data_id = null)
	{
		$this->db->where('formula_id', $data_id);
		$this->db->delete('formula');
		return $data_id;
	}

	public function deleteKpiFormula($data_id = null)
	{
		$this->db->where('kpi_id', $data_id);
		$this->db->delete('formula');
		return $data_id;
	}
}
