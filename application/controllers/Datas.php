<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datas extends CI_Controller
{
	private $theme = 'default';
	private $system_configs = array();
	private $form_per_page = 5;
	private $present_per_page = 15;

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation', 'email'));
		$this->load->model(array('Commons_model', 'Logs_model', 'Configs_model', 'Datas_model', 'Agendas_model', 'Users_model', 'Groups_model', 'Presents_model'));
		$this->load->helper(array('Commons_helper'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}

		$this->system_configs = array(
			'email_noti' => $this->Configs_model->getConfigs(array('config_id' => 1))[0],
			'data_dupp_noti' => $this->Configs_model->getConfigs(array('config_id' => 6))[0],
			'meeting_report' => $this->Configs_model->getConfigs(array('config_id' => 5))[0],
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
		redirect(base_url("datas/dashboard_datas"));
		exit;
	}

	public function dashboard_datas($year = null)
	{
		$cond = $this->search_form(array('meeting_name', 'meeting_project', 'meeting_description', 'meeting_room'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("datas/dashboard_datas");
		$count_rows = $this->Datas_model->countDatas($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		if ($year == null) $year = date('Y');
		$data['content_data'] = array(
			'search_url' => base_url("datas/dashboard_datas"),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'overview' => $this->Datas_model->getDatasOverview($year),
			'datas' => $this->Datas_model->getDatas($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/dashboard_datas';
		$this->load->view($this->theme, $data);
	}

	public function view_data($meeting_id = null)
	{
		$config_pager = $this->config->item('pager');
		$config_pager['per_page'] = $this->present_per_page;
		$config_pager['base_url'] = base_url("datas/view_data/{$meeting_id}");
		$count_rows = $this->Presents_model->countGroupPresents(array('meeting_id' => $meeting_id), true);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$groups_list = array();
		$groups = $this->Datas_model->getDataGroups(array('meeting_id' => $meeting_id), array(), true);
		if (!empty($groups)) {
			foreach ($groups as $group) {
				$groups_list[$group->group_id] = $group;
			}
		}

		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'present_status_list' => $this->Commons_model->getPresentStatusList(),
			'data_data' => $this->Datas_model->getDatas(array('meeting_id' => $meeting_id))[0],
			'groups_list' => $groups_list,
			'users_list' => $this->Presents_model->getGroupPresents(array('meeting_id' => $meeting_id), array(), $config_pager['per_page'], $page),
			'users_temporary_list' => $this->Datas_model->getDataUsersTemporary(array('meeting_id' => $meeting_id, 'user_status' => 'active')),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/view_data';
		$this->load->view($this->theme, $data);
	}

	public function new_data()
	{
		$config_pager = $this->config->item('pager');
		$config_pager['per_page'] = $this->form_per_page;
		$config_pager['base_url'] = base_url("datas/new_data");
		$count_rows = $this->Groups_model->countGroups();
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'gender_list' => $this->Commons_model->getGenderList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'groups_list' => $this->Groups_model->getGroups(array('group_status' => 'active'), array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/form_data';
		$this->load->view($this->theme, $data);
	}

	public function edit_data($meeting_id = null)
	{
		$config_pager = $this->config->item('pager');
		$config_pager['per_page'] = $this->form_per_page;
		$config_pager['base_url'] = base_url("datas/edit_data/{$meeting_id}");
		$count_rows = $this->Datas_model->countDataGroups(array('meeting_id' => $meeting_id));
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		$groups_list_selected = $this->Datas_model->getDataGroups(array('meeting_id' => $meeting_id), array(), true);
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'gender_list' => $this->Commons_model->getGenderList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'data_data' => $this->Datas_model->getDatas(array('meeting_id' => $meeting_id))[0],
			'groups_list' => $this->Datas_model->getDataGroups(array('meeting_id' => $meeting_id), array(), false, $config_pager['per_page'], $page),
			'groups_list_selected' => $groups_list_selected,
			'users_temporary' => $this->Datas_model->getDataUsersTemporary(array('meeting_id' => $meeting_id, 'user_status' => 'active')),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/form_data';
		$this->load->view($this->theme, $data);
	}

	public function save($meeting_id = null)
	{
		$action = 'create';
		if ($meeting_id != null && $meeting_id != '') {
			$action = 'update';
		}

		$this->form_validation->set_rules('meeting_name', 'ชื่อการประชุม', 'required|trim');
		$this->form_validation->set_rules('meeting_description', 'รายละเอียดการประชุม', 'trim');
		$this->form_validation->set_rules('meeting_date', 'วันที่ประชุม', 'required|trim');
		$this->form_validation->set_rules('meeting_starttime', 'เวลาเริ่ม', 'required|trim');
		$this->form_validation->set_rules('meeting_endtime', 'เวลาสิ้นสุด', 'required|trim');
		$this->form_validation->set_rules('meeting_room', 'ห้องประชุม', 'trim');
		//$this->form_validation->set_rules('meeting_status', 'สถานะ', 'required|trim');

		$this->form_validation->set_message('required', 'กรุณาระบุข้อมูล {field}');

		if ($this->form_validation->run()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}
			$groups = $this->input->post('groups');

			if ($action == 'create') {
				$meeting_id = $this->Datas_model->insertData($data);
				$this->Agendas_model->defaltAgendas($meeting_id);
			} else {
				$this->Datas_model->updateData($meeting_id, $data);

			}
			$this->Datas_model->updateDataGroups($meeting_id, $groups);
			$this->Presents_model->saveGroupUsers($meeting_id, $this->Groups_model->getUsersByGroup($groups));
			$this->Users_model->saveTemporaryUsers($meeting_id, $this->input->post('users_temporary'));
			if (strtolower($this->input->post('meeting_status')) != 'cancel') {
				redirect(base_url("datas/view_data/{$meeting_id}"));
			}
			redirect(base_url("datas/dashboard_datas"));
			exit;
		} else {
			$config_pager = $this->config->item('pager');
			$config_pager['per_page'] = $this->form_per_page;
			$page = 0;
			$groups_list_selected = $user_temporary = array();

			if ($meeting_id != null && $meeting_id != '') {
				$config_pager['base_url'] = base_url("datas/save/{$meeting_id}");
				if (isset($_GET['per_page'])) $page = $_GET['per_page'];
				$count_rows = $this->Datas_model->countDataGroups(array('meeting_id' => $meeting_id));
				$config_pager['total_rows'] = $count_rows;
				$this->pagination->initialize($config_pager);
				$groups_list = $this->Datas_model->getDataGroups(array('meeting_id' => $meeting_id), array(), false, $config_pager['per_page'], $page);

				$groups_list_selected = $this->Datas_model->getDataGroups(array('meeting_id' => $meeting_id), array(), true);
				$user_temporary = $this->Datas_model->getDataUsersTemporary(array('meeting_id' => $meeting_id, 'user_status' => 'active'));
			} else {
				$config_pager['base_url'] = base_url("groups/save");
				if (isset($_GET['per_page'])) $page = $_GET['per_page'];
				$count_rows = $this->Groups_model->countGroups();
				$config_pager['total_rows'] = $count_rows;
				$this->pagination->initialize($config_pager);
				$groups_list = $this->Groups_model->getGroups(array('group_status' => 'active'), array(), $config_pager['per_page'], $page);
			}

			$data['content_data'] = array(
				'prefix_list' => $this->Commons_model->getPrefixList(),
				'gender_list' => $this->Commons_model->getGenderList(),
				'status_list' => $this->Commons_model->getDataStatusList(),
				'groups_list' => $groups_list,
				'groups_list_selected' => $groups_list_selected,
				'users_temporary' => $user_temporary,
				'data_data' => (object)array(
					'meeting_id' => $meeting_id,
					'meeting_name' => $this->input->post('meeting_name'),
					'meeting_project' => $this->input->post('meeting_project'),
					'meeting_description' => $this->input->post('meeting_description'),
					'meeting_date' => $this->input->post('meeting_date'),
					'meeting_starttime' => $this->input->post('meeting_starttime'),
					'meeting_endtime' => $this->input->post('meeting_endtime'),
					'meeting_room' => $this->input->post('meeting_room'),
					'meeting_status' => $this->input->post('meeting_status'),
				),

				'pages' => $this->pagination->create_links(),
				'count_rows' => $count_rows,
			);
			$data['content_view'] = 'pages/form_data';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_data($meeting_id = null)
	{
		$this->db->set('meeting_status', 'cancel');
		$this->db->where('meeting_id', $meeting_id);
		$this->db->update('data');
		redirect(base_url("datas/dashboard_datas"));
		exit;
	}

	public function status_data($meeting_id = null, $status = null)
	{
		$data = $this->Datas_model->getDatas(array('meeting_id' => $meeting_id))[0];
		if ($data->meeting_status != 'cancel') {
			$this->db->set('meeting_status', $status);
			$this->db->where('meeting_id', $meeting_id);
			$this->db->update('data');

			if (strtolower($status) == 'pending') {
				$this->send_email_pending($meeting_id, $data);
				redirect(base_url("attends/view_attend/{$meeting_id}"));
				exit;
			} else if (strtolower($status) == 'active') {
				$this->send_email_active($meeting_id, $data);
				redirect(base_url("attends/view_attend/{$meeting_id}"));
				exit;
			} else if (strtolower($status) == 'complete') {
				$this->send_email_complete($meeting_id, $data);
				redirect(base_url("records/view_record/{$meeting_id}"));
				exit;
			}
		}
		redirect(base_url("datas/view_data/{$meeting_id}"));
		exit;
	}

	public function get_calendar_events()
	{
		$json_datas = array();
		$datas = $this->Datas_model->getCalendarDatas();
		if (!empty($datas)) {
			foreach ($datas as $data) {
				$json_datas[] = array(
					'id' => $data->meeting_id,
					'title' => $data->meeting_name,
					'start' => "{$data->meeting_date} {$data->meeting_starttime}",
					'end' => "{$data->meeting_date} {$data->meeting_endtime}",
					'url' => base_url("datas/view_data/{$data->meeting_id}"),
					'allDay' => false
				);
			}
		}

		echo json_encode($json_datas);
		exit;
	}

	public function edit_present($meeting_id = null)
	{
		$cond = $this->search_form(array('citizen_id', 'prename', 'name', 'surname', 'position_code', 'level_code', 'department', 'email', 'telephone'));
		if ($this->input->post('form_search_element')['group_id'] != '') {
			$cond['group_id'] = $this->input->post('form_search_element')['group_id'];
		}
		$cond['meeting_id'] = $meeting_id;

		$config_pager = $this->config->item('pager');
		$config_pager['per_page'] = $this->present_per_page;
		$config_pager['base_url'] = base_url("datas/edit_present/{$meeting_id}");
		$count_rows = $this->Presents_model->countGroupPresents($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$groups_list = array();
		$groups = $this->Datas_model->getDataGroups(array('meeting_id' => $meeting_id), array(), true);
		if (!empty($groups)) {
			foreach ($groups as $group) {
				$groups_list[$group->group_id] = $group;
			}
		}

		$data['content_data'] = array(
			'search_url' => base_url("datas/edit_present/{$meeting_id}"),
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getPresentStatusList(),
			'meeting_id' => $meeting_id,
			'groups_list' => $groups_list,
			'users_list' => $this->Presents_model->getGroupPresents($cond, array(), $config_pager['per_page'], $page),
			'groups_selected_list' => $this->Datas_model->getDataGroups(array('meeting_id' => $meeting_id)),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/form_present';
		$this->load->view($this->theme, $data);
	}

	public function save_present($meeting_id = null)
	{
		$groups = $this->input->post('user_type_id');
		if (!empty($groups)) {
			foreach ($groups as $group_id => $users) {
				if (!empty($users)) {
					foreach ($users as $user => $type) {
						$data = array(
							'meeting_id' => $meeting_id,
							'group_id' => $group_id,
							'user_id' => $user,
							'user_type_id' => $type,
						);
						$this->Presents_model->savePresent($data);
					}
				}
			}
		}

		redirect(base_url("datas/view_data/{$meeting_id}"));
		exit;
	}

	public function delete_temporary_user($meeting_id = null, $user_id = null)
	{
		$this->Users_model->deleteUser($user_id);
		redirect(base_url("datas/edit_data/{$meeting_id}"));
		exit;
	}

	// function email sending

	public function send_email_pending($meeting_id = null, $data = array())
	{
		if ($meeting_id !== null && !empty($data)) {
			$meeting_date = date_thai($data->meeting_date, false);
			$meeting_starttime = time_thai($data->meeting_starttime, false);
			$meeting_endtime = time_thai($data->meeting_endtime);
			$users = $this->Datas_model->getDataUsers(array('meeting_id' => $meeting_id));
			if (!empty($users)) {
				$message = "ท่านได้ถูกเรียนเชิญให้เข้าร่วมประชุม {$data->meeting_name} ในวันที่ {$meeting_date} เวลา {$meeting_starttime} ถึง {$meeting_endtime}";
				$message .= "\r\nณ.ห้องประชุม{$data->meeting_room} โครงการ{$data->meeting_project}";
				$message .= "\r\nโดยมีรายละเอียดดังนี้ {$data->meeting_description}";
				foreach ($users as $user) {
					if ($user->user_type == 'temporary') {
						$send = array(
							'subject' => "แจ้งเตือนเข้าร่วมประชุม {$data->meeting_name}",
							'message' => $message . "\r\nเข้าร่วมประชุมตามเวลาที่กำหนด : <a href=\"" . base_url("attends/view_attend/{$meeting_id}") . "\" target=\"_blank\">เข้าร่วมการประชุม</a>",
							'to' => $user->email,
						);
						$this->send_email($send);
					} else if (isset($this->system_configs['email_noti']) && strtolower($this->system_configs['email_noti']->config_status) == 'active') {
						$send = array(
							'subject' => "แจ้งเตือนเข้าร่วมประชุม {$data->meeting_name}",
							'message' => $message,
							'to' => $user->email,
						);
						$this->send_email($send);
					}
				}

				if (isset($this->system_configs['data_dupp_noti']) && strtolower($this->system_configs['data_dupp_noti']->config_status) == 'active') {
					$tmp_user = array();
					foreach ($users as $user) $tmp_user[$user->user_id] = $user->user_id;

					$condition_dupp = " ( {$this->db->dbprefix}data.meeting_date = '{$data->meeting_date}' AND (";
					$condition_dupp .= "{$this->db->dbprefix}data.meeting_starttime BETWEEN '{$data->meeting_starttime}' AND '{$data->meeting_endtime}'";
					$condition_dupp .= "OR {$this->db->dbprefix}data.meeting_endtime BETWEEN '{$data->meeting_starttime}' AND '{$data->meeting_endtime}'";
					$condition_dupp .= ")) ";
					$data_dupp = $this->Datas_model->getDatas(array($condition_dupp))[0];
					if (!empty($data_dupp)) {
						$meeting_date = date_thai($data->meeting_date, false);
						$meeting_starttime = time_thai($data->meeting_starttime, false);
						$meeting_endtime = time_thai($data->meeting_endtime);
						foreach ($data_dupp as $meeting_dupp) {
							$users_dupp = $this->Datas_model->getDataUsers(array('meeting_id' => $meeting_dupp->meeting_id));
							if (!empty($users_dupp)) {
								$meeting_dupp_date = date_thai($meeting_dupp->meeting_date, false);
								$meeting_dupp_starttime = time_thai($meeting_dupp->meeting_starttime, false);
								$meeting_dupp_endtime = time_thai($meeting_dupp->meeting_endtime);
								$message = "ท่านมีช่วงเวลาการเข้าประชุมที่ทับซ้อนกันระหว่าง {$data->meeting_name} ในวันที่ {$meeting_date} เวลา {$meeting_starttime} ถึง {$meeting_endtime} และ {$meeting_dupp} ในวันที่ {$meeting_dupp_date} เวลา {$meeting_dupp_starttime} ถึง {$meeting_dupp_endtime}";
								foreach ($users_dupp as $user) {
									if (isset($tmp_user[$user->user_id])) {
										$send = array(
											'subject' => "แจ้งเตือนช่วงเวลาการประชุมทับซ้อน {$data->meeting_name}",
											'message' => $message,
											'to' => $user->email,
										);
										$this->send_email($send);
									}
								}
							}
						}
					}
				}
			}
			return true;
		} else {
			return false;
		}
	}

	public function send_email_active($meeting_id = null, $data = array())
	{
		if ($meeting_id !== null && !empty($data)) {
			$meeting_date = date_thai($data->meeting_date, false);
			$meeting_starttime = time_thai($data->meeting_starttime, false);
			$meeting_endtime = time_thai($data->meeting_endtime);
			$user_temporary = $this->Datas_model->getDataUsersTemporary(array('meeting_id' => $meeting_id));
			if (!empty($user_temporary)) {
				$message = "ได้เริ่มการประชุม {$data->meeting_name} ในวันที่ {$meeting_date} เวลา {$meeting_starttime} ถึง {$meeting_endtime}";
				$message .= "\r\nณ.ห้องประชุม{$data->meeting_room} โครงการ{$data->meeting_project}";
				$message .= "\r\nโปรดเข้าร่วมประชุม : <a href=\"" . base_url("attends/view_attend/{$meeting_id}") . "\" target=\"_blank\">เข้าร่วมการประชุม</a>";

				foreach ($user_temporary as $user) {
					$send = array(
						'subject' => "แจ้งเตือนเข้าร่วมประชุม {$data->meeting_name}",
						'message' => $message,
						'to' => $user->email,
					);
					$this->send_email($send);
				}
			}
			return true;
		} else {
			return false;
		}
	}

	public function send_email_complete($meeting_id = null, $data = array())
	{
		if ($meeting_id !== null && !empty($data)) {
			$meeting_date = date_thai($data->meeting_date, false);
			$meeting_starttime = time_thai($data->meeting_starttime, false);
			$meeting_endtime = time_thai($data->meeting_endtime);
			$download_url = "\r\nPDF : " . base_url("reports/export/{$meeting_id}/pdf");
			$download_url .= "\r\nWord : " . base_url("reports/export/{$meeting_id}/word");
			$download_url .= "\r\nExcel : " . base_url("reports/export/{$meeting_id}/excel");
			$users = $this->Datas_model->getDataUsers(array('meeting_id' => $meeting_id));
			if (isset($this->system_configs['meeting_report']) && strtolower($this->system_configs['meeting_report']->config_status) == 'active') {
				$message = "จากการประชุม {$data->meeting_name} ในวันที่ {$meeting_date} เวลา {$meeting_starttime} ถึง {$meeting_endtime} ได้มีรายงานการประชุมดังนี้" . $download_url;
				foreach ($users as $user) {
					$send = array(
						'subject' => "แจ้งเตือนรายงานการประชุม {$data->meeting_name}",
						'message' => $message,
						'to' => $user->email,
					);
					$this->send_email($send);
				}
			}
			return true;
		} else {
			return false;
		}
	}

	public function send_email($data = array())
	{
		if (!empty($data)) {
			if (!empty($this->config->item('sendmail_smtp'))) { //use smtp
				$this->email->initialize($this->config->item('sendmail_smtp'));
			} else { //use sendmail
				$this->email->initialize();
			}

			$this->email->from($this->config->item('sender_email'), $this->config->item('sender_name'));
			$this->email->to($data['to']);
			if (isset($data['cc']) && $data['cc'] != '') $this->email->cc($data['cc']);
			if (isset($data['bcc']) && $data['bcc'] != '') $this->email->bcc($data['bcc']);

			$this->email->subject($data['subject']);
			$this->email->message($data['message']);

			if (!$this->email->send()) {
				// Generate error
				$this->email->print_debugger();
			}
		}
	}

	public function test_send_email() // function for test send email datas/test_send_email?to={your email}
	{
		$email = $this->input->get('to');
		if ($email != '') {
			if (!empty($this->config->item('sendmail_smtp'))) { //use smtp
				$this->email->initialize($this->config->item('sendmail_smtp'));
			} else { //use sendmail
				$this->email->initialize();
			}

			$this->email->from($this->config->item('sender_email'), $this->config->item('sender_name'));
			$this->email->to($email);

			$this->email->subject('Test send email.');
			$this->email->message('Success!');

			if (!$this->email->send()) {
				// Generate error
				echo $this->email->print_debugger();
			}else{
				echo 'Send success!';
			}
		}else{
			echo 'Valid email!';
		}
		exit;
	}
}
