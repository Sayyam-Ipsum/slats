<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Dashboard $Dashboard
 * @property Account $Account
 * @property Transaction $Transaction
 * @property Item $Item
 * @property User $User
 * @property Warehouse $Warehouse
 * @property Journal $Journal
 */
class Dashboard extends MY_Controller
{

    public $Account = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = $this->lang->line('dashboard');
        $this->load->model('Account');
        $this->load->model('Transaction');
        $this->load->model('Item');
        $this->load->model('User');
        $this->load->model('Warehouse');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['count_of_customers'] = $this->Account->fetch_nb_of_accounts_by_type('Customer')["count"];
        $data['count_of_suppliers'] = $this->Account->fetch_nb_of_accounts_by_type('Supplier')["count"];
        $data['count_of_orders'] = $this->Transaction->fetch_nb_of_orders()["count"];
        $data['count_of_products'] = $this->Item->fetch_nb_of_items()["count"];
        $data['count_of_users'] = $this->User->fetch_nb_of_users()["count"];
        $data['count_of_warehouses'] = $this->Warehouse->warehouses_count()["count"];
        $data['sum_of_sales_today'] = $this->Transaction->fetch_sum_of_invoices_by_date(date('Y-m-d'))["sum"] ?? 0;
        $data['sum_of_sales_yesterday'] = $this->Transaction->fetch_sum_of_invoices_by_date(date('Y-m-d', strtotime('-1 day')))["sum"] ?? 0;

        $this->load->view('templates/header', [
            '_page_title' => 'Dashboard',
            '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
        ]);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'jquery.dataTable.pagination.input']]);

    }
}
