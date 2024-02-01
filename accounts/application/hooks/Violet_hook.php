<?php

/**
 * @property MY_Controller $ci
 * @property string $current_url
 */
#[AllowDynamicProperties]
class Violet_hook
{

	public function __construct()
	{
		$this->ci = &get_instance();
		$this->current_url = "{$this->ci->uri->rsegment(1)}/{$this->ci->uri->rsegment(2)}";
	}

	public function boot_up()
	{
		$this->check_logged_in_user();
		if ($this->ci->violet_auth->is_logged_in())
			$this->check_selected_fiscal_year();
	}

	private function check_logged_in_user()
	{
		$controller = $this->ci->uri->rsegment(1);
		$action = $this->ci->uri->rsegment(2);
		$login_uri = $this->ci->violet_auth->get_login_uri();
		// var_dump($controller."/".$action);
		// exit;
		if (!$this->ci->violet_auth->is_logged_in() && $login_uri !== $this->current_url) {
			redirect($login_uri);
		} elseif ($this->ci->violet_auth->is_logged_in() && $login_uri !== $this->current_url) {
			if ($this->ci->violet_auth->get_user_type() !== "Master Admin") {
				$permission_controller = [
					"accounts", "items", "opening_items", "transfers",
					"quotations", "orders", "sales", "return_sales",
					"order_purchases", "purchases", "return_purchases", "payments", "receipts",
					"fiscal_years", "currencies", "warehouses", "configurations", "users"
				];
				$permission_action = [
					"add", "edit", "delete", "to_invoice", "add_to_order", "to_purchase", "receipt", "payment"
				];
				$url_permission_group = [
					"accounts/index", "items/index", "opening_items/index", "transfers/index", "warehouses/inventory",
					"quotations/index", "orders/index", "sales/index", "delivery_notes/index", "return_sales/index",
					"order_purchases/index", "purchases/index", "return_purchases/index", "payments/index", "receipts/index",
					"fiscal_years/index", "currencies/index", "warehouses/index", "configurations/index", "users/index",
					"users/choose_fiscal_year", "journal_accounts/index", "warehouses/reports", "reports/orders", "reports/employees", 
					"reports/activity", "reports/receiving_items", "reports/customer_receiving_items", "reports/purchase_orders", 
					"reports/pickup", "reports/pfand", "sales/driver_page", "employees/pickup_notes"
				];
				$this->ci->load->model("User");
				$user_id = $this->ci->violet_auth->get_user_id();
				if (in_array($this->current_url, $url_permission_group)) {
					$permission = $this->ci->User->check_user_permission($user_id, "{$controller}/{$action}")["count"];
					if ($permission === "0") {
						if ("{$controller}/{$action}" === "accounts/index") {
							redirect('users/no_user_permissions');
						} else {
							$this->ci->session->set_flashdata('message', 'Sorry, you do not have permission to access the requested page.');
							redirect('accounts/index');
						}
					}
				} else {
					if (in_array("{$controller}", $permission_controller) === true) {
						$controller_permission = $this->ci->User->check_user_permission($user_id, "{$controller}/index")["count"];
						if ($controller_permission === '0') {
							if (in_array("{$action}", $permission_action) === true) {
								if ("{$controller}/{$action}" === "accounts/index") {
									redirect('users/no_user_permissions');
								} else {
									$this->ci->session->set_flashdata('message', 'Sorry, you do not have permission to access the requested page.');
									redirect('accounts/index');
								}
							}
						}
					}
				}
			}
		}
	}

	private function check_selected_fiscal_year()
	{
		$select_fiscal_year_uri = $this->ci->violet_auth->get_select_fiscal_year_uri();
		if (empty($this->ci->violet_auth->get_fiscal_year_id()) && $select_fiscal_year_uri !== $this->current_url) {
			redirect($select_fiscal_year_uri);
		}
	}
}
