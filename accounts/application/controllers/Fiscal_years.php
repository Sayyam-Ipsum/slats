<?php

defined('BASEPATH') OR die('No direct script access allowed');

/**
 * @property Fiscal_year $Fiscal_year
 */
class Fiscal_years extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->pageTitle = $this->lang->line('fiscal_years');
		$this->load->model('Fiscal_year');
	}

	public function index() {
		if ($this->input->is_ajax_request()) {
			$this->_render_json($this->Fiscal_year->load_fiscal_years_data_tables());
		} else {
			$this->pageTitle = $this->lang->line('fiscal_years');
			$data['records'] = $this->Fiscal_year->paginate_fiscal_years();
			$this->load->view('templates/header',[
			'_page_title' => $this->lang->line('fiscal_years'),
			'_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
			]);
			$this->load->view('fiscal_years/index', $data);
			$this->load->view('templates/footer', [
				'_moreJs' => [
					'jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'fiscal_years/index'
				]
			]);
		}
	}

	public function add() {
		$this->save($this->lang->line('add_fiscal_year'), 0);
	}

	public function edit($id = '0') {
		$this->save($this->lang->line('edit_fiscal_year'), $id);
	}

	private function save($page_title, $id = '0') {
		$fetched = ($id > 0 ? $this->Fiscal_year->fetch(_gnv($id)) : false);
		$post = $this->input->post(null, true);
		if (!empty($post)) {
			$this->Fiscal_year->set_fields($post);
			$saved = $fetched ? $this->Fiscal_year->update() : $this->Fiscal_year->insert();
			if ($saved) {
				redirect('fiscal_years/index');
			} elseif ($this->Fiscal_year->is_valid()) {
				redirect('fiscal_years/index');
			}
		}
		$data["title"] = $page_title;
		$this->load->view('templates/header', [
			'_moreCss' => ['js/air-datepicker/css/datepicker.min'],
			'_page_title' => $page_title
		]);
		$this->load->view('fiscal_years/form', $data);
		$this->load->view('templates/footer', [
			'_moreJs' => [
				'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
				'fiscal_years/form'
			]
		]);
	}

	public function delete($id) {
		$active = $this->Fiscal_year->check_if_fiscal_year_is_active($id);
		if ($active["count"] === "0") {
			if ($this->Fiscal_year->delete($id)) {
				redirect('fiscal_years/index');
			} else {
				redirect('fiscal_years/index');
			}
		}else{
			$this->session->set_flashdata('message', '*Can not be deleted: this fiscal year already used*');
			redirect('fiscal_years/index');
		}		
	}

}
