<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_five_years extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation','m_pdf','excel'));
		$this->load->model(array('Commons_model', 'Activities_model','CriteriaDatas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("report_five_years/dashboard_report_five_years"));
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

	public function dashboard_report_five_years()
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

		$data_temp = array();
		$project_list = $this->Activities_model->getActivities($cond, array('year'=>'DESC'));
		if(count($project_list) > 0){
			foreach ($project_list as $key => $value) {
				$data_temp[$value->id][$value->year] = $this->Activities_model->getTargetTask($value->id)[0]->weight;
			}
		}

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
		$year_start = date('Y');
		$year_end = date('Y')+5;

		$data_point = $this->calculateScore($project_list,$target_data,$result_data,$search_year_start,$search_year_end);

		$data['content_data'] = array(
			'project_list'=>$project_list,
			'data'=>$data_temp,
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
			'target_data' => $target_data,
			'result_data' => $result_data,
			'year_start' => $year_start,
			'year_end' => $year_end,
			'year_list' => $this->Commons_model->getYearList(),
			'search_year_start' => $search_year_start,
			'search_year_end' => $search_year_end,
			'data_detail' => $data_point
		);
		// echo "<pre>";
		// print_r($data['content_data']);
		// die();
		$data['content_view'] = 'pages/dashboard_report_five_years';
		$this->load->view($this->theme, $data);
	}

	public function view_reports_assessment($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/view_reports_assessment';
		$this->load->view($this->theme, $data);
	}

	public function new_reports_assessment($id = null)
	{
		$data['content_data'] = array(
		);
		$data['content_view'] = 'pages/form_reports_assessment';
		$this->load->view($this->theme, $data);
	}

	public function edit_reports_assessment($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_reports_assessment';
		$this->load->view($this->theme, $data);
	}



	public function delete_reports_assessment($id = null)
	{
		redirect(base_url("report_five_years/dashboard_report_five_years"));
		exit;
	}

	private function calculateScore($data,$target_data,$result_data,$search_year_start,$search_year_end)
	{
		$target = array(); //เป้าหมายร้อยล่ะ
		$target_total = array(); //เป้าหมายร้อยล่ะรวม
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
				$target_all = 0;
				for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {

					// หาค่าเป้าหมายร้อยละ
					if(isset($target_data[$activity_data->id][$search_year_start+$i])){
						if($target_data[$activity_data->id][$search_year_start+$i] != '' && $target_data[$activity_data->id][$search_year_start+$i] != 0){
							$target[$activity_data->id][$search_year_start+$i] = $target_data[$activity_data->id][$search_year_start+$i];
							if(isset($target[$activity_data->id][$search_year_start+$i-1])){
								$target_total[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i] + $target[$activity_data->id][$search_year_start+$i-1];
							}else{
								$target_total[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];
							}

						}else{
							$target[$activity_data->id][$search_year_start+$i] = '';
						}

						// คะแนนเต็ม
						$point[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];
						$target_all += $target[$activity_data->id][$search_year_start+$i];
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

					// echo "search_year_start = ".($search_year_start+$i);
					// echo "<hr/>";
					// echo "year_start = ".$activity_data->year_start;
					// echo "<hr/>";
					// echo "year_end = ".$activity_data->year_end;
					// echo "<hr/>";

					// echo "<hr/>";
					// var_dump(($activity_data->year_start >= $search_year_start+$i ) && ($search_year_start+$i < $activity_data->year_end) && ($activity_data->year_start != $activity_data->year_end));
					// echo " year > $activity_data->year_start >= ".($search_year_start+$i);
					// echo " year < (".($search_year_start+$i)." < $activity_data->year_end)" ;
					// echo "<hr/>";

					if($search_year_start+$i == $activity_data->year_start ){
						// $target_total[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];

						// น้ำหนักที่ได้
						if($point[$activity_data->id][$search_year_start+$i] !='' && $point[$activity_data->id][$search_year_start+$i] != 0){
							$weight_result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * $weight_per_year[$activity_data->id][$search_year_start+$i]) / $point[$activity_data->id][$search_year_start+$i];

							$result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * 100) / $point[$activity_data->id][$search_year_start+$i];
						}else{
							$weight_result[$activity_data->id][$search_year_start+$i] = '';
							$result[$activity_data->id][$search_year_start+$i] = '';
						}

						if($target[$activity_data->id][$search_year_start+$i] != ''){
							// ส่วนต่างน้ำหนัก
							$weight_diff[$activity_data->id][$search_year_start+$i] = $weight_per_year[$activity_data->id][$search_year_start+$i] - $weight_result[$activity_data->id][$search_year_start+$i];

							// ส่วนต่างคะแนน
							$point_diff[$activity_data->id][$search_year_start+$i] = $point[$activity_data->id][$search_year_start+$i] - $score[$activity_data->id][$search_year_start+$i];
						}else{
							// ส่วนต่างน้ำหนัก
							$weight_diff[$activity_data->id][$search_year_start+$i] = '';

							// ส่วนต่างคะแนน
							$point_diff[$activity_data->id][$search_year_start+$i] = '';
						}




					}else if(($search_year_start+$i >= $activity_data->year_start) && ($search_year_start+$i <= $activity_data->year_end) && ($activity_data->year_start != $activity_data->year_end)){
						// echo "<pre>";
						// echo $search_year_start+$i;
						// echo $search_year_start+$i-1;
						// print_r($weight_diff);
						// $target_total[$activity_data->id][$search_year_start+$i] += $target[$activity_data->id][$search_year_start+$i];

						if($search_year_start+$i == $activity_data->year_end){
							if($target[$activity_data->id][$search_year_start+$i] == ''){
								if( (100 - $target_all) < 0){
										$target[$activity_data->id][$search_year_start+$i] = 0;
										$weight_per_year[$activity_data->id][$search_year_start+$i] = ($weight_all * ($target[$activity_data->id][$search_year_start+$i]))/100;
										$point[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];
									}else{
										$target[$activity_data->id][$search_year_start+$i] = 100 - $target_all;
										$weight_per_year[$activity_data->id][$search_year_start+$i] = ($weight_all * ($target[$activity_data->id][$search_year_start+$i]))/100;
										$point[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];
									}
							}
						}
						if(isset($target_total[$activity_data->id][$search_year_start+$i-1])){
								$target_total[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i] + $target_total[$activity_data->id][$search_year_start+$i-1];
						}else{
							$target_total[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i] ;
						}

						if(!isset($weight_diff[$activity_data->id][$search_year_start+$i-1])){
							$weight_diff[$activity_data->id][$search_year_start+$i-1] = '';
						}

						if(!isset($point_diff[$activity_data->id][$search_year_start+$i-1])){
							$point_diff[$activity_data->id][$search_year_start+$i-1] = '';
						}
						// น้ำหนักรวม
						$weight_total[$activity_data->id][$search_year_start+$i] = $weight_per_year[$activity_data->id][$search_year_start+$i] + $weight_diff[$activity_data->id][$search_year_start+$i-1];
						// echo "$weight_total";
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

					}
					// else if(($search_year_start+$i == $activity_data->year_end) && ($activity_data->year_start != $activity_data->year_end) ){
					// 	// $weight_total[$activity_data->id][$search_year_start+$i] = ;
					// 	if($target[$activity_data->id][$search_year_start+$i] == ''){
					//
					// 		if( (100 - $weight_all) < 0){
					// 			$target[$activity_data->id][$search_year_start+$i] = 0;
					// 		}else{
					// 			$target[$activity_data->id][$search_year_start+$i] = 100 - $weight_all;
					// 		}
					//
					// 	}
					//
					// }
					else if(($search_year_start+$i > $activity_data->year_end) ){
						if((($search_year_start+$i)-$activity_data->year_end) > 1){
							$target[$activity_data->id][$search_year_start+$i] = '';
						}else{
							if($target[$activity_data->id][$search_year_start+$i-1] != ''){
								$target[$activity_data->id][$search_year_start+$i] = (100 - $target_all);

								$weight_per_year[$activity_data->id][$search_year_start+$i] = ($weight_all * ($target[$activity_data->id][$search_year_start+$i]))/100;
								$point[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];

								if(isset($target_total[$activity_data->id][$search_year_start+$i-1])){
										$target_total[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i] + $target_total[$activity_data->id][$search_year_start+$i-1];
								}else{
									$target_total[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i] ;
								}

								if(!isset($weight_diff[$activity_data->id][$search_year_start+$i-1])){
									$weight_diff[$activity_data->id][$search_year_start+$i-1] = '';
								}

								if(!isset($point_diff[$activity_data->id][$search_year_start+$i-1])){
									$point_diff[$activity_data->id][$search_year_start+$i-1] = '';
								}
								// น้ำหนักรวม
								$weight_total[$activity_data->id][$search_year_start+$i] = $weight_per_year[$activity_data->id][$search_year_start+$i] + $weight_diff[$activity_data->id][$search_year_start+$i-1];
								// echo "$weight_total";
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
							}

						}

					}
					else{

						// if($weight_diff[$activity_data->id][$search_year_start+$i-1] != 0 && $weight_diff[$activity_data->id][$search_year_start+$i-1] != ''){
						// 	$weight_total[$activity_data->id][$search_year_start+$i] = $weight_diff[$activity_data->id][$search_year_start+$i-1];
						// 	$weight_result[$activity_data->id][$search_year_start+$i] = $weight_diff[$activity_data->id][$search_year_start+$i-1];
						// 	$point_new[$activity_data->id][$search_year_start+$i] = $point_diff[$activity_data->id][$search_year_start+$i-1];
						// 	$target[$activity_data->id][$search_year_start+$i] = 0;
						// 	$weight_per_year[$activity_data->id][$search_year_start+$i] = 0;
						// 	$point[$activity_data->id][$search_year_start+$i] = 0;
						// 	$weight_diff[$activity_data->id][$search_year_start+$i] = 0;
						// 	$point_diff[$activity_data->id][$search_year_start+$i] = 0;
						// 	$result[$activity_data->id][$search_year_start+$i] = 100;
						// 	$score[$activity_data->id][$search_year_start+$i] = $point_new[$activity_data->id][$search_year_start+$i];
						// }else{
						// 	$weight_total[$activity_data->id][$search_year_start+$i] = '';
						// 	$point_new[$activity_data->id][$search_year_start+$i] = '';
						// 	$weight_diff[$activity_data->id][$search_year_start+$i] = '';
						// 	$point_diff[$activity_data->id][$search_year_start+$i] = '';
						// }




					}


				}

			}
		}
		$result_temp = array();
		$result_temp['target'] = $target;
		$result_temp['target_total'] = $target_total;
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

	// private function calculateScore($data,$target_data,$result_data,$search_year_start,$search_year_end)
	// {
	// 	$target = array(); //เป้าหมายร้อยล่ะ
	// 	$score = array(); // คะแนนที่ทำได้
	// 	// $weight = array(); // น้ำหนัก
	// 	$point = array(); // คะแนนเต็ม
	// 	$weight_per_year = array(); //น้ำหนักรายปี
	// 	$weight_result = array(); // น้ำหนักที่ได้
	// 	$point_result = array();
	// 	$weight_diff = array(); //น้ำหนักส่วนต่าง
	// 	$point_diff = array(); // คะแนนส่วนต่าง
	// 	$result = array(); // ร้อยละความสำเร็จ
	//
	// 	$point_new = array(); // คะแนนเต็มใหม่
	// 	$weight_total = array();//น้ำหนักรวม
	// 	if(!empty($data)){
	//
	// 		foreach ($data as $activity_key => $activity_data) {
	// 			$weight_all = $activity_data->weight;
	//
	// 			for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
	//
	// 				// หาค่าเป้าหมายร้อยละ
	// 				if(isset($target_data[$activity_data->id][$search_year_start+$i])){
	// 					$target[$activity_data->id][$search_year_start+$i] = $target_data[$activity_data->id][$search_year_start+$i];
	// 					// คะแนนเต็ม
	// 					$point[$activity_data->id][$search_year_start+$i] = $target[$activity_data->id][$search_year_start+$i];
	// 				}else{
	// 					$target[$activity_data->id][$search_year_start+$i] = '';
	// 					// คะแนนเต็ม
	// 					$point[$activity_data->id][$search_year_start+$i] = '';
	// 				}
	//
	// 				// หาค่าคะแนนที่ได้
	// 				if(isset($result_data[$activity_data->id][$search_year_start+$i])){
	// 					$score[$activity_data->id][$search_year_start+$i] = $result_data[$activity_data->id][$search_year_start+$i];
	// 				}else{
	// 					$score[$activity_data->id][$search_year_start+$i] = '';
	// 				}
	//
	// 				// หาค่าน้ำหนักรายปี
	// 				if($target[$activity_data->id][$search_year_start+$i] != 0 && $weight_all != 0){
	// 					$weight_per_year[$activity_data->id][$search_year_start+$i] = ($weight_all * ($target[$activity_data->id][$search_year_start+$i]))/100;
	// 				}else{
	// 					$weight_per_year[$activity_data->id][$search_year_start+$i] = '';
	// 				}
	//
	//
	//
	// 				if($search_year_start+$i == $activity_data->year_start ){
	// 					// น้ำหนักที่ได้
	// 					if($point[$activity_data->id][$search_year_start+$i] !='' && $point[$activity_data->id][$search_year_start+$i] != 0){
	// 						$weight_result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * $weight_per_year[$activity_data->id][$search_year_start+$i]) / $point[$activity_data->id][$search_year_start+$i];
	//
	// 						$result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * 100) / $point[$activity_data->id][$search_year_start+$i];
	// 					}else{
	// 						$weight_result[$activity_data->id][$search_year_start+$i] = '';
	// 						$result[$activity_data->id][$search_year_start+$i] = '';
	// 					}
	//
	// 					// ส่วนต่างน้ำหนัก
	// 					$weight_diff[$activity_data->id][$search_year_start+$i] = $weight_per_year[$activity_data->id][$search_year_start+$i] - $weight_result[$activity_data->id][$search_year_start+$i];
	//
	// 					// ส่วนต่างคะแนน
	// 					$point_diff[$activity_data->id][$search_year_start+$i] = $point[$activity_data->id][$search_year_start+$i] - $score[$activity_data->id][$search_year_start+$i];
	//
	//
	//
	// 				}else if(($search_year_start+$i > $activity_data->year_start) && ($search_year_start+$i <= $activity_data->year_end) && ($activity_data->year_start != $activity_data->year_end)){
	// 					// echo "<pre>";
	// 					// echo $search_year_start+$i;
	// 					// echo $search_year_start+$i-1;
	// 					// print_r($weight_diff);
	// 					// น้ำหนักรวม
	// 					$weight_total[$activity_data->id][$search_year_start+$i] = $weight_per_year[$activity_data->id][$search_year_start+$i] + $weight_diff[$activity_data->id][$search_year_start+$i-1];
	// 					// หาคะแนนเต็มใหม่
	// 					$point_new[$activity_data->id][$search_year_start+$i] = $point[$activity_data->id][$search_year_start+$i] + $point_diff[$activity_data->id][$search_year_start+$i-1];
	//
	// 					// น้ำหนักที่ได้
	// 					if($point_new[$activity_data->id][$search_year_start+$i] !='' && $point_new[$activity_data->id][$search_year_start+$i] != 0){
	// 						$weight_result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * $weight_total[$activity_data->id][$search_year_start+$i]) / $point_new[$activity_data->id][$search_year_start+$i];
	//
	// 						$result[$activity_data->id][$search_year_start+$i] = ($score[$activity_data->id][$search_year_start+$i] * 100) / $point_new[$activity_data->id][$search_year_start+$i];
	// 					}else{
	// 						$weight_result[$activity_data->id][$search_year_start+$i] = '';
	// 						$result[$activity_data->id][$search_year_start+$i] = '';
	// 					}
	//
	// 					// ส่วนต่างน้ำหนัก
	// 					$weight_diff[$activity_data->id][$search_year_start+$i] = $weight_total[$activity_data->id][$search_year_start+$i] - $weight_result[$activity_data->id][$search_year_start+$i];
	//
	// 					// ส่วนต่างคะแนน
	// 					$point_diff[$activity_data->id][$search_year_start+$i] = $point_new[$activity_data->id][$search_year_start+$i] - $score[$activity_data->id][$search_year_start+$i];
	//
	// 				}else{
	// 					if($weight_diff[$activity_data->id][$search_year_start+$i-1] != 0 && $weight_diff[$activity_data->id][$search_year_start+$i-1] != ''){
	// 						$weight_total[$activity_data->id][$search_year_start+$i] = $weight_diff[$activity_data->id][$search_year_start+$i-1];
	// 						$weight_result[$activity_data->id][$search_year_start+$i] = $weight_diff[$activity_data->id][$search_year_start+$i-1];
	// 						$point_new[$activity_data->id][$search_year_start+$i] = $point_diff[$activity_data->id][$search_year_start+$i-1];
	// 						$target[$activity_data->id][$search_year_start+$i] = 0;
	// 						$weight_per_year[$activity_data->id][$search_year_start+$i] = 0;
	// 						$point[$activity_data->id][$search_year_start+$i] = 0;
	// 						$weight_diff[$activity_data->id][$search_year_start+$i] = 0;
	// 						$point_diff[$activity_data->id][$search_year_start+$i] = 0;
	// 						$result[$activity_data->id][$search_year_start+$i] = 100;
	// 						$score[$activity_data->id][$search_year_start+$i] = $point_new[$activity_data->id][$search_year_start+$i];
	// 					}else{
	// 						$weight_total[$activity_data->id][$search_year_start+$i] = '';
	// 						$point_new[$activity_data->id][$search_year_start+$i] = '';
	// 						$weight_diff[$activity_data->id][$search_year_start+$i] = '';
	// 						$point_diff[$activity_data->id][$search_year_start+$i] = '';
	// 					}
	//
	//
	//
	//
	// 				}
	//
	//
	// 			}
	//
	// 		}
	// 	}
	// 	$result_temp = array();
	// 	$result_temp['target'] = $target;
	// 	$result_temp['score'] = $score;
	// 	// $result_temp['weight'] = $weight;
	// 	$result_temp['point'] = $point;
	// 	$result_temp['weight_per_year'] = $weight_per_year;
	// 	$result_temp['weight_result'] = $weight_result;
	// 	$result_temp['weight_diff'] = $weight_diff;
	// 	$result_temp['point_diff'] = $point_diff;
	// 	$result_temp['result'] = $result;
	// 	$result_temp['point_new'] = $point_new;
	// 	$result_temp['weight_total'] = $weight_total;
	// 	return $result_temp;
	// }

	public function export($type = '')
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

		$data_temp = array();
		$project_list = $this->Activities_model->getActivities($cond, array('year'=>'DESC'));
		if(count($project_list) > 0){
			foreach ($project_list as $key => $value) {
				$data_temp[$value->id][$value->year] = $this->Activities_model->getTargetTask($value->id)[0]->weight;
			}
		}

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
		$year_start = date('Y');
		$year_end = date('Y')+5;

		$data_detail = $this->calculateScore($project_list,$target_data,$result_data,$search_year_start,$search_year_end);

		$data = array(
			'project_list'=>$project_list,
			'data'=>$data_temp,
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
			'target_data' => $target_data,
			'result_data' => $result_data,
			'year_start' => $year_start,
			'year_end' => $year_end,
			'year_list' => $this->Commons_model->getYearList(),
			'search_year_start' => $search_year_start,
			'search_year_end' => $search_year_end,
			'data_detail' => $data_detail
		);
		if($type == 'pdf'){
			$pdfFilePath = "รายงานโครงการ5ปี.pdf";
			$html = $this->load->view('pages/report_five_year_pdf', $data,true);
			$mpdf = new $this->m_pdf('"en-GB-x","A4-L","","",10,10,10,10,6,3,L');
			$mpdf->pdf->WriteHTML($html);
			$mpdf->pdf->Output($pdfFilePath, 'D');
			exit;
		}else if($type == 'word'){
			$this->load->view('pages/report_five_year_word', $data);
		}else if($type == 'excel'){
			// activate worksheet number 1
			$objWorkSheet = $this->excel->setActiveSheetIndex(0);
			//name the worksheet
			// $this->excel->getActiveSheet()->setTitle('test worksheet');
			//set cell A1 content with some text
			// $this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
			//change the font size
			// $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
			//make the font become bold
			// $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			//merge cell A1 until D1
			// $this->excel->getActiveSheet()->mergeCells('A1:D1');
			//set aligment to center for that merged cell (A1 to D1)
			// $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objWorkSheet->setCellValue('A1','ลำดับ');
			$objWorkSheet->setCellValue('B1','ชื่อโครงการ');
			$objWorkSheet->setCellValue('C1','ปีงบประมาณ');
			$objWorkSheet->setCellValue('D1','น้ำหนักโครงการ');
			$objWorkSheet->getColumnDimension('A')->setWidth('12');
			$objWorkSheet->getColumnDimension('B')->setWidth('50');
			$objWorkSheet->getColumnDimension('C')->setWidth('25');
			$objWorkSheet->getColumnDimension('D')->setWidth('25');

			$chr = 4;
			for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
				$chr++;
				$objWorkSheet->setCellValue($this->numberToColumn($chr).'1','เป้าหมายปี '.($search_year_start+$i+543));
				$objWorkSheet->getColumnDimension($this->numberToColumn($chr))->setWidth('20');
				$chr++;
				$objWorkSheet->setCellValue($this->numberToColumn($chr).'1','ผลการประเมิน');
				$objWorkSheet->getColumnDimension($this->numberToColumn($chr))->setWidth('20');
				$chr++;
				$objWorkSheet->setCellValue($this->numberToColumn($chr).'1','ร้อยละความสำเร็จ');
				$objWorkSheet->getColumnDimension($this->numberToColumn($chr))->setWidth('20');
			}
			$chr++;
			$objWorkSheet->setCellValue($this->numberToColumn($chr).'1','ร้อยละความสำเร็จทั้งโครงการ');
			$objWorkSheet->getColumnDimension($this->numberToColumn($chr))->setWidth('25');
			// $chr = 1;
			// foreach ($columns as $value) {
			// 	$objWorkSheet->setCellValue(numberToColumn($chr).'1', $value['name']);
			// 	$chr++;
			// }

			$no = 1;
			if (isset($project_list) && !empty($project_list)) {
				$index = 1;
				foreach ($project_list as $key => $data) {
					$index++;
					$year_show = '';
					if($data->year_start == $data->year_end){
						$year_show =  $data->year_start+543;
					}else{
						$year_start_show = $data->year_start+543;
						$year_end_show = $data->year_end+543;
						$year_show = "$year_start_show - $year_end_show" ;
					}
					$objWorkSheet->setCellValue('A'.$index,number_format($no + $key, 0));
					$objWorkSheet->setCellValue('B'.$index,$data->project_name);
					$objWorkSheet->setCellValue('C'.$index,$year_show);
					$objWorkSheet->setCellValue('D'.$index,$data->weight);
					$result_all = 0;
					$chr = 4;
					for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
						$target = isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:'';
						$score = isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:'';
						$result = isset($data_detail['result'][$data->id][$search_year_start+$i]) && $data_detail['result'][$data->id][$search_year_start+$i] != ''?number_format($data_detail['result'][$data->id][$search_year_start+$i],2):'';
						$result_all += $score;
						$chr++;
						$objWorkSheet->setCellValue($this->numberToColumn($chr).$index,$target);
						$chr++;
						$objWorkSheet->setCellValue($this->numberToColumn($chr).$index,$score);
						$chr++;
						$objWorkSheet->setCellValue($this->numberToColumn($chr).$index,$result);
					}
					$chr++;
					$objWorkSheet->setCellValue($this->numberToColumn($chr).$index,$result_all);

				}
			}
			$styleArray = array(
			    'borders' => array(
			        'allborders' => array(
			            'style' => PHPExcel_Style_Border::BORDER_THIN,
			        ),
			    ),
			    'alignment' => array(
			        'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
			    ),
			);
			$objWorkSheet->getStyle('A1:'.$this->numberToColumn($chr).$index)->applyFromArray($styleArray);
			// $objWorkSheet->getStyle('N2:N' . ($i - 1))->getAlignment()->setWrapText(true);

			// if($type == 'pdf'){
			// 	ini_set('memory_limit', '-1');
			// 	 ini_set('max_execution_time', 300);
			// 	 echo "ABSPATH".base_url();
			// 	 $filename = 'transaction_' . date('Ymdhis') . '.html';
			// 	 $path = base_url() . 'assets/tmp/';
			// 	 $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'HTML');
			// 	 $objWriter->save($path . $filename);
			// 	 $html = file_get_contents($path . $filename);
			// 	 unlink($path . $filename);
			// 	 $html = $this->pdfRemoveTag($html);
			// 	 $pdfFilePath = 'transaction.pdf';
			// 	 //
			// 	 // $mpdf = new Mpdf('"en-GB-x","A4","","",10,10,10,10,6,3');
			// 	 $htmlcontent = $this->AdjustHTML($html);
			// 	 $this->mpdf->param = ('"en-GB-x","A4","","",10,10,10,10,6,3,L');
			// 	 $this->mpdf->pdf->WriteHTML($htmlcontent);
			// 	 $this->mpdf->pdf->Output($pdfFilePath, 'D');
			// }else if($type == 'excel'){
				$filename='รายงานโครงการ5ปี.xls'; //save our workbook as this file name
				header('Content-Type: application/vnd.ms-excel'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0'); //no cache

				//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
				//if you want to save it as .XLSX Excel 2007 format
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
				//force user to download the Excel file without writing it to server's HD
				$objWriter->save('php://output');
			// }else if($type == 'word'){
			//
			// }else{
			// 	redirect(base_url("report_targets/view_report"));
			// 	exit;
			// }

			// $this->load->view('pages/report_five_year_excel', $data);
		}else{
			redirect(base_url("report_targets/view_report"));
			exit;
		}
	}

	private function numberToColumn($number)
	{
		$chr = '';
		if (is_numeric($number)) {
			$number = $number - 1;
			$first = floor($number / 26);
			$last = $number % 26;
			$firstChr = '';
			$lastChr = '';
			if ($first > 0 && $first < 26) {
				$firstChr = chr($first - 1 + 65);
			}
			$lastChr = chr($last + 65);
			$chr = $firstChr.$lastChr;
		}
		return $chr;
	}


	private function AdjustHTML($html, $usepre = true)
	{
	    $regexp = '|<script.*?</script>|si';
	    $html = preg_replace($regexp, '', $html);

	    $html = str_replace("\r\n", "\n", $html);
	    $html = str_replace("\f", '', $html);
	    $html = str_replace("\r", '', $html);
	    if ($usepre) {
	        $regexp = '#<pre(.*?)>(.+?)</pre>#si';
	        $thereispre = preg_match_all($regexp, $html, $temp);
	        $regexp2 = '#<textarea(.*?)>(.+?)</textarea>#si';
	        $thereistextarea = preg_match_all($regexp2, $html, $temp2);
	        $html = str_replace("\n", ' ', $html);
	        $html = str_replace("\t", ' ', $html);
	        $regexp3 = '#\s{2,}#s';
	        $html = preg_replace($regexp3, ' ', $html);
	        $iterator = 0;
	        while ($thereispre) {
	            $temp[2][$iterator] = str_replace("\n", '<br>', $temp[2][$iterator]);
	            $html = preg_replace($regexp, '<erp' . $temp[1][$iterator] . '>' . $temp[2][$iterator] . '</erp>', $html, 1);
	            --$thereispre;
	            ++$iterator;
	        }
	        $iterator = 0;
	        while ($thereistextarea) {
	            $temp2[2][$iterator] = str_replace(' ', '&nbsp;', $temp2[2][$iterator]);
	            $html = preg_replace($regexp2, '<aeratxet' . $temp2[1][$iterator] . '>' . trim($temp2[2][$iterator]) . '</aeratxet>', $html, 1);
	            --$thereistextarea;
	            ++$iterator;
	        }

	        $html = str_replace('<erp', '<pre', $html);
	        $html = str_replace('</erp>', '</pre>', $html);
	        $html = str_replace('<aeratxet', '<textarea', $html);
	        $html = str_replace('</aeratxet>', '</textarea>', $html);
	    } else {
	        $html = str_replace("\n", ' ', $html);
	        $html = str_replace("\t", ' ', $html);
	        $regexp = '/\\s{2,}/s';
	        $html = preg_replace($regexp, ' ', $html);
	    }

	    $regexp = '/(<br[ \/]?[\/]?>)+?<\/div>/si';
	    $html = preg_replace($regexp, '</div>', $html);

	    return $html;
	}

	private function pdfRemoveTag($html)
	{
	    $html = preg_replace('#<head(.*?)>(.*?)</head>#is', '', $html);
	    $html = preg_replace('#<title(.*?)>(.*?)</title>#is', '', $html);
	    $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
	    $html = preg_replace('#<col(.*?)>#is', '', $html);

	    $html = str_replace(array('class="column0 style2 s"', 'class="column0 style1 s"'), ' width="15"', $html);
	    $html = str_replace(array('class="column1 style2 s"', 'class="column1 style1 s"'), ' width="32"', $html);
	    $html = str_replace(array('class="column2 style3 s"', 'class="column2 style1 s"', 'class="column2 style3 n"'), '', $html);
	    $html = str_replace(array('class="column3 style3 s"', 'class="column3 style1 s"', 'class="column3 style3 n"'), ' width="125"', $html);
	    $html = str_replace(array('class="column4 style2 s"', 'class="column4 style1 s"'), ' width="35"', $html);
	    $html = str_replace(array('class="column5 style3 s"', 'class="column5 style1 s"', 'class="column5 style3 n"'), ' width="45"', $html);
	    $html = str_replace(array('class="column6 style2 s"', 'class="column6 style1 s"'), ' width="35"', $html);
	    $html = str_replace(array('class="column7 style2 s"', 'class="column7 style1 s"'), ' width="25"', $html);
	    $html = str_replace(array('class="column8 style2 s"', 'class="column8 style1 s"'), ' width="25"', $html);
	    $html = str_replace(array('class="column9 style2 s"', 'class="column9 style1 s"'), ' width="25"', $html);
	    $html = str_replace(array('class="column10 style2 s"', 'class="column10 style1 s"', 'class="column10 style2 n"'), ' width="25"', $html);

	    $html = str_replace(array('class="column11 style2 s"', 'class="column11 style1 s"', 'class="column11 style3 s"', 'class="column11 style3 n"'), '', $html);
	    $html = str_replace(array('class="column12 style2 s"', 'class="column12 style1 s"', 'class="column12 style2 n"'), '', $html);
	    $html = str_replace(array('class="column13 style2 s"', 'class="column13 style1 s"'), '', $html);
	    $html = str_replace(array('class="column14 style2 s"', 'class="column14 style1 s"', 'class="column14 style2 n"'), '', $html);
	    $html = str_replace(array('class="column15 style3 s"', 'class="column15 style1 s"', 'class="column15 style3 n"'), '', $html);
	    $html = str_replace(array('class="column16 style5 s"', 'class="column16 style1 s"', 'class="column16 style4 s"', 'class="column16 style6 s"', 'class="column16 style7 s"'), '', $html);
	    $html = str_replace(array('class="column17 style2 s"', 'class="column17 style1 s"', 'class="column17 style5 n"', 'class="column17 style8 n"'), '', $html);
	    $html = str_replace(array('class="column18 style2 s"', 'class="column18 style1 s"', 'class="column18 style8 n"'), '', $html);
	    $html = str_replace(array('class="column19 style3 s"', 'class="column19 style1 s"', 'class="column19 style3 n"'), '', $html);
	    $html = str_replace(array('class="column20 style2 s"', 'class="column20 style1 s"'), '', $html);

	    $html = str_replace('
	      ', '', $html);
	    $html = str_replace('            ', '', $html);
	    $html = str_replace('        ', '', $html);
	    $html = str_replace('          ', '', $html);
	    $html = str_replace('td  width', 'td width', $html);
	    $html = str_replace('<td >', '<td>', $html);
	    $html = str_replace('<tr><td></td></tr>', '', $html);
	    $html = str_replace('</td>  </tr>  <tr', '</td></tr><tr', $html);
	    $html = str_replace('cellpadding="0"', 'cellpadding="2"', $html);
	    $html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><!-- Generated by PHPExcel - http://www.phpexcel.net --><html>    <body>    ', '', $html);
	    $html = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', '\\2', $html);
	    $html = str_replace('  </body></html>', '', $html);
	    $html = str_replace('border="0"', 'border="1" style="font-size:18px;" ', $html);
	    $html = preg_replace('#<title(.*?)>(.*?)</title>#is', '', $html);

	    return $html;
	}


}
