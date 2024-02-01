<?php

/**
 * @property MY_Controller $ci
 */
#[AllowDynamicProperties]

class Violet_auth
{

	public function __construct()
	{
		$this->ci = &get_instance();
	}

	private $login_uri = 'users/login';
	private $select_fiscal_year_uri = 'users/choose_f_year';

	public function get_login_uri()
	{
		return $this->login_uri;
	}

	public function get_select_fiscal_year_uri()
	{
		return $this->select_fiscal_year_uri;
	}

	public function build_user_session($user)
	{
		$data = [
			'vauth_user_id' => $user['id'],
			'vauth_user_name' => $user['user_name'],
			'vauth_user_email' => $user['user_email'],
			'vauth_user_type' => $user['user_type'],
			'vauth_fiscal_year_id' => $user['fiscal_year_id'],
			'vauth_fiscal_year' => $user['year_name'],
			'vauth_user_logged_in' => true
		];
		return $this->ci->session->set_userdata($data);
	}

	public function is_logged_in()
	{
		return true === $this->ci->session->userdata('vauth_user_logged_in');
	}

	public function get_user_id()
	{
		return $this->ci->session->userdata('vauth_user_id');
	}

	public function get_user_name()
	{
		return $this->ci->session->userdata('vauth_user_name');
	}

	public function get_user_email()
	{
		return $this->ci->session->userdata('vauth_user_email');
	}

	public function get_user_type()
	{
		return $this->ci->session->userdata('vauth_user_type');
	}

	public function get_fiscal_year_id()
	{
		return $this->ci->session->userdata('vauth_fiscal_year_id');
	}

	public function get_fiscal_year()
	{
		return $this->ci->session->userdata('vauth_fiscal_year');
	}

	public function set_fiscal_year_id($fiscal_year_id)
	{
		return $this->ci->session->set_userdata('vauth_fiscal_year_id', $fiscal_year_id);
	}

	public function set_fiscal_year($fiscal_year_id)
	{
		return $this->ci->session->set_userdata('vauth_fiscal_year', $fiscal_year_id);
	}
}
