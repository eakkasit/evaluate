<?php

class Units_model extends CI_Model
{
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_kpi', TRUE);
	 }


	public function countUnits($cond = array())
	{
		$this->db->select('*');
		$this->db->from('unit');
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

	public function getUnits($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('unit');
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

	public function getUnitsLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('unit')->order_by('unit_id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->unit_id] = $val->unit_name;
			}
		}
		return $list;
	}

	public function insertUnits($data = array())
	{
		$this->db->set('unit_name', $data['unit_name']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('unit');
		return $this->db->insert_id();
	}

	public function updateUnits($unit_id = null, $data = array())
	{
		$this->db->set('unit_name', $data['unit_name']);
		$this->db->where('unit_id', $unit_id);
		$this->db->update('unit');
		return $unit_id;
	}



	public function deleteUnits($unit_id = null)
	{
		// $update = ['status' => '4'];
		$this->db->where('unit_id', $unit_id);
		$this->db->delete('unit');
		return $unit_id;
	}
}
