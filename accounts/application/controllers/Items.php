<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Item $Item
 * @property Transaction $Transaction
 * @property Warehouse $Warehouse
 * @property Currency $Currency
 */
class Items extends MY_Controller
{

	public $Item = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->pageTitle = $this->lang->line('items');
		$this->load->model('Item');
	}

	public function index()
	{
		if ($this->input->is_ajax_request()) {
			$this->_render_json($this->Item->load_items_data_tables());
		} else {
			$this->session->unset_userdata('previous_url');
			$this->session->set_userdata('previous_url', 'items/index');
			$this->pageTitle = $this->lang->line('items');
			$brands =  $this->Item->load_all_items_brands();
			$data["brands"] = [];
			foreach ($brands as $b) {
				$data["brands"][$b["brand"]] = $b["brand"];
			}
			$operations	= ["=", "+", "-", "*", "/"];
			$data['operations'] = array_combine($operations, $operations);
			$data['records'] = $this->Item->paginate_items();

            foreach ($data['records'] as &$value) {
                //if active
                if ($this->Item->fetch_all_item_details($value['id'])) {
                    $value['status'] = 1;
                } else {
                    $value['status'] = 0;
                }
            }

			$data['title'] = $this->lang->line('products');
			$this->load->view('templates/header', [
				'_page_title' => $this->lang->line('products'),
				'_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
			]);
			$this->load->view('items/index', $data);
			$this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'jquery.dataTable.pagination.input', 'items/index']]);
		}
	}

	public function add()
	{
		$this->save($this->lang->line('add_product'), 0);
	}

	public function edit($id = '0')
	{
		$this->save($this->lang->line('edit_product'), $id);
	}

	private function save($page_title, $id = '0')
	{
		$fetched = ($id > 0 ? $this->Item->fetch(_gnv($id)) : false);
		$post = $this->input->post(null, true);
		if (!empty($post)) {
			// var_dump($post);
			// exit;
			$this->Item->set_fields($post);
			if (!$fetched) {
				$this->Item->set_field('open_qty', 0);
				$this->Item->set_field('qty', 0);
			} else {
				$this->load->model('Transaction');
				$last_trans_date = $this->Transaction->fetch_last_trans_date_of_purchase_or_transfer_of_item($id);
				if ($last_trans_date === NULL) {
					$this->load->model('Currency');
					$currency_id = $this->Currency->fetch_currency_id_by_code("€");
					$currency_rate = $this->Currency->fetch_currency_rate($currency_id["id"])["currency_rate"];
					$this->Item->set_field('cost', $post["open_cost"] * $currency_rate);
				}
			}
			$saved = $fetched ? $this->Item->update() : $this->Item->insert();
			if ($saved) {
				if (!$fetched) {
					if (array_key_exists('ean', $post)) {
						$this->load->model('Item_ean');
						$this->Item_ean->delete_all_item_ean($this->Item->get_field('id'));
						foreach ($post['ean'] as $e) {
							if($e)
							$this->Item_ean->add_new_ean_for_item($this->Item->get_field('id'), $e);
						}
					}
					$this->session->set_flashdata('message_success', '*Item Successfully Saved*');
					// redirect("items/edit/" . $this->Item->get_field('id'));
					redirect("items/index");
				} else {
					if (array_key_exists('ean', $post)) {
						$this->load->model('Item_ean');
						$this->Item_ean->delete_all_item_ean($this->Item->get_field('id'));
						foreach ($post['ean'] as $e) {
							if($e)
							$this->Item_ean->add_new_ean_for_item($this->Item->get_field('id'), $e);
						}
					}
					$this->session->set_flashdata('message_success', '*Item Successfully Updated*');
					redirect("items/index");
				}
			}
		}
		$this->load->model('Configuration');
		$TVA1 = $this->Configuration->fetch_TVA1()["valueStr"];
		$TVA2 = $this->Configuration->fetch_TVA2()["valueStr"];
		$TVA = [0, doubleval($TVA1), doubleval($TVA2)];
		$data['TVA'] = array_combine($TVA, $TVA);
		$data['title'] = $page_title;
		$data['eans'] = [];
		if ($fetched) {
			$this->load->model('Item_ean');
			$data['eans'] = $this->Item_ean->load_all_item_eans($id);
		}
		$this->load->view('templates/header', [
			'_page_title' => $page_title
		]);
		$this->load->view('items/form', $data);
		$this->load->view('templates/footer', [
			'_moreJs' => [
				'items/generate', 'items/form'
			]
		]);
	}

	public function delete($id)
	{
		if ($this->Item->fetch_all_item_details($id)) {
			$this->session->set_flashdata('message', 'Warning: Active Item can not be deleted!');
			redirect('items/index');
		} else {
			if ($this->Item->delete($id)) {
				redirect('items/index');
			} else {
				redirect('items/index');
			}
		}
	}

	public function fetchitemnumberfromDatabase()
	{
		$get_last_number = $this->Item->generate_autonumber();
		echo $get_last_number;
		$this->Item->set_field('barcode', $get_last_number);
	}

	public function activity($id)
	{
		if ($this->input->is_ajax_request()) {
			$this->_render_json($this->Item->load_items_activity_data_tables($id));
		} else {
			$this->session->unset_userdata('previous_url');
			$this->session->set_userdata('previous_url', 'items/activity/' . $id);
			$this->pageTitle = $this->lang->line('items');
			$data['records'] = $this->Item->paginate_items_activity($id);
			$data['item_id'] = $id;
			$data['title'] = $this->lang->line('item_activity');
			if ($data['records']) {
				$this->load->view('templates/header', [
					'_page_title' => $this->lang->line('item_activity'),
					'_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
				]);
				$this->load->view('items/activity', $data);
				$this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'items/activity']]);
			} else {
				$this->session->set_flashdata('message', 'Warning: Item Not Active!');
				redirect('items/index');
			}
		}
	}

	public function warehouses($id)
	{
		$this->session->unset_userdata('from_url');
		$this->session->set_userdata('from_url', 'items/warehouses/' . $id);
		$data['item_id'] = $id;
		$item = $this->Item->load_item_data($id);
		$data['item'] = $item[0]["description"] . " - " . $item[0]["barcode"];
		$this->load->model('Warehouse');
		//$warehouses_list= $this->Warehouse->load_item_all_warehouses_and_shelfs($id);
		$warehouses_ids = $this->Warehouse->fetch_warehouse_id_by_item_id($id);
		$data['warehouses_shelfs'] = [];
		foreach ($warehouses_ids as $w_id) {
			$qty = $this->Warehouse->load_item_all_warehouses_and_shelfs($w_id["warehouse_id"], $id)["qty"];
			if (intval($qty) > 0) {
				$result = $this->Warehouse->fetch_warehouse_shelf($w_id["warehouse_id"]);
				$data['warehouses_shelfs'][$w_id["warehouse_id"]] = $result["w_s"];
			}
		}
		$all = $this->Warehouse->fetch_all_warehouse_shelf();
		$data['all_warehouses_shelfs'] = [];
		foreach ($all as $a) {
			$data['all_warehouses_shelfs'][$a["id"]] = $a["w_s"];
		}
		$data['records'] = [];
		foreach ($warehouses_ids as $w_id) {
			$res = $this->Warehouse->load_item_all_warehouses_and_shelfs($w_id["warehouse_id"], $id);
			if (intval($res["qty"]) > 0) {
				array_push($data['records'], $res);
			}
		}
		if ($data['records'] === []) {
			$this->session->set_flashdata('message', 'Warning: Item Not found in any warehouse!');
			redirect('items/index');
		}
		$data["title"] = $this->lang->line('item_warehouses_activity');
		$this->load->view('templates/header', [
			'_page_title' => $this->lang->line('item_warehouses_activity'),
			'_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min', 'js/air-datepicker/css/datepicker.min']
		]);
		$this->load->view('items/warehouses', $data);
		$this->load->view('templates/footer', [
			'_moreJs' => [
				'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
				'jquery.autocomplete.min', 'items/warehouses'
			]
		]);
	}

	public function transfer($id)
	{
		$this->load->model('Transaction');
		$this->load->model('Configuration');
		$transType = "TR";
		$post = $this->input->post(null, true);
		$lc_currency = $this->Configuration->fetch_local_currency()['valueInt'];
		$auto_nb = $this->Transaction->set_next_auto_number($transType);
		$this->Transaction->set_field('id', '');
		$this->Transaction->set_field('auto_no', $auto_nb);
		$this->Transaction->set_field('trans_date', $post["trans_date"]);
		$this->Transaction->set_field('account_id', 1);
		$this->Transaction->set_field('account2_id', 1);
		$this->Transaction->set_field('currency_id', $lc_currency);
		$this->Transaction->set_field('currency_rate', 1);
		$this->Transaction->set_field('trans_type', $transType);
		$this->Transaction->set_field('fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$this->Transaction->set_field('user_id', $this->violet_auth->get_user_id());
		$saved = $this->Transaction->insert();
		if ($saved) {
			$this->Transaction->save_transaction_items_for_product_transfer($post["from"], $id, $post["qty"], $post["cost"] ?? 0, -1);
			$this->Transaction->save_transaction_items_for_product_transfer($post["to"], $id, $post["qty"], $post["cost"] ?? 0, 1);

			//update cost
			// $this->load->model('Transaction_item');
			// $last_trans_date = $this->Transaction->fetch_last_trans_date_of_purchase_or_transfer_of_item($id);
			// if ($last_trans_date !== null) {
			// 	$last_trans_item_id = $this->Transaction->fetch_last_purchase_or_transfer_of_item_using_trans_date($id, $last_trans_date["trans_date"]);
			// 	$last = $this->Transaction_item->fetch_trans_item_data($last_trans_item_id["transaction_item_id"]);
			// 	$this->load->model('Currency');
			// 	$currency_code = $this->Currency->fetch_currency_code($last['currency_id'])["currency_code"];
			// 	$this->Transaction->update_cost_and_price_of_item($last, $last['currency_rate'], $currency_code);
			// } else {
			// 	$this->load->model('Item');
			// 	$open_cost = $this->Item->fetch_open_cost_of_item($id)["open_cost"];
			// 	$this->Item->update_item_cost_by_item_id($id, $open_cost);
			// 	$this->Item->update_item_purchase_cost_by_item_id($id, 0);
			// }
			if ($this->session->userdata('from_url') === "items/warehouses/" . $id) {
				redirect("items/warehouses/" . $id);
			} else {
				if ($this->session->userdata('from_url') === "warehouses/inventory") {
					$this->session->set_flashdata('message_inventory', '*item Successfully transfered*');
					redirect("warehouses/inventory");
				}
			}
		}
	}

	public function add_opening_item($id)
	{
		$this->load->model(['Transaction', 'Configuration']);
		$this->session->unset_userdata('previous_url');
		$this->session->set_userdata('previous_url', 'items/edit/' . $id);
		$lc_currency = $this->Configuration->fetch_local_currency()['valueInt'];
		$op_id = $this->Transaction->fetch_OP_trans_id_for_item($id);
		if ($op_id === NULL) {
			$transType = "OI";
			$post = $this->input->post(['trans', 'transItems', 'submitBtn'], true);
			if (($this->Transaction->set_next_auto_number($transType))) {
				$this->Transaction->set_field('auto_no', $this->Transaction->set_next_auto_number($transType));
			}
			if ($post['submitBtn']) {
				if ($this->Transaction->set_next_auto_number($transType)) {
					$post['trans']['auto_no'] = $this->Transaction->set_next_auto_number($transType);
				}
				$this->Transaction->set_fields($post['trans']);
				$this->Transaction->set_field('account_id', 1);
				$this->Transaction->set_field('account2_id', 1);
				$this->Transaction->set_field('currency_id', $lc_currency);
				$this->Transaction->set_field('currency_rate', 1);
				$this->Transaction->set_field('trans_type', $transType);
				$this->Transaction->set_field('fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
				$this->Transaction->set_field('user_id', $this->violet_auth->get_user_id());
				$saved = $this->Transaction->insert();
				if ($saved) {
					$this->load->model('Item');
					$qty_array = $this->Transaction->get_array_of_qty_of_each_item_in_trans($post['transItems']);
					$this->Item->update_open_qty($qty_array, $post['trans']["currency_rate"]);
					$this->Item->update_qty_in_op($qty_array);
					// $items_purchased = $this->Transaction->check_if_items_purchased($post['transItems']);
					// $profit_qty_cost_array = $this->Item->fetch_profit_for_each_item_in_trans($qty_array);
					// $this->Item->update_cost_and_price_in_opening_items($items_purchased, $profit_qty_cost_array, $post['trans']["currency_rate"]);
					$this->Transaction->save_opening_items_trans_items($post['transItems'], 1);
					$this->session->set_flashdata('message_op', '*Successfully saved*');
					redirect('items/edit/' . $id);
				} elseif ($this->Transaction->is_valid()) {
					redirect('items/edit/' . $id);
				}
			}
			$data = $this->_load_related_models_for_OP($transType, $id);
			$data['item_id'] = $id;
			$this->load->view('templates/header', [
				'_moreCss' => ['js/air-datepicker/css/datepicker.min'],
				'_page_title' => $data['transTypeText']
			]);
			$this->load->view('opening_items/form', $data);
			$this->load->view('templates/footer', [
				'_moreJs' => [
					'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
					'jquery.autocomplete.min', 'items/opening_items'
				]
			]);
		} else {
			redirect('opening_items/edit/' . $op_id["id"]);
		}
	}

	private function _load_related_models_for_OP($transType, $item_id)
	{
		$data = [];
		$this->load->model(['Currency']);
		$data['transTypeText'] = 'Add New ' . $this->Transaction->get_transaction_types_list()[$transType];
		$data['transType'] = $this->Transaction->get_transaction_types_list()[$transType];
		$currency_id = $this->Currency->fetch_currency_id_by_code("€")["id"];
		$data['currency_rate'] = $this->Currency->fetch_currency_rate($currency_id)["currency_rate"];
		// $data['system_currency_code'] = $this->Currency->fetch_currency_code($system_currency_id)["currency_code"];
		$this->load->model('Warehouse');
		$w1 = $this->Warehouse->load_warehouses_list_for_OP($item_id);
		$w2 = $this->Warehouse->load_warehouses_ids_list();
		$result = array_diff($w2, $w1);
		$w = [];
		foreach ($result as $r) {
			$res = $this->Warehouse->fetch_warehouse_and_shelf($r);
			array_push($w, $res["warehouse"]);
		}
		$data['warehouses_list'] = array_combine($w, $w);
		$data['trans_date'] = date("d-m-Y");
		return $data;
	}

	public function get_max_qty_for_each_warehouse_shelf()
	{
		$item_id = $this->input->post('item_id');
		$qty = $this->input->post('qty');
		$warehouse = $this->input->post('warehouse');
		$shelf = $this->input->post('shelf');
		$this->load->model(['Warehouse']);
		$warehouse_id = $this->Warehouse->get_warehouse_id_by_warehouse_and_shelf($warehouse, $shelf)["id"];
		$qty_max = $this->Warehouse->load_item_all_warehouses_and_shelfs($warehouse_id, $item_id)["qty"];
		echo ($qty_max);
	}

	public function check_if_barcode_exists()
	{
		$barcode = $this->input->post('barcode');
		$result = $this->Item->check_barcode($barcode);
		echo ($result["count"]);
	}

	public function get_transaction_type()
	{
		$trans_id = $this->input->post('trans_id');
		$this->load->model(['Transaction']);
		$result = $this->Transaction->fetch_trans_type_by_trans_id($trans_id);
		echo ($result);
	}

	public function check_if_barcode_exists_on_item_update()
	{
		$barcode = $this->input->post('barcode');
		$item_id = $this->input->post('id');
		$item_barcode = $this->Item->get_barcode_by_id($item_id)["barcode"];
		$res = $this->Item->check_barcode($barcode)["count"];
		// echo ($result["count"]);
		$result["count"] = $res;
		$result["barcode"] = $item_barcode;
		// echo($item_barcode);
		$this->_render_json(
			$result
		);
	}

	public function get_item_cost_LC()
	{
		$item_id = $this->input->post('item_id');
		$cost = $this->Item->fetch_item_cost($item_id)[0]["cost"];
		echo ($cost);
	}

	public function get_item_qty()
	{
		$item_id = $this->input->post('item_id');
		$qty = $this->Item->fetch_qty_by_item_id($item_id)["qty"];
		echo ($qty);
	}

	public function calculate_cost_lc_by_purch_cost()
	{
		$this->load->model(['Currency']);
		$currency_id = $this->Currency->fetch_currency_id_by_code("€")["id"];
		$currency_rate = $this->Currency->fetch_currency_rate($currency_id)["currency_rate"];
		$purchase_cost = $this->input->post('purchase_cost');
		$cost_lc = $purchase_cost * $currency_rate;
		echo ($cost_lc);
	}

	public function calculate_cost_lc_by_open_cost()
	{
		$this->load->model(['Currency']);
		$currency_id = $this->Currency->fetch_currency_id_by_code("€")["id"];
		$currency_rate = $this->Currency->fetch_currency_rate($currency_id)["currency_rate"];
		$open_cost = $this->input->post('open_cost');
		$cost_lc = $open_cost * $currency_rate;
		echo ($cost_lc);
	}

	public function get_item_info()
	{
		$item_id = $this->input->post('item_id');
		$item = $this->Item->load_item_data($item_id)[0];
		echo ($item["description"] . " - " . $item["barcode"]);
	}

	public function add_item_by_modal()
	{
		$form_data = $this->input->post('form_data');
		$inputs = [];
		foreach ($form_data as $d) {
			$inputs[$d["name"]] = $d["value"];
		}
		$this->Item->set_fields($inputs);
		$this->Item->set_field('open_qty', 0);
		$this->Item->set_field('qty', 0);
		// if ($inputs["profit"] === "") {
		// 	$this->Item->set_field('profit', 0);
		// }
		// if ($inputs["price"] === "") {
		// 	$this->Item->set_field('price', 0);
		// 	$this->Item->set_field('price_ttc', 0);
		// }
		// if (floatval($inputs["profit"]) > 0) {
		// 	$this->Item->set_field('price', $inputs["cost"] * (1 + ($inputs["profit"] / 100)));
		// 	$this->Item->set_field('price_ttc', ($inputs["cost"] * (1 + ($inputs["profit"] / 100))) * (1 + ($inputs["TVA"] / 100)));
		// }
		$saved = $this->Item->insert();
		var_dump($saved);
	}

	public function get_item_warehouses()
	{
		$this->load->model('Warehouse');
		$item_id = $this->input->post('item_id');
		$warehouses_ids = $this->Warehouse->fetch_warehouse_id_by_item_id($item_id);
		$data['warehouses_shelfs'] = [];
		foreach ($warehouses_ids as $w_id) {
			$qty = $this->Warehouse->load_item_all_warehouses_and_shelfs($w_id["warehouse_id"], $item_id)["qty"];
			if (intval($qty) > 0) {
				$result = $this->Warehouse->fetch_warehouse_shelf($w_id["warehouse_id"]);
				$data['warehouses_shelfs'][$w_id["warehouse_id"]] = $result["w_s"];
			}
		}
		$all = $this->Warehouse->fetch_all_warehouse_shelf();
		$data['all_warehouses_shelfs'] = [];
		foreach ($all as $a) {
			$data['all_warehouses_shelfs'][$a["id"]] = $a["w_s"];
		}
		$data['records'] = [];
		foreach ($warehouses_ids as $w_id) {
			$res = $this->Warehouse->load_item_all_warehouses_and_shelfs($w_id["warehouse_id"], $item_id);
			if (intval($res["qty"]) > 0) {
				array_push($data['records'], $res);
			}
		}
		if ($data['records']) {
			$this->_render_json(
				$data['records']
			);
		} else {
			echo ("no");
		}
	}

	public function check_if_EAN_exists()
	{
		$EAN = $this->input->post('EAN');
		$result = $this->Item->check_EAN($EAN);
		echo ($result["count"]);
	}

	public function check_if_EAN_exists_on_item_update()
	{
		$EAN = $this->input->post('EAN');
		$item_id = $this->input->post('id');
		$item_EAN = $this->Item->get_barcode_by_id($item_id)["EAN"];
		$res = $this->Item->check_EAN($EAN)["count"];
		$result["count"] = $res;
		$result["item_EAN"] = $item_EAN;
		$this->_render_json(
			$result
		);
	}

	public function check_if_artical_number_exists()
	{
		$artical_number = $this->input->post('artical_number');
		$result = $this->Item->check_artical_number($artical_number);
		echo ($result["count"]);
	}

	public function check_if_artical_number_exists_on_item_update()
	{
		$artical_number = $this->input->post('artical_number');
		$item_id = $this->input->post('id');
		$item_artical_number = $this->Item->get_barcode_by_id($item_id)["artical_number"];
		$res = $this->Item->check_artical_number($artical_number)["count"];
		$result["count"] = $res;
		$result["item_artical_number"] = $item_artical_number;
		$this->_render_json(
			$result
		);
	}

	public function add_item_in_group_opening()
	{
		$EAN = $this->input->post('EAN');
		$barcode = $this->input->post('barcode');
		$this->Item->set_field('barcode', $barcode);
		$this->Item->set_field('description', $barcode);
		$this->Item->set_field('EAN', $EAN);
		$this->Item->set_field('open_qty', 0);
		$this->Item->set_field('open_cost', 0);
		$this->Item->set_field('cost', 0);
		$this->Item->set_field('qty', 0);
		$this->Item->set_field('profit', 0);
		$this->Item->set_field('price', 0);
		$this->Item->set_field('price_ttc', 0);
		$saved = $this->Item->insert();
		var_dump($saved);
	}

	public function get_item_data_by_EAN()
	{
		$EAN = $this->input->post('EAN');
		$item = $this->Item->fetch_item_data_by_EAN($EAN);
		$this->_render_json(
			$item
		);
	}

	public function lookup_items_by_EAN_and_artical_nb()
	{
		$this->load->model('Item');
		$this->_render_json(
			$this->Item->search_suggestions_EAN_and_artical_number(trim($this->input->get('query', true)))
		);
	}

	public function get_max_qty_for_each_warehouse_shelf_for_edit_sale()
	{
		$item_id = $this->input->post('item_id');
		$qty = $this->input->post('qty');
		$warehouse = $this->input->post('warehouse');
		$shelf = $this->input->post('shelf');
		$trans_id =  $this->input->post('trans_id');
		$this->load->model(['Warehouse']);
		$warehouse_id = $this->Warehouse->get_warehouse_id_by_warehouse_and_shelf($warehouse, $shelf)["id"];
		$qty_max = $this->Warehouse->calculate_max_qty_for_sale_edit_validation($trans_id, $warehouse_id, $item_id)["qty"];
		echo ($qty_max);
	}

	public function group_update_for_items()
	{
		$items_id = $this->input->post('items_id');
		$operation = $this->input->post('operation');
		$profit = $this->input->post('profit');
		// var_dump($items_id);
		$result = $this->Item->update_group_of_items($items_id, $operation, $profit);
		// echo($result);
	}

	public function get_products_names_by_brand()
	{
		$brand = $this->input->post('brand');
		$result = $this->Item->load_items_names_by_brand($brand);
		$this->_render_json(
			$result
		);
	}

	public function group_items_update()
	{
		$post = $this->input->post(null, true);
		$data['items'] = $this->Item->load_all_items_by_brand_and_description($post["brand"], $post["name_list"]);
		foreach ($data['items'] as $k => $item) {
			$new_price = $item["cost"] * (1 + ($post["profit"] / 100));
			// $new_price_ttc= $new_price * (1+ ($item["TVA"]/100));
			$data['items'][$k]["new_profit"] = $post["profit"];
			$data['items'][$k]["new_net_price"] = $new_price;
			// $data['items'][$k]["new_price"] = $new_price_ttc;
		}
		$data['title'] = "Brand - Name: " . $post["brand"] . " - " . $post["name_list"];
		$this->load->view('templates/header', [
			'_page_title' => $this->lang->line('items_group_action'),
		]);
		$this->load->view('items/group_actions', $data);
		$this->load->view('templates/footer');
	}

	public function confirm_group_items_update()
	{
		$post = $this->input->post(null, true);
		$this->Item->group_update_of_profit_and_price($post);
		redirect('items/index');
	}

	public function edit_item_by_modal()
	{
		$form_data = $this->input->post('form_data');
		$inputs = [];
		foreach ($form_data as $d) {
			if ($d["name"] !== 'id_item_model') {
				$inputs[$d["name"]] = $d["value"];
			} else {
				$inputs['id'] = $d["value"];
			}
		}
		$this->Item->set_fields($inputs);
		$saved = $this->Item->update();
		var_dump($saved);
	}

	public function get_items_availability_table_data()
	{
		$this->load->model('Warehouse');
		$item_ids = $this->input->post('item_ids');
		$data = [];
		foreach (array_unique($item_ids) as $id) {
			$res = $this->Warehouse->fetch_item_in_inventory($id);
			$data[] = $res;
		}
		// var_dump($data);
		$this->_render_json(
			$data
		);
	}

	public function custom_inventory_transfer()
	{
		$this->load->model(['Transaction', 'Configuration', 'Warehouse']);
		$transType = "TR";
		$post = $this->input->post(null, true);
		$lc_currency = $this->Configuration->fetch_local_currency()['valueInt'];
		$from_warehouse_id = $this->Warehouse->fetch_warehouse_id_by_warehouse_shelf('5314', '20-IN');
		if ($from_warehouse_id) {
			$auto_nb = $this->Transaction->set_next_auto_number($transType);
			$this->Transaction->set_field('id', '');
			$this->Transaction->set_field('auto_no', $auto_nb);
			$this->Transaction->set_field('trans_date', date('d-m-Y'));
			$this->Transaction->set_field('account_id', 1);
			$this->Transaction->set_field('account2_id', 1);
			$this->Transaction->set_field('currency_id', $lc_currency);
			$this->Transaction->set_field('currency_rate', 1);
			$this->Transaction->set_field('trans_type', $transType);
			$this->Transaction->set_field('fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
			$this->Transaction->set_field('user_id', $this->violet_auth->get_user_id());
			$saved = $this->Transaction->insert();
			if ($saved) {
				foreach ($post['transItems'] as $t) {
					$this->Transaction->save_transaction_items_for_product_transfer($from_warehouse_id['id'], $t['item_id'], $t["qty"], 0, -1);
					$this->Transaction->save_transaction_items_for_product_transfer($post['warehouse_id'], $t['item_id'], $t["qty"], 0, 1);
				}
				$this->Transaction->update_items_qty($post['transItems']);
			}
			$this->session->set_flashdata('message_inventory', '*Successfully transfered*');
			redirect("warehouses/inventory");
		} else {
			$this->session->set_flashdata('erorr_inventory', '*Erorr: warehouse-shelf (5314 - 20-IN) Not Found!*');
			redirect("warehouses/inventory");
		}
	}

	public function get_item_data_by_item_id()
	{
		$item_id = $this->input->post('item_id');
		$item = $this->Item->load_item_data($item_id);
		$this->_render_json(
			$item[0]
		);
	}
}
