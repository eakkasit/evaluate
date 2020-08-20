<?php

class Targets_model extends CI_Model
{

	public function countTargets($cond = array())
	{
		$this->db->select('*');
		$this->db->from('target');
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

	public function getTargets($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('target');
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


	public function insertTargets($data = array())
	{
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->set('project_id', $data['project_id']);
		$this->db->set('target', $data['target']);
		$this->db->set('result', $data['result']);
		$this->db->set('percent', $data['percent']);
		$this->db->set('year', $data['year']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('target');
		return $this->db->insert_id();
	}

	public function updateTargets($profile_id = null, $data = array())
	{
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->set('project_id', $data['project_id']);
		$this->db->set('target', $data['target']);
		$this->db->set('result', $data['result']);
		$this->db->set('percent', $data['percent']);
		$this->db->set('year', $data['year']);
		$this->db->where('id', $profile_id);
		$this->db->update('target');
		return $profile_id;
	}

	public function deleteTargets($profile_id = null)
	{
		$this->db->where('id', $profile_id);
		$this->db->delete('target');
		return $profile_id;
	}
}
