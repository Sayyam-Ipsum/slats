<?php
defined('BASEPATH') or die('No direct script access allowed');
class Item_ean extends MY_Model
{
	protected $modelName = 'Item_ean';
	protected $_table = 'items_ean';
	protected $_listFieldName = 'id';
	protected $_fieldsNames = ['id', 'item_id', 'ean'];
	protected $allowedNulls = [];

	public function __construct()
	{
		parent::__construct();
    }

    public function load_all_item_eans($item_id){
        $query = [
			'select' => "items_ean.ean",
			'where' => [["items_ean.item_id", $item_id]]
		];
		return $this->load_all($query);
    }

	public function add_new_ean_for_item($item_id, $ean){
		$data = [
			'item_id' => $item_id,
			'ean' => $ean,
		];
		$this->db->insert('items_ean', $data);
	}

	public function delete_all_item_ean($item_id){
		$this->db->delete('items_ean', ['item_id' => $item_id]);
	}
}