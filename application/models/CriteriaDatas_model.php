<?php

class CriteriaDatas_model extends CI_Model
{

	public function countCriteriaDatas($cond = array())
	{
		$this->db->select('*');
		$this->db->from('criteria_data');
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

	public function getCriteriaDatas($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('criteria_data');
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

	public function insertCriteriaDatas($data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria_data');
		return $this->db->insert_id();
	}

	public function updateCriteriaDatas($profile_id = null, $data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->set('detail', $data['detail']);
		$this->db->where('id', $profile_id);
		$this->db->update('criteria_data', $update);
		return $profile_id;
	}

	public function deleteCriteriaDatas($profile_id = null)
	{
		// $update = ['status' => '4'];
		$this->db->where('id', $profile_id);
		$this->db->delete('criteria_data', $update);
		return $profile_id;
	}
}
