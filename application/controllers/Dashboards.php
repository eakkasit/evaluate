<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboards extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
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

	public function index($year = null)
	{
		$cond = $this->search_form(array('meeting_name', 'meeting_project', 'meeting_description', 'meeting_room'));
		$cond['meeting_status'] = 'active';

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("records/dashboard_records");
		$count_rows = $this->Agendas_model->countDataAgendas($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		if ($year == null) $year = date('Y');
		$data['content_data'] = array(
			'search_url' => base_url("dashboards"),
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'overview' => $this->Datas_model->getDatasOverview($year),
			'meetings' => $this->Agendas_model->getDataAgendas($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/dashboard_overview';
		$this->load->view($this->theme, $data);
	}
}
