<?php

class Variable_model extends CI_Model
{
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
	 }


	public function countVariable($cond = array())
	{
		$this->db->select('*');
		$this->db->from('variable');
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

	public function getVariable($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('variable');
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

	public function getVariableLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('variable')->order_by('var_id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->var_id] = $val->var_name;
			}
		}
		return $list;
	}

	public function getVariableUnitLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('variable')->order_by('var_id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->var_id] = $val->var_unit_id;
			}
		}
		return $list;
	}

	public function insertVariable($data = array())
	{
		$this->db->set('var_name', $data['var_name']);
		$this->db->set('var_unit_id', $data['var_unit_id']);
		$this->db->set('var_type_id', $data['var_type_id']);
		$this->db->set('var_import_id', $data['var_import_id']);
		$this->db->set('var_value', $data['var_value']);
		$this->db->set('var_max_length', $data['var_max_length']);
		$this->db->set('var_sql', $data['var_sql']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('variable');
		return $this->db->insert_id();
	}

	public function updateVariable($variable_id = null, $data = array())
	{
		$this->db->set('var_name', $data['var_name']);
		$this->db->set('var_unit_id', $data['var_unit_id']);
		$this->db->set('var_type_id', $data['var_type_id']);
		$this->db->set('var_import_id', $data['var_import_id']);
		$this->db->set('var_value', $data['var_value']);
		$this->db->set('var_max_length', $data['var_max_length']);
		$this->db->set('var_sql', $data['var_sql']);
		$this->db->where('var_id', $variable_id);
		$this->db->update('variable');
		return $variable_id;
	}



	public function deleteVariable($variable_id = null)
	{
		// $update = ['status' => '4'];
		$this->db->where('var_id', $variable_id);
		$this->db->delete('variable');
		return $variable_id;
	}
}
