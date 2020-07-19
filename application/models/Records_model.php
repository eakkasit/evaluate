<?php

class Records_model extends CI_Model
{

	public function getAgendasRecord($meeting_id = null)
	{
		$record = array();
		$this->db->select('record.*, user.prename, user.name, user.surname');
		$this->db->from('data');
		$this->db->join('agenda', 'agenda.meeting_id = data.meeting_id');
		$this->db->join('record', 'record.agenda_id = agenda.agenda_id AND record.meeting_id = data.meeting_id');
		$this->db->join('user', 'user.user_id = record.user_id');
		$this->db->where('data.meeting_id', $meeting_id);
		$this->db->order_by('agenda.agenda_no');
		$this->db->order_by('record.update_date');
		$records = $this->db->get()->result();
		if (!empty($records)) {
			foreach ($records as $value) {
				$record[$value->agenda_id][] = $value;
			}
		}
		return $record;
	}

	public function countRecord($cond = array())
	{
		$this->db->select('*');
		$this->db->from('record');
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

	public function getRecord($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('record.*, user.prename, user.name, user.surname');
		$this->db->from('record');
		$this->db->join('user', 'user.user_id = record.user_id');
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
			$this->db->order_by('record.create_date');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}


	public function saveRecord($data = array())
	{
		// $this->db->select('*');
		// $this->db->where('meeting_id', $data['meeting_id']);
		// $this->db->where('agenda_id', $data['agenda_id']);
		// $this->db->where('user_id', $data['user_id']);
		// $this->db->from('record');
		// if ($this->db->get()->num_rows() > 0) {//update
		// 	$result = $this->updateRecord($data);
		// } else {//insert
		// 	$result = $this->insertRecord($data);
		// }

		if($data['record_id'] == ''){
			$result = $this->insertRecord($data);
		}else{
			$result = $this->updateRecord($data);
		}
		return $result;
	}

	public function insertRecord($data = array())
	{
		if(isset($data['reply_id']) && !empty($data['reply_id']) ){
			$this->db->set('reply_id', $data['reply_id']);
		}
		$this->db->set('meeting_id', $data['meeting_id']);
		$this->db->set('agenda_id', $data['agenda_id']);
		$this->db->set('user_id', $data['user_id']);

		$this->db->set('record_detail', $data['record_detail']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('record');
		return $this->db->insert_id();
	}

	public function insertRecordFile($data = array())
	{
		$this->db->set('record_id', $data['record_id']);
		$this->db->set('record_filename', $data['record_filename']);
		$this->db->set('record_detail', $data['record_detail']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('record_file');
		return $this->db->insert_id();
	}

	public function updateRecord($data = array())
	{
		$this->db->set('record_detail', $data['record_detail']);
		// $this->db->where('meeting_id', $data['meeting_id']);
		// $this->db->where('agenda_id', $data['agenda_id']);
		// $this->db->where('user_id', $data['user_id']);
		$this->db->where('record_id', $data['record_id']);
		$this->db->update('record');
		return $data;
	}

	public function deleteRecord($meeting_id = null, $agenda_id = null, $user_id = null)
	{
		// $this->db->where('meeting_id', $meeting_id);
		// $this->db->where('agenda_id', $agenda_id);
		// $this->db->where('user_id', $user_id);
		$this->db->where('record_id', $data['record_id']);
		$this->db->delete('record');
		return $meeting_id;
	}

	public function getRecordSave($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('record.*,CONCAT(meeting_user.`name`,\' \',meeting_user.surname) AS fullname,
		meeting_user_type.user_type_name AS position');
        $this->db->from('record');
		$this->db->join('meeting_user','meeting_user.user_id = meeting_record.user_id','LEFT');
		$this->db->join('meeting_user2present','meeting_user2present.meeting_id = meeting_record.meeting_id
		AND meeting_user2present.user_id = meeting_record.user_id','LEFT');
		$this->db->join('meeting_user_type','meeting_user2present.user_type_id = meeting_user_type.user_type_id','LEFT');
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
			$this->db->order_by('create_date');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function getRecords($cond = array(), $order = array(), $limit = null, $start = 0){

        $this->db->select('record.*,CONCAT(meeting_user.`name`,\' \',meeting_user.surname) AS fullname,
		meeting_user_type.user_type_name AS position');
        $this->db->from('record');
		$this->db->where('reply_id IS NULL');
		$this->db->join('meeting_user','meeting_user.user_id = meeting_record.user_id','LEFT');
		$this->db->join('meeting_user2present','meeting_user2present.meeting_id = meeting_record.meeting_id
		AND meeting_user2present.user_id = meeting_record.user_id','LEFT');
		$this->db->join('meeting_user_type','meeting_user2present.user_type_id = meeting_user_type.user_type_id','LEFT');
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
			$this->db->order_by('create_date');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}

        $parent = $this->db->get();

        $records = $parent->result();
        $i=0;
        foreach($records as $record){

            $records[$i]->reply = $this->getSubRecords($record->record_id,$cond,$order,$limit,$start);
            $i++;
        }
        return $records;
    }

    public function getSubRecords($id,$cond = array(), $order = array(), $limit = null, $start = 0){

        $this->db->select('record.*,CONCAT(meeting_user.`name`,\' \',meeting_user.surname) AS fullname,
		meeting_user_type.user_type_name AS position');
        $this->db->from('record');
		$this->db->where('reply_id', $id);
		$this->db->join('meeting_user','meeting_user.user_id = meeting_record.user_id','LEFT');
		$this->db->join('meeting_user2present','meeting_user2present.meeting_id = meeting_record.meeting_id
		AND meeting_user2present.user_id = meeting_record.user_id','LEFT');
		$this->db->join('meeting_user_type','meeting_user2present.user_type_id = meeting_user_type.user_type_id','LEFT');

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
			$this->db->order_by('create_date');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}

        $child = $this->db->get();
        $records = $child->result();
        $i=0;
        foreach($records as $record){

            $records[$i]->reply = $this->getSubRecords($record->record_id,$cond,$order,$limit,$start);
            $i++;
        }
        return $records;
	}

	public function getRecordFiles($cond = array(), $order = array())
	{
		$this->db->select('*');
		$this->db->from('record_file');
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
			$this->db->order_by('record_id');
			$this->db->order_by('record_file_id');
		}
		return $this->db->get()->result();
	}

	public function getUserType($cond = array(), $order = array(), $limit = null, $start = 0){
		$this->db->select('*');
		$this->db->from('user2present');
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
			$this->db->order_by('create_date');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}


	public function insertMinutes($data = array())
	{
		$this->db->set('meeting_id', $data['meeting_id']);
		$this->db->set('agenda_id', $data['agenda_id']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('minutes');
		return $this->db->insert_id();
	}

	public function updateMinutes($data = array()){
		$this->db->set('minutesdetail', $data['minutesdetail']);
		$this->db->set('conclusion', $data['conclusion']);
		$this->db->where('meeting_id', $data['meeting_id']);
		$this->db->where('agenda_id', $data['agenda_id']);
		$this->db->update('minutes');
		return $data;
	}

	public function getMeetingStatus($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('minutes');
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
			$this->db->order_by('create_date');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function saveRecordLog($data = array())
	{
		$this->db->select('*');
		$this->db->where('meeting_id', $data['meeting_id']);
		$this->db->where('agenda_id', $data['agenda_id']);
		$this->db->where('user_id', $data['user_id']);
		$this->db->from('record_log');
		if ($this->db->get()->num_rows() > 0) {//update
			$result = $this->updatetRecordLog($data);
		} else {//insert
			$result = $this->insertRecordLog($data);
		}
	}

	public function insertRecordLog($data = array())
	{
		$this->db->set('meeting_id', $data['meeting_id']);
		$this->db->set('agenda_id', $data['agenda_id']);
		$this->db->set('user_id', $data['user_id']);
		$this->db->set('time_in', $data['time_in']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('record_log');
		return $this->db->insert_id();
	}

	public function updatetRecordLog($data = array()){
		$this->db->set('time_out', $data['time_out']);
		$this->db->where('meeting_id', $data['meeting_id']);
		$this->db->where('agenda_id', $data['agenda_id']);
		$this->db->where('user_id', $data['user_id']);
		$this->db->update('record_log');
		return $data;
	}

	public function getRecordLog($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('record_log');
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
			$this->db->order_by('create_date');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function updatetRecordLogTimeAll($data = array()){
		$this->db->set('time_out', date('H:i'));
		$this->db->where('meeting_id', $data['meeting_id']);
		$this->db->where('agenda_id', $data['agenda_id']);
		$this->db->where('time_out', '');
		$this->db->update('record_log');
		return $data;
	}


	public function getReportAttend($meeting_id = null, $agenda_id = null){
		$this->db->select('meeting_user.prename,meeting_user.`name`,meeting_user.surname,meeting_record_log.time_in,meeting_record_log.time_out');
		$this->db->from('user2present');
		$this->db->join('meeting_user','user2present.user_id = meeting_user.user_id','left');
		$this->db->join('meeting_record_log','user2present.meeting_id = meeting_record_log.meeting_id AND user2present.user_id = meeting_record_log.user_id AND meeting_record_log.agenda_id = '.$agenda_id,'left');
		$this->db->where('user2present.meeting_id',$meeting_id);
		$this->db->group_by(array("user2present.meeting_id", "user2present.user_id"));

		if (!empty($order)) {
			foreach ($order as $k => $v) {
				if (is_string($k)) {
					$this->db->order_by($k, $v);
				} else {
					$this->db->order_by($v);
				}
			}
		} else {//default order
			$this->db->order_by('user2present.user_id');
		}

		return $this->db->get()->result();
	}
}
