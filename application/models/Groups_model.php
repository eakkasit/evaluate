<?php

class Groups_model extends CI_Model
{
	public function countGroups($cond = array())
	{
		$this->db->select('*');
		$this->db->from('group');
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

	public function getGroups($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('group');
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

	public function countGroupUsers($cond = array(), $inner = false)
	{
		$this->db->select('user.user_id');
		$this->db->from('user');
		if (isset($cond['group_id'])) {
			if ($inner) {
				$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id = ' . $cond['group_id']);
			} else {
				$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id = ' . $cond['group_id'], 'left');
			}
		} else {
			$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id IS NULL', 'left');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'group_id') {
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
		}

		return $this->db->get()->num_rows();
	}

	public function getGroupUsers($cond = array(), $order = array(), $inner = false, $limit = null, $start = 0)
	{
		$this->db->select('user.user_id, user.prename, user.name, user.surname, user.profile_picture, user.position_code,  user.department, user2group.group_id');
		$this->db->from('user');
		if (isset($cond['group_id'])) {
			if ($inner) {
				$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id = ' . $cond['group_id']);
			} else {
				$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id = ' . $cond['group_id'], 'left');
			}
		} else {
			$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id IS NULL', 'left');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'group_id') {
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
			$this->db->order_by('IF(' . $this->db->dbprefix . 'user2group.group_id IS NULL, 1, 0) ASC');
			$this->db->order_by('user.create_date', 'desc');
		}
		/*if ($group_id != null) {
			if ($inner) {
				$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id = ' . $group_id);
			} else {
				$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id = ' . $group_id, 'left');
			}
		} else {
			$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2group.group_id IS NULL', 'left');
		}
		$this->db->where('user_status', 'active');
		$this->db->order_by('IF(' . $this->db->dbprefix . 'user2group.group_id IS NULL, 1, 0) ASC');
		$this->db->order_by('user.create_date', 'desc');*/
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function getUsersByGroup($group_id = array())
	{
		if (!empty($group_id)) {
			$this->db->select('user_id, group_id');
			$this->db->from('user2group');
			$this->db->where('group_id IN', '(' . implode(', ', $group_id) . ')', false);
			$this->db->order_by('group_id');
			$this->db->order_by('user_id');
			return $this->db->get()->result();
		} else {
			return null;
		}
	}

	public function insertGroup($data = array())
	{
		$this->db->set('group_name', $data['group_name']);
		$this->db->set('group_status', $data['group_status']);
		$this->db->set('group_description', $data['group_description']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('group');
		return $this->db->insert_id();
	}

	public function updateGroup($group_id = null, $data = array())
	{
		$this->db->set('group_name', $data['group_name']);
		$this->db->set('group_status', $data['group_status']);
		$this->db->set('group_description', $data['group_description']);
		$this->db->where('group_id', $group_id);
		$this->db->update('group');
		return $group_id;
	}

	public function updateGroupUsers($group_id = null, $users = array())
	{
		$this->db->where('group_id', $group_id);
		$this->db->delete('user2group');
		if (isset($users) && !empty($users)) {
			foreach ($users as $user_id) {
				$this->db->set('user_id', $user_id);
				$this->db->set('group_id', $group_id);
				$this->db->set('create_date', 'NOW()', false);
				$this->db->insert('user2group');
			}
		}
		return $group_id;
	}
}
