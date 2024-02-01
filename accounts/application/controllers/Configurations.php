<?php

/**
 * @property Configuration $Configuration
 *
 */
class Configurations extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Configuration');
	}

	public function index() {
		if ($this->input->is_ajax_request()) {
			die("handle ajax request");
		} else {
			$post = $this->input->post(null, true);
			if (isset($post['variables'])) {
				// unset($post['variables']['Language']);
				// var_dump();
				// exit;
				$this->Configuration->save($post['variables']);
				redirect('configurations/index');
			}
			// $this->load->model('Currency');
			// var_dump($this->Currency->load_currencies_list());
			// exit;
			$data['configSet1'] = $this->Configuration->load_conf_group_form_elements('set_1', 'has-secondary');
			$data['configSet2'] = $this->Configuration->load_conf_group_form_elements('set_2', 'has-secondary');
			//$data['configSet3'] = $this->Configuration->load_conf_group_form_elements('set_3', 'has-warning');
			// die("This is how you read TestTwo [ {$this->Configuration->get_conf_val('TestTwo')} ]");
			// die("This is how you read all: <pre>" . print_r($this->Configuration->load_configurations(), true) . "</pre>");
			// die("This is how you read some: <pre>" . print_r($this->Configuration->load_configurations(['TestThree']), true) . "</pre>");
			$this->load->view('templates/header', ['_page_title' => 'System Configurations']);
			$this->load->view('configurations/form', $data);
			$this->load->view('templates/footer', [
				'_moreJs' => [
					'configurations/form'
				]
			]);
		}
	}

}