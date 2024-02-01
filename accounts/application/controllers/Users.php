<?php


/**
 * @property User $User
 * @property Fiscal_year $Fiscal_year
 * @property CI_Form_validation $form_validation
 */

class Users extends MY_Controller

{

    public $user = NULL;


    public function __construct()

    {

        parent::__construct();

        $this->pageTitle = $this->lang->line('users');

        $this->load->model('User');

        $this->load->model('Account');

    }


    public function index()

    {

        if ($this->input->is_ajax_request()) {

            $this->_render_json($this->User->load_users_data_tables());

        } else {

            $this->pageTitle = $this->lang->line('users');

            $data['records'] = $this->User->paginate_users();

            $this->load->view('templates/header', [

                '_page_title' => $this->lang->line('users'),

                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']

            ]);

            $this->load->view('users/index', $data);

            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'users/index']]);

        }

    }


    public function add()

    {

        $this->save($this->lang->line('add_user'), 0);

    }


    public function edit($id = '0')

    {

        $this->save($this->lang->line('edit_user'), $id);

    }


    private function save($page_title, $id = '0')

    {

        $fetched = ($id > 0 ? $this->User->fetch(_gnv($id)) : false);


        $post = $this->input->post(null, true);

        $types = ["Master Admin", "Admin", "Employee", "Driver", "Employee & Driver"];

        $data["user_type"] = array_combine($types, $types);

        if (!empty($post)) {

            // $enc_pass= $this->User->encryptPass($post["user_password"]);

            // $dicrip_pass= $this->User->decryptPass($enc_pass);

            $this->User->set_field('user_name', $post["user_name"]);

            $this->User->set_field('user_email', $post["user_email"]);

            $this->User->set_field('user_type', $post["user_type"]);

            $this->User->set_field('fiscal_year_id', $post["fiscal_year_id"]);

            $this->User->set_field('user_password', $this->User->encryptPass($post["user_password"]));

            $saved = $fetched ? $this->User->update() : $this->User->insert();

            if ($saved) {

                if (!$fetched) {

                    foreach ($post["permissions"] as $p) {

                        if ($p !== '') {

                            $this->User->add_user_permission($this->User->get_field('id'), $p);

                        }

                    }

                    redirect('users/index');

                } else {

                    $this->User->delete_user_permissions($this->User->get_field('id'));

                    foreach ($post["permissions"] as $p) {

                        if ($p !== '') {

                            $this->User->add_user_permission($this->User->get_field('id'), $p);

                        }

                    }

                    redirect('users/index');

                }

            } elseif ($this->User->is_valid()) {

                redirect('users/index');

            }

        }

        $data["permissions_list"]['name'] = [

            "accounts", "products", "opening", "transfer", "inventory",

            "quotations", "sale_orders", "invoices", "delivery_notes", "return_invoices",

            "order_purchases", "purchases", "return_purchases", "payments", "receipts",

            "fiscal_years", "currencies", "warehouses", "configurations", "users",

            "choose_fiscal_year", "journals_report", "warehouse_report", "orders_report",

            "employee_report", "activity_report", "receiving_items_report", "customer_receiving_items_report",

            "purchase_orders_report", "pickup_report", "pfand_report", "driver_page", "employee_page", "inventory_total_cost"

        ];

        $data["permissions_list"]['value'] = [

            "accounts/index", "items/index", "opening_items/index", "transfers/index", "warehouses/inventory",

            "quotations/index", "orders/index", "sales/index", "delivery_notes/index", "return_sales/index",

            "order_purchases/index", "purchases/index", "return_purchases/index", "payments/index", "receipts/index",

            "fiscal_years/index", "currencies/index", "warehouses/index", "configurations/index", "users/index",

            "users/choose_fiscal_year", "journal_accounts/index", "warehouses/reports", "reports/orders", "reports/employees",

            "reports/activity", "reports/receiving_items", "reports/customer_receiving_items", "reports/purchase_orders",

            "reports/pickup", "reports/pfand", "sales/driver_page", "employees/pickup_notes", "inventory_total_cost"

        ];

        $data["limit"] = floor(count($data["permissions_list"]['name']) / 2);

        if ($fetched) {

            foreach ($data["permissions_list"]['value'] as $k => $p) {

                $data["perm"][$k] = $this->User->check_user_permission($this->User->get_field('id'), $p)["count"];

            }

        } else {

            foreach ($data["permissions_list"]['value'] as $k => $p) {

                $data["perm"][$k] = '1';

            }

        }

        $fiscal_years = $this->User->fetch_all_years();

        foreach ($fiscal_years as $f) {

            $data['fiscal_year'][$f["id"]] = $f["year_name"];

        }

        $data["title"] = $page_title;

        $this->load->view('templates/header', [

            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],

            '_page_title' => $page_title

        ]);

        $this->load->view('users/form', $data);

        $this->load->view('templates/footer', [

            '_moreJs' => [

                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',

                'users/form'

            ]

        ]);

    }


    public function delete($id)

    {

        $active = $this->User->check_if_user_is_active($id);

        if ($active["count"] === "0") {

            if ($this->User->delete($id)) {

                redirect('users/index');

            } else {

                redirect('users/index');

            }

        } else {

            $this->session->set_flashdata('message', '*Can not be deleted: this user is active*');

            redirect('users/index');

        }

    }


    public function login()

    {

        $post = $this->input->post(null, true);

        if (!empty($post)) {

            $this->authenticate();

        }

        $data['title'] = $this->lang->line('login');

        $this->load->view('templates/header', [

            '_page_title' => "Login Page"

        ]);

        $this->load->view('users/login_form', $data);

        $this->load->view('templates/footer', [

            '_moreJs' => [

                'users/validation'

            ]

        ]);

    }


    private function authenticate()

    {

        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required');

        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {

            $user = $this->User->authenticate();

            if (false !== $user) {

                $this->violet_auth->build_user_session($user);

                $this->session->set_flashdata('user_loggedin', 'You are now logged in');

                if ($user["user_type"] === "Driver") {

                    redirect('sales/driver_page');

                } else {

                    if ($user["user_type"] === "Employee") {

                        redirect('employees/pickup_notes');

                    } else {

                        redirect('');

                    }

                }

            } else {

                $this->session->set_flashdata('message', '*You enter a wrong "Username" or "Password"*');

            }

        }

    }


    public function logout()

    {

        $this->load->model('Transaction');

        $transactions = $this->Transaction->load_all_locked_transactions_by_user($this->violet_auth->get_user_id());

        foreach ($transactions as $t) {

            $this->Transaction->update_edit_user_id_and_locked($t['id'], 0, '');

        }

        // Unset user data

        $this->session->unset_userdata('vauth_user_logged_in');

        $this->session->unset_userdata('vauth_fiscal_year');

        $this->session->unset_userdata('vauth_user_name');

        $this->session->unset_userdata('vauth_user_id');

        $this->session->unset_userdata('vauth_user_email');

        $this->session->unset_userdata('vauth_fiscal_year_id');

        // Set message

        $this->session->set_flashdata('user_loggedout', 'You are now logged out');

        redirect('users/login');

    }


    public function choose_fiscal_year()

    {

        $this->load->model('Fiscal_year');

        $post = $this->input->post(null, true);

        if (!empty($post)) {

            $fiscal_year_id = _gnv($post['fiscal_year_id']);

            $fiscalYear = $this->Fiscal_year->load($fiscal_year_id);

            if (false === $fiscalYear) {

                redirect('users/choose_fiscal_year');

            }

            $this->violet_auth->set_fiscal_year_id($fiscalYear['id']);

            $this->violet_auth->set_fiscal_year($fiscalYear['year_name']);

            $this->load->model('User');

            $this->User->fetch($this->violet_auth->get_user_id());

            $this->User->set_field('fiscal_year_id', $fiscal_year_id);

            $this->User->update();

            redirect('');

        }

        $data['title'] = $this->lang->line('choose_fiscal_year');

        $data['fiscal_year'] = $this->Fiscal_year->load_fiscal_years_list();

        $this->load->view('templates/header', [

            '_page_title' => $data['title']

        ]);

        $this->load->view('users/choose_f_year', $data);

        $this->load->view('templates/footer');

    }


    public function no_user_permissions()

    {

        $data["msg"] = $this->lang->line('sorry_you_do_not_have_permission_to_access_the_requested_page');

        $this->load->view('templates/header', [

            '_page_title' => $this->lang->line('user_permissions')

        ]);

        $this->load->view('users/no_user_permissions', $data);

        $this->load->view('templates/footer');

    }


    public function check_password()
    {

        $old_pass = $this->input->post('old_pass');

        $pass = $this->User->encryptPass($old_pass);

        $res = $this->User->check_user_password($this->violet_auth->get_user_id(), $pass);

        $this->_render_json($res);

    }


    public function change_user_password()
    {

        $post = $this->input->post(null, true);

        $res = $this->User->update_user_password($this->violet_auth->get_user_id(), $post['modal_new_pass']);

        if ($res) {

            $this->session->set_flashdata('message_success_header', 'Password Updated Successfully.');

            redirect($_SERVER['HTTP_REFERER']);

        } else {

            $this->session->set_flashdata('message_error_header', 'Sorry, Something Went Wrong!');

            redirect($_SERVER['HTTP_REFERER']);

        }

    }


    public function get_logedin_user_pass()
    {

        $pass1 = $this->User->get_user_password($this->violet_auth->get_user_id());

        $pass = $this->User->decryptPass($pass1['user_password']);

        $this->_render_json($pass);

    }

}

