<?php

class Activities_model extends CI_Model
{
	// private $default_user_type_id = 5;
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db = $this->load->database('db_activity', TRUE);
	 }

	public function countActivities($cond = array())
	{
		$this->db->select('*');
		$this->db->from('project');
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

	public function getActivities($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('project');
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
			$this->db->order_by('create_dt', 'desc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}

	public function getActivityLists($cond=array(),$order = array())
	{
		$list = array();
		$this->db->select('*');
		$this->db->from('project');
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
			$this->db->order_by('id', 'asc');
		}
		$profile_list = $this->db->get()->result();

		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->id] = $val->project_name;
			}
		}
		return $list;
	}

	public function insertActivities($data = array())
	{
		$this->db->set('project_name', $data['project_name']);
		$this->db->set('year', $data['year']);
		$this->db->set('date_start', $data['date_start']);
		$this->db->set('date_end', $data['date_end']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('status', $data['status']);

		$this->db->set('create_dt', 'NOW()', false);
		$this->db->insert('project');
		return $this->db->insert_id();
	}

	public function updateActivities($project_id = null, $data = array())
	{
		$this->db->set('project_name', $data['project_name']);
		$this->db->set('year', $data['year']);
		$this->db->set('date_start', $data['date_start']);
		$this->db->set('date_end', $data['date_end']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('status', $data['status']);
		$this->db->where('id', $project_id);
		$this->db->update('project', $update);
		return $project_id;
	}

	public function deleteActivities($project_id = null)
	{
		$update = ['status' => '4'];
		$this->db->where('id', $project_id);
		$this->db->update('project', $update);
		return $project_id;
	}

	public function getTargetTask($project_id='')
	{
		$this->db->select('SUM(weight) AS weight');
		$this->db->where('project_id',$project_id);
		$this->db->where('parent_task_id','0');
		$this->db->from('task');
		return $this->db->get()->result();
	}

	public function getTasks($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('task');
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
			$this->db->order_by('task_id', 'asc');
		}
		if ($limit != null) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result();
	}



}
