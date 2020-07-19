<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CI_Controller
{
	private $theme = 'default';
	private $form_per_page = 15;

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Logs_model', 'Configs_model', 'Groups_model', 'Users_model'));
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
		redirect(base_url("groups/dashboard_groups"));
		exit;
	}

	public function dashboard_groups()
	{
		$cond = $this->search_form(array('group_name', 'group_description'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("groups/dashboard_groups");
		$count_rows = $this->Groups_model->countGroups($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("groups/dashboard_groups"),
			'status_list' => $this->Commons_model->getGroupStatusList(),
			'groups' => $this->Groups_model->getGroups($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/dashboard_groups';
		$this->load->view($this->theme, $data);
	}

	public function view_group($group_id = null)
	{
		$config_pager = $this->config->item('pager');
		$config_pager['per_page'] = $this->form_per_page;
		$config_pager['base_url'] = base_url("groups/view_group/{$group_id}");
		$count_rows = $this->Groups_model->countGroupUsers(array('group_id' => $group_id), true);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getGroupStatusList(),
			'group_data' => $this->Groups_model->getGroups(array('group_id' => $group_id))[0],
			'users_list' => $this->Groups_model->getGroupUsers(array('group_id' => $group_id), array(), true, $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows
		);
		$data['content_view'] = 'pages/view_group';
		$this->load->view($this->theme, $data);
	}

	public function new_group()
	{
		$config_pager = $this->config->item('pager');
		$config_pager['per_page'] = $this->form_per_page;
		$config_pager['base_url'] = base_url("groups/new_group");
		$count_rows = $this->Users_model->countUsers(array('user_status' => 'active'));
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getGroupStatusList(),
			'users_list' => $this->Users_model->getUsers(array('user_status' => 'active'), array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/form_group';
		$this->load->view($this->theme, $data);
	}

	public function edit_group($group_id = null)
	{
		$config_pager = $this->config->item('pager');
		$config_pager['per_page'] = $this->form_per_page;
		$config_pager['base_url'] = base_url("groups/edit_group/{$group_id}");
		$count_rows = $this->Groups_model->countGroupUsers(array('group_id' => $group_id, 'user_status' => 'active'));
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		$users_list_selected = $this->Groups_model->getGroupUsers(array('group_id' => $group_id), array(), true);
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getGroupStatusList(),
			'group_data' => $this->Groups_model->getGroups(array('group_id' => $group_id))[0],
			'users_list' => $this->Groups_model->getGroupUsers(array('group_id' => $group_id, 'user_status' => 'active'), array(), false, $config_pager['per_page'], $page),
			'users_list_selected' => $users_list_selected,

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/form_group';
		$this->load->view($this->theme, $data);
	}

	public function save($group_id = null)
	{
		$action = 'create';
		if ($group_id != null && $group_id != '') {
			$action = 'update';
		}

		$this->form_validation->set_rules('group_name', 'องค์คณะประชุม', 'required|trim');
		$this->form_validation->set_rules('group_status', 'สถานะองค์คณะประชุม', 'required|trim');
		$this->form_validation->set_rules('group_description', 'รายละเอียด', 'required|trim');

		$this->form_validation->set_message('required', 'กรุณาระบุข้อมูล {field}');

		if ($this->form_validation->run()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$group_id = $this->Groups_model->insertGroup($data);
				$this->Groups_model->updateGroupUsers($group_id, $this->input->post('users'));
				if (strtolower($this->input->post('group_status')) == 'active') {
					redirect(base_url("groups/view_group/{$group_id}"));
				} else {
					redirect(base_url("groups/dashboard_groups"));
				}
				exit;
			} else {
				$this->Groups_model->updateGroup($group_id, $data);
				$this->Groups_model->updateGroupUsers($group_id, $this->input->post('users'));
				if (strtolower($this->input->post('group_status')) == 'active') {
					redirect(base_url("groups/view_group/{$group_id}"));
				} else {
					redirect(base_url("groups/dashboard_groups"));
				}
				exit;
			}
		} else {
			$config_pager = $this->config->item('pager');
			$config_pager['per_page'] = $this->form_per_page;
			$page = 0;
			$users_list_selected = array();

			if ($group_id != null && $group_id != '') {
				$config_pager['base_url'] = base_url("groups/save/{$group_id}");
				if (isset($_GET['per_page'])) $page = $_GET['per_page'];
				$count_rows = $this->Groups_model->countGroupUsers(array('group_id' => $group_id, 'user_status' => 'active'), false);
				$config_pager['total_rows'] = $count_rows;
				$this->pagination->initialize($config_pager);
				$users_list = $this->Groups_model->getGroupUsers(array('group_id' => $group_id, 'user_status' => 'active'), array(), false, $config_pager['per_page'], $page);

				$users_list_selected = $this->Groups_model->getGroupUsers(array('group_id' => $group_id), array(), true);
			} else {
				$config_pager['base_url'] = base_url("groups/save");
				if (isset($_GET['per_page'])) $page = $_GET['per_page'];
				$count_rows = $this->Users_model->countUsers(array('user_status' => 'active'));
				$config_pager['total_rows'] = $count_rows;
				$this->pagination->initialize($config_pager);
				$users_list = $this->Users_model->getUsers(array('user_status' => 'active'), array(), $config_pager['per_page'], $page);
			}

			$data['content_data'] = array(
				'prefix_list' => $this->Commons_model->getPrefixList(),
				'status_list' => $this->Commons_model->getGroupStatusList(),
				'users_list' => $users_list,
				'users_list_selected' => $users_list_selected,
				'group_data' => (object)array(
					'group_id' => $group_id,
					'group_name' => $this->input->post('group_name'),
					'group_status' => $this->input->post('group_status'),
					'group_description' => $this->input->post('group_description'),
				),

				'pages' => $this->pagination->create_links(),
				'count_rows' => $count_rows,
			);
			$data['content_view'] = 'pages/form_group';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_group($group_id = null)
	{
		$this->db->set('group_status', 'revoke');
		$this->db->where('group_id', $group_id);
		$this->db->update('group');
		$this->db->where('group_id', $group_id);
		$this->db->delete('user2group');
		redirect(base_url("groups/dashboard_groups"));
		exit;
	}

	public function get_group_users($group_id)
	{
		$group = $this->Groups_model->getGroups(array('group_id' => $group_id))[0];
		$users = $this->Groups_model->getGroupUsers(array('group_id' => $group_id), array(), true);
		$group_users = array();
		if (!empty($users)) {
			$prefix_list = $this->Commons_model->getPrefixList();
			foreach ($users as $user) {
				$profile_picture = base_url("assets/images/no_images.jpg");
				if (isset($user->profile_picture) && $user->profile_picture != '') {
					$profile_picture = base_url("assets/uploads/{$user->profile_picture}");
				}

				$group_users[] = array(
					'img' => $profile_picture,
					'full_name' => "{$prefix_list[$user->prename]} {$user->name}   {$user->surname}",
					'position_code' => $user->position_code,
					'department' => $user->department,
				);
			}
		}
		$json_group = array(
			'group_name' => $group->group_name,
			'users_list' => $group_users,
		);
		echo json_encode($json_group);
		exit;
	}
}
