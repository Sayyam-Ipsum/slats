<?php
class Authentications extends MY_Controller
{
	public $Authentication = NULL;

	public function __construct()
	{
		parent::__construct();
		//$this->pageTitle = $this->lang->line('users');
		$this->load->model('Authentication');
	}

	public function index()
	{

		$this->load->view('templates/header', [
			'_page_title' => 'login'

		]);
		$this->load->view('authentications/login_form');
		$this->load->view('templates/footer');
	}

	public function authenticate()
	{
		$this->Authentication->authenticate();
	}
}
