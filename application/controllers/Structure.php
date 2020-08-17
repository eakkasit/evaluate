<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Structure extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model','Structure_model','KpiTree_model','Kpi_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("structure/dashboard_structure"));
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

	public function dashboard_structure()
	{
		$cond = $this->search_form(array('profile_name', 'year', 'detail', 'status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("structure/dashboard_structure");
		$count_rows = $this->Structure_model->countStructure($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("structure/dashboard_structure"),
			'status_list' => $this->Commons_model->getActiveList(),
			'datas' => $this->Structure_model->getStructure($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_structure';
		$this->load->view($this->theme, $data);
	}

	public function view_structure($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->Structure_model->getStructure(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_structure';
		$this->load->view($this->theme, $data);
	}

	public function new_structure($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
		);
		$data['content_view'] = 'pages/form_structure';
		$this->load->view($this->theme, $data);
	}

	public function edit_structure($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->Structure_model->getStructure(array('structure_id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_structure';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('structure_name', 'ชื่อแม่แบบเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_rules('profile_year', 'ปี', 'required|trim');
		$this->form_validation->set_rules('structure_status', 'สถานะการใช้งาน', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($structure_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($structure_id != null && $structure_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$structure_id = $this->Structure_model->insertStructure($data);
				redirect(base_url("structure/dashboard_structure"));
				exit;
			} else {
				$this->Structure_model->updateStructure($structure_id, $data);
				redirect(base_url("structure/dashboard_structure"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'status_list'=> $this->Commons_model->getActiveList(),
				'year_list' => $this->Commons_model->getYearList(),
				'data' => (object)array(
					'structure_id' => $structure_id,
					'structure_name' => $this->input->post('structure_name'),
					'profile_name' => $this->input->post('profile_year'),
					'structure_status' => $this->input->post('structure_status')
				)
			);
			$data['content_view'] = 'pages/form_structure';
			$this->load->view($this->theme, $data);
		}
	}



	public function delete_structure($id = null)
	{
		$this->Structure_model->deleteStructure($id);
		redirect(base_url("structure/dashboard_structure"));
		exit;
	}


	public function new_structure_tree($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
			'structure_id' => $id,
			'units_list'=> $this->Commons_model->getUnitsList(),
			'tree_list' => $this->KpiTree_model->getTree($id,0,'')
		);
		$data['content_view'] = 'pages/form_structure_tree';
		$this->load->view($this->theme, $data);
	}


	public function ajax_save_tree($value='')
	{
		$data = array();
		foreach ($_POST as $key => $value) {
			$data[$key] = $this->input->post($key);
		}
		// echo "<pre>";
		// print_r($data);
		// echo $this->KpiTree_model->loopTreeSelect('1','1','1','','');
		if($data['tree_id']!=''){
			$this->KpiTree_model->updateKpiTree($data['tree_id'],$data);
		}else{
			$this->KpiTree_model->insertKpiTree($data);
		}
		// die();
		// $this->KpiTree_model->insertKpiTree($data);
	}

	public function ajax_tree_list($id='')
	{
		echo $this->KpiTree_model->getTree($id,0,'');
	}

	public function ajax_search_kpi($search_text='')
	{
		$data = array();
		$data = $this->Kpi_model->getKpi(array('kpi_name LIKE '=>'%'.$_GET['keyword'].'%','kpi_id !='=>''));
		$data['content_data'] = array(
			// 'profile_id' => $id,
			'data' => (object) $data,
			'units_list' => $this->Commons_model->getUnitsList(),
			'structure_id' => isset($_GET['structure_id'])?$_GET['structure_id']:'',
			'tree_id' => isset($_GET['structure_id'])?$_GET['tree_id']:'',
			'tree_number' => isset($_GET['structure_id'])?$_GET['tree_number']:'',
			'tree_weight' => isset($_GET['structure_id'])?$_GET['tree_weight']:'',
			'tree_target' => isset($_GET['structure_id'])?$_GET['tree_target']:'',
			// 'activities' => $this->Activities_model->getActivityLists(),
		);
		$data['content_view'] = 'ajax/ajax_search_kpi';
		$this->load->view('ajax', $data);
	}

	public function ajax_save_kpi($id='')
	{
		$data = array();
		foreach ($_GET as $key => $value) {

			if($key == 'tree_id'){
				$data['tree_parent'] = $this->input->get($key);
			}else{
				$data[$key] = $this->input->get($key);
			}
		}
		$data['tree_type'] = 2;
		$data['tree_name'] = '';

		$this->KpiTree_model->insertKpiTree($data);
		redirect(base_url("structure/new_structure_tree/".$_GET['structure_id']));
	}

	public function ajax_kpi_tree($id='')
	{
		echo $this->KpiTree_model->getTreeList($id,0,'');
	}

	public function ajax_tree_detail($structure_id,$tree_id="")
	{
		$tree_data_tmp = array();
		$tree_data = array();
		$kpi_data = array();
		$condition = array();
		$tree_parent_name = '';
		$parent_list = array();
		$tree_name = array();
		if($tree_id != ""){
			$condition = array('tree_id'=>$tree_id);
		}else{
			$condition = array('structure_id'=>$structure_id,'tree_parent'=>0);
		}
		$tree_data_tmp = $this->KpiTree_model->getKpiTree($condition,array('ABS(tree_number)'=>'ASC','tree_id'=>'ASC'));
		if(!empty($tree_data_tmp)){
			$tree_data = (object) $tree_data_tmp[0];
			if($tree_data->tree_parent == '0'){
				$tree_parent_name = 'หมวดหมู่หลัก';
			}else{
				$tree_parent_name = $this->KpiTree_model->getKpiTree(array('tree_id'=>$tree_id))[0]->tree_name;
			}

			if($tree_data->kpi_id != '0'){
				$kpi_data = $this->Kpi_model->getKpi(array('kpi_id' => $tree_data->kpi_id))[0];
			}

		}

		$parent_list = $this->KpiTree_model->getKpiTree(array('tree_parent'=>$tree_id),array('ABS(tree_number)'=>'ASC','tree_id'=>'ASC'));
		if(!empty($parent_list)){
			foreach ($parent_list as $key => $value) {
				if($value->tree_type=='1'){
					$tree_name[$value->tree_id] = $value->tree_name;
				}else{
					$tree_name[$value->tree_id] = $this->Kpi_model->getKpi(array('kpi_id' => $value->kpi_id))[0]->kpi_name;
				}
			}
		}

		$data['content_data'] = array(
			'data' => $tree_data,
			'parent_list' => $parent_list,
			'units_list' => $this->Commons_model->getUnitsList(),
			'structure_id' => $structure_id,
			'tree_id' => $tree_id,
			'tree_parent_name' => $tree_parent_name,
			'kpi_data' => (object) $kpi_data,
			'tree_name' => $tree_name
		);
		$data['content_view'] = 'ajax/ajax_kpi_detail';
		$this->load->view('ajax', $data);
	}

	public function ajax_get_tree_data($id='')
	{
		$data = array();
		$data = $this->KpiTree_model->getKpiTree(array('tree_id'=>$id))[0];
		if($data){
			if($data->kpi_id != 0){
				$data->kpi_name = $this->Kpi_model->getKpi(array('kpi_id'=>$data->kpi_id))[0]->kpi_name;
			}
		}
		echo json_encode($data);
	}

	public function deleteKpiTree($id='')
	{
		$this->KpiTree_model->deleteKpiTree($id);
	}

	public function testWeight($id='',$kpi='')
	{
		$this->KpiTree_model->treeweightsum($id,$kpi);
	}
}
