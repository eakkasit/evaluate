<?php

class Criterias_model extends CI_Model
{

	public function countCriterias($cond = array())
	{
		$this->db->select('*');
		$this->db->from('criteria');
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

	public function getCriterias($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('criteria');
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

	public function insertCriterias($data = array())
	{
		$this->db->set('criteria_name', $data['criteria_name']);
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->set('parent_id', $data['parent_id']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria');
		return $this->db->insert_id();
	}

	public function updateCriterias($variable_id = null, $data = array())
	{
		$this->db->set('criteria_name', $data['criteria_name']);
		$this->db->set('parent_id', $data['parent_id']);
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->where('id', $variable_id);
		$this->db->update('criteria', $update);
		return $variable_id;
	}

	public function deleteCriterias($variable_id = null)
	{
		// $update = ['status' => '4'];
		$this->db->where('id', $variable_id);
		$this->db->delete('criteria', $update);
		return $variable_id;
	}


	function getOneLevelData($profile_id,$id,$child){

			$this->db->select('*');
			$this->db->from('criteria');
			$this->db->where('profile_id',$profile_id);
			$this->db->where('parent_id',$id);
			$query = $this->db->get();
			$res = $query->result_array();
			if($query->num_rows() >0){
					foreach ($res as $key => $value) {
						// $row = array();
						// $row['id'] = $value['id'];
						// $row['name'] = $value['name'];
						$child[] = $value;
					}

			}
			return $child;
	}

	function getItemChild($profile_id,$parent_id) {
			$tree = array();
			$tree = $this->getOneLevelData($profile_id,$parent_id,[]);
			$child = [];
			foreach ($tree as $key => $val) {
					if($this->getItemChild($profile_id,$val['id'])){
						$val['data'] = $this->getItemChild($profile_id,$val['id']);
					}
					 $child[] = (object) $val;
			}
			return $child;
	}

}
