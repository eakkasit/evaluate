<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_attends extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation','m_pdf'));
		$this->load->model(array('Commons_model', 'Logs_model', 'Configs_model', 'Datas_model', 'Presents_model', 'Agendas_model', 'Records_model'));
		$this->load->helper(array('Commons_helper'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
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

	public function index()
	{
		redirect(base_url("report_attends/record_reports"));
		exit;
	}

	public function record_reports($year = null)
	{
		$cond = $this->search_form(array('meeting_name', 'meeting_project', 'meeting_description', 'meeting_room'));
		$cond['meeting_status'] = 'complete';

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("records/dashboard_records");
		$count_rows = $this->Agendas_model->countDataAgendas($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		if ($year == null) $year = date('Y');
		$data['content_data'] = array(
			'search_url' => base_url("report_attends/record_reports"),
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'overview' => $this->Datas_model->getDatasOverview($year),
			'meetings' => $this->Agendas_model->getDataAgendas($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/dashboard_record_reports';
		$this->load->view($this->theme, $data);
	}

	public function view_record_report($meeting_id = null)
	{
		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'type_list' => $this->Agendas_model->getAgendaTypes(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'files' => $this->Agendas_model->getDataAgendasFiles(array('data.meeting_id' => $meeting_id)),
			'records' => $this->Records_model->getAgendasRecord($meeting_id),
		);
		$data['content_view'] = 'pages/view_record_report';
		$this->load->view($this->theme, $data);
	}

	public function view_attend_report($meeting_id = null, $agenda_id = null)
	{
		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'type_list' => $this->Agendas_model->getAgendaTypes(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'files' => $this->Agendas_model->getDataAgendasFiles(array('data.meeting_id' => $meeting_id)),
			'records' => $this->Records_model->getAgendasRecord($meeting_id),
			'report' => $this->Records_model->getReportAttend($meeting_id,$agenda_id),
			'agenda_id' => $agenda_id
		);
		$data['content_view'] = 'pages/view_attend_report';
		$this->load->view($this->theme, $data);
	}

	public function export_attend($meeting_id = null, $agenda_id = null , $type = '')
	{
		$data = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'type_list' => $this->Agendas_model->getAgendaTypes(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'users_list' => $this->Presents_model->getGroupPresents(array('meeting_id' => $meeting_id)),
			'users_temporary_list' =>$this->Datas_model->getDataUsersTemporary(array('meeting_id' => $meeting_id, 'user_status' => 'active')),
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'files' => $this->Agendas_model->getDataAgendasFiles(array('data.meeting_id' => $meeting_id)),
			'records' => $this->Records_model->getAgendasRecord($meeting_id),
			'report' => $this->Records_model->getReportAttend($meeting_id,$agenda_id)
		);
		if($type == 'pdf'){
			$pdfFilePath = "รายงานการเข้าประชุม{$data['meeting_data']->meeting_name}.pdf";
			$html = $this->load->view('pages/report_attend_export_pdf', $data,true);
			$this->m_pdf->pdf->WriteHTML($html);
			$this->m_pdf->pdf->Output($pdfFilePath, 'D');
			exit;
		}else if($type == 'word'){
			$this->load->view('pages/report_attend_export_word', $data);
		}else if($type == 'excel'){
			$this->load->view('pages/report_attend_export_excel', $data);
		}else{
			redirect(base_url("record_reports/view_record_report"));
			exit;
		}
	}
}
