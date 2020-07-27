<?php

class CriteriaProfiles_model extends CI_Model
{

	public function countCriteriaProfiles($cond = array())
	{
		$this->db->select('*');
		$this->db->from('criteria_profile');
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

	public function getCriteriaProfiles($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('criteria_profile');
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

	public function insertCriteriaProfiles($data = array())
	{
		$this->db->set('profile_name', $data['profile_name']);
		$this->db->set('year', $data['year']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('status', $data['status']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria_profile');
		return $this->db->insert_id();
	}

	public function updateCriteriaProfiles($profile_id = null, $data = array())
	{
		$this->db->set('profile_name', $data['profile_name']);
		$this->db->set('year', $data['year']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('status', $data['status']);
		$this->db->where('id', $profile_id);
		$this->db->update('criteria_profile', $update);
		return $profile_id;
	}

	public function deleteCriteriaProfiles($profile_id = null)
	{
		$update = ['status' => '4'];
		$this->db->where('id', $profile_id);
		$this->db->update('criteria_profile', $update);
		return $profile_id;
	}
}
