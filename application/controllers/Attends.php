<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attends extends CI_Controller
{
	private $theme = 'default';
	private $system_configs = array();
	private $upload_config = array(
		'upload_path' => '/var/www/php56/meeting/meetingsystem/assets/attaches/',
		'allowed_types' => 'gif|jpg|jpeg|jpe|png|pdf|doc|docx',
		'max_size' => 10240,// 10 MB
	);
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
		redirect(base_url("attends/dashboard_attends"));
		exit;
	}

	public function dashboard_attends($year = null)
	{
		$cond = $this->search_form(array('meeting_name', 'meeting_project', 'meeting_description', 'meeting_room'));
		$cond['meeting_status'] = array('active','pending');//' (meeting_status = \'active\' OR meeting_status = \'pending\')';//array('active','pending');
		// $cond['meeting_status'] = array('OR'=>array('active','pending'));
		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("attends/dashboard_attends");
		$count_rows = $this->Agendas_model->countDataAgendas($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		if ($year == null) $year = date('Y');
		$data['content_data'] = array(
			'search_url' => base_url("attends/dashboard_attends"),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'overview' => $this->Datas_model->getDatasOverview($year),
			'meetings' => $this->Agendas_model->getDataAgendas($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);
		$data['content_view'] = 'pages/dashboard_attends';
		$this->load->view($this->theme, $data);
	}

	public function view_attend($meeting_id = null)
	{
		$data['content_data'] = array(
			'prefix_list' => $this->Commons_model->getPrefixList(),
			'status_list' => $this->Commons_model->getDataStatusList(),
			'type_list' => $this->Agendas_model->getAgendaTypes(),
			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'files' => $this->Agendas_model->getDataAgendasFiles(array('data.meeting_id' => $meeting_id)),
			'attends' => $this->Records_model->getAgendasRecord($meeting_id),
		);
		$data['content_view'] = 'pages/view_attend';
		$this->load->view($this->theme, $data);
	}

	public function edit_attend($meeting_id = null, $agenda_id = null)
	{
		$record = array();
		$record_data = $this->Records_model->getRecord(array('meeting_id' => $meeting_id, 'agenda_id' => $agenda_id, 'record.user_id' => $this->user_id));
		if (!empty($record_data)) {
			$record = $record_data[0];
			unset($record_data);
		}

		$user_type = array();
		$user_type_data = $this->Records_model->getUserType(array('meeting_id' => $meeting_id,'user_id'=>$this->user_id));
		if (!empty($user_type_data)) {
			$user_type = $user_type_data[0];
			unset($user_type_data);
		}
		else{
			$data['content_data'] = array(
				'configs' => $this->system_configs,
				'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
				'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
				'agenda' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id, 'agenda.agenda_id' => $agenda_id))[0],
				'files' => $this->Agendas_model->getAgendaFiles(array('agenda_id' => $agenda_id)),
				'permit' => false
			);
			$data['content_view'] = 'pages/form_attend';
			$this->load->view($this->theme, $data);
			return;
			// redirect(base_url("attends/dashboard_attends"));
			// exit;
		}

		$meeting_status = array();
		$meeting_status_data = $this->Records_model->getMeetingStatus(array('meeting_id' => $meeting_id, 'agenda_id' => $agenda_id));
		if(!empty($meeting_status_data)){
			$meeting_status = $meeting_status_data[0];
		}
		
		$user_meeting = array();
		$user_meeting_data = $this->Records_model->getRecordLog(array('meeting_id' => $meeting_id, 'agenda_id' => $agenda_id,'user_id' => $this->user_id));
		if(!empty($user_meeting_data)){
			$user_meeting = $user_meeting_data[0];
		}



		$data['content_data'] = array(
			'configs' => $this->system_configs,

			'meeting_data' => $this->Agendas_model->getDataAgendas(array('meeting_id' => $meeting_id))[0],
			'agendas' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id)),
			'agenda' => $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id, 'agenda.agenda_id' => $agenda_id))[0],
			'files' => $this->Agendas_model->getAgendaFiles(array('agenda_id' => $agenda_id)),
			'record' => $record,
			'user_id' => $this->user_id,
			'user_fullname' => $this->user_fullname,
			'user_type' => $user_type->user_type_id,
			'meeting_status' => $meeting_status,
			'user_meeting' => $user_meeting,
			'permit' => true
		);
		$data['content_view'] = 'pages/form_attend';
		$this->load->view($this->theme, $data);
	}

	public function save($meeting_id = null, $agenda_id = null)
	{
		$data = array();
		foreach ($_POST as $key => $value) {
			$data[$key] = $this->input->post($key);
		}
		$save_data = $this->Records_model->saveRecord($data);
		if (!empty($_FILES) && $_FILES['record_file']['name'][0] != '') {
			$config = $this->upload_config;
			$this->load->library('upload', $config);
			$config['upload_path'] .= "{$meeting_id}/";

			if (!file_exists($config['upload_path'])) {
				mkdir($config['upload_path'], 0777);
				chmod($config['upload_path'], 0777);
			}

			$files = $_FILES;
			$cpt = count($_FILES['record_file']['name']);
			for ($i = 0; $i < $cpt; $i++) {
				$_FILES['record_file']['name'] = $files['record_file']['name'][$i];
				$_FILES['record_file']['type'] = $files['record_file']['type'][$i];
				$_FILES['record_file']['tmp_name'] = $files['record_file']['tmp_name'][$i];
				$_FILES['record_file']['error'] = $files['record_file']['error'][$i];
				$_FILES['record_file']['size'] = $files['record_file']['size'][$i];

				$config['file_name'] = "{$meeting_id}_{$agenda_id}_{$save_data}_" . time();
				$this->upload->initialize($config);
				if ($this->upload->do_upload('record_file')) {
					$upload = array('upload_data' => $this->upload->data());

					$file = array(
						'record_id' => $save_data,
						'record_filename' => $upload['upload_data']['file_name'],
						'record_detail' => $files['record_file']['name'][$i]
					);
					$this->Records_model->insertRecordFile($file);
				} else {
					$upload_msg = $this->upload->display_errors();
					$error_page = true;
					break;
				}
			}
		}

		echo json_encode($this->Records_model->getRecordSave(array('record_id'=>$save_data))[0]);
		exit;
	}

	public function delete_record($meeting_id = null, $agenda_id = null)
	{
		$this->Agendas_model->deleteAgendaFiles("{$this->upload_config[upload_path]}{$meeting_id}/", $agenda_id);
		$this->Agendas_model->deleteAgenda($meeting_id, $agenda_id);
		redirect(base_url("attends/view_record/{$meeting_id}"));
		exit;
	}

	public function get_meeting_attends($meeting_id = null, $agenda_id = null)
	{
		$agenda = $this->Agendas_model->getAgendas(array('agenda.meeting_id' => $meeting_id, 'agenda.agenda_id' => $agenda_id))[0];
		$attends = $this->Records_model->getAgendasRecord($meeting_id);
		$meeting_record = array();
		if (isset($attends[$agenda_id]) && !empty($attends[$agenda_id])) {
			$prefix_list = $this->Commons_model->getPrefixList();
			foreach ($attends[$agenda_id] as $record) {
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
			'attends_list' => $meeting_record,
		);
		echo json_encode($json_record);
		exit;
	}

	public function get_history($meeting_id = null, $agenda_id = null)
	{
		$record = array();
		$record_data = $this->Records_model->getRecords(array('record.meeting_id' => $meeting_id, 'agenda_id' => $agenda_id));
		foreach ($record_data as $key => $value) {
			$files = $this->Records_model->getRecordFiles(array('record_id' => $value->record_id));
			?>
			<li class="dd-item dd2-item" data-id="<?php echo $value->record_id ?>">
				<div class="widget-box widget-color-blue">
					<div class="widget-header widget-header-small">
						<h4 class="widget-title">
							<?php echo $value->position."(".$value->fullname.")" ?>
						</h4>
						<div class="widget-toolbar">
							<?php 
								if(!empty($files)){
									?>
									<div class="inline pull-right position-relative dropdown-hover">
										<button class="btn btn-minier bigger btn-primary"  title="เอกสารแนบ">
											<i class="ace-icon fa fa-download icon-only bigger-120" title="เอกสารแนบ"></i>
										</button>

										<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-caret dropdown-close dropdown-menu-right">
											<?php 
												foreach ($files as $key => $file) {
													?>
													<li>
														<a href="<?php echo base_url("assets/attaches/{$meeting_id}/{$file->record_filename}"); ?>" class="tooltip-success" data-rel="tooltip" title="Download">
															<span class="green">
																<i class="ace-icon fa fa-paperclip bigger-110"></i>
																<?php echo $file->record_detail ?>
															</span>
														</a>
													</li>
													<?php

												}
											?>
										</ul>
									</div>
									<?php
								}
							?>
							<span class="label label-info label-sm">
								<?php echo time_thai(date('H:i',strtotime($value->create_date))) ?></span>
						</div>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<p class="alert alert-info">
								<?php echo $value->record_detail ?>
							</p>
						</div>
						<div class="widget-toolbox padding-4 clearfix">
							<div class="btn-group pull-left">
							</div>
							<div class="btn-group pull-right">
								<?php 
									if($value->user_id != $this->user_id){
										?>
										<button class="btn btn-sm btn-danger btn-white btn-round" onclick="reply_comment(<?php echo $value->record_id ?>)">
											<i class="ace-icon fa fa-reply bigger-125"></i>
											ตอบกลับ
										</button>
										<?php
									}else{
										?>
										<a class="btn btn-sm btn-danger btn-white btn-round edit-record" data-id="<?php echo $value->record_id ?>">
											<i class="ace-icon fa fa-edit bigger-125"></i>
											แก้ไข
										</a>
										<?php
									}
								?>
								
							</div>
						</div>
					</div>
				</div>
				<?php
			if(!empty($value->reply)){
				echo '<ol class="dd-list sub">';
				foreach ($value->reply as $key => $reply) {
					$file_replys = $this->Records_model->getRecordFiles(array('record_id' => $reply->record_id));
					?>
					
						<div class="space"></div>
						<li class="dd-item dd2-item">
							<div class="widget-box widget-color-orange">
								<div class="widget-header widget-header-small"  title="ตอบกลับ">
									<h4 class="widget-title"><?php echo $reply->position."(".$reply->fullname.")" ?> ตอบกลับ</h4>
									<div class="widget-toolbar">
										<span class="label label-warning label-sm"><?php echo time_thai(date('H:i',strtotime($reply->create_date))) ?></span>
										<?php 
										if(!empty($file_replys)){
											?>
											<div class="inline pull-right position-relative dropdown-hover">
												<button class="btn btn-minier bigger btn-warning" title="เอกสารแนบ">
													<i class="ace-icon fa fa-download icon-only bigger-120"></i>
												</button>

												<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-caret dropdown-close dropdown-menu-right">
													<?php 
														foreach ($file_replys as $key => $file_reply) {
															?>
															<li>
																<a href="<?php echo base_url("assets/attaches/{$meeting_id}/{$file_reply->record_filename}"); ?>" class="tooltip-success" data-rel="tooltip" title="Download">
																	<span class="green">
																		<i class="ace-icon fa fa-paperclip bigger-110"></i>
																		<?php echo $file_reply->record_detail ?>
																	</span>
																</a>
															</li>
															<?php

														}
													?>
												</ul>
											</div>
											<?php
										}
									?>
									</div>
								</div>
								<div class="widget-body">
									<div class="widget-main">
										<p class="alert alert-warning"><?php echo $reply->record_detail ?></p>
									</div>
									<div class="widget-toolbox padding-4 clearfix">
										<div class="btn-group pull-left">
										</div>
										<div class="btn-group pull-right">
										<?php 
											if($reply->user_id != $this->user_id){
												?>
												<!-- <button class="btn btn-sm btn-danger btn-white btn-round" onclick="reply_comment(<?php echo $reply->record_id ?>)">
													<i class="ace-icon fa fa-reply bigger-125"></i>
													ตอบกลับ
												</button> -->
												<?php
											}else{
												?>
												<button class="btn btn-sm btn-danger btn-white btn-round edit-record" data-id="<?php echo $reply->record_id ?>">
													<i class="ace-icon fa fa-edit bigger-125"></i>
													แก้ไข
												</button>
												<?php
											}
										?>
										</div>
									</div>
								</div>
							</div>
						</li>
						<div class="space"></div>
					<?php
					
				}
				echo "</ol>";
			}
			?>
			</li>
			<div class="space"></div>
			<?php
		}
	}


	public function status_data($meeting_id = null, $agenda_id = null, $status = null){
		if($status == 'open'){
			$save_data = $this->Records_model->insertMinutes(array('meeting_id' => $meeting_id, 'agenda_id' => $agenda_id));
			redirect(base_url("attends/edit_attend/{$meeting_id}/{$agenda_id}"));
		}else{
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}
			$save_data = $this->Records_model->updateMinutes($data);
			$save_data = $this->Records_model->updatetRecordLogTimeAll($data);
			redirect(base_url("attends/view_attend/{$meeting_id}"));
		}
		
		exit;
	}

	public function save_record_log($meeting_id = null, $agenda_id = null, $user_id ,$type)
	{
		if($type == "in"){
			$save_data = $this->Records_model->saveRecordLog(array('meeting_id'=>$meeting_id,'agenda_id'=>$agenda_id,'user_id'=>$user_id,'time_in'=>date('H:i')));
			redirect(base_url("attends/edit_attend/{$meeting_id}/{$agenda_id}"));
		}else{
			$save_data = $this->Records_model->saveRecordLog(array('meeting_id'=>$meeting_id,'agenda_id'=>$agenda_id,'user_id'=>$user_id,'time_out'=>date('H:i')));
			redirect(base_url("attends/view_attend/{$meeting_id}"));
		}
		exit;
	}
}
