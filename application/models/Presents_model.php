<?php

class Presents_model extends CI_Model
{
	private $default_user_type = 5; //meeting_user_type.user_type_id

	public function countGroupPresents($cond = array())
	{
		$this->db->select('user2present.meeting_id');
		$this->db->from('user2present');
		$this->db->join('user', 'user.user_id = user2present.user_id', 'left');
		$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2present.group_id = user2group.group_id', 'left');
		if (isset($cond['meeting_id'])) {
			$this->db->where('user2present.meeting_id', $cond['meeting_id']);
		} else {
			$this->db->where('user2present.meeting_id IS NOT NULL');
		}
		if (isset($cond['group_id'])) {
			$this->db->where('user2present.group_id', $cond['group_id']);
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id' && $k !== 'group_id') {
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

	public function getGroupPresents($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$group_users = array();
		$this->db->select('user2present.meeting_id, IFNULL(' . $this->db->dbprefix . 'user2group.group_id, 0) AS group_id, user2present.user_type_id, user.*');
		$this->db->from('user2present');
		$this->db->join('user', 'user.user_id = user2present.user_id', 'left');
		$this->db->join('user2group', 'user.user_id = user2group.user_id AND user2present.group_id = user2group.group_id', 'left');
		if (isset($cond['meeting_id'])) {
			$this->db->where('user2present.meeting_id', $cond['meeting_id']);
		} else {
			$this->db->where('user2present.meeting_id IS NOT NULL');
		}
		if (isset($cond['group_id'])) {
			$this->db->where('user2present.group_id', $cond['group_id']);
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id' && $k !== 'group_id') {
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
			$this->db->order_by('user2group.group_id');
			$this->db->order_by('user2present.user_type_id');
			$this->db->order_by('user.user_id');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function countDataUser($meeting_id = null, $group_id = null, $user_id = null)
	{
		$this->db->select('user_id');
		$this->db->from('user2present');
		$this->db->where('meeting_id', $meeting_id);
		$this->db->where('group_id', $group_id);
		$this->db->where('user_id', $user_id);
		return $this->db->get()->num_rows();
	}

	public function saveGroupUsers($meeting_id = null, $groups = array())
	{
		if (!empty($groups)) {
			foreach ($groups as $user) {
				$save = array(
					'meeting_id' => $meeting_id,
					'group_id' => $user->group_id,
					'user_id' => $user->user_id,
				);
				$this->savePresent($save);
			}
		}
		return $meeting_id;
	}

	public function savePresent($data = array())
	{
		if (!isset($data['user_type_id']) || $data['user_type_id'] == '') {
			$data['user_type_id'] = $this->default_user_type;
		}
		if ($this->countDataUser($data['meeting_id'], $data['group_id'], $data['user_id']) > 0) {
			$this->updatePresent($data['meeting_id'], $data['group_id'], $data['user_id'], $data);
		} else {
			$this->insertPresent($data);
		}
		return $data['meeting_id'];
	}

	public function insertPresent($data = array())
	{
		$this->db->set('meeting_id', $data['meeting_id']);
		$this->db->set('group_id', $data['group_id']);
		$this->db->set('user_id', $data['user_id']);
		$this->db->set('user_type_id', $data['user_type_id']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('user2present');
		return $this->db->insert_id();
	}

	public function updatePresent($meeting_id = null, $group_id = null, $user_id = null, $data = array())
	{
		$this->db->set('user_type_id', $data['user_type_id']);
		$this->db->where('meeting_id', $meeting_id);
		$this->db->where('group_id', $group_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('user2present');
		return $data['meeting_id'];
	}
}
