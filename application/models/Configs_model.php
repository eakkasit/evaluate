<?php

class Configs_model extends CI_Model
{
	public function getConfigs($cond = array(), $order = array())
	{
		$this->db->select('*');
		$this->db->from('config');
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
			$this->db->order_by('config_id');
		}
		return $this->db->get()->result();
	}

	public function saveConfigs($configs = array())
	{
		$this->db->set('config_status', 'inactive');
		$this->db->update('config');

		if (!empty($configs)) {
			foreach ($configs as $config_id) {
				$this->db->set('config_status', 'active');
				$this->db->where('config_id', $config_id);
				$this->db->update('config');
			}
		}
		return true;
	}
}
