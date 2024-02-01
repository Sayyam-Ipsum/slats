<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Account $Account
 * @property Journal $Journal
 */
class Accounts extends MY_Controller
{

    public $Account = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = $this->lang->line('accounts');
        $this->load->model('Account');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->_render_json($this->Account->load_accounts_data_tables());
        } else {
            $this->pageTitle = $this->lang->line('accounts');
            $data['records'] = $this->Account->paginate_accounts();
            $data['title'] = $this->lang->line('accounts');
            $this->load->view('templates/header', [
                '_page_title' => $this->lang->line('accounts'),
                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
            ]);
            $this->load->view('accounts/index', $data);
            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'jquery.dataTable.pagination.input', 'accounts/index']]);
        }
    }

    public function add()
    {
        $this->save($this->lang->line('add_account'), 0);
    }

    public function edit($id = '0')
    {
        $this->save($this->lang->line('edit_account'), $id);
    }

    private function save($page_title, $id = '0')
    {
        $fetched = ($id > 0 ? $this->Account->fetch(_gnv($id)) : false);
        $post = $this->input->post(null, true);
        if (!empty($post)) {
            $this->Account->set_fields($post);
            if (empty($id)) {
                $this->Account->set_field('balance', $post["open_balance"]);
                $this->Account->set_field('credit', "0");
                $this->Account->set_field('debit', "0");
            } else {
                $this->load->model('Journal');
                $balance = $this->Journal->calculate_account_balance($id)["total"];
                $final_balance = doubleval($balance) + doubleval($post["open_balance"]);
                $this->Account->set_field('balance', $final_balance);
            }
            $saved = $fetched ? $this->Account->update() : $this->Account->insert();
            if ($saved) {
                redirect('accounts/index');
            } elseif ($this->Account->is_valid()) {
                redirect('accounts/index');
            }
        }
        if ($fetched) {
            $data['opening_date'] = $this->Account->get_field('opening_date');
            $data['open_balance'] = $this->Account->get_field('open_balance');
        } else {
            $data['opening_date'] = date("d-m-Y");
            $data['open_balance'] = 0;
        }
        $this->load->model('Currency');
        $data['currenciesList'] = $this->Currency->load_currencies_list();
        $data['types'] = array(
            "Individual" => "Individual", "Carage" => "Carage"
        );
        $data['account_type'] = array(
            "Customer" => "Customer", "Supplier" => "Supplier",
            "Cash" => "Cash", "Expenses" => "Expenses",
            "Bank" => "Bank", "Sale VAT" => "Sale VAT",
            "Purchase VAT" => "Purchase VAT"
        );
        $data['title'] = $page_title;
        $this->load->view('templates/header', [
            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],
            '_page_title' => $page_title
        ]);
        $this->load->view('accounts/form', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
                'accounts/form',
                'accounts/generate'
            ]
        ]);
    }

    public function delete($id)
    {
        $account_type = $this->Account->fetch_account_type($id)["account_type"];
        if ($account_type === "Purchases" || $account_type === "Sales") {
            $this->session->set_flashdata('message', 'Warning: Purchases & Sales accounts can not be deleted!');
            redirect('accounts/index');
        } else {
            $this->load->model('Journal');
            $active = $this->Journal->paginate_accounts_activity($id);
            if ($active === []) {
                if ($this->Account->delete($id)) {
                    redirect('accounts/index');
                } else {
                    redirect('accounts/index');
                }
            } else {
                $this->session->set_flashdata('message', 'Warning: Active account can not be deleted!');
                redirect('accounts/index');
            }
        }
    }

    public function fetchaccountnumberfromDatabase()
    {
        $whatselected = $this->input->post('whatselected');
        $this->Account->generate_autonumber($whatselected);
        echo $this->Account->generate_autonumber($whatselected);
    }

    public function activity($id)
    {
        $this->load->model("Journal");
        if ($this->input->is_ajax_request()) {
            $this->_render_json($this->Journal->load_accounts_activity_data_tables($id));
        } else {
            $this->session->unset_userdata('previous_url');
            $this->session->set_userdata('previous_url', 'accounts/activity/' . $id);
            $this->pageTitle = $this->lang->line('accounts');
            $data['records'] = $this->Journal->paginate_accounts_activity($id);
            $data['account_id'] = $id;
            $this->load->view('templates/header', [
                '_page_title' => $this->lang->line('accounts'),
                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
            ]);
            $this->load->view('accounts/activity', $data);
            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'accounts/activity']]);
        }
    }

    public function filter($account_type)
    {
        if ($this->input->is_ajax_request()) {
            $this->_render_json($this->Account->load_filtered_accounts_data_tables($account_type));
        } else {
            $this->pageTitle = $this->lang->line('accounts');
            $data['records'] = $this->Account->paginate_filtered_accounts($account_type);
            $data['title'] = $this->lang->line('accounts');
            $this->load->view('templates/header', [
                '_page_title' => $this->lang->line('accounts'),
                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
            ]);
            $this->load->view('accounts/index', $data);
            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'accounts/index']]);
        }
    }

    public function add_edit_account_by_modal()
    {
        $form_data = $this->input->post('form_data');
        $inputs = [];
        foreach ($form_data as $d) {
            $inputs[$d["name"]] = $d["value"];
        }
        if ($inputs["id"] === '') {
            $this->Account->set_fields($inputs);
            $this->Account->set_field('credit', "0");
            $this->Account->set_field('debit', "0");
            $saved = $this->Account->insert();
            var_dump($saved);
        } else {
            $this->Account->set_fields($inputs);
            $saved = $this->Account->update();
            var_dump($saved);
        }
    }

    public function get_account_edit_modal_data()
    {
        $acc_id = $this->input->post('acc_id');
        $acc_data = $this->Account->fetch_account_data($acc_id);
        $this->_render_json(
            $acc_data
        );
    }

    public function get_account_id()
    {
        $account_number = $this->input->post('account_number');
        $acc_id = $this->Account->fetch_account_id($account_number)["id"];
        echo $acc_id;
    }

    public function get_journal_type()
    {
        $journal_id = $this->input->post('journal_id');
        $this->load->model(['Journal']);
        $result = $this->Journal->fetch_journal_type_by_journal_id($journal_id);
        echo($result);
    }

    public function get_journal_or_trans_id()
    {
        $journal_id = $this->input->post('journal_id');
        $this->load->model(['Journal']);
        $result = $this->Journal->fetch_trans_id_by_journal_id($journal_id);
        echo($result);
    }

    public function get_account_balance()
    {
        $acc_id = $this->input->post('acc_id');
        $acc_balance = $this->Account->fetch_account_balance($acc_id)["balance"];
        echo $acc_balance;
    }

    public function customer_info_preview($id)
    {
        $this->load->model('Account');
        $this->load->model('Transaction');
        $data['trans'] = $this->Transaction->load_trans_data_by_trans_id($id);
        $data['customer_info'] = $this->Account->fetch_account_info($data['trans']["account_id"]);
        $data['title'] = "Customer Info";
        $this->load->view('templates/header', [
            '_page_title' => $data['title']
        ]);
        $this->load->view('sales/customer_info_preview', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'sales/preview'
            ]
        ]);
    }

    public function lookup_monthly_customers_accounts()
    {
        $this->_render_json(
            $this->Account->search_monthly_customers_suggestions(trim($this->input->get('query', true)))
        );
    }
}
