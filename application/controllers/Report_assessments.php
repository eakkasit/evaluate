<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_assessments extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation','m_pdf'));
		$this->load->model(array('Commons_model','Structure_model','KpiTree_model','Formula_model','Kpi_model','CriteriaDatas_model','Activities_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("report_assessments/dashboard_report_assessments"));
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

	public function dashboard_report_assessments()
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


		$data['content_view'] = 'pages/dashboard_report_assessment';
		$this->load->view($this->theme, $data);
	}

	public function view_reports_assessment($id = null)
	{
		$result_query = $this->CriteriaDatas_model->getCriteriaDataResult(array('structure_id'=>$id));
		$project_list = $this->Activities_model->getActivityLists();
		$result = array();
		if(isset($result_query) && !empty($result_query)){
			foreach ($result_query as $key => $value) {
				$result['tree_id'][$value->tree_id] = $value->tree_id;
				$result['structure_id'][$value->tree_id] = $value->structure_id;
				$result['tree_number'][$value->tree_id] = $value->tree_number;
				$result['criteria_name'][$value->tree_id] = $value->criteria_name;
				$result['project_id'][$value->tree_id] = $value->project_id;
				$result['project_name'][$value->tree_id] = $project_list[$value->project_id];
				$result['result'][$value->tree_id] = $value->result;
				$result['percent'][$value->tree_id] = $value->percent;
				$result['weight'][$value->tree_id] = $value->weight;
				$result['total'][$value->tree_id] = $value->total;
			}
		}
		$data['content_data'] = array(
			'structure_id' => $id,
			'tree' => $this->KpiTree_model->getKpiTree(array('structure_id' => $id,'tree_parent' => 0)),
			'tree_db' => $this->KpiTree_model,
			'kpi_db' => $this->Kpi_model,
			'formula_db' => $this->Formula_model,
			'result' => $result


		);
		$data['content_view'] = 'pages/view_reports_assessment';
		$this->load->view($this->theme, $data);
	}

	public function export($id,$type = '')
	{
		$data = array(
			'structure_id' => $id,
			'tree' => $this->KpiTree_model->getKpiTree(array('structure_id' => $id,'tree_parent' => 0)),
			'tree_db' => $this->KpiTree_model,
			'kpi_db' => $this->Kpi_model,
			'formula_db' => $this->Formula_model

		);
		if($type == 'pdf'){
			$pdfFilePath = "รายงานการประเมินองค์กร.pdf";
			$html = $this->load->view('pages/report_assessment_pdf', $data,true);
			$this->m_pdf->pdf->WriteHTML($html);
			$this->m_pdf->pdf->Output($pdfFilePath, 'D');
			exit;
		}else if($type == 'word'){
			$this->load->view('pages/report_assessment_word', $data);
		}else if($type == 'excel'){
			$this->load->view('pages/report_assessment_excel', $data);
		}else{
			redirect(base_url("report_targets/view_report"));
			exit;
		}
	}


}
