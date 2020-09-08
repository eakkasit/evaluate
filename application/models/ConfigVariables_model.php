<?php

class ConfigVariables_model extends CI_Model
{
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
	 }


	public function countConfigVariables($cond = array())
	{
		$this->db->select('*');
		$this->db->from('variable_system');
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

	public function getConfigVariables($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('variable_system');
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

	public function getConfigVariablesLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('unit')->order_by('unit_id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->id] = $val->name;
			}
		}
		return $list;
	}

	public function insertConfigVariables($data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('sql', $data['sql']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('variable_system');
		return $this->db->insert_id();
	}

	public function updateConfigVariables($variable_id = null, $data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('sql', $data['sql']);
		$this->db->where('id', $variable_id);
		$this->db->update('variable_system');
		return $variable_id;
	}



	public function deleteConfigVariables($variable_id = null)
	{
		$this->db->where('id', $variable_id);
		$this->db->delete('variable_system');
		return $variable_id;
	}
}
