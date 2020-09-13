<?php

class CriteriaDatas_model extends CI_Model
{
	function __construct(){
			 parent::__construct();
			 //load our second db and put in $db2
			 $this->db_activity = $this->load->database('db_activity', TRUE);
	 }

	public function countCriteriaDatas($cond = array())
	{
		$this->db->select('*');
		$this->db->from('criteria_data');
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

	public function getCriteriaDatas($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('criteria_data');
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

	public function insertCriteriaDatas($data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria_data');
		return $this->db->insert_id();
	}

	public function updateCriteriaDatas($profile_id = null, $data = array())
	{
		$this->db->set('name', $data['name']);
		$this->db->set('profile_id', $data['profile_id']);
		$this->db->set('detail', $data['detail']);
		$this->db->where('id', $profile_id);
		$this->db->update('criteria_data', $update);
		return $profile_id;
	}

	public function deleteCriteriaDatas($profile_id = null)
	{
		// $update = ['status' => '4'];
		$this->db->where('id', $profile_id);
		$this->db->delete('criteria_data', $update);
		return $profile_id;
	}

	public function insertCriteriaDataPoints($data = array())
	{
		$this->db->set('criteria_data_id', $data['criteria_data_id']);
		$this->db->set('criteria_id', $data['criteria_id']);
		$this->db->set('criteria_parent_id', $data['criteria_parent_id']);
		$this->db->set('criteria_name', $data['criteria_name']);
		$this->db->set('project_id', $data['project_id']);
		$this->db->set('result', $data['result']);
		$this->db->set('percent', $data['percent']);
		$this->db->set('weight', $data['weight']);
		$this->db->set('total', $data['total']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria_data_point');
		return $this->db->insert_id();
	}

	public function updateCriteriaDataPoints($id = null, $data = array())
	{
		$this->db->set('criteria_data_id', $data['criteria_data_id']);
		$this->db->set('criteria_id', $data['criteria_id']);
		$this->db->set('criteria_parent_id', $data['criteria_parent_id']);
		$this->db->set('criteria_name', $data['criteria_name']);
		$this->db->set('project_id', $data['project_id']);
		$this->db->set('result', $data['result']);
		$this->db->set('percent', $data['percent']);
		$this->db->set('weight', $data['weight']);
		$this->db->set('total', $data['total']);
		$this->db->where('id', $id);
		$this->db->update('criteria_data_point');
		return $id;
	}

	public function insertResult($data=array())
	{
		$this->db->set('project_id', $data['project_id']);
		$this->db->set('task_id', $data['task_id']);
		$this->db->set('year', $data['year']);
		$this->db->set('result', $data['result']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('data_result');
		$this->sumReult($data['project_id'],$data['task_id']);
		return $this->db->insert_id();
	}

	public function sumReult($project_id,$task_id)
	{
		// find parent
		$this->db_activity->select('*');
		$this->db_activity->where('project_id',$project_id);
		$this->db_activity->where('task_id',$task_id);
		$this->db_activity->from('task');
		$activity_parent = $this->db_activity->get()->row();
		$activity_parent_id = $activity_parent->parent_task_id;

		// find task id
		$this->db_activity->select('task_id');
		$this->db_activity->where('parent_task_id',$activity_parent_id);
		$this->db_activity->from('task');
		$activity_list_res = $this->db_activity->get()->result();
		$activity_list = array();

		if(isset($activity_list_res) && !empty($activity_list_res)){
			foreach ($activity_list_res as $key => $value) {
				$activity_list[$key] = $value->task_id;
			}
		}

		// check parant in data result
		$this->db->select('*');
		$this->db->where('project_id',$project_id);
		$this->db->where('task_id',$activity_parent_id);
		$this->db->from('data_result');
		$task_parent_id = $this->db->get()->row();

		// sum data result
		$this->db->select('sum(result) AS result');
		$this->db->where('project_id',$project_id);
		$this->db->where_in('task_id',$activity_list);
		$this->db->from('data_result');
		$sum_result = $this->db->get()->row()->result;


		// $this->db->query();

		// insert parent data result
		if(isset($task_parent_id) && !empty($task_parent_id)){
			$this->db->set('project_id', $activity_parent->project_id);
			$this->db->set('task_id', $activity_parent_id);
			$this->db->set('year', $activity_parent->task_year);
			$this->db->set('result', $sum_result);
			$this->db->where('id', $task_parent_id->id);
			$this->db->update('data_result');
		}else{
			$this->db->set('project_id', $activity_parent->project_id);
			$this->db->set('task_id', $activity_parent_id);
			$this->db->set('year', $activity_parent->task_year);
			$this->db->set('result', $sum_result);
			$this->db->set('create_date', 'NOW()', false);
			$this->db->insert('data_result');
		}


	}


	public function updateResult($id,$data=array())
	{
		$this->db->set('project_id', $data['project_id']);
		$this->db->set('task_id', $data['task_id']);
		$this->db->set('year', $data['year']);
		$this->db->set('result', $data['result']);
		$this->db->where('id', $id);
		$this->db->update('data_result');
		$this->sumReult($data['project_id'],$data['task_id']);
		return $id;
	}

	public function getResult($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('data_result');
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

	public function insertCriteriaResult($data = array())
	{

		$this->db->set('structure_id', $data['structure_id']);
		if(isset($data['tree_number'])){
			$this->db->set('tree_number', $data['tree_number']);
		}

		$this->db->set('tree_id', $data['tree_id']);
		$this->db->set('criteria_name', $data['criteria_name']);

		if(isset($data['project_id'])){
			$this->db->set('project_id', $data['project_id']);
		}

		if(isset($data['result'])){
			$this->db->set('result', $data['result']);
		}

		if(isset($data['percent'])){
			$this->db->set('percent', $data['percent']);
		}

		if(isset($data['weight'])){
			$this->db->set('weight', $data['weight']);
		}

		if(isset($data['total'])){
			$this->db->set('total', $data['total']);
		}
		//
		// $this->db->set('project_id', $data['project_id']);
		// $this->db->set('result', $data['result']);
		// $this->db->set('percent', $data['percent']);
		// $this->db->set('weight', $data['weight']);
		// $this->db->set('total', $data['total']);
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria_result');
		return $this->db->insert_id();
	}

	public function getCriteriaDataResult($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('criteria_result');
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

}
