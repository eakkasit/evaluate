<?php

class Commons_model extends CI_Model
{
	private $gender = array(
		'male' => 'ชาย',
		'female' => 'หญิง',
	);
	private $prename = array(
		1 => 'นาย',
		2 => 'นาง',
		3 => 'นางสาว',
	);
	public $dict_th = array();

	public function __construct()
	{
		$this->dict_th = array(
			'active' => 'ใช้งาน',
			'revoke' => 'ระงับ',
			'invoke' => 'ระงับ',
			'cancel' => 'ยกเลิก',
			'draft' => 'แบบร่าง',
			'pending' => 'พร้อมประชุม',
			'complete' => 'เสร็จสิ้น',
		);

		$this->project_status = array(
			'0' => 'ร่างโครงการ',
			'1' => 'อยู่ระหว่างดำเนินการ',
			'2' => 'ดำเนินการเสร็จสิ้น',
			'3' => 'ยกเลิก / ระงับ',
		);

		$this->year = array(
			'2562' => '2562',
			'2563' => '2563',
		);

		$this->active_status = array(
			'0' => 'ไม่ใช้งาน',
			'1' => 'ใช้งาน'
		);
	}

		public function getPrefixList($prename_id = null)
		{
			if ($prename_id != null) {
				if (isset($this->prename[$prename_id])) {
					return $this->prename[$prename_id];
				} else {
					return '';
				}
			} else {
				return $this->prename;
			}
		}


		public function getProjectStatus($status_id = null)
		{
			if ($status_id != null) {
				if (isset($this->project_status[$status_id])) {
					return $this->project_status[$status_id];
				} else {
					return '';
				}
			} else {
				return $this->project_status;
			}
		}

		public function getYearList($year = null)
		{
			if ($year != null) {
				if (isset($this->year[$year])) {
					return $this->year[$year];
				} else {
					return '';
				}
			} else {
				return $this->year;
			}
		}

		public function getActiveList($active = null)
		{
			if ($active != null) {
				if (isset($this->active_status[$active])) {
					return $this->active_status[$active];
				} else {
					return '';
				}
			} else {
				return $this->active_status;
			}
		}

	public function getGenderList($gender_id = null)
	{
		if ($gender_id != null) {
			if (isset($this->gender[$gender_id])) {
				return $this->gender[$gender_id];
			} else {
				return '';
			}
		} else {
			return $this->gender;
		}
	}

	public function getUserStatusList()
	{
		return $this->getEnumOptions('user', 'user_status');
	}

	public function getGroupStatusList()
	{
		return $this->getEnumOptions('group', 'group_status');
	}

	public function getDataStatusList()
	{
		return $this->getEnumOptions('data', 'meeting_status');
	}

	public function getPresentStatusList()
	{
		$list = array();
		$present_status = $this->db->select('*')->from('user_type')->order_by('user_type_id')->get()->result();
		if(!empty($present_status)){
			foreach($present_status as $val){
				$list[$val->user_type_id] = $val->user_type_name;
			}
		}
		return $list;
	}

	public function getEnumOptions($table = '', $filed = '', $translate = 'th')
	{
		$options = array();
		$type = $this->db->query("SHOW COLUMNS FROM {$this->db->dbprefix}{$table} WHERE Field = '{$filed}'")->row(0)->Type;
		preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
		$enum = explode("','", $matches[1]);
		if (!empty($enum)) {
			foreach ($enum as $val) {
				if ($translate == 'th' && isset($this->dict_th[$val])) {
					$options[$val] = $this->dict_th[$val];
				} else {
					$options[$val] = ucfirst($val);
				}
			}
		}
		return $options;
	}
}
