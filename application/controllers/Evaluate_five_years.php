<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluate_five_years extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Activities_model','CriteriaDatas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("evaluate_five_years/dashboard_evaluate_five_years"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element')['text'] && !empty($fields)) {
			$search_text = explode(' ', $this->input->post('form_search_element')['text']);
			$cond_str = "( ";
			foreach ($search_text as $text) {
				$text = trim($text);
				if ($text != '') {
					foreach ($fields as $field) {
						$cond_str .= "{$field} LIKE '%{$text}%' OR ";
					}
				}
			}
			$cond = array(substr($cond_str, 0, -3) . " )");
		}
		return $cond;
	}

	public function dashboard_evaluate_five_years()
	{
		$cond = $this->search_form(array('project_name'));
		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("evaluate_datas/dashboard_evaluate_datas");
		$search_year_start = date('Y');
		$search_year_end = date('Y')+4;
		if(isset($_POST['search_year_start']) &&  isset($_POST['search_year_end'])){
			$search_year_start = $_POST['search_year_start'];
			$search_year_end = $_POST['search_year_end'];
			$con = "year BETWEEN '{$search_year_start}' AND '{$search_year_end}'";
			array_push($cond,$con);

		}else if(isset($_POST['search_year_start'])){
			$search_year_start = $_POST['search_year_start'];
			$con = "year >= '{$search_year_start}'";
			array_push($cond,$con);
		}else if(isset($_POST['search_year_end'])){
			$search_year_end = $_POST['search_year_end'];
			$con = "year <= '{$search_year_end}'";
			array_push($cond,$con);
		}else{

		}
		$count_rows = $this->Activities_model->countActivities($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$target_data_temp = $this->CriteriaDatas_model->getTarget();
		$target_data = array();
		if(isset($target_data_temp) && !empty($target_data_temp)){
			foreach ($target_data_temp as $key => $value) {
				$target_data[$value->project_id][$value->year] = $value->target;
			}
		}
		$result_data_temp = $this->CriteriaDatas_model->getResult();
		$result_data = array();
		if(isset($result_data_temp) && !empty($result_data_temp)){
			foreach ($result_data_temp as $key => $value) {
				$result_data[$value->project_id][$value->year] = $value->assessment_results;
			}
		}

		$data = $this->Activities_model->getActivities($cond, array('year'=>'DESC'));
		$data_point = $this->calculateScore($data,$target_data,$result_data,$search_year_start,$search_year_end);




		// $weight_result = array();
		// $point_result = array();

		// $weight_diff = array(); //น้ำหนักส่วนต่าง
		// $point_diff = array(); // คะแนนส่วนต่าง
		// $result = array(); // ร้อยละความสำเร็จ
		//
		// $point_new = array(); // คะแนนเต็มใหม่
		// $weight_total = array();//น้ำหนักรวม
		// echo "<pre>";
		// print_r($data_point);
		// echo "</pre>";
		// die();



		$data['content_data'] = array(
			'datas'=>$data,
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
			'year_list' => $this->Commons_model->getYearList(),
			'search_year_start' => $search_year_start,
			'search_year_end' => $search_year_end,
			'target_data' => $target_data,
			'result_data' => $result_data,
			'data_detail' => $data_point
		);

		$data['content_view'] = 'pages/dashboard_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}

	private function calculateScore($data,$target_data,$result_data,$search_year_start,$search_year_end)
	{
		$target = array(); //เป้าหมายร้อยล่ะ
		$score = array(); // คะแนนที่ทำได้
		// $weight = array(); // น้ำหนัก
		$point = array(); // คะแนนเต็ม
		$weight_per_year = array(); //น้ำหนักรายปี
		$weight_result = array(); // น้ำหนักที่ได้
		$point_result = array();
		$weight_diff = array(); //น้ำหนักส่วนต่าง
		$point_diff = array(); // คะแนนส่วนต่าง
		$result = array(); // ร้อยละความสำเร็จ

		$point_new = array(); // คะแนนเต็มใหม่
		$weight_total = array();//น้ำหนักรวม
		if(!empty($data)){

			foreach ($data as $activity_key => $activity_data) {
				$weight_all = $activity_data->weight;

				for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {

					// หาค่าเป้าหมายร้อยละ
					if(isset($target_data[$activity_data->id][$search_year_start+$i])){
						$target[$activity_data->id][$search_year_start+$i] = $target_data[$activity_data->id][$search_year_start+$i];
						// คะแนนเต็ม
						$point[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];
					}else{
						$target[$activity_data->id][$search_year_start+$i] = '';
						// คะแนนเต็ม
						$point[$activity_data->id][$search_year_start+$i] = '';
					}

					// หาค่าคะแนนที่ได้
					if(isset($result_data[$activity_data->id][$search_year_start+$i])){
						$score[$activity_data->id][$search_year_start+$i] = $result_data[$activity_data->id][$search_year_start+$i];
					}else{
						$score[$activity_data->id][$search_year_start+$i] = '';
					}

					// หาค่าน้ำหนักรายปี
					if($target[$activity_data->id][$search_year_start+$i] != 0 && $weight_all != 0){
						$weight_per_year[$activity_data->id][$search_year_start+$i] = ($weight_all * ($target[$activity_data->id][$search_year_start+$i]))/100;
					}else{
						$weight_per_year[$activity_data->id][$search_year_start+$i] = '';
					}



					if($search_year_start+$i == $activity_data->year_start ){
						// น้ำหนักที่ได้
						if($point[$activity_data->id][$search_year_start+$i] !='' && $point[$activity_data->id][$search_year_start+$i] != 0){
							$weight_result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * $weight_per_year[$activity_data->id][$search_year_start+$i]) / $point[$activity_data->id][$search_year_start+$i];

							$result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * 100) / $point[$activity_data->id][$search_year_start+$i];
						}else{
							$weight_result[$activity_data->id][$search_year_start+$i] = '';
							$result[$activity_data->id][$search_year_start+$i] = '';
						}

						// ส่วนต่างน้ำหนัก
						$weight_diff[$activity_data->id][$search_year_start+$i] = $weight_per_year[$activity_data->id][$search_year_start+$i] - $weight_result[$activity_data->id][$search_year_start+$i];

						// ส่วนต่างคะแนน
						$point_diff[$activity_data->id][$search_year_start+$i] = $point[$activity_data->id][$search_year_start+$i] - $score[$activity_data->id][$search_year_start+$i];



					}else if(($search_year_start+$i > $activity_data->year_start) && ($search_year_start+$i <= $activity_data->year_end) && ($activity_data->year_start != $activity_data->year_end)){
						// echo "<pre>";
						// echo $search_year_start+$i;
						// echo $search_year_start+$i-1;
						// print_r($weight_diff);
						// น้ำหนักรวม
						$weight_total[$activity_data->id][$search_year_start+$i] = $weight_per_year[$activity_data->id][$search_year_start+$i] + $weight_diff[$activity_data->id][$search_year_start+$i-1];
						// หาคะแนนเต็มใหม่
						$point_new[$activity_data->id][$search_year_start+$i] = $point[$activity_data->id][$search_year_start+$i] + $point_diff[$activity_data->id][$search_year_start+$i-1];

						// น้ำหนักที่ได้
						if($point_new[$activity_data->id][$search_year_start+$i] !='' && $point_new[$activity_data->id][$search_year_start+$i] != 0){
							$weight_result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * $weight_total[$activity_data->id][$search_year_start+$i]) / $point_new[$activity_data->id][$search_year_start+$i];

							$result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * 100) / $point_new[$activity_data->id][$search_year_start+$i];
						}else{
							$weight_result[$activity_data->id][$search_year_start+$i] = '';
							$result[$activity_data->id][$search_year_start+$i] = '';
						}

						// ส่วนต่างน้ำหนัก
						$weight_diff[$activity_data->id][$search_year_start+$i] = $weight_total[$activity_data->id][$search_year_start+$i] - $weight_result[$activity_data->id][$search_year_start+$i];

						// ส่วนต่างคะแนน
						$point_diff[$activity_data->id][$search_year_start+$i] = $point_new[$activity_data->id][$search_year_start+$i] - $score[$activity_data->id][$search_year_start+$i];

					}else{
						if($weight_diff[$activity_data->id][$search_year_start+$i-1] != 0 && $weight_diff[$activity_data->id][$search_year_start+$i-1] != ''){
							$weight_total[$activity_data->id][$search_year_start+$i] = $weight_diff[$activity_data->id][$search_year_start+$i-1];
							$weight_result[$activity_data->id][$search_year_start+$i] = $weight_diff[$activity_data->id][$search_year_start+$i-1];
							$point_new[$activity_data->id][$search_year_start+$i] = $point_diff[$activity_data->id][$search_year_start+$i-1];
							$target[$activity_data->id][$search_year_start+$i] = 0;
							$weight_per_year[$activity_data->id][$search_year_start+$i] = 0;
							$point[$activity_data->id][$search_year_start+$i] = 0;
							$weight_diff[$activity_data->id][$search_year_start+$i] = 0;
							$point_diff[$activity_data->id][$search_year_start+$i] = 0;
							$result[$activity_data->id][$search_year_start+$i] = 100;
							$score[$activity_data->id][$search_year_start+$i] = $point_new[$activity_data->id][$search_year_start+$i];
						}else{
							$weight_total[$activity_data->id][$search_year_start+$i] = '';
							$point_new[$activity_data->id][$search_year_start+$i] = '';
							$weight_diff[$activity_data->id][$search_year_start+$i] = '';
							$point_diff[$activity_data->id][$search_year_start+$i] = '';
						}




					}


				}

			}
		}
		$result_temp = array();
		$result_temp['target'] = $target;
		$result_temp['score'] = $score;
		// $result_temp['weight'] = $weight;
		$result_temp['point'] = $point;
		$result_temp['weight_per_year'] = $weight_per_year;
		$result_temp['weight_result'] = $weight_result;
		$result_temp['weight_diff'] = $weight_diff;
		$result_temp['point_diff'] = $point_diff;
		$result_temp['result'] = $result;
		$result_temp['point_new'] = $point_new;
		$result_temp['weight_total'] = $weight_total;
		return $result_temp;
	}

	public function view_evaluate_five_year($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/view_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}

	public function new_evaluate_five_year($id = null)
	{
		$data['content_data'] = array(
		);
		$data['content_view'] = 'pages/form_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}

	public function edit_evaluate_five_year($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}


	public function save($result_id = null)
	{
		// echo "<pre>";
		// print_r($_POST);
		$result_array = array();
		$target_array = array();
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well
		foreach ($_POST['data'] as $key => $value) {
			if($key == 'target'){
				foreach ($value as $key_target => $value_target) {
					foreach ($value_target as $key_profile => $target) {
						$target_array['project_id'] = $key_target;
						$target_array['year'] = $key_profile;
						$target_array['target'] = $target;
						$this->CriteriaDatas_model->replaceTarget($target_array);
					}
				}
			}else{
				foreach ($value as $key_result => $value_result) {
					foreach ($value_result as $key_r_profile => $result) {
						$result_array['project_id'] = $key_result;
						$result_array['year'] = $key_r_profile;
						$result_array['result'] = $result;
						$this->CriteriaDatas_model->replaceResult($result_array);
					}
				}
			}
		}
		$this->db->trans_complete(); # Completing transaction

		/*Optional*/

		if ($this->db->trans_status() === FALSE) {
		    # Something went wrong.
		    $this->db->trans_rollback();
				redirect(base_url("evaluate_five_years/dashboard_evaluate_five_years"));
				exit;
		}
		else {
		    # Everything is Perfect.
		    # Committing data to the database.
		    $this->db->trans_commit();
				redirect(base_url("evaluate_five_years/dashboard_evaluate_five_years"));
				exit;
		}
	}


	public function delete_evaluate_five_year($id = null)
	{
		redirect(base_url("evaluate_five_years/dashboard_evaluate_five_years"));
		exit;
	}


}
