<?php

class Users_model extends CI_Model
{
	private $default_user_type_id = 5;

	public function countUsers($cond = array())
	{
		$this->db->select('*');
		$this->db->from('user');
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

	public function getUsers($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('user');
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

	public function insertUser($data = array())
	{
		$this->db->set('citizen_id', $data['citizen_id']);
		$this->db->set('prename', $data['prename']);
		$this->db->set('name', $data['name']);
		$this->db->set('surname', $data['surname']);
		$this->db->set('position_code', $data['position_code']);
		$this->db->set('level_code', $data['level_code']);
		$this->db->set('gender', $data['gender']);
		$this->db->set('department', $data['department']);
		$this->db->set('email', $data['email']);
		$this->db->set('telephone', $data['telephone']);
		$this->db->set('user_status', $data['user_status']);
		if (isset($data['user_type']) && $data['user_type'] != '') $this->db->set('user_type', $data['user_type']);
		$this->db->set('profile_picture', $data['profile_picture']);

		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('user');
		return $this->db->insert_id();
	}

	public function updateUser($user_id = null, $data = array())
	{
		$this->db->set('citizen_id', $data['citizen_id']);
		$this->db->set('prename', $data['prename']);
		$this->db->set('name', $data['name']);
		$this->db->set('surname', $data['surname']);
		$this->db->set('position_code', $data['position_code']);
		$this->db->set('level_code', $data['level_code']);
		$this->db->set('gender', $data['gender']);
		$this->db->set('department', $data['department']);
		$this->db->set('email', $data['email']);
		$this->db->set('telephone', $data['telephone']);
		$this->db->set('user_status', $data['user_status']);
		if (isset($data['user_type']) && $data['user_type'] != '') $this->db->set('user_type', $data['user_type']);
		$this->db->set('profile_picture', $data['profile_picture']);
		$this->db->where('user_id', $user_id);
		$this->db->update('user', $update);
		return $user_id;
	}

	public function deleteUser($user_id = null)
	{
		$update = ['user_status' => 'invoke'];
		$this->db->where('user_id', $user_id);
		$this->db->update('user', $update);
		return $user_id;
	}

	public function saveTemporaryUsers($meeting_id = null, $users = array())
	{
		if (isset($users['new']) && !empty($users['new'])) {
			foreach ($users['new'] as $user) {
				$user['user_status'] = 'active';
				$user['user_type'] = 'temporary';
				$user_id = $this->insertUser($user);
				$this->db->set('meeting_id', $meeting_id);
				$this->db->set('group_id', 0);
				$this->db->set('user_id', $user_id);
				$this->db->set('user_type_id', $this->default_user_type_id);
				$this->db->set('create_date', 'NOW()', false);
				$this->db->insert('user2present');
			}
		}
		if (isset($users['edit']) && !empty($users['edit'])) {
			foreach ($users['edit'] as $user) {
				$this->updateUser($user['user_id'], $user);
				$this->db->set('meeting_id', $meeting_id);
				$this->db->set('group_id', 0);
				$this->db->set('user_id', $user['user_id']);
				$this->db->set('user_type_id', $this->default_user_type_id);
				$this->db->set('create_date', 'NOW()', false);
				$this->db->insert('user2present');
			}
		}
	}
}
