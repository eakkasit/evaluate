<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model','Structure_model','KpiTree_model','Kpi_model','Formula_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("criteria/dashboard_criteria"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element')['text'] != '' && !empty($fields)) {
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

	public function dashboard_criteria()
	{
		$cond = $this->search_form(array('profile_name', 'year', 'detail', 'status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("criteria/dashboard_criteria");
		$count_rows = $this->Structure_model->countStructure($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("criteria/dashboard_criteria"),
			'status_list' => $this->Commons_model->getActiveList(),
			'datas' => $this->Structure_model->getStructure($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_criteria';
		$this->load->view($this->theme, $data);
	}

	public function view_criteria($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->Structure_model->getStructure(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_criteria';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
		);
		$data['content_view'] = 'pages/form_criteria';
		$this->load->view($this->theme, $data);
	}

	public function edit_criteria($id = null)
	{
		$data['content_data'] = array(
			// 'status_list' => $this->Commons_model->getActiveList(),
			// 'year_list' => $this->Commons_model->getYearList(),
			// 'data' => $this->Structure_model->getStructure(array('criteria_id'=>$id))[0]
			'structure_id'=>$id
		);
		$data['content_view'] = 'pages/form_criteria';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('criteria_name', 'ชื่อแม่แบบเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_rules('profile_year', 'ปี', 'required|trim');
		$this->form_validation->set_rules('criteria_status', 'สถานะการใช้งาน', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($criteria_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($criteria_id != null && $criteria_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$criteria_id = $this->Structure_model->insertStructure($data);
				redirect(base_url("criteria/dashboard_criteria"));
				exit;
			} else {
				$this->Structure_model->updateStructure($criteria_id, $data);
				redirect(base_url("criteria/dashboard_criteria"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'status_list'=> $this->Commons_model->getActiveList(),
				'year_list' => $this->Commons_model->getYearList(),
				'data' => (object)array(
					'criteria_id' => $criteria_id,
					'criteria_name' => $this->input->post('criteria_name'),
					'profile_name' => $this->input->post('profile_year'),
					'criteria_status' => $this->input->post('criteria_status')
				)
			);
			$data['content_view'] = 'pages/form_criteria';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_criteria($id = null)
	{
		$this->Structure_model->deleteStructure($id);
		redirect(base_url("criteria/dashboard_criteria"));
		exit;
	}

	public function ajax_kpi_tree($id='')
	{
		echo $this->KpiTree_model->getTreeFormList($id,0,'');
	}

	public function ajax_var_data($kpi_id,$kpi_standard_type=''){
		$data = array();
		$data['content_data'] = array(
			'fomular' => (object) $this->Formula_model->getFomularVariable($kpi_id),
			'kpi_standard_type' => $kpi_standard_type,
			'units_list'=> $this->Commons_model->getUnitsList(),
		);
		$data['content_view'] = 'ajax/ajax_save_variable';
		$this->load->view('ajax', $data);
	}

	public function ajax_save_variable_data($value='')
	{
		$data = array();
		foreach ($_POST as $key => $value) {
			$data[$key] = $this->input->post($key);
		}

		if(isset($data['VAR'])){
			$datecreate = date("Y-m-d H:i:s");
			foreach( $data['VAR'] as $kpi_id => $var_array ){
				if(count($var_array)>0){
            foreach( $var_array as $var_id => $var_data ){

                // $this->Kpi_model->query("INSERT INTO kpi_var_data SET
								// var_id='$var_id' ,
								// var_data='$var_data' ,
								// kpi_id='$kpi_id' ,
								// date_var='' ,
								// structure_id='19' ,
								// org_id='1' ,
								// user_id='1' ,
								// time_count='1' ,
								// user_owner='1' ,
								// datecreate='$datecreate'
								// ");
								$this->Kpi_model->saveKpiVardata($var_id,$var_data,$kpi_id);
                $arr_formula[] = $this->Kpi_model->getFormula($var_id,$kpi_id);
                $arr_replace[] = $var_data;
            }
        }

				// $kfa = $this->Kpi_model->query("SELECT * FROM kpi_data WHERE kpi_id='$kpi_id' ")->row();
        //     if($kfa->kpi_formula!=''){
        //         $formula_data = str_replace($arr_formula,$arr_replace,$kfa->kpi_formula);
        //         @eval("\$formula_value = ".$formula_data.";");
        //     }else{
        //         $formula_data = $var_data;
        //         $formula_value = $formula_data;
        //     }
				//
        //     //fix 1 - 5
        //     $tree_weight = $this->Kpi_model->query("SELECT tree_weight FROM  `kpi_tree` WHERE  `tree_id` ='".$_GET['tree_id']."' ")->row()->tree_weight;
        //     if($kfa->kpi_standard_label1==''){
        //         $kfa->kpi_standard_label1 = 1;
        //     }
        //     if($kfa->kpi_standard_label2==''){
        //         $kfa->kpi_standard_label2 = 2;
        //     }
        //     if($kfa->kpi_standard_label3==''){
        //         $kfa->kpi_standard_label3 = 3;
        //     }
        //     if($kfa->kpi_standard_label4==''){
        //         $kfa->kpi_standard_label4 = 4;
        //     }
        //     if($kfa->kpi_standard_label5==''){
        //         $kfa->kpi_standard_label5 = 5;
        //     }
				//
        //     if($kfa->kpi_standard_type=='2'){
        //         if($kfa->kpi_standard_1<$kfa->kpi_standard_5){
        //             if($formula_value<=$kfa->kpi_standard_1){
				//
        //                 $grade_map = 1;
        //                 $score_real = $kfa->kpi_standard_label1;
        //                 $grade_map = $score_real;
        //                 $formula_score = round((($grade_map/$kfa->kpi_standard_label1)*$tree_weight),2);
        //             }else if($formula_value>=$kfa->kpi_standard_1 and $formula_value<$kfa->kpi_standard_2){
				//
        //                 $grade_map = 1+(($formula_value-$kfa->kpi_standard_1)/($kfa->kpi_standard_2-$kfa->kpi_standard_1));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label1;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label1+($grade_map-0)*($kfa->kpi_standard_label2-$kfa->kpi_standard_label1))/$kfa->kpi_standard_label5)*$tree_weight),2);
        //             }else if($formula_value>=$kfa->kpi_standard_2 and $formula_value<$kfa->kpi_standard_3){
				//
        //                 $grade_map = 2+(($formula_value-$kfa->kpi_standard_2)/($kfa->kpi_standard_3-$kfa->kpi_standard_2));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label2;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label2+($grade_map-1)*($kfa->kpi_standard_label3-$kfa->kpi_standard_label2))/$kfa->kpi_standard_label5)*$tree_weight),2);
        //             }else if($formula_value>=$kfa->kpi_standard_3 and $formula_value<$kfa->kpi_standard_4){
				//
        //                 $grade_map = 3+(($formula_value-$kfa->kpi_standard_3)/($kfa->kpi_standard_4-$kfa->kpi_standard_3));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label3;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label3+($grade_map-2)*($kfa->kpi_standard_label4-$kfa->kpi_standard_label3))/$kfa->kpi_standard_label5)*$tree_weight),2);
        //             }else if($formula_value>=$kfa->kpi_standard_4 and $formula_value<$kfa->kpi_standard_5){
				//
        //                 $grade_map = 4+(($formula_value-$kfa->kpi_standard_4)/($kfa->kpi_standard_5-$kfa->kpi_standard_4));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label4;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label4+($grade_map-3)*($kfa->kpi_standard_label5-$kfa->kpi_standard_label4))/$kfa->kpi_standard_label5)*$tree_weight),2);
        //             }else if($formula_value>=$kfa->kpi_standard_5){
				//
        //                 $grade_map = 5;
        //                 $score_real = $kfa->kpi_standard_label5;
        //                 $grade_map = $score_real;
        //                 $formula_score = round((($grade_map/$kfa->kpi_standard_label5)*$tree_weight),2);
        //             }
        //         }else{
        //             if($formula_value<=$kfa->kpi_standard_5){
				//
        //                 $grade_map = 5;
        //                 $score_real = $kfa->kpi_standard_label5;
        //                 $grade_map = $score_real;
        //                 $formula_score = round((($grade_map/$kfa->kpi_standard_label5)*$tree_weight),2);
        //             }else if($formula_value>=$kfa->kpi_standard_5 and $formula_value<$kfa->kpi_standard_4){
				//
        //                 $grade_map = 4+(($kfa->kpi_standard_4-$formula_value)/($kfa->kpi_standard_4-$kfa->kpi_standard_5));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label4;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label4+($grade_map-3)*($kfa->kpi_standard_label5-$kfa->kpi_standard_label4))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //             }else if($formula_value>=$kfa->kpi_standard_4 and $formula_value<$kfa->kpi_standard_3){
				//
        //                 $grade_map = 3+(($kfa->kpi_standard_3-$formula_value)/($kfa->kpi_standard_3-$kfa->kpi_standard_4));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label3;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label3+($grade_map-2)*($kfa->kpi_standard_label4-$kfa->kpi_standard_label3))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //             }else if($formula_value>=$kfa->kpi_standard_3 and $formula_value<$kfa->kpi_standard_2){
				//
        //                 $grade_map = 2+(($kfa->kpi_standard_2-$formula_value)/($kfa->kpi_standard_2-$kfa->kpi_standard_3));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label2;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label2+($grade_map-1)*($kfa->kpi_standard_label3-$kfa->kpi_standard_label2))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //             }else if($formula_value>=$kfa->kpi_standard_2 and $formula_value<$kfa->kpi_standard_1){
				//
        //                 $grade_map = 1+(($kfa->kpi_standard_1-$formula_value)/($kfa->kpi_standard_1-$kfa->kpi_standard_2));
        //                 $grade_map = round($grade_map,2);
        //                 $score_real = $kfa->kpi_standard_label1;
        //                 $grade_map = $score_real;
        //                 $formula_score = round(((($kfa->kpi_standard_label1+($grade_map-0)*($kfa->kpi_standard_label2-$kfa->kpi_standard_label1))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //             }else if($formula_value>=$kfa->kpi_standard_1){
				//
        //                 $grade_map = 1;
        //                 $score_real = $kfa->kpi_standard_label1;
        //                 $grade_map = $score_real;
        //                 $formula_score = round((($grade_map/$kfa->kpi_standard_label1)*$tree_weight),2);
        //             }
        //         }
				//
        //     }else{
        //         if($formula_value<=1){
				//
        //             $grade_map = 1;
        //             $score_real = $kfa->kpi_standard_label1;
        //             $grade_map = $score_real;
        //             $formula_score = round((@($grade_map/$kfa->kpi_standard_label1)*$tree_weight),2);
				//
        //         }else if($formula_value>=1 and $formula_value<2){
				//
        //             $grade_map = 1;
        //             $score_real = $kfa->kpi_standard_label1;
        //             $grade_map = $score_real;
        //             $formula_score = round(((($kfa->kpi_standard_label1+($grade_map-0)*($kfa->kpi_standard_label2-$kfa->kpi_standard_label1))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //         }else if($formula_value>=2 and $formula_value<3){
				//
        //             $grade_map = 2;
        //             $score_real = $kfa->kpi_standard_label2;
        //             $grade_map = $score_real;
        //             $formula_score = round(((($kfa->kpi_standard_label2+($grade_map-1)*($kfa->kpi_standard_label3-$kfa->kpi_standard_label2))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //         }else if($formula_value>=3 and $formula_value<4){
				//
        //             $grade_map = 3;
        //             $score_real = $kfa->kpi_standard_label3;
        //             $grade_map = $score_real;
        //             $formula_score = round(((($kfa->kpi_standard_label3+($grade_map-2)*($kfa->kpi_standard_label4-$kfa->kpi_standard_label3))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //         }else if($formula_value>=4 and $formula_value<5){
				//
        //             $grade_map = 4;
        //             $score_real = $kfa->kpi_standard_label4;
        //             $grade_map = $score_real;
        //             $formula_score = round(((($kfa->kpi_standard_label4+($grade_map-3)*($kfa->kpi_standard_label5-$kfa->kpi_standard_label4))/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //         }else if($formula_value>=5){
				//
        //             $grade_map = 5;
        //             $score_real = $kfa->kpi_standard_label5;
        //             $grade_map = $score_real;
        //             $formula_score = round((($grade_map/$kfa->kpi_standard_label5)*$tree_weight),2);
				//
        //         }
				//
        //     }
				//
				//
        //    $this->Kpi_model->query("INSERT INTO kpi_formula_data SET
        //                                 formula_data_id='1',
				// 						kpi_id='$kpi_id' ,
				// 						structure_id='19' ,
				// 						org_id='1' ,
				// 						user_id='1' ,
				// 						formula_data='$formula_data' ,
				// 						formula_value='$formula_value' ,
				// 						formula_score='$formula_score' ,
				// 						time_count='1' ,
				// 						grade_map='$grade_map' ,
				// 						user_owner='1' ,
				// 						datecreate='$datecreate',
				// 						score_real='$score_real'
				// 						");
        //     unset($arr_formula);
        //     unset($arr_replace);
			}

		}
		echo "<pre>";
		print_r($data);
		die();
	}
}
