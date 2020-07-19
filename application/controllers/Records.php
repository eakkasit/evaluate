<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records extends CI_Controller
{
	private $theme = 'default';
	private $system_configs = array();
	public $user_id = 0;
	public $user_fullname = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Logs_model', 'Configs_model', 'Configs_model', 'Records_model', 'Agendas_model', 'Datas_model'));
		$this->load->helper(array('Commons_helper'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}

		$this->user_id = $this->session->userdata('user_id');
		$this->user_fullname = $this->Commons_model->getPrefixList($this->session->userdata('prename')) . ' ' . $this->session->userdata('name') . '   ' . $this->session->userdata('surname');

		$this->system_configs = array(
			'speach_to_text' => $this->Configs_model->getConfigs(array('config_id' => 4))[0],
		);
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
		redirect(base_url("records/dashboard_records"));
		exit;
	}

	public function dashboard_records($year = null)
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
			'search_url' => base_url("records/dashboard_records"),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'overview' => $this->Datas_model->getDatasOverview($year),
			'meetings' => $this->Agendas_model->getDataAgendas($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/dashboard_records';
		$this->load->view($this->theme, $data);
	}

	public function view_record($meeting_id = null)
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
		$data['content_view'] = 'pages/view_record';
		$this->load->view($this->theme, $data);
	}

	public function edit_record($meeting_id = null, $agenda_id = null)
	{
		$record = array();
		$record_data = $this->Records_model->getRecord(array('meeting_id' => $meeting_id, 'agenda_id' => $agenda_id));
		if (!empty($record_data)) {
			$record = $record_data;
			unset($record_data);
		}

		$data['content_data'] = array(
			'configs' => $this->system_configs,
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'agenda' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id, 'agenda.agenda_id' => $agenda_id))[0],
			'files' => $this->Agendas_model->getAgendaFiles(array('agenda_id' => $agenda_id)),
			'record' => $record,
			'user_id' => $this->user_id,
			'user_fullname' => $this->user_fullname,
		);
		$data['content_view'] = 'pages/edit_record';
		$this->load->view($this->theme, $data);
	}

	public function save($meeting_id = null, $agenda_id = null)
	{
		$error_page = false;
		$this->form_validation->set_rules('record_detail', 'บันทึกการประชุม', 'required|trim');

		$this->form_validation->set_message('required', 'กรุณาระบุข้อมูล {field}');

		if ($this->form_validation->run()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}
			if (!$error_page) {
				$this->Records_model->saveRecord($data);
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
				'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
				'agenda' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id))[0],
				'files' => $this->Agendas_model->getAgendaFiles(array('agenda_id' => $agenda_id)),
				'user_id' => $this->user_id,
				'user_fullname' => $this->user_fullname,
				'record_detail' => $this->input->post('record_detail'),
			);
			$data['content_view'] = 'pages/form_record';
			$this->load->view($this->theme, $data);
		} else {
			redirect(base_url("records/view_record/{$meeting_id}/{$agenda_id}"));
			exit;
		}
	}

	public function update()
	{
		$data = array();

		foreach ($_POST as $key => $value) {
			$data[$key] = $this->input->post($key);
		}
		// echo "<pre>";
		// print_r($data);
		$this->Records_model->updateRecord($data);
		echo $data['record_detail'];
	}

	public function delete_record($meeting_id = null, $agenda_id = null)
	{
		$this->Agendas_model->deleteAgendaFiles("{$this->upload_config[upload_path]}{$meeting_id}/", $agenda_id);
		$this->Agendas_model->deleteAgenda($meeting_id, $agenda_id);
		redirect(base_url("records/view_record/{$meeting_id}"));
		exit;
	}

	public function get_meeting_records($meeting_id = null, $agenda_id = null)
	{
		$agenda = $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id, 'agenda.agenda_id' => $agenda_id))[0];
		$records = $this->Records_model->getAgendasRecord($meeting_id);
		$meeting_record = array();
		if (isset($records[$agenda_id]) && !empty($records[$agenda_id])) {
			$prefix_list = $this->Commons_model->getPrefixList();
			foreach ($records[$agenda_id] as $record) {
				$meeting_record[] = array(
					'full_name' => "{$prefix_list[$record->prename]} {$record->name}   {$record->surname}",
					'record_detail' => $record->record_detail,
					'create_date' => $record->create_date,
					'update_date' => $record->update_date,
				);
			}
		}
		$json_record = array(
			'agenda_name' => "<u><b>วาระที่ {$agenda->agenda_no}</b></u> เรื่อง{$agenda->agenda_name}",
			'agenda_story' => ($agenda->agenda_story != '') ? "เรื่องเดิม&nbsp;{$agenda->agenda_story}" : "",
			'agenda_detail' => "&emsp;{$agenda->agenda_detail}",
			'records_list' => $meeting_record,
		);
		echo json_encode($json_record);
		exit;
	}
}
