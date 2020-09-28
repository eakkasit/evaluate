<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{
	private $theme = 'default';
	private $upload_config = array(
		'upload_path' => '/var/www/php56/meeting/meetingsystem/assets/uploads/',
		'allowed_types' => 'gif|jpg|png',
		'max_size' => 5120,// 5 MB
		'max_width' => 1024,// 1024 PX
		'max_height' => 768,// 768 PX
	);
	private $image_config = array(
		'image_library' => 'gd2',
		'source_image' => '',
		'maintain_ratio' => false,
		'width' => 256,
		'height' => 256,
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Logs_model', 'Configs_model', 'Users_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("users/dashboard_users"));
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

	public function dashboard_users()
	{
		$cond = $this->search_form(array('citizen_id', 'prename', 'name', 'surname', 'position_code', 'level_code', 'department', 'email', 'telephone'));
		$cond['user_type'] = 'employee';

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("users/dashboard_users");
		$count_rows = $this->Users_model->countUsers($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("users/dashboard_users"),
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'gender_list' => $this->Commons_model->getGenderList(),
			'status_list' => $this->Commons_model->getUserStatusList(),
			'users' => $this->Users_model->getUsers($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_users';
		$this->load->view($this->theme, $data);
	}

	public function view_user($user_id = null)
	{
		$user_data = $this->Users_model->getUsers(array('user_id' => $user_id))[0];
		$data['content_data'] = array(
			'prefix' => $this->Commons_model->getPrefixList($user_data->prename),
			'gender' => $this->Commons_model->getGenderList($user_data->gender),
			'status_list' => $this->Commons_model->getUserStatusList(),
			'user_data' => $user_data,
		);
		$data['content_view'] = 'pages/view_user';
		$this->load->view($this->theme, $data);
	}

	public function new_user()
	{
		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'gender_list' => $this->Commons_model->getGenderList(),
			'status_list' => $this->Commons_model->getUserStatusList(),
		);
		$data['content_view'] = 'pages/form_user';
		$this->load->view($this->theme, $data);
	}

	public function edit_user($user_id = null)
	{
		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'gender_list' => $this->Commons_model->getGenderList(),
			'status_list' => $this->Commons_model->getUserStatusList(),
			'user_data' => $this->Users_model->getUsers(array('user_id' => $user_id))[0]
		);
		$data['content_view'] = 'pages/form_user';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('citizen_id', 'เลขบัตรประชาชน', 'trim|exact_length[13]');
		$this->form_validation->set_rules('prename', 'คำนำหน้าชื่อ', 'required|trim');
		$this->form_validation->set_rules('name', 'ชื่อ', 'required|trim');
		$this->form_validation->set_rules('surname', 'นามสกุล', 'required|trim');
		$this->form_validation->set_rules('position_code', 'ตำแหน่ง', 'trim');
		$this->form_validation->set_rules('level_code', 'ระดับ', 'trim');
		$this->form_validation->set_rules('gender', 'เพศ', 'trim');
		$this->form_validation->set_rules('department', 'สังกัด', 'trim');
		$this->form_validation->set_rules('email', 'อีเมล์', 'required|trim|valid_email');
		$this->form_validation->set_rules('telephone', 'หมายเลขโทรศัพท์', 'required|trim|numeric|exact_length[10]');
		//$this->form_validation->set_rules('user_status', 'สถานะผู้ใช้ระบบประชุม', 'required');
		//$this->form_validation->set_rules('user_type', 'ประเภทผู้ใช้ระบบประชุม', 'trim');
		$this->form_validation->set_rules('profile_picture', 'ชื่อรูปโปรไฟล์', 'trim');

		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($user_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($user_id != null && $user_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if (!empty($_FILES) && $_FILES['profile_picture']['name'] != '') {
				$config = $this->upload_config;
				$config['file_name'] = md5(uniqid(rand(), true));//$this->input->post('citizen_id');
				$this->load->library('upload', $config);

				if ($this->upload->do_upload('profile_picture')) {
					$upload = array('upload_data' => $this->upload->data());
					$data['profile_picture'] = $upload['upload_data']['file_name'];

					if ($upload['upload_data']['image_width'] != $upload['upload_data']['image_height']) {//resize
						$min_size = $upload['upload_data']['image_width'];
						if ($upload['upload_data']['image_height'] < $upload['upload_data']['image_width']) {
							$min_size = $upload['upload_data']['image_height'];
						}
						$config_image = $this->image_config;
						$config_image['source_image'] = $upload['upload_data']['full_path'];
						$config_image['width'] = $min_size;
						$config_image['height'] = $min_size;
						$config_image['y_axis'] = ($upload['upload_data']['image_height'] - $min_size) / 2;
						$config_image['x_axis'] = ($upload['upload_data']['image_width'] - $min_size) / 2;
						$this->load->library('image_lib', $config_image);
						$this->image_lib->crop();
					}
				} else {
					$upload_msg = $this->upload->display_errors();
					$error_page = true;
				}
			} else {
				if ($action == 'update' && $this->input->post('profile_picture_tmp') == '') {//check unlink file
					$this->check_unlink_file($user_id);
				}
				$data['profile_picture'] = $this->input->post('profile_picture_tmp');
			}
			if (!$error_page) {
				if ($action == 'create') {
					$user_id = $this->Users_model->insertUser($data);
					if ($this->input->post('user_status') == 'active') {
						redirect(base_url("users/view_user/{$user_id}"));
					} else {
						redirect(base_url("users/dashboard_users"));
					}
					exit;
				} else {
					$this->Users_model->updateUser($user_id, $data);
					if ($this->input->post('user_status') == 'active') {
						redirect(base_url("users/view_user/{$user_id}"));
					} else {
						redirect(base_url("users/dashboard_users"));
					}
					exit;
				}
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'prefix_list' => $this->Commons_model->getPrefixList(),
				'gender_list' => $this->Commons_model->getGenderList(),
				'status_list' => $this->Commons_model->getUserStatusList(),
				'upload_msg' => $upload_msg,
				'user_data' => (object)array(
					'user_id' => $user_id,
					'citizen_id' => $this->input->post('citizen_id'),
					'prename' => $this->input->post('prename'),
					'name' => $this->input->post('name'),
					'surname' => $this->input->post('surname'),
					'position_code' => $this->input->post('position_code'),
					'level_code' => $this->input->post('level_code'),
					'gender' => $this->input->post('gender'),
					'department' => $this->input->post('department'),
					'email' => $this->input->post('email'),
					'telephone' => $this->input->post('telephone'),
					'user_status' => $this->input->post('user_status'),
					'profile_picture' => $this->input->post('profile_picture'),
					'profile_picture_tmp' => $this->input->post('profile_picture_tmp'),
				)
			);
			$data['content_view'] = 'pages/form_user';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_user($user_id = null)
	{
		$this->Users_model->deleteUser($user_id);
		redirect(base_url("users/dashboard_users"));
		exit;
	}

	public function ajax_validate()
	{
		//$_POST = json_decode(file_get_contents("php://input"), true);
		$json_response = array();
		if (!$this->validate()) {
			$json_response = array(
				'citizen_id' => form_error('citizen_id'),
				'prename' => form_error('prename'),
				'name' => form_error('name'),
				'surname' => form_error('surname'),
				'position_code' => form_error('position_code'),
				'level_code' => form_error('level_code'),
				'gender' => form_error('gender'),
				'department' => form_error('department'),
				'email' => form_error('email'),
				'telephone' => form_error('telephone'),
				'user_status' => form_error('user_status'),
				'user_type' => form_error('user_type'),
				'profile_picture' => form_error('profile_picture'),
			);

		}

		echo json_encode($json_response);
		exit;
	}

	public function check_unlink_file($user_id = null)
	{
		$user_data = $this->Users_model->getUsers(array('user_id' => $user_id))[0];
		if (isset($user_data->profile_picture) && $user_data->profile_picture != '') {
			$file = $this->upload_config['upload_path'] . $user_data->profile_picture;
			if (file_exists($file)) {
				unlink($file);
				return true;
			}
		} else {
			return false;
		}
	}
}
