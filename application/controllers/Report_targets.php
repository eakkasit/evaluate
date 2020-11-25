<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_targets extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation','m_pdf'));
		$this->load->model(array('Commons_model', 'TargetProfiles_model','Targets_model','Activities_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("report_targets/dashboard_report_targets"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->get('search') && !empty($fields)) {
			$search_text = explode(' ', $this->input->get('search'));
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

	public function dashboard_report_targets()
	{

		$data_temp = array();
		$con = array();
		$year_show = 1;
		$year_start = date('Y')+543;
		$year_end = date('Y')+543+5;
		// $con = array("year BETWEEN '$year_start' and '$year_end'");

		// $year =  date('Y')+543;
		// if(isset($_GET['year'])){
		// 	$year = $_GET['year'];
		// }
		// $con = array("year"=>$year);
		$project_list = $this->Activities_model->getActivities($con,array('year'=>'DESC','id'=>'ASC'));
		if(count($project_list) > 0){
			foreach ($project_list as $key => $value) {
				$data_temp[$value->id][$value->year] = $this->Activities_model->getTargetTask($value->id)[0]->weight;
			}
		}
		// echo "<pre>";
		// print_r($project_list);
		// die();
		$data['content_data'] = array(
			'project_list' => $project_list,
			'year_list' => $this->Commons_model->getYearList(),
			'year_show' => $year_show,
			'data' => $data_temp,
			'year_start' => $year_start,
			'year_end' => $year_end,
			'year' => $year
		);

		$data['content_view'] = 'pages/dashboard_report_target';
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
		redirect(base_url("report_targets/dashboard_report_targets"));
		exit;
	}

	public function export($type = '')
	{
		$data_temp = array();
		$con = array();
		$year_show = 1;
		$year_start = date('Y')+543;
		$year_end = date('Y')+543+5;
		$con = array("year BETWEEN '$year_start' and '$year_end'");
		$year =  date('Y')+543;
		if(isset($_GET['year'])){
			$year = $_GET['year'];
		}
		$con = array("year"=>$year);
		$project_list = $this->Activities_model->getActivities($con,array('year'=>'DESC','id'=>'ASC'));
		if(count($project_list) > 0){
			foreach ($project_list as $key => $value) {
				$data_temp[$value->id][$value->year] = $this->Activities_model->getTargetTask($value->id)[0]->weight;
			}
		}
		$data = array(
			'project_list' => $project_list,
			'year_list' => $this->Commons_model->getYearList(),
			'year_show' => $year_show,
			'data' => $data_temp,
			'year_start' => $year_start,
			'year_end' => $year_end,
			'year' => $year
		);
		if($type == 'pdf'){
			$pdfFilePath = "รายงานเป้าหมายโครงการ.pdf";
			$html = $this->load->view('pages/report_target_pdf', $data,true);
			$this->m_pdf->pdf->WriteHTML($html);
			$this->m_pdf->pdf->Output($pdfFilePath, 'D');
			exit;
		}else if($type == 'word'){
			$this->load->view('pages/report_target_word', $data);
		}else if($type == 'excel'){
			$this->load->view('pages/report_target_excel', $data);
		}else{
			redirect(base_url("report_targets/view_report"));
			exit;
		}
	}


}
