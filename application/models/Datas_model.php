<?php

class Datas_model extends CI_Model
{
	private $default_data_status = 'draft';

	public function getDatasOverview($year = null)
	{
		if ($year == null) {
			$year = date('Y');
		}

		$year_datas = $this->countYearDatas($year)[0];
		$count_all = $year_datas->count_success + $year_datas->count_fail;
		$success_rate = 0;
		if ($year_datas->count_success > 0 && $year_datas->count_fail > 0) {
			$success_rate = ($year_datas->count_success * 100) / $count_all;
		}

		return array(
			'year' => $year,
			'organize_name' => 'ทดสอบ',

			'login_users' => $this->db->from('user')->where('user_status', 'active')->count_all_results(),
			'login_users_rate' => 0,

			'sum_data' => $count_all,
			'sum_data_rate' => 0,

			'success_data' => $success_rate,
			'success_data_rate' => 0,
		);
	}

	public function countYearDatas($year = null)
	{
		$this->db->select('SUM(IF(' . $this->db->dbprefix . 'data.meeting_status = \'active\', 1, 0)) AS count_success');
		$this->db->select('SUM(IF(' . $this->db->dbprefix . 'data.meeting_status = \'active\', 0, 1)) AS count_fail');
		$this->db->from('data');
		$this->db->where($this->db->dbprefix . 'data.meeting_date LIKE \'' . $year . '%\'');
		return $this->db->get()->result();
	}

