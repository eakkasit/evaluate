<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_evaluates extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation','m_pdf','excel'));
		$this->load->model(array('Commons_model', 'Activities_model','CriteriaDatas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("report_evaluates/dashboard_report_evaluates"));
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

	public function dashboard_report_evaluates()
	{
		$result = array();
		$result_data = $this->CriteriaDatas_model->getResult();
		if(isset($result_data) && !empty($result_data)){
			foreach ($result_data as $key => $value) {
				// if($result[$value->project_id][$value->year]){
					$result[$value->project_id][$value->year] = $value->result;
				// }else{
					// $result[$value->project_id][$value->year] = $value->result;
				// }
			}
		}
		$cond = $this->search_form(array('project_name'));
		$data['content_data'] = array(
			'datas'=>$this->Activities_model->getActivities($cond, array('year'=>'DESC')),
			'result'=>$result
		);
		// echo "<pre>";
		// print_r($result);
		// die();
		$data['content_view'] = 'pages/dashboard_report_evaluates';
		$this->load->view($this->theme, $data);
	}

	public function view_reports_evaluate($id = null)
	{
		$project_data = $this->Activities_model->getActivities(array('id'=>$id));
		$result_data = $this->CriteriaDatas_model->getResult(array('project_id'=>$id),array('year'=>'ASC'));
		$data['content_data'] = array(
			'project_data' => $project_data,
			'project_id'=>$id,
			'datas' => $result_data,
		);
		$data['content_view'] = 'pages/view_reports_evaluate';
		$this->load->view($this->theme, $data);
	}

	public function new_reports_evaluate($id = null)
	{
		$data['content_data'] = array(
		);
		$data['content_view'] = 'pages/form_reports_evaluate';
		$this->load->view($this->theme, $data);
	}

	public function edit_reports_evaluate($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_reports_evaluate';
		$this->load->view($this->theme, $data);
	}



	public function export($id,$type = '')
	{
		$project_data = $this->Activities_model->getActivities(array('id'=>$id));
		$result_data = $this->CriteriaDatas_model->getResult(array('project_id'=>$id),array('year'=>'ASC'));
		$data = array(
			'project_data' => $project_data,
			'project_id'=>$id,
			'datas' => $result_data,
		);
		// $project_data = $this->Activities_model->getActivities(array('id'=>$id));
		// $result_data = $this->CriteriaDatas_model->getResult(array('project_id'=>$id),array('year'=>'ASC'));
		// $data['content_data'] = array(
		// 	'project_data' => $project_data,
		// 	'project_id'=>$id,
		// 	'datas' => $result_data,
		// );
		if($type == 'pdf'){
			$pdfFilePath = "รายงานการประเมินองค์กร.pdf";
			$html = $this->load->view('pages/report_evaluate_pdf', $data,true);
			$this->m_pdf->pdf->WriteHTML($html);
			$this->m_pdf->pdf->Output($pdfFilePath, 'D');
			exit;
		}else if($type == 'word'){
			$this->load->view('pages/report_evaluate_word', $data);
		}else if($type == 'excel'){
			// $this->load->view('pages/report_evaluate_excel', $data);
			$objWorkSheet = $this->excel->setActiveSheetIndex(0);
			$objWorkSheet->setCellValue('A1','ลำดับ');
			$objWorkSheet->setCellValue('B1','ชื่อโครงการ');
			$objWorkSheet->setCellValue('C1','ปีงบประมาณ');
			$objWorkSheet->setCellValue('D1','ผู้รับผิดชอบ');
			$objWorkSheet->setCellValue('E1','วัตถุประสงค์');
			$objWorkSheet->setCellValue('F1','ผลการดำเนินโครงการ');
			$objWorkSheet->setCellValue('G1','ผลผลิต');
			$objWorkSheet->setCellValue('H1','ผลลัพธ์');
			$objWorkSheet->setCellValue('I1','ผลการประเมิน');
			$i = 1;
			if (isset($result_data) && !empty($result_data)) {

				foreach ($result_data as $key => $data) {
					$i++;
					$objWorkSheet->setCellValue('A'.$i,number_format($key+1, 0));
					$objWorkSheet->setCellValue('B'.$i,$project_data[0]->project_name);
					$objWorkSheet->setCellValue('C'.$i,$data->year+543);
					$objWorkSheet->setCellValue('D'.$i,'-');
					$objWorkSheet->setCellValue('E'.$i,'');
					$objWorkSheet->setCellValue('F'.$i,$data->project_result);
					$objWorkSheet->setCellValue('G'.$i,$data->product);
					$objWorkSheet->setCellValue('H'.$i,$data->result);
					$objWorkSheet->setCellValue('I'.$i,$data->assessment_results);
				}
			}
			$styleArray = array(
					'borders' => array(
							'allborders' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
					),
					'alignment' => array(
							'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
					),
			);
			$objWorkSheet->getStyle('A1:I'.($i-1))->applyFromArray($styleArray);
			$filename='รายงานการประเมินองค์กร.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache

			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}else{
			redirect(base_url("report_targets/view_report"));
			exit;
		}
	}


}
