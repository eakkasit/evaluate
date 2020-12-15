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

	public function dashboard_report_assessments()
	{

		$cond = $this->search_form(array('structure_name', 'profile_year', 'structure_status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("report_assessments/dashboard_report_assessments");
		$count_rows = $this->Structure_model->countStructure($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("report_assessments/dashboard_report_assessments"),
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
		$activity_model = $this->load->database('db_activity',true);
		$evaluate_model = $this->load->database('default',true);
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
				$project_content = '';


				$project_data = $evaluate_model->query("select * from `evaluate_criteria_project` where structure_id = '".$value->structure_id."' and tree_id = '{$value->tree_id}'")->result();
				if(!empty($project_data)){
					$project_detail = $project_data[0];
					if(!empty($project_detail->project_data) && $project_detail->project_data != ''){
						$project = $evaluate_model->query("select group_concat(project_name) as project_name from `project` WHERE id in (" . $project_detail->project_data .")")->result();
						if(!empty($project)){
							$project_content .= $project[0]->project_name;
						}
					}
					if(!empty($project_detail->bsc_data) && $project_detail->bsc_data != ''){
						$bsc = $evaluate_model->query("SELECT group_concat( IFNULL( task_name ,( SELECT project_name FROM project WHERE id = project_id))) AS title_name FROM bsc_person_indicators WHERE bsc_ps_inct_id in(".$project_detail->bsc_data.")")->result();
						if(!empty($bsc)){
							if($project_content != ''){
								$project_content .= " , ";
							}
							$project_content .= $bsc[0]->title_name;
						}
					}
					if(!empty($project_detail->task_data) && $project_detail->task_data != ''){
						$task = $evaluate_model->query("select group_concat(task_name) as task_name from `task` WHERE task_id in (" . $project_detail->task_data .")")->result();

						if(!empty($task)){
							if($project_content != ''){
								$project_content .= " , ";
							}
							$project_content .= $task[0]->task_name;
						}
					}
					if(!empty($project_detail->other_data) && $project_detail->other_data != ''){
							if($project_content != ''){
								$project_content .= " , ";
							}
							$project_content .= $project_detail->other_data;
					}
				}

				$result['project_name_list'][$value->tree_id] = $project_content;

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
		$result_query = $this->CriteriaDatas_model->getCriteriaDataResult(array('structure_id'=>$id));
		$project_list = $this->Activities_model->getActivityLists();
		$activity_model = $this->load->database('db_activity',true);
		$evaluate_model = $this->load->database('default',true);
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
				$project_content = '';


				$project_data = $evaluate_model->query("select * from `evaluate_criteria_project` where structure_id = '".$value->structure_id."' and tree_id = '{$value->tree_id}'")->result();
				if(!empty($project_data)){
					$project_detail = $project_data[0];
					if(!empty($project_detail->project_data) && $project_detail->project_data != ''){
						$project = $evaluate_model->query("select group_concat(project_name) as project_name from `project` WHERE id in (" . $project_detail->project_data .")")->result();
						if(!empty($project)){
							$project_content .= $project[0]->project_name;
						}
					}
					if(!empty($project_detail->bsc_data) && $project_detail->bsc_data != ''){
						$bsc = $evaluate_model->query("SELECT group_concat( IFNULL( task_name ,( SELECT project_name FROM project WHERE id = project_id))) AS title_name FROM bsc_person_indicators WHERE bsc_ps_inct_id in(".$project_detail->bsc_data.")")->result();
						if(!empty($bsc)){
							if($project_content != ''){
								$project_content .= " , ";
							}
							$project_content .= $bsc[0]->title_name;
						}
					}
					if(!empty($project_detail->task_data) && $project_detail->task_data != ''){
						$task = $evaluate_model->query("select group_concat(task_name) as task_name from `task` WHERE task_id in (" . $project_detail->task_data .")")->result();

						if(!empty($task)){
							if($project_content != ''){
								$project_content .= " , ";
							}
							$project_content .= $task[0]->task_name;
						}
					}
					if(!empty($project_detail->other_data) && $project_detail->other_data != ''){
							if($project_content != ''){
								$project_content .= " , ";
							}
							$project_content .= $project_detail->other_data;
					}
				}
				$result['project_name_list'][$value->tree_id] = $project_content;

			}
		}
		$data = array(
			'structure_id' => $id,
			'tree' => $this->KpiTree_model->getKpiTree(array('structure_id' => $id,'tree_parent' => 0)),
			'tree_db' => $this->KpiTree_model,
			'kpi_db' => $this->Kpi_model,
			'formula_db' => $this->Formula_model,
			'result' => $result
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
