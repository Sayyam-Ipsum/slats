<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Account $Account
 * @property Currency $Currency
 * @property Item $Item
 * @property Transaction $Transaction
 * @property Warehouse $Warehouse

 */
class Missing_products extends MY_Controller
{

    public $Transaction = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = $this->lang->line('missing_products');
        $this->load->model('Transaction');
    }

    public function index()
    {   $this->load->model('Item');
        if ($this->input->is_ajax_request()) {
            $this->_render_json($this->Transaction->load_missing_products_data_tables());
        } else {
            $this->pageTitle = $this->lang->line('missing_products');
            $this->load->model('Transaction_item');
            $data['records'] = $this->Item->paginate_missing_products_not_checked();
            $data['title'] = $this->lang->line('missing_products');
            $this->load->view('templates/header', [
                '_page_title' => "Missing Products",
                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
            ]);
            $this->load->view('missing_products/index', $data);
            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'missing_products/index']]);
        }
    }

    public function save_changes(){
        $post = $this->input->post(null, true);
        foreach($post["transaction_items"] as $t){
            if(count($t) > 1){
                $this->Transaction->update_checkbox_in_transaction_items($t["id"]); 
            }            
        }
        redirect('missing_products/index');
    }

    public function show_all(){
        $this->load->model('Item');
        $data['title'] = $this->lang->line('missing_products');
        $data['records'] = $this->Item->paginate_missing_products();
        $this->load->view('templates/header', [
            '_page_title' => "Missing Products",
            '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
        ]);
        $this->load->view('missing_products/index', $data);
        $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'missing_products/index']]);
    }

    public function show_checked(){
        $this->load->model('Item');
        $data['title'] = $this->lang->line('missing_products');
        $data['records'] = $this->Item->paginate_missing_products_checked();
        $this->load->view('templates/header', [
            '_page_title' => "Missing Products",
            '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
        ]);
        $this->load->view('missing_products/index', $data);
        $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'missing_products/index']]);
    }

    public function delete($id)
    {
        if ($this->Transaction->delete($id)) {

            redirect('missing_products/index');
        } else {
            redirect('missing_products/index');
        }
    }

}
