<?php

class AgendaTypes_model extends CI_Model
{
	public function countAgendaTypes($cond = array())
	{
		$this->db->select('*');
		$this->db->from('agenda_type');
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

	public function getAgendaTypes($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('agenda_type');
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

	public function insertAgendaType($data = array())
	{
		$this->db->set('agenda_type_name', $data['agenda_type_name']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('agenda_type');
		return $this->db->insert_id();
	}

	public function updateAgendaType($agenda_type_id = null, $data = array())
	{
		$this->db->set('agenda_type_name', $data['agenda_type_name']);
		$this->db->where('agenda_type_id', $agenda_type_id);
		$this->db->update('agenda_type');
		return $agenda_type_id;
	}

	public function deleteAgendaType($agenda_type_id = null)
	{
		$this->db->where('agenda_type_id', $agenda_type_id);
		$this->db->delete('agenda_type');
		return $agenda_type_id;
	}

	
}
