<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model','Kpi_model','Formula_model','Variable_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("kpi/dashboard_kpi"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element')['text'] != '' && !empty($fields)) {
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

	public function dashboard_kpi()
	{
		$cond = $this->search_form(array('kpi_name'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("kpi/dashboard_kpi");
		$count_rows = $this->Kpi_model->countKpi($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("kpi/dashboard_kpi"),
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'level_list'=> $this->Commons_model->getLevelList(),
			'datas' => $this->Kpi_model->getKpi($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_kpi';
		$this->load->view($this->theme, $data);
	}

	public function view_kpi($id = null)
	{
		$data['content_data'] = array(
			'level_list'=> $this->Commons_model->getLevelList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'variable'=> (object) $this->Variable_model->getVariable(),
			'variable_lists'=> $this->Variable_model->getVariableLists(),
			'variable_unit_lists'=> $this->Variable_model->getVariableUnitLists(),
			'formulas' => $this->Formula_model->getFormula(array('kpi_id'=>$id)),
			'data' => $this->Kpi_model->getKpi(array('kpi_id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_kpi';
		$this->load->view($this->theme, $data);
	}

	public function new_kpi($id = null)
	{
		$data['content_data'] = array(
			'level_list'=> $this->Commons_model->getLevelList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'variable'=> (object) $this->Variable_model->getVariable(),
		);
		$data['content_view'] = 'pages/form_kpi';
		$this->load->view($this->theme, $data);
	}

	public function edit_kpi($id = null)
	{
		$data['content_data'] = array(
			'level_list'=> $this->Commons_model->getLevelList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'variable'=> (object) $this->Variable_model->getVariable(),
			'variable_lists'=> $this->Variable_model->getVariableLists(),
			'variable_unit_lists'=> $this->Variable_model->getVariableUnitLists(),
			'formulas' => $this->Formula_model->getFormula(array('kpi_id'=>$id)),
			'data' => $this->Kpi_model->getKpi(array('kpi_id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_kpi';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('kpi_name', 'ชื่อตัวแปรเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_rules('unit_id', 'หน่วยวัด', 'required|trim');
		$this->form_validation->set_rules('level_id', 'ชนิดของการแสดงผล', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($kpi_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($kpi_id != null && $kpi_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}
			// echo "<pre>";
			// print_r($data);
			// die();

			if ($action == 'create') {
				$kpi_id = $this->Kpi_model->insertKpi($data);
				if(isset($data['formula_value'])){
					$this->Formula_model->deleteKpiFormula($kpi_id);
					foreach( $data['formula_value'] as $key => $formula_value){
						$depend = $data['formula_depend'][$key];
						// $data_temp = array();
						$data_temp = array(
							'formula_value' => $formula_value,
							'var_id' => $key,
							'kpi_id' => $kpi_id,
							'depend' => $depend
						);
						$this->Formula_model->insertFormula($data_temp);
					}
				}
				redirect(base_url("kpi/dashboard_kpi"));
				exit;
			} else {
				$this->Kpi_model->updateKpi($kpi_id, $data);
				$this->Formula_model->deleteKpiFormula($kpi_id);
				if(isset($data['formula_value'])){
					foreach( $data['formula_value'] as $key => $formula_value){
						$depend = $data['formula_depend'][$key];
						// $data_temp = array();
						$data_temp = array(
							'formula_value' => $formula_value,
							'var_id' => $key,
							'kpi_id' => $kpi_id,
							'depend' => $depend
						);
						$this->Formula_model->insertFormula($data_temp);
					}
				}
				redirect(base_url("kpi/dashboard_kpi"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'show_type_list'=> $this->Commons_model->getShowTypeList(),
				'field_type_list'=> $this->Commons_model->getFieldTypeList(),
				'units_list'=> $this->Commons_model->getUnitsList(),
				'data' => (object)array(
					'var_id' => $kpi_id,
					'var_name' => $this->input->post('var_name'),
					'var_unit_id' => $this->input->post('var_unit_id'),
					'var_type_id' => $this->input->post('var_type_id'),
					'var_import_id' => $this->input->post('var_import_id'),
					'var_value' => $this->input->post('var_value'),
					'var_max_length' => $this->input->post('var_max_length'),
					'var_sql' => $this->input->post('var_sql'),
				)
			);
			$data['content_view'] = 'pages/form_kpi';
			$this->load->view($this->theme, $data);
		}
	}


	public function delete_kpi($id = null)
	{
		$this->Kpi_model->deleteKpi($id);
		redirect(base_url("kpi/dashboard_kpi"));
		exit;
	}

	public function ajax_get_unit_type($type_id,$id=null){
		$data = array();
		if($id){
			$data = (object) $this->Kpi_model->getKpi(array('kpi_id'=>$id))[0];
		}
		$data['content_data'] = array(
			'kpi_standard_type' => $type_id,
			'data' =>  $data,
			// 'variable_lists' => $this->CriteriaVariables_model->getCriteriaVariableLists()
		);
		$data['content_view'] = 'ajax/ajax_get_unit_type';
		$this->load->view('ajax', $data);
	}

}
