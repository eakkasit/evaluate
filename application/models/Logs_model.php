<?php

class Logs_model extends CI_Model
{
	public function countLogs($cond = array())
	{
		$this->db->select('*');
		$this->db->from('log');
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

	public function getLogs($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('log');
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

	public function getUserDateLogin($date = null)
	{
		if ($date == null) {
			$date = date('Y-m-d', strtotime('-1 year'));
		}
		$this->db->select('user.user_id, MAX(' . $this->db->dbprefix . 'log.create_date) as create_date');
		$this->db->from('user');
		$this->db->join('log', 'log.user_id = user.user_id');
		$this->db->where('log.create_date <= "' . $date . '"');
		$this->db->group_by('user.user_id');
		return $this->db->get()->result();
	}

	public function insertLog($action = '')
	{
		$this->db->set('log_action', $action);
		$this->db->set('user_id', $this->session->userdata('user_id'));
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('log');
		return $this->db->insert_id();
	}
}
