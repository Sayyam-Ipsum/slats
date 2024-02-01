<?php
defined('BASEPATH') or die('No direct script access allowed');
/**
 * @property Account $Account
 * @property Currency $Currency
 * @property Item $Item
 * @property Transaction $Transaction
 * @property Warehouse $Warehouse
 * @property Transaction_item $Transaction_item
 */
class Adjusts extends MY_Controller
{
	public $Transaction = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->pageTitle = $this->lang->line('adjusts');
		$this->load->model('Transaction');
	}

	public function save()
	{
		$transType = Transaction::AdjustTransType;
		$this->load->model('Configuration');
		$lc_currency = $this->Configuration->fetch_local_currency()['valueInt'];
		$post = $this->input->post(null, true);
		// var_dump($post);
		// exit;
		if ($post) {
			$auto_no = $this->Transaction->set_next_auto_number($transType);
			$saved = $this->Transaction->add_new_adjust_trans($auto_no, $lc_currency, $transType);
			if ($saved) {
				$trans_id = $this->Transaction->fetch_transaction_id_by_autono($auto_no, $transType)[0]["id"];
				foreach ($post['transItems'] as $t) {
					$this->load->model('Transaction_item');
					$this->Transaction_item->add_adjust_trans_items($trans_id, $t['item_id'], $post['warehouse_id'], $post['mvt_type'], $t['qty']);
				}
				$this->Transaction->update_items_qty($post['transItems']);
			}
		}
		redirect('warehouses/inventory');
	}

	public function edit($id)
	{
		$this->load->model('Transaction_item');
		$data['trans'] = $this->Transaction->load_trans_data_by_trans_id($id);
		$data['trans_items'] = $this->Transaction_item->load_all_trans_items($id);
		// var_dump($data['trans_items']);
		// exit;
		$data["title"] = $this->lang->line('adjust');
        $this->load->view('templates/header', [
            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],
            '_page_title' => $data["title"]
        ]);
        $this->load->view('adjust/view', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
                'jquery.autocomplete.min', 'reports/pfand'
            ]
        ]);
	}

	public function save_inventory_row()
	{
		$transType = Transaction::AdjustTransType;
		$this->load->model('Configuration');
		$lc_currency = $this->Configuration->fetch_local_currency()['valueInt'];
		$post = $this->input->post(null, true);
		if ($post) {
			$auto_no = $this->Transaction->set_next_auto_number($transType);
			$saved = $this->Transaction->add_new_adjust_trans($auto_no, $lc_currency, $transType);
			if ($saved) {
				$trans_id = $this->Transaction->fetch_transaction_id_by_autono($auto_no, $transType)[0]["id"];
				$this->load->model('Warehouse');
				$warehouse_id = $this->Warehouse->fetch_warehouse_id_by_warehouse_shelf($post["warehouse"], $post["shelf"])["id"];
				$diff = intval($post["new_qty"]) - intval($post["old_qty"]);
				$qty = abs($diff);
				$mvt_type = 0;
				if ($diff > 0) {
					$mvt_type = 1;
				} elseif ($diff < 0) {
					$mvt_type = -1;
				}
				$this->load->model('Transaction_item');
				$this->Transaction_item->add_adjust_trans_items($trans_id, $post['item_id'], $warehouse_id, $mvt_type, $qty);
				$items[0]['item_id'] = $post['item_id'];
				$this->Transaction->update_items_qty($items);
			}
		}
		redirect('warehouses/inventory');
	}

	public function clear_shelf()
	{
		$this->load->model('Warehouse');
		$warehouse_id = $this->input->post('warehouse_id');
		$rows = $this->Warehouse->fetch_warehouse_shelf_in_inventory($warehouse_id);
		$transType = Transaction::AdjustTransType;
		$this->load->model('Configuration');
		$lc_currency = $this->Configuration->fetch_local_currency()['valueInt'];
		$post = $this->input->post(null, true);
		if ($post) {
			$auto_no = $this->Transaction->set_next_auto_number($transType);
			$saved = $this->Transaction->add_new_adjust_trans($auto_no, $lc_currency, $transType);
			if ($saved) {
				$trans_id = $this->Transaction->fetch_transaction_id_by_autono($auto_no, $transType)[0]["id"];
				$this->load->model('Transaction_item');
				foreach ($rows as $row) {
					if ($row['total_qty'] > 0) {
						$mvt_type = -1;
					} elseif ($row['total_qty'] < 0) {
						$mvt_type = 1;
					}
					$qty = abs($row['total_qty']);
					$this->Transaction_item->add_adjust_trans_items($trans_id, $row['item_id'], $warehouse_id, $mvt_type, $qty);
				}
				$this->Transaction->update_items_qty($rows);
				echo ('1');
			} else {
				echo ('0');
			}
		} else {
			echo ('0');
		}
	}

	public function delete($id){
		$this->load->model('Transaction_item');
		$items = $this->Transaction_item->load_all_trans_items($id);
		if ($this->Transaction->delete($id)) {
			$this->Transaction->update_items_qty($items);
		}
		redirect('items/activity/'.$items[0]['item_id']);
	}
}
