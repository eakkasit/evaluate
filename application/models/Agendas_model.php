<?php

class Agendas_model extends CI_Model
{
	private $defalt_agendas = array();

	public function __construct()
	{
		$this->setDefaultAgendas();
	}

	public function setDefaultAgendas()
	{
		//$this->db->select('*')->from('config')->where('config_id', 7);
		$agenda_defaults = $this->db->select('*')->from('agenda_default')->get()->result();
		if(!empty($agenda_defaults)){
			$this->defalt_agendas = $agenda_defaults;
		}
	}

	public function getAgendaTypes($agenda_type_id = null)
	{
		$agenda_types = array();
		$this->db->select('agenda_type_id, agenda_type_name')->from('agenda_type');
		if ($agenda_type_id != null) {
			$this->db->where('agenda_type_id', $agenda_type_id);
		}
		$agendas = $this->db->get()->result();
		if (!empty($agendas)) {
			foreach ($agendas as $type) {
				$agenda_types[$type->agenda_type_id] = $type->agenda_type_name;
			}
		}
		return $agenda_types;
	}

	public function countDataAgendas($cond = array())
	{
		$this->db->select('data.meeting_id');
		$this->db->from('data');
		$this->db->join('(SELECT meeting_id, MIN(agenda_no) AS agenda_no FROM ' . $this->db->dbprefix . 'agenda GROUP BY meeting_id) AS min_agenda', 'min_agenda.meeting_id = data.meeting_id', 'left');
		$this->db->join('agenda', 'agenda.agenda_no = min_agenda.agenda_no AND agenda.meeting_id = min_agenda.meeting_id', 'left');
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

	public function getDataAgendas($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('data.meeting_id, data.meeting_name, data.meeting_project, data.meeting_description, data.meeting_date, data.meeting_starttime, data.meeting_endtime, data.meeting_room, data.meeting_status, agenda.agenda_id, agenda.agenda_name, agenda.agenda_story, agenda.agenda_detail');
		$this->db->from('data');
		$this->db->join('(SELECT meeting_id, MIN(agenda_no) AS agenda_no FROM ' . $this->db->dbprefix . 'agenda GROUP BY meeting_id) AS min_agenda', 'min_agenda.meeting_id = data.meeting_id', 'left');
		$this->db->join('agenda', 'agenda.agenda_no = min_agenda.agenda_no AND agenda.meeting_id = min_agenda.meeting_id', 'left');
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
		} else { //default order
			$this->db->order_by('data.meeting_date', 'desc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function getAgendas($cond = array(), $order = array())
	{
		$this->db->select('agenda.*, minutes.minutesdetail, minutes.conclusion');
		$this->db->from('agenda');
		$this->db->join('minutes', 'minutes.meeting_id = agenda.meeting_id AND minutes.agenda_id = agenda.agenda_id', 'left');
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
		} else { //default order
			$this->db->order_by('agenda_no');
		}
		return $this->db->get()->result();
	}

	public function getDataAgendasFiles($cond = array(), $order = array())
	{
		$agenda_files = array();
		$this->db->select('agenda_file.*');
		$this->db->from('data');
		$this->db->join('agenda', 'data.meeting_id = agenda.meeting_id');
		$this->db->join('agenda_file', 'agenda.agenda_id = agenda_file.agenda_id');
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
		} else { //default order
			$this->db->order_by('agenda_file_id');
		}
		$files = $this->db->get()->result();
		if (!empty($files)) {
			foreach ($files as $file) {
				$agenda_files[$file->agenda_id][] = $file;
			}
		}
		return $agenda_files;
	}

	public function getAgendaFiles($cond = array(), $order = array())
	{
		$this->db->select('*');
		$this->db->from('agenda_file');
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
		} else { //default order
			$this->db->order_by('agenda_id');
			$this->db->order_by('agenda_file_id');
		}
		return $this->db->get()->result();
	}