	public function countDatas($cond = array())
	{
		$this->db->select('data.meeting_id');
		$this->db->from('data');
		$this->db->join('(SELECT meeting_id, MIN(group_id) AS group_id FROM ' . $this->db->dbprefix . 'group2present GROUP BY meeting_id) AS min_group', 'min_group.meeting_id = data.meeting_id', 'left');
		$this->db->join('group2present', 'group2present.group_id = min_group.group_id AND group2present.meeting_id = min_group.meeting_id', 'left');
		if (isset($cond['meeting_id'])) {
			$this->db->where('data.meeting_id', $cond['meeting_id']);
		} else {
			$this->db->where('data.meeting_id IS NOT NULL');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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

	public function getDatas($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('data.*, group2present.group_id');
		$this->db->from('data');
		$this->db->join('(SELECT meeting_id, MIN(group_id) AS group_id FROM ' . $this->db->dbprefix . 'group2present GROUP BY meeting_id) AS min_group', 'min_group.meeting_id = data.meeting_id', 'left');
		$this->db->join('group2present', 'group2present.group_id = min_group.group_id AND group2present.meeting_id = min_group.meeting_id', 'left');
		if (isset($cond['meeting_id'])) {
			$this->db->where('data.meeting_id', $cond['meeting_id']);
		} else {
			$this->db->where('data.meeting_id IS NOT NULL');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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
			$this->db->order_by('meeting_date', 'desc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function countDataUsers($cond = array())
	{
		$this->db->select('user2present.meeting_id, user.user_id');
		$this->db->from('user2present');
		$this->db->join('user', 'user.user_id = user2present.user_id');
		if (isset($cond['meeting_id'])) {
			$this->db->where('user2present.meeting_id', $cond['meeting_id']);
			$this->db->group_by('user2present.meeting_id');
			$this->db->group_by('user.user_id');
		} else {
			$this->db->where('user2present.meeting_id IS NOT NULL');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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
		return $this->db->get()->result();
	}

	public function getDataUsers($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('user2present.meeting_id, user.*');
		$this->db->from('user2present');
		$this->db->join('user', 'user.user_id = user2present.user_id');
		if (isset($cond['meeting_id'])) {
			$this->db->where('user2present.meeting_id', $cond['meeting_id']);
			$this->db->group_by('user2present.meeting_id');
			$this->db->group_by('user.user_id');
		} else {
			$this->db->where('user2present.meeting_id IS NOT NULL');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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
			$this->db->order_by('user.user_id', 'desc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function countDataGroups($cond = array(), $inner = false)
	{
		$this->db->select('group.group_id');
		$this->db->from('group');
		if (isset($cond['meeting_id'])) {
			if ($inner) {
				$this->db->join('group2present', 'group.group_id = group2present.group_id AND group2present.meeting_id = ' . $cond['meeting_id']);
			} else {
				$this->db->join('group2present', 'group.group_id = group2present.group_id AND group2present.meeting_id = ' . $cond['meeting_id'], 'left');
			}
		} else {
			$this->db->join('group2present', 'group.group_id = group2present.group_id', 'left');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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

	public function getDataGroups($cond = array(), $order = array(), $inner = false, $limit = null, $start = 0)
	{
		$this->db->select('group.group_id, group.group_name, group.group_description, group2present.meeting_id');
		$this->db->from('group');
		if (isset($cond['meeting_id'])) {
			if ($inner) {
				$this->db->join('group2present', 'group.group_id = group2present.group_id AND group2present.meeting_id = ' . $cond['meeting_id']);
			} else {
				$this->db->join('group2present', 'group.group_id = group2present.group_id AND group2present.meeting_id = ' . $cond['meeting_id'], 'left');
			}
		} else {
			$this->db->join('group2present', 'group.group_id = group2present.group_id', 'left');
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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
			$this->db->order_by('IF(' . $this->db->dbprefix . 'group2present.meeting_id IS NULL, 1, 0) ASC');
			$this->db->order_by('group.create_date', 'asc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function getCalendarDatas()
	{
		$this->db->select('*');
		$this->db->from('data');
		if (isset($_GET['start']) && isset($_GET['end'])) {
			$this->db->where($this->db->dbprefix . 'data.meeting_date >= \'' . $_GET['start'] . '\'');
			$this->db->where($this->db->dbprefix . 'data.meeting_date <= \'' . $_GET['end'] . '\'');
		} else {
			$date = date('Y-m');
			$this->db->where($this->db->dbprefix . 'data.meeting_date LIKE \'' . $date . '%\'');
		}
		$this->db->order_by('meeting_date', 'desc');
		return $this->db->get()->result();
	}

	public function insertData($data = array())
	{
		$this->db->set('meeting_name', $data['meeting_name']);
		$this->db->set('meeting_project', $data['meeting_project']);
		$this->db->set('meeting_description', $data['meeting_description']);
		$this->db->set('meeting_date', $data['meeting_date']);
		$this->db->set('meeting_starttime', $data['meeting_starttime']);
		$this->db->set('meeting_endtime', $data['meeting_endtime']);
		$this->db->set('meeting_room', $data['meeting_room']);
		$this->db->set('meeting_status', $this->default_data_status);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('data');
		return $this->db->insert_id();
	}

	public function updateData($meeting_id = null, $data = array())
	{
		$this->db->set('meeting_name', $data['meeting_name']);
		$this->db->set('meeting_project', $data['meeting_project']);
		$this->db->set('meeting_description', $data['meeting_description']);
		$this->db->set('meeting_date', $data['meeting_date']);
		$this->db->set('meeting_starttime', $data['meeting_starttime']);
		$this->db->set('meeting_endtime', $data['meeting_endtime']);
		$this->db->set('meeting_room', $data['meeting_room']);
		$this->db->where('meeting_id', $meeting_id);
		$this->db->update('data');
		return $meeting_id;
	}

	public function updateDataGroups($meeting_id = null, $groups = array())
	{
		$this->db->where('meeting_id', $meeting_id);
		$this->db->delete('user2present');

		$this->db->where('meeting_id', $meeting_id);
		$this->db->delete('group2present');

		if (isset($groups) && !empty($groups)) {
			foreach ($groups as $group_id) {
				$this->db->set('meeting_id', $meeting_id);
				$this->db->set('group_id', $group_id);
				$this->db->set('create_date', 'NOW()', false);
				$this->db->insert('group2present');
			}
		}
		return $meeting_id;
	}

	public function countDataUsersTemporary($cond = array())
	{
		$this->db->select('user.*');
		$this->db->from('user2present');
		$this->db->join('user', 'user.user_id = user2present.user_id');
		$this->db->where('user.user_type', 'temporary');
		if (isset($cond['meeting_id'])) {
			$this->db->where('user2present.meeting_id', $cond['meeting_id']);
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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
		return $this->db->get()->result();
	}

	public function getDataUsersTemporary($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('user.*');
		$this->db->from('user2present');
		$this->db->join('user', 'user.user_id = user2present.user_id');
		$this->db->where('user.user_type', 'temporary');
		$this->db->where('user.user_status', 'active');
		if (isset($cond['meeting_id'])) {
			$this->db->where('user2present.meeting_id', $cond['meeting_id']);
		}
		if (!empty($cond)) {
			foreach ($cond as $k => $v) {
				if ($k !== 'meeting_id') {
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
			$this->db->order_by('user2present.create_date', 'desc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}
}
