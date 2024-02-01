<?php

defined('BASEPATH') OR die('No direct script access allowed');

/**
 * @property Journal_accounts $Journal_accounts
 */
class Journal_accounts extends MY_Controller {

	public $Journal_accounts = NULL;

	public function __construct() {
		parent::__construct();
		$this->pageTitle = $this->lang->line('journal_accounts');
		$this->load->model('Journal_account');
	}

	public function index() {
		if ($this->input->is_ajax_request()) {
			$this->_render_json($this->Journal_account->load_journal_accounts_data_tables());
			} else {

			$this->pageTitle = $this->lang->line('journal_accounts');
			$data['records'] = $this->Journal_account->paginate_Journal_accounts();
			$data['title'] = $this->lang->line('journals_report');
			$this->load->view('templates/header',[
				'_page_title' => "Journal",
				'_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
			]);
			$this->load->view('journal_accounts/index', $data);
			$this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'journal_accounts/index']]);
		}
	}

}
