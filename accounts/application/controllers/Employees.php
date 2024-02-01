<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Account $Account
 * @property Transaction $Transaction
 */
class Employees extends MY_Controller
{

    public $Account = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = $this->lang->line('employees');
        $this->load->model('Account');
    }

    public function pickup_notes()
    {
        $this->load->model('Transaction');
        $employee_id = $this->violet_auth->get_user_id();
        if ($this->input->is_ajax_request()) {
            $this->_render_json($this->Transaction->load_employee_pickup_notes_data_tables($employee_id));
        } else {
            $data['records'] = $this->Transaction->paginate_employee_pickup_notes_notes($employee_id);
            $this->load->view('templates/header', [
                '_page_title' => "Employee Pickup",
                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
            ]);
            $this->load->view('employees/pickup_notes', $data);
            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'sales/employee_page']]);
        }
    }

    public function scan_box()
	{
        $this->load->model('Transaction');
		if ($this->input->is_ajax_request()) {
			$this->_render_json($this->Transaction->load_employee_scan_box_data_tables(Transaction::SaleTransType));
		} else {			
			$data['records'] = $this->Transaction->paginate_employee_scan_box_data(Transaction::SaleTransType);
			$data['title'] = $this->lang->line('invoices');
			$this->load->view('templates/header', [
				'_page_title' => $this->lang->line('invoices'),
				'_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
			]);
			$this->load->view('employees/scan_box', $data);
			$this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.datetime.format', 'jquery.dataTable.pagination.input', 'employees/scan_box']]);
		}
	}
}
