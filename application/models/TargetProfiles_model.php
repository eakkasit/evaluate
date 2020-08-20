<?php

class TargetProfiles_model extends CI_Model
{

	public function countTargetProfiles($cond = array())
	{
		$this->db->select('*');
		$this->db->from('target_profile');
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

	public function getTargetProfiles($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('target_profile');
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

	public function getTargetProfileLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('target_profile')->order_by('id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->id] = $val->name;
			}
		}
		return $list;
	}

	public function insertTargetProfiles($data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('year_start', $data['year_start']);
		$this->db->set('year_end', $data['year_end']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('target_profile');
		return $this->db->insert_id();
	}

	public function updateTargetProfiles($profile_id = null, $data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('year_start', $data['year_start']);
		$this->db->set('year_end', $data['year_end']);
		$this->db->set('detail', $data['detail']);
		$this->db->where('id', $profile_id);
		$this->db->update('target_profile');
		return $profile_id;
	}

	public function deleteTargetProfiles($profile_id = null)
	{
		$this->db->where('id', $profile_id);
		$this->db->delete('target_profile');
		return $profile_id;
	}
}
