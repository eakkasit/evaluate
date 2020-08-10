<?php

class Structure_model extends CI_Model
{
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
	 }


	public function countStructure($cond = array())
	{
		$this->db->select('*');
		$this->db->from('structure');
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

	public function getStructure($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('structure');
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

	public function getStructureLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('structure')->order_by('structure_id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->var_id] = $val->var_name;
			}
		}
		return $list;
	}

	public function insertStructure($data = array())
	{
		$this->db->set('structure_name', $data['structure_name']);
		$this->db->set('profile_year', $data['profile_year']);
		$this->db->set('frequency_id', '4');
		$this->db->set('structure_status', $data['structure_status']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('structure');
		return $this->db->insert_id();
	}

	public function updateStructure($structure_id = null, $data = array())
	{
		$this->db->set('structure_name', $data['structure_name']);
		$this->db->set('profile_year', $data['profile_year']);
		$this->db->set('frequency_id', '4');
		$this->db->set('structure_status', $data['structure_status']);
		$this->db->where('structure_id', $structure_id);
		$this->db->update('structure');
		return $structure_id;
	}



	public function deleteStructure($structure_id = null)
	{
		$this->db->where('structure_id', $structure_id);
		$this->db->delete('structure');
		return $structure_id;
	}
}
