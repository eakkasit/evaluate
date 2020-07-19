<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agendas extends CI_Controller
{
	private $theme = 'default';
	private $upload_config = array(
		'upload_path' => '/var/www/php56/meeting/meetingsystem/assets/attaches/',
		'allowed_types' => 'gif|jpg|jpeg|jpe|png|pdf|doc|docx',
		'max_size' => 10240,// 10 MB
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Logs_model', 'Configs_model', 'Agendas_model', 'Datas_model'));
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
		redirect(base_url("agendas/dashboard_agendas"));
		exit;
	}

	public function dashboard_agendas($year = null)
	{
		$cond = $this->search_form(array('meeting_name', 'meeting_project', 'meeting_description', 'meeting_room'));
		$cond['meeting_status'] = 'draft';

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("agendas/dashboard_agendas");
		$count_rows = $this->Agendas_model->countDataAgendas($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		if ($year == null) $year = date('Y');
		$data['content_data'] = array(
			'search_url' => base_url("agendas/dashboard_agendas"),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'overview' => $this->Datas_model->getDatasOverview($year),
			'meetings' => $this->Agendas_model->getDataAgendas($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/dashboard_agendas';
		$this->load->view($this->theme, $data);
	}

	public function view_agenda($meeting_id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getDataStatusList(),
			'type_list' => $this->Agendas_model->getAgendaTypes(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'files' => $this->Agendas_model->getDataAgendasFiles(array('data.meeting_id' => $meeting_id)),
		);
		$data['content_view'] = 'pages/view_agenda';
		$this->load->view($this->theme, $data);
	}


	public function new_agenda($meeting_id = null)
	{
		$data['content_data'] = array(
			'type_list' => $this->Agendas_model->getAgendaTypes(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
		);
		$data['content_view'] = 'pages/form_agenda';
		$this->load->view($this->theme, $data);
	}

	public function edit_agenda($meeting_id = null, $agenda_id = null)
	{
		$data['content_data'] = array(
			'type_list' => $this->Agendas_model->getAgendaTypes(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'agenda' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id, 'agenda.agenda_id' => $agenda_id))[0],
			'files' => $this->Agendas_model->getAgendaFiles(array('agenda_id' => $agenda_id)),
		);
		$data['content_view'] = 'pages/form_agenda';
		$this->load->view($this->theme, $data);
	}

	public function save($meeting_id = null, $agenda_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($agenda_id != null && $agenda_id != '') {
			$action = 'update';
		}

		$this->form_validation->set_rules('agenda_name', 'วาระเรื่อง', 'required|trim');
		$this->form_validation->set_rules('agenda_type_id', 'วาระ', 'required|trim');
		$this->form_validation->set_rules('agenda_story', 'เรื่องเดิม', 'trim');
		$this->form_validation->set_rules('agenda_detail', 'เนื้อหา', 'required|trim');

		$this->form_validation->set_message('required', 'กรุณาระบุข้อมูล {field}');

		if ($this->form_validation->run()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if (!$error_page) {
				if ($action == 'create') {
					$agenda_id = $this->Agendas_model->insertAgendas($meeting_id, $data);
				} else {
					$this->Agendas_model->updateAgendas($meeting_id, $agenda_id, $data);
				}
				if (!empty($_FILES) && $_FILES['agenda_file']['name'][0] != '') {
					$config = $this->upload_config;
					$this->load->library('upload', $config);
					$config['upload_path'] .= "{$meeting_id}/";

					if (!file_exists($config['upload_path'])) {
						mkdir($config['upload_path'], 0777);
						chmod($config['upload_path'], 0777);
					}

					$files = $_FILES;
					$cpt = count($_FILES['agenda_file']['name']);
					for ($i = 0; $i < $cpt; $i++) {
						$_FILES['agenda_file']['name'] = $files['agenda_file']['name'][$i];
						$_FILES['agenda_file']['type'] = $files['agenda_file']['type'][$i];
						$_FILES['agenda_file']['tmp_name'] = $files['agenda_file']['tmp_name'][$i];
						$_FILES['agenda_file']['error'] = $files['agenda_file']['error'][$i];
						$_FILES['agenda_file']['size'] = $files['agenda_file']['size'][$i];

						$config['file_name'] = "{$meeting_id}_{$agenda_id}_" . time();
						$this->upload->initialize($config);
						if ($this->upload->do_upload('agenda_file')) {
							$upload = array('upload_data' => $this->upload->data());

							$file = array(
								'agenda_id' => $agenda_id,
								'agenda_filename' => $upload['upload_data']['file_name'],
								'agenda_detail' => $files['agenda_file']['name'][$i]
							);
							$this->Agendas_model->insertAgendaFile($file);
						} else {
							$upload_msg = $this->upload->display_errors();
							$error_page = true;
							break;
						}
					}
				}
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			if ($action == 'create') {
				$data['content_data'] = array(
					'type_list' => $this->Agendas_model->getAgendaTypes(),
					'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
					'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
				);
				$data['content_view'] = 'pages/form_agenda';
				$this->load->view($this->theme, $data);
			} else {
				$data['content_data'] = array(
					'type_list' => $this->Agendas_model->getAgendaTypes(),
					'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
					'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
					'upload_msg' => $upload_msg,
					'agenda' => (object)array(
						'agenda_id' => $agenda_id,
						'agenda_no' => $this->input->post('agenda_no'),
						'agenda_name' => $this->input->post('agenda_name'),
						'agenda_type_id' => $this->input->post('agenda_type_id'),
						'agenda_story' => $this->input->post('agenda_story'),
						'agenda_detail' => $this->input->post('agenda_detail'),
					),
					'files' => $this->Agendas_model->getAgendaFiles(array('agenda_id' => $agenda_id)),
				);
				$data['content_view'] = 'pages/form_agenda';
				$this->load->view($this->theme, $data);
			}
		} else {
			redirect(base_url("agendas/view_agenda/{$meeting_id}/{$agenda_id}"));
			exit;
		}

	}

	public function delete_agenda($meeting_id = null, $agenda_id = null)
	{
		$this->Agendas_model->deleteAgendaFiles("{$this->upload_config[upload_path]}{$meeting_id}/", $agenda_id);
		$this->Agendas_model->deleteAgenda($meeting_id, $agenda_id);
		redirect(base_url("agendas/view_agenda/{$meeting_id}"));
		exit;
	}

	public function delete_agenda_file($meeting_id = null, $agenda_id = null, $file_id = null)
	{
		$this->Agendas_model->deleteAgendaFiles("{$this->upload_config[upload_path]}{$meeting_id}/", $agenda_id, $file_id);
		redirect(base_url("agendas/edit_agenda/{$meeting_id}/{$agenda_id}"));
		exit;
	}
}
