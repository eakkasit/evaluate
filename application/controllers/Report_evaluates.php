<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_evaluates extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation','m_pdf'));
		$this->load->model(array('Commons_model', 'Activities_model','CriteriaDatas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("report_evaluates/dashboard_report_evaluates"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element') && !empty($fields)) {
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

	public function dashboard_report_evaluates()
	{
		$result = array();
		$result_data = $this->CriteriaDatas_model->getResult();
		if(isset($result_data) && !empty($result_data)){
			foreach ($result_data as $key => $value) {
				// if($result[$value->project_id][$value->year]){
					$result[$value->project_id][$value->year] = $value->result;
				// }else{
					// $result[$value->project_id][$value->year] = $value->result;
				// }
			}
		}
		$cond = $this->search_form(array('project_name'));
		$data['content_data'] = array(
			'datas'=>$this->Activities_model->getActivities($cond, array('year'=>'DESC')),
			'result'=>$result
		);
		echo "<pre>";
		print_r($result);
		die();
		$data['content_view'] = 'pages/dashboard_report_evaluates';
		$this->load->view($this->theme, $data);
	}

	public function view_reports_evaluate($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/view_reports_evaluate';
		$this->load->view($this->theme, $data);
	}

	public function new_reports_evaluate($id = null)
	{
		$data['content_data'] = array(
		);
		$data['content_view'] = 'pages/form_reports_evaluate';
		$this->load->view($this->theme, $data);
	}

	public function edit_reports_evaluate($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_reports_evaluate';
		$this->load->view($this->theme, $data);
	}



	public function export($type = '')
	{
		$data = array(
			'datas'=>$this->Activities_model->getActivities(array(), array('year'=>'DESC')),
		);
		if($type == 'pdf'){
			$pdfFilePath = "รายงานการประเมินองค์กร.pdf";
			$html = $this->load->view('pages/report_evaluate_pdf', $data,true);
			$this->m_pdf->pdf->WriteHTML($html);
			$this->m_pdf->pdf->Output($pdfFilePath, 'D');
			exit;
		}else if($type == 'word'){
			$this->load->view('pages/report_evaluate_word', $data);
		}else if($type == 'excel'){
			$this->load->view('pages/report_evaluate_excel', $data);
		}else{
			redirect(base_url("report_targets/view_report"));
			exit;
		}
	}


}
