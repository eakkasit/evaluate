<?php

class CriteriaVariables_model extends CI_Model
{

	public function countCriteriaVariables($cond = array())
	{
		$this->db->select('*');
		$this->db->from('criteria_variable');
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

	public function getCriteriaVariables($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('criteria_variable');
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

	public function getCriteriaVariableLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('criteria_variable')->order_by('id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->id] = $val->variable_name;
			}
		}
		return $list;
	}

	public function insertCriteriaVariables($data = array())
	{
		$this->db->set('variable_name', $data['variable_name']);
		$this->db->set('units', $data['units']);
		$this->db->set('type_show', $data['type_show']);
		$this->db->set('type_field', $data['type_field']);
		$this->db->set('variable_value', $data['variable_value']);
		$this->db->set('sql_text', $data['sql_text']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria_variable');
		return $this->db->insert_id();
	}

	public function updateCriteriaVariables($variable_id = null, $data = array())
	{
		$this->db->set('variable_name', $data['variable_name']);
		$this->db->set('units', $data['units']);
		$this->db->set('type_show', $data['type_show']);
		$this->db->set('type_field', $data['type_field']);
		$this->db->set('variable_value', $data['variable_value']);
		$this->db->set('sql_text', $data['sql_text']);
		$this->db->where('id', $variable_id);
		$this->db->update('criteria_variable', $update);
		return $variable_id;
	}

	public function deleteCriteriaVariables($variable_id = null)
	{
		// $update = ['status' => '4'];
		$this->db->where('id', $variable_id);
		$this->db->delete('criteria_variable', $update);
		return $variable_id;
	}
}