	public function insertAgendas($meeting_id = null, $data = array())
	{
		$this->db->set('agenda_no', $data['agenda_no']);
		$this->db->set('meeting_id', $meeting_id);
		$this->db->set('agenda_type_id', $data['agenda_type_id']);
		$this->db->set('agenda_name', $data['agenda_name']);
		$this->db->set('agenda_story', $data['agenda_story']);
		$this->db->set('agenda_detail', $data['agenda_detail']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('agenda');
		return $this->db->insert_id();
	}

	public function updateAgendas($meeting_id = null, $agenda_id = null, $data = array())
	{
		$this->db->set('agenda_type_id', $data['agenda_type_id']);
		$this->db->set('agenda_name', $data['agenda_name']);
		$this->db->set('agenda_story', $data['agenda_story']);
		$this->db->set('agenda_detail', $data['agenda_detail']);
		$this->db->where('agenda_id', $agenda_id);
		$this->db->update('agenda');
		return $agenda_id;
	}

	public function deleteAgenda($meeting_id = null, $agenda_id = null)
	{
		$this->db->where('agenda_id', $agenda_id);
		$this->db->delete('agenda');
		$agendas = $this->getAgendas($meeting_id);
		if (!empty($agendas)) {
			$no = 1;
			foreach ($agendas as $agenda) {
				$update = ['agenda_no' => $no];
				$this->db->where('agenda_id', $agenda->agenda_id);
				$this->db->update('agenda', $update);
				$no++;
			}
		}
		return $agenda_id;
	}

	public function insertAgendaFile($data = array())
	{
		$this->db->set('agenda_id', $data['agenda_id']);
		$this->db->set('agenda_filename', $data['agenda_filename']);
		$this->db->set('agenda_detail', $data['agenda_detail']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('agenda_file');
		return $this->db->insert_id();
	}

	public function deleteAgendaFiles($file_path = '', $agenda_id = null, $file_id = null)
	{
		$files = $this->getAgendaFiles($agenda_id);
		if ($file_id != null) {
			$this->db->where('agenda_file_id', $file_id);
			foreach ($files as $file) {
				if ($file->agenda_file_id == $file_id) {
					unlink("{$file_path}{$file->agenda_filename}");
				}
			}
		} else {
			$this->db->where('agenda_id', $agenda_id);
			foreach ($files as $file) {
				unlink("{$file_path}{$file->agenda_filename}");
			}
		}
		$this->db->delete('agenda_file');
		return $agenda_id;
	}

	public function defaltAgendas($meeting_id = null)
	{
		if (!empty($this->defalt_agendas)) {
			foreach ($this->defalt_agendas as $key => $item) {
				$this->db->set('agenda_no', $key + 1);
				$this->db->set('meeting_id', $meeting_id);
				$this->db->set('agenda_type_id', 1);
				if (isset($item->agenda_default_name) && $item->agenda_default_name != '') {
					$this->db->set('agenda_name', $item->agenda_default_name);
				}
				if (isset($item->agenda_default_story) && $item->agenda_default_story != '') {
					$this->db->set('agenda_story', $item->agenda_default_story);
				}
				if (isset($item->agenda_default_detail) && $item->agenda_default_detail != '') {
					$this->db->set('agenda_detail', $item->agenda_default_detail);
				}
				$this->db->set('create_date', 'NOW()', false);
				$this->db->insert('agenda');
			}
		}

		return $meeting_id;
	}

	public function countAgendaDefaults($cond = array())
	{
		$this->db->select('*');
		$this->db->from('agenda_default');
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

	public function getAgendaDefaults($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('agenda_default');
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
			$this->db->order_by('agenda_default_id');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function insertAgendaDefault($data = array())
	{
		$this->db->set('agenda_default_name', $data['agenda_default_name']);
		if (isset($data['agenda_default_story']) && $data['agenda_default_story'] != '') $this->db->set('agenda_default_story', $data['agenda_default_story']);
		if (isset($data['agenda_default_detail']) && $data['agenda_default_detail'] != '') $this->db->set('agenda_default_detail', $data['agenda_default_detail']);

		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('agenda_default');
		return $this->db->insert_id();
	}

	public function updateAgendaDefault($agenda_default_id = null, $data = array())
	{
		$this->db->set('agenda_default_name', $data['agenda_default_name']);
		if (isset($data['agenda_default_story']) && $data['agenda_default_story'] != '') $this->db->set('agenda_default_story', $data['agenda_default_story']);
		if (isset($data['agenda_default_detail']) && $data['agenda_default_detail'] != '') $this->db->set('agenda_default_detail', $data['agenda_default_detail']);

		$this->db->where('agenda_default_id', $agenda_default_id);
		$this->db->update('agenda_default');
		return $agenda_default_id;
	}

	public function deleteAgendaDefault($agenda_default_id = null)
	{
		$this->db->where('agenda_default_id', $agenda_default_id);
		$this->db->delete('agenda_default');
		return $agenda_default_id;
	}
}
