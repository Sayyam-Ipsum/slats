<?phpdefined('BASEPATH') or die('No direct script access allowed');/** * @property Warehouse $Warehouse * @property Item $Item */class Warehouses extends MY_Controller{    public function __construct()    {        parent::__construct();        $this->pageTitle = $this->lang->line('warehouses');        $this->load->model('Warehouse');    }    public function index()    {        if ($this->input->is_ajax_request()) {            $this->_render_json($this->Warehouse->load_warehouses_data_tables());        } else {            $this->pageTitle = $this->lang->line('warehouses');            $data['records'] = $this->Warehouse->paginate_warehouses();            $data['title'] = $this->lang->line('warehouses');            $this->load->view('templates/header', [                '_page_title' => $this->lang->line('warehouses'),                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']            ]);            $this->load->view('warehouses/index', $data);            $this->load->view('templates/footer', [                '_moreJs' => [                    'jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'jquery.dataTable.pagination.input', 'warehouse/index'                ]            ]);        }    }    public function expand($warehouse)    {        $warehouse = str_replace("%20", " ", $warehouse);        if ($this->input->is_ajax_request()) {            $this->_render_json($this->Warehouse->load_expand_shelfs_data_tables($warehouse));        } else {            $this->pageTitle = $this->lang->line('warehouses');            $data['warehouse_name'] = $warehouse;            $data['records'] = $this->Warehouse->paginate_expand_shelfs($warehouse);            $data['title'] = $this->lang->line('warehouses');            $this->load->view('templates/header', [                '_page_title' => $this->lang->line('warehouses'),                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']            ]);            $this->load->view('warehouses/expand_shelfs', $data);            $this->load->view('templates/footer', [                '_moreJs' => [                    'jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'jquery.dataTable.pagination.input', 'warehouse/expand_shelfs'                ]            ]);        }    }    public function add()    {        $this->save($this->lang->line('add_warehouse'), 0);    }    public function edit($id = '0')    {        $this->save($this->lang->line('edit_warehouse'), $id);    }    private function save($page_title, $id = '0')    {        $fetched = ($id > 0 ? $this->Warehouse->fetch(_gnv($id)) : false);        $post = $this->input->post(null, true);        if (!empty($post)) {            if (!$fetched) {                $exists = $this->Warehouse->is_warehouse_shelf_exists($post["warehouse"], $post["shelf"]);                if (!$exists) {                    $this->Warehouse->set_fields($post);                } else {                    $this->session->set_flashdata('message', 'Warning: this warehouse-shelf exists!');                    redirect('warehouses/add');                }            } else {                $this->Warehouse->set_fields($post);            }            $saved = $fetched ? $this->Warehouse->update() : $this->Warehouse->insert();            if ($saved) {                redirect('warehouses/index');            } elseif ($this->Warehouse->is_valid()) {                redirect('warehouses/index');            }        }        $data["title"] = $page_title;        $this->load->view('templates/header', [            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],            '_page_title' => $page_title        ]);        $this->load->view('warehouses/form', $data);        $this->load->view('templates/footer', [            '_moreJs' => [                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',                'warehouse/form'            ]        ]);    }    public function delete($id)    {        $warehouse = $this->Warehouse->fetch_warehouse_and_shelf($id)["warehouse"];        $count = $this->Warehouse->warehouses_count();        $active = $this->Warehouse->is_warhouse_id_active_in_trans_items($id);        if ($active) {            $this->session->set_flashdata('message', 'Warning: Active Warehouse can not be deleted!');            redirect('warehouses/expand/' . $warehouse);        } else {            if ($count["count"] > 1) {                if ($this->Warehouse->delete($id)) {                    redirect('warehouses/expand/' . $warehouse);                } else {                    redirect('warehouses/expand/' . $warehouse);                }            } else {                $this->session->set_flashdata('message', 'Warning: Last Warehouse can not be deleted!');                redirect('warehouses/expand/' . $warehouse);            }        }    }    public function get_warehouses_for_item()    {        $item_id = $this->input->post('whatselected');        // $warehouse_ids = $this->Warehouse->get_warehouse_ids_of_item($item_id);        // $w = [];        // foreach ($warehouse_ids as $w_id) {        // 	$res = $this->Warehouse->fetch_warehouse_and_shelf($w_id["warehouse_id"]);        // 	array_push($w, $res["warehouse"]);        // }        $w = $this->Warehouse->fetch_item_in_inventory($item_id);        $this->_render_json(            $w        );    }    public function get_shelfs_for_item()    {        $item_id = $this->input->post('item_id');        $warehouse_selected = $this->input->post('whatselected');        // $warehouse_ids = $this->Warehouse->get_warehouse_ids_of_item($item_id);        // $s = [];        // foreach ($warehouse_ids as $w_id) {        // 	$res = $this->Warehouse->fetch_warehouse_and_shelf($w_id["warehouse_id"]);        // 	if ($warehouse_selected === $res["warehouse"]) {        // 		array_push($s, $res["shelf"]);        // 	}        // }        $s = $this->Warehouse->fetch_item_warehouse_shelfs($item_id, $warehouse_selected);        $this->_render_json(            $s        );    }    public function check_if_item_found_in_a_warehouse()    {        $item_id = $this->input->post('whatselected');        $count = $this->Warehouse->check_if_item_exists_in_any_warehouse($item_id);        echo($count["count"]);    }    public function reports()    {        $data['title'] = $this->lang->line('warehouse_report');        $post = $this->input->post(null, true);        if (!empty($post)) {            $this->load->model('Warehouse');            if ($post['shelf_text'] === "0") {                $data["group_by_visibility"] = 0;                $data['items'] = $this->Warehouse->fetch_items_by_warehouse_only($post['warehouse_text']);            } else {                $data["group_by_visibility"] = 1;                $data['items'] = $this->Warehouse->fetch_items_by_warehouse_and_shelf($post['warehouse_text'], $post['shelf_text']);            }            $data['group_by'] = 0;            $data['shelf_name'] = $post['shelf_text'];            $data['warehouse_name'] = $post['warehouse_text'];            $this->load->view('templates/header', [                '_moreCss' => ['js/air-datepicker/css/datepicker.min'],                '_page_title' => "warehouse"            ]);            $this->load->view('warehouses/reports_view', $data);            $this->load->view('templates/footer', [                '_moreJs' => [                    'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',                    'warehouse/warehouse_reports'                ]            ]);        } else {            $this->load->model('Warehouse');            $data['warehouses'] = $this->Warehouse->load_warehouses_list_without_order_warehouses();            $this->load->view('templates/header', [                '_moreCss' => ['js/air-datepicker/css/datepicker.min'],                '_page_title' => "warehouse"            ]);            $this->load->view('warehouses/reports', $data);            $this->load->view('templates/footer', [                '_moreJs' => [                    'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',                    'warehouse/warehouse_reports'                ]            ]);        }    }    public function reports_groupby($w, $s)    {        $data['title'] = $this->lang->line('warehouse_report');        if ($s == 0)            $data['items'] = $this->Warehouse->fetch_items_by_warehouse_only_group_by($w);        else            $data['items'] = $this->Warehouse->fetch_items_by_warehouse_and_shelf($w, $s);        $data['shelf_name'] = $s;        $data['warehouse_name'] = $w;        $data['group_by'] = 1;        $data["group_by_visibility"] = 0;        $this->load->view('templates/header', [            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],            '_page_title' => "warehouse"        ]);        $this->load->view('warehouses/reports_view', $data);        $this->load->view('templates/footer', [            '_moreJs' => [                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',                'warehouse/warehouse_reports'            ]        ]);    }    public function get_max_qty()    {        $warehouse_id = $this->input->post('whatselected');        $item_id = $this->input->post('item_id');        $qty = $this->Warehouse->load_item_all_warehouses_and_shelfs($warehouse_id, $item_id)["qty"];        echo $qty;    }    public function find_item_warehouse_shelf()    {        $item_id = $this->input->post('whatselected');        $warehouses = $this->Warehouse->fetch_item_in_inventory($item_id);        $warehouses_shelfs = [];        foreach ($warehouses as $w_id) {            // $result = $this->Warehouse->fetch_warehouse_shelf($w_id["warehouse_id"]);            $warehouses_shelfs[$w_id["id"]] = $w_id["warehouse"] . " - " . $w_id["shelf"];        }        $this->_render_json(            $warehouses_shelfs        );    }    public function inventory()    {        $this->session->unset_userdata('from_url');        $this->session->set_userdata('from_url', 'warehouses/inventory');        if ($this->input->is_ajax_request()) {            $this->_render_json($this->Warehouse->load_inventory_data_tables());        } else {            $post = $this->input->post(null, true);            if ($post) {                $data['artical_nb'] = $post['artical_number_alt'] ?? null;                $this->load->model('Item');                $item = $this->Item->fetch_item_data_by_artical_nb($post['artical_number_alt']);                /* var_dump($item);                 exit;*/                if ($item) {                    $alternatives = $this->Item->load_all_item_alternative($post['artical_number_alt']);                   /* var_dump($alternatives);                    exit;*/                    $res = $this->Warehouse->load_item_availabilty_table_with_alternatives_for_QU($item['id'], $post['artical_number_alt']);                    if ($res) {                        $count2 = 0;                        $res_item_ids = [];                        $all_ids[$item['id']] = $item['id'];                        foreach ($alternatives as $a) {                            $all_ids[$a['item_id']] = $a['item_id'];                        }                        foreach ($res as $row) {                            $res_item_ids[$row['item_id']] = $row['item_id'];                        }                        $items_not_found = array_diff($all_ids, $res_item_ids);                        foreach ($items_not_found as $n) {                            if ($n === $item['id']) {                                $res[] = array(                                    'item_id' => $item['id'],                                    'artical_number' => $post['artical_number_alt'],                                    'brand' => $item['brand'],                                    'warehouse' => 'Not Found',                                    'shelf' => 'Not Found',                                    'total_qty' => 0,                                );                            } else {                                foreach ($alternatives as $alt) {                                    if ($n == $alt['item_id']) {                                        $res[] = array(                                            'item_id' => $n,                                            'artical_number' => $alt['artical_number'],                                            'brand' => $alt['brand'],                                            'warehouse' => 'Not Found',                                            'shelf' => 'Not Found',                                            'total_qty' => 0,                                        );                                        break;                                    }                                }                            }                        }                    } else {                        $res[0] = array(                            'item_id' => $item['id'],                            'artical_number' => $post['artical_number_alt'],                            'brand' => $item['brand'],                            'warehouse' => 'Not Found',                            'shelf' => 'Not Found',                            'total_qty' => 0,                        );                        if ($alternatives != []) {                            foreach ($alternatives as $a) {                                $new = $a;                                $new['warehouse'] = "Not Found";                                $new['shelf'] = "Not Found";                                $new['total_qty'] = 0;                                $res[] = $new;                            }                        }                    }                    $data['rows'] = $res;                } else {                    $data['rows'] = [];                }            } else {                $data['rows'] = [];                $data['artical_nb'] = '';            }            $this->pageTitle = $this->lang->line('warehouses');            $rows = $this->Warehouse->calculate_inventory_total_value('All', '');            $data['tot'] = 0;            foreach ($rows as $r) {                $data['tot'] = $data['tot'] + ($r["total_qty"] * $r["cost"]);            }            $data['records'] = $this->Warehouse->paginate_inventory();            // $warehouses = $this->Warehouse->load_warehouses_list_without_order_warehouses();            $warehouses = $this->Warehouse->load_warehouses_list();            $data['warehouses']['All'] = '';            foreach ($warehouses as $w) {                $data['warehouses'][$w] = $w;            }            $this->load->model('User');            $data['total_permission'] = $this->User->check_user_permission($this->violet_auth->get_user_id(), "inventory_total_cost")['count'];            // var_dump($data['total_permission'] );            // exit;            $data['title'] = $this->lang->line('inventory');            $this->load->view('templates/header', [                '_page_title' => $this->lang->line('inventory'),                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min', 'js/air-datepicker/css/datepicker.min', 'css/bootstrap-select.min']            ]);            $this->load->view('warehouses/inventory', $data);            $this->load->view('templates/footer', [                '_moreJs' => [                    'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en', 'jquery.dataTables.min', 'dataTables.bootstrap.min', 'jquery.dataTable.pagination.input', 'dataTables.fixedHeader.min', 'jquery.autocomplete.min', 'bootstrap-select.min', 'warehouse/inventory'                ]            ]);        }    }    public function get_all_warehouses_and_shelfs()    {        $all = $this->Warehouse->fetch_all_warehouse_shelf();        $this->_render_json(            $all        );    }    public function get_item_warehouses_and_shelfs()    {        $item_id = $this->input->post('item_id');        $warehouses_ids = $this->Warehouse->fetch_warehouse_id_by_item_id($item_id);        $all = [];        foreach ($warehouses_ids as $k => $w_id) {            $qty = $this->Warehouse->load_item_all_warehouses_and_shelfs($w_id["warehouse_id"], $item_id)["qty"];            if (intval($qty) > 0) {                $result = $this->Warehouse->fetch_warehouse_shelf($w_id["warehouse_id"]);                // $all[$w_id["warehouse_id"]] = $result["w_s"];                $all[$k] = ["id" => $w_id["warehouse_id"], "w_s" => $result["w_s"]];            }        }        $this->_render_json(            $all        );    }    public function get_transfer_view_request()    {        $data['item_id'] = $this->input->post('item_id');        $data['warehouse'] = $this->input->post('warehouse');        $data['shelf'] = $this->input->post('shelf');        $this->load->model('Item');        $data['item'] = $this->Item->load_item_data($data['item_id'])[0];        $data['product_info'] = $data['item']["description"] . " - " . $data['item']["barcode"];        $data['warehouse_id'] = $this->Warehouse->fetch_warehouse_id_by_warehouse_shelf($data['warehouse'], $data['shelf'])["id"];        $data['from'][$data['warehouse_id']] = $data['warehouse'] . " - " . $data['shelf'];        $all = $this->Warehouse->fetch_all_warehouse_shelf();        $data['all_warehouses_shelfs'] = [];        foreach ($all as $a) {            $data['all_warehouses_shelfs'][$a["id"]] = $a["w_s"];        }        $data['max_qty'] = $this->Warehouse->load_item_all_warehouses_and_shelfs($data['warehouse_id'], $data['item_id'])["qty"];        // echo ($max_qty);        $this->load->view('warehouses/transfer_modal', $data);    }    public function get_order_warehouses()    {        $all = $this->Warehouse->fetch_all_order_warehouses();        $warehouses = [];        foreach ($all as $w) {            $warehouses[$w["warehouse"]] = $w["warehouse"];        }        $this->_render_json(            $warehouses        );    }    public function get_shelf_of_order_warehouse()    {        $warehouse = $this->input->post('whatselected');        $all = $this->Warehouse->fetch_shelf_of_order_warehouse($warehouse);        $this->_render_json(            $all        );    }    public function calculate_inventory_total_value()    {        $warehouse = $this->input->post('warehouse');        $shelf = $this->input->post('shelf');        $data = $this->Warehouse->calculate_inventory_total_value($warehouse, $shelf);        $tot = 0;        foreach ($data as $d) {            $tot = $tot + ($d["total_qty"] * $d["cost"]);        }        echo($tot);    }    public function add_shelfs($warehouse)    {        $w = $this->Warehouse->load_warehouses_list();        $data['warehouses_list'] = array_combine($w, $w);        $data['warehouse'] = $warehouse;        $data['title'] = $this->lang->line('add_shelfs');        $this->load->view('templates/header', [            '_page_title' => $data['title'],            '_moreCss' => ['css/jquery-tag-this']        ]);        $this->load->view('warehouses/shelfs_form', $data);        $this->load->view('templates/footer', [            '_moreJs' => ['jquery.tagthis', 'warehouse/shelfs_form']        ]);    }    public function add_multi_shelfs()    {        $warehouse = $this->input->post('warehouse');        $shelfs = $this->input->post('shelfs');        $res = $this->Warehouse->check_if_warehouse_shelf_exists($warehouse, $shelfs);        if ($res === []) {            $res = $this->Warehouse->insert_multi_shelfs_for_one_warehouse($warehouse, $shelfs);            echo(1);        } else {            $shelfs = implode(",", $res);            echo("Shelfs already exists: " . $shelfs . ".");        }    }    public function get_shelfs_for_order_warehouses()    {        $warehouse = $this->input->post('warehouse');        $shelfs = $this->Warehouse->fetch_all_shelfs_for_warehouse($warehouse);        $this->_render_json(            $shelfs        );    }    public function get_warehouse_id()    {        $warehouse = $this->input->post('warehouse');        $shelf = $this->input->post('shelf');        $warehouse_id = $this->Warehouse->fetch_warehouse_id_by_warehouse_shelf($warehouse, $shelf)['id'];        $this->_render_json(            $warehouse_id        );    }    public function get_warehouse_id_by_shelf_only()    {        $shelf = $this->input->post('shelf');        $warehouse_data = $this->Warehouse->fetch_warehouse_id_by_shelf($shelf);        $cases = [];        if (count($warehouse_data) === 1) {            $warehouse_data[0]['case'] = 1;            $this->_render_json(                $warehouse_data[0]            );        } else {            if (count($warehouse_data) > 1) {                $cases['case'] = 'not unique';                $this->_render_json(                    $cases                );            } else {                $cases['case'] = null;                $this->_render_json(                    $cases                );            }        }    }    public function get_item_availabilty_table_with_alternatives()    {        $item_id = $this->input->post('item_id');        $this->load->model('Item');        $item = $this->Item->load_item_data($item_id);        $res = $this->Warehouse->load_item_availabilty_table_with_alternatives($item_id, $item[0]['artical_number']);        $this->_render_json(            $res        );    }    public function get_item_availabilty_table_with_alternatives_for_QU()    {        $item_id = $this->input->post('item_id');        $this->load->model('Item');        $item = $this->Item->load_item_data($item_id);        $alternatives = $this->Item->load_all_item_alternative($item[0]['artical_number']);        $res = $this->Warehouse->load_item_availabilty_table_with_alternatives_for_QU($item_id, $item[0]['artical_number']);        if ($res) {            $count2 = 0;            foreach ($res as $r) {                $count = 0;                if ($r['item_id'] === $item_id) {                    $count2++;                }                if ($alternatives != []) {                    foreach ($alternatives as $a) {                        if ($a['item_id'] === $r['item_id']) {                            $count++;                        }                    }                    if ($count == 0) {                        $new = $a;                        $new['warehouse'] = "Not Found";                        $new['shelf'] = "Not Found";                        $new['total_qty'] = 0;                        $res[] = $new;                    }                }            }            if ($count2 === 0) {                $res[] = array(                    'item_id' => $item_id,                    'artical_number' => $item[0]['artical_number'],                    'brand' => $item[0]['brand'],                    'warehouse' => 'Not Found',                    'shelf' => 'Not Found',                    'total_qty' => 0,                );            }        } else {            $res[0] = array(                'item_id' => $item_id,                'artical_number' => $item[0]['artical_number'],                'brand' => $item[0]['brand'],                'warehouse' => 'Not Found',                'shelf' => 'Not Found',                'total_qty' => 0,            );            if ($alternatives != []) {                foreach ($alternatives as $a) {                    $new = $a;                    $new['warehouse'] = "Not Found";                    $new['shelf'] = "Not Found";                    $new['total_qty'] = 0;                    $res[] = $new;                }            }        }        $this->_render_json(            $res        );    }    public function find_alternatives()    {        $post = $this->input->post(null, true);        if ($post) {            $data['artical_nb'] = $post['artical_number'];            $this->load->model('Item');            $item = $this->Item->fetch_item_data_by_artical_nb($post['artical_number']);            // var_dump($item);            if ($item) {                $alternatives = $this->Item->load_all_item_alternative($post['artical_number']);                $res = $this->Warehouse->load_item_availabilty_table_with_alternatives_for_QU($item['id'], $post['artical_number']);                if ($res) {                    $count2 = 0;                    $res_item_ids = [];                    $all_ids[$item['id']] = $item['id'];                    foreach ($alternatives as $a) {                        $all_ids[$a['item_id']] = $a['item_id'];                    }                    foreach ($res as $row) {                        $res_item_ids[$row['item_id']] = $row['item_id'];                    }                    $items_not_found = array_diff($all_ids, $res_item_ids);                    foreach ($items_not_found as $n) {                        if ($n === $item['id']) {                            $res[] = array(                                'item_id' => $item['id'],                                'artical_number' => $post['artical_number'],                                'brand' => $item['brand'],                                'warehouse' => 'Not Found',                                'shelf' => 'Not Found',                                'total_qty' => 0,                            );                        } else {                            foreach ($alternatives as $alt) {                                if ($n == $alt['item_id']) {                                    $res[] = array(                                        'item_id' => $n,                                        'artical_number' => $alt['artical_number'],                                        'brand' => $alt['brand'],                                        'warehouse' => 'Not Found',                                        'shelf' => 'Not Found',                                        'total_qty' => 0,                                    );                                    break;                                }                            }                        }                    }                } else {                    $res[0] = array(                        'item_id' => $item['id'],                        'artical_number' => $post['artical_number'],                        'brand' => $item['brand'],                        'warehouse' => 'Not Found',                        'shelf' => 'Not Found',                        'total_qty' => 0,                    );                    if ($alternatives != []) {                        foreach ($alternatives as $a) {                            $new = $a;                            $new['warehouse'] = "Not Found";                            $new['shelf'] = "Not Found";                            $new['total_qty'] = 0;                            $res[] = $new;                        }                    }                }                $data['records'] = $res;            } else {                $data['records'] = [];            }        } else {            $data['records'] = [];            $data['artical_nb'] = '';        }        $data['title'] = 'Find Alternatives';        $this->load->view('templates/header', [            '_page_title' => $data['title'],            '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']        ]);        $this->load->view('warehouses/find_alternatives', $data);        $this->load->view('templates/footer', [            '_moreJs' => [                'jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'jquery.dataTable.pagination.input'            ]        ]);    }}