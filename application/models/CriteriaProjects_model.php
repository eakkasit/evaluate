<?php

class CriteriaProjects_model extends CI_Model
{

	public function countCriteriaProjects($cond = array())
	{
		$this->db->select('*');
		$this->db->from('criteria_project');
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

	public function getCriteriaProjects($cond = array(), $order = array(), $limit = null, $start = 0)
	{
		$this->db->select('*');
		$this->db->from('criteria_project');
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

	public function getCriteriaProjectLists()
	{
		$list = array();
		$profile_list = $this->db->select('*')->from('criteria_project')->order_by('id')->get()->result();
		if(!empty($profile_list)){
			foreach($profile_list as $val){
				$list[$val->id] = $val->project_name;
			}
		}
		return $list;
	}

	public function saveProjectdata($data=array())
	{
		$this->db->select('*');
		$this->db->where('structure_id',$data['structure_id']);
		$this->db->where('tree_id',$data['tree_id']);
		$this->db->from('criteria_project');
		$check_data = $this->db->get()->num_rows();

		if($check_data > 0){
			$this->updateCriteriaProjects($data);
		}else{
			$this->insertCriteriaProjects($data);
		}

	}

	public function insertCriteriaProjects($data = array())
	{
		$this->db->set('project_data', $data['project_data']);
		$this->db->set('bsc_data', $data['bsc_data']);
		$this->db->set('task_data', $data['task_data']);
		$this->db->set('other_data', $data['other_data']);
		$this->db->set('structure_id', $data['structure_id']);
		$this->db->set('tree_id', $data['tree_id']);
		if(isset($data['result_id'])){
			$this->db->set('result_id', $data['result_id']);
		}
		$this->db->set('create_date', 'NOW()', false);
		$this->db->insert('criteria_project');
		return $this->db->insert_id();
	}

	public function updateCriteriaProjects($data = array())
	{
		$this->db->set('project_data', $data['project_data']);
		$this->db->set('bsc_data', $data['bsc_data']);
		$this->db->set('task_data', $data['task_data']);
		$this->db->set('other_data', $data['other_data']);
		$this->db->set('structure_id', $data['structure_id']);
		$this->db->set('tree_id', $data['tree_id']);
		if(isset($data['result_id'])){
			$this->db->set('result_id', $data['result_id']);
		}
		$this->db->where('structure_id', $data['structure_id']);
		$this->db->where('tree_id', $data['tree_id']);
		return $this->db->update('criteria_project');
		// return $project_id;
	}

	public function deleteCriteriaProjects($project_id = null)
	{
		// $update = ['status' => '4'];
		$this->db->where('id', $project_id);
		$this->db->delete('criteria_project');
		return $project_id;
	}
}
