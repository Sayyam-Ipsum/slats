<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ******** CONTROLLERS *********
 * @property CI_DB_query_builder $db
 * @property CI_Config $config
 * @property CI_Controller $controller
 * @property CI_Email $email
 * @property CI_Input $input
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Log $log
 * @property CI_Output $output
 * @property CI_Profiler $profiler
 * @property CI_Session $session
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $agent
 * @property CI_Zip $zip
 * @property Image_Upload $image_upload
 * @property Preferences $preferences
 * @property Iz_auth $iz_auth

 * ******** Properties *********
 * @property boolean $hasAccounting
 * @property boolean $hasSimPOS
 * @property int $company_id
 * @property int $fiscal_year_id
 * @property int $year
 */
class MY_Model extends CI_Model {

	protected $modelName = '';
	protected $_table = '';
	protected $_fields = [];
	protected $_fieldsNames = [];
	protected $allowedNulls = [];
	protected $_dateFields = [];
	protected $_listFieldName = "name";
	protected $_pk = "id";
	protected $paginationLinks = '';
	protected $paginationTotalRows = 0;
	protected $validationErrors = [];
	protected $load_list_defaultConfig = [];
	protected $paginate_defaultConfig = [];
	protected $builtInLogs = False;
	protected $validate = [];

	public function __construct() {
		parent::__construct();
		// get CI db instance
		$this->lang->load('validation_message');
		//initialize the _fields array
		$this->_fields = array_fill_keys($this->_fieldsNames, NULL);
		// set default conf arrays
		$this->load_list_defaultConfig = [
			'key' => $this->_pk,
			'value' => $this->_listFieldName,
			'optgroup' => false,
			'firstLine' => false,
			'lastLine' => false
		];
		$this->paginate_defaultConfig = [
			'anchorAttributes' => '',
			'currAttr' => [],
			'htmlCurrAfter' => '<li>',
			'htmlCurrBefore' => '<li class="active">',
			'htmlWrapAfter' => '</li>',
			'htmlWrapBefore' => '<li>',
			'inPage' => 10,
			'nextText' => '&gt;',
			'padding' => 2,
			'page' => NULL,
			'prevText' => '&lt;',
			'return' => 'array',
			'uri_segment' => 3,
			'urlPrefix' => ''
		];
	}

	public static function date_to_mysql($dateStr) {
		$d = new DateTime($dateStr);
		return $d->format('Y-m-d');
	}

	public static function date_to_human($dateStr, $f = "d-m-Y") {
		if (empty($dateStr) || !self::can_compare_to_date($dateStr)) {
			if (empty($dateStr) || FALSE !== strpos(strtolower($dateStr), 'day') && FALSE !== strpos(strtolower($dateStr), 'month') && FALSE !== strpos(strtolower($dateStr), 'year')) {
				return $dateStr;
			}
		}
		$d = new DateTime($dateStr);
		return $d->format($f);
	}

	public function __sleep() {
		return ['_fields'];
	}

	public function __wakeup() {
		
	}

	protected function adjust_auto_increment() {
		$query = $this->db->select_max($this->_pk, 'MaxID')->get($this->_table);
		$MaxID = $query->row()->MaxID + 1;
		$this->db->simple_query("ALTER TABLE `{$this->_table}` AUTO_INCREMENT = {$MaxID};");
	}

	/**
	 * A member function, is passed the property’s name and its new value.
	 * @access public
	 * @param $property,$newValue
	 */
	public function set($property, $newValue) {
		$this->$property = $newValue;
	}

	/**
	 * A member function, is passed the property’s name, and it's should return a value.
	 * @access public
	 * @param $property
	 * @see __get()
	 * @return value, the value of this property
	 */
	public function get($property) {
		return $this->$property;
	}

	/**
	 * A member function, it's should return a value fields.
	 * @access public
	 * @param $fields
	 * @see get_field()
	 * @return Values, value fields on success or NULL on failure
	 */
	public function get_fields($fields = NULL) {

		return empty($fields) ? $this->_fields : array_intersect_key($this->_fields, array_fill_keys($fields, ''));
	}

	/**
	 * A member function, it's should return a value of specific field .
	 * @access public
	 * @param $field
	 * @see get_fields()
	 * @return Value, value field on success or NULL on failure
	 */
	public function get_field($field) {
		if (array_key_exists($field, $this->_fields)) {
			return $this->_fields[$field];
		}
		trigger_error("(Use of unkown field \"" . $field . "\" in \"" . $this->modelName . "\")");
		return NULL;
	}

	/**
	 * A member function, it's should reset a value fields.
	 * @access public
	 */
	public function reset_fields() {
		$this->set('_fields', array_fill_keys($this->_fieldsNames, NULL));
	}

	function result_field_to_array($field, $query) {
		$array = [];
		foreach ($query->result_array() as $rec) {
			array_push($array, $rec[$field]);
		}
		return $array;
	}

	/**
	 * A member function, it's should set a new value of specific field.
	 * @access public
	 * @param $field,$value
	 * @see set_fields()
	 */
	public function set_field($field, $value) {
		if (array_key_exists($field, $this->_fields)) {
			$this->_fields[$field] = $value;
		} else {
			trigger_error("(Use of unkown field \"" . $field . "\" of \"" . $this->modelName . "\")");
		}
	}

	/**
	 * A member function, it's should set a new values of fields.
	 * @access public
	 * @param $values
	 * @see set_field()
	 */
	public function set_fields($values) {
		if (is_array($values)) {
			$values = array_intersect_key($values, $this->_fields);
			$this->_fields = array_merge($this->_fields, $values);
		}
	}

	public function prep_query($queryArr) {
		$this->parse_user_calls($queryArr);
	}

	/**
	 * A member function, it's allow to define a query parts(select,where,order by...).
	 * @access private
	 * @param $userQueryParts
	 * @see load(),load_all(),load_list()
	 */
	private function parse_user_calls($userQueryParts = []) {
		foreach ($userQueryParts as $userCall => $call) {
			if (is_array($call[0])) {
				foreach ($call as $subCall) {
					call_user_func_array([$this->db, $userCall], $subCall);
				}
			} else {
				is_array($call) ? call_user_func_array([$this->db, $userCall], $call) : call_user_func([$this->db, $userCall], $call);
			}
		}
	}

	/**
	 * A member function, it's get a row specific from the database and fill the row in array on the model(row as limit 1 and id specific).
	 * @access public
	 * @param $where
	 * where can have an integer value in which case the integer will be compared to the primary key
	 * or associative array where it will be used with get_where()
	 * @return bool, TRUE on success or FALSE on failure
	 * @see load()
	 */
	function fetch($where) {
		$this->db->limit(1);
		if (is_numeric($where)) {
			$result = $this->db->get_where($this->_table, [$this->_pk => $where]);
		} else {
			$result = $this->db->get_where($this->_table, $where);
		}
		if ($result->num_rows() > 0) {
			$row = $result->row_array();
			foreach ($this->_dateFields as $fld) {
				if (!empty($row[$fld])) {
					$row[$fld] = $this->date_to_human($row[$fld]);
				}
			}
			$this->set_fields($row);
			return true;
		}
		return false;
	}

	/**
	 * A member function, same functionality of fetch(), plus it's load a query parts(order by,where...) and returns result as an array
	 * @access public
	 * @param $query
	 * @return bool, TRUE on success or FALSE on failure
	 * @see fetch(),load_all()
	 */
	function load($query) {
		$this->db->limit(1);
		is_numeric($query) ? $this->db->where($this->_pk, $query) : $this->parse_user_calls($query);
		$result = $this->db->get($this->_table);
		if ($result->num_rows() > 0) {
			$row = $result->row_array();
			foreach ($this->_dateFields as $fld) {
				if (isset($row[$fld]) && !empty($row[$fld])) {
					$row[$fld] = $this->date_to_human($row[$fld]);
				}
			}
			return $row;
		}
		return FALSE;
	}

	/**
	 * A member function, same functionality of load(), plus it's load an array of query.
	 * @access public
	 * @param $$userQueryParts,$return
	 * @return array, an associative array of results on success or 'array' on failure
	 * @see load()
	 */
	public function load_all($userQueryParts = [], $return = 'array') {
		$this->parse_user_calls($userQueryParts);
		$result = $this->db->get($this->_table);
		return $return == 'array' ? $result->result_array() : $result->result();
	}

	/**
	 * A member function, same functionality of load(), plus it's load an array of query.
	 * @access public
	 * @param $$userQueryParts,$return
	 * @return array, an associative array of results on success or 'array' on failure
	 * @see load()
	 */
	public function load_all_numeric($userQueryParts = []) {
		$this->parse_user_calls($userQueryParts);
		$result = $this->db->get($this->_table);
		return $this->mysql_result_array_num($result);
	}

	public function load_data_tables_results($init_override = []) {
		/*
		 * "sEcho": 2, "iTotalRecords": 57, "iTotalDisplayRecords": 57,
		 */
		$init = [
			'columns' => $this->_fieldsNames,
			'search_in' => FALSE,
			'customQuery' => []
		];
		$init_override = array_intersect_key($init_override, $init);
		$init = array_merge($init, $init_override);
		unset($init_override);
		$data = [];
		$perPageDisplayLength = $this->input->post('iDisplayLength', TRUE) * 1;
		$page = $perPageDisplayLength ? $this->input->post('iDisplayStart', TRUE) / $perPageDisplayLength + 1 : 1;
		$this->pagination_config('page');
		// Customize User Query :: Add Sorting & Filtering
		$userQueryParts = ['order_by' => []];
		$sColIdx = 0;
		$sortingCol = $this->input->post("iSortCol_{$sColIdx}");
		while (NULL !== $sortingCol) {
			if (isset($init['columns'][$sortingCol])) {
				$userQueryParts['order_by'][] = [
					is_array($init['columns'][$sortingCol]) ? $init['columns'][$sortingCol][1] : $init['columns'][$sortingCol],
					$this->input->post("sSortDir_{$sColIdx}")
				];
			}
			$sColIdx++;
			$sortingCol = $this->input->post("iSortCol_{$sColIdx}");
		}
		if (0 === count($userQueryParts['order_by'])) {
			unset($userQueryParts['order_by']);
		}
		$this->pagination_config_set('page', $page, TRUE);
		$this->pagination_config_set('inPage', $perPageDisplayLength);
		if (0 == count($init['customQuery'])) {
			$init['customQuery'] = ['select' => implode(', ', $init['columns'])];
		}
		$userQueryParts = array_merge($init['customQuery'], $userQueryParts);
		$sSearch = trim((string) $this->input->post('sSearch', TRUE));
		$advFilter = $this->input->post('advFilter', TRUE);
		$or_like_search = "";
		if (NULL === $advFilter) {
			if ($sSearch && is_array($init['search_in']) && 0 < count($init['search_in'])) {
				foreach ($init['search_in'] as $sFld) {
					$or_like_search .= "OR {$sFld} LIKE " . $this->db->escape("%{$sSearch}%");
				}
				if (strlen($or_like_search) > 3) {
					$userQueryParts['where'][] = ["(" . substr($or_like_search, 2) . ")"];
				}
			}
		} else {
			
		}
		$data['data'] = $this->paginate($userQueryParts);
		$data['sEcho'] = $this->input->post('sEcho', TRUE);
		$data['iTotalDisplayRecords'] = $this->get('paginationTotalRows');
		$data['iTotalRecords'] = $this->get('paginationTotalRows');
		return $data;
	}

	public function load_first($fld = FALSE) {
		if (FALSE === $fld) {
			$fld = $this->_pk;
		}
		$query = $this->db->order_by($fld, 'ASC')->limit(1)->get($this->_table);
		if (1 === $query->num_rows()) {
			return $query->row_array();
		}
		return FALSE;
	}

	public function load_last($fld = FALSE) {
		if (FALSE === $fld) {
			$fld = $this->_pk;
		}
		$query = $this->db->order_by($fld, 'DESC')->limit(1)->get($this->_table);
		if (1 === $query->num_rows()) {
			return $query->row_array();
		}
		return FALSE;
	}

	public function load_next($fldVal, $fldName = FALSE) {
		if (FALSE === $fldName) {
			$fldName = $this->_pk;
		}
		$query = $this->db->where("{$fldName} >", $fldVal)->order_by($fldName, 'ASC')->limit(1)->get($this->_table);
		if (1 === $query->num_rows()) {
			return $query->row_array();
		}
		return FALSE;
	}

	public function load_previous($fldVal, $fldName = FALSE) {
		if (FALSE === $fldName) {
			$fldName = $this->_pk;
		}
		$query = $this->db->where("{$fldName} <", $fldVal)->order_by($fldName, 'DESC')->limit(1)->get($this->_table);
		if (1 === $query->num_rows()) {
			return $query->row_array();
		}
		return FALSE;
	}

	/**
	 * Query result.  "array" version.
	 *
	 * @access	public
	 * @return	array
	 */
	public function mysql_result_array_num(&$Result) {
		// In the event that query caching is on the result_id variable
		// will return FALSE since there isn't a valid SQL resource so
		// we'll simply return an empty array.
		if ($Result->result_id === FALSE OR $Result->num_rows() == 0) {
			return [];
		}

		$Result->_data_seek(0);
		while ($row = mysql_fetch_array($Result->result_id, MYSQL_NUM)) {
			$Result->result_array[] = $row;
		}

		return $Result->result_array;
	}

	/**
	 * A member function, it's get the info. from the Database where(id,name specific) and put the result on a dropdownlist .
	 * @access public
	 * @param $userQuery,$config
	 * @return array, an associative array
	 * @see load(),load_all()
	 */
	public function load_list($userQuery = [], $config = []) {
		$config = array_intersect_key($config, $this->load_list_defaultConfig);
		$config = array_merge($this->load_list_defaultConfig, $config);
		if (!isset($userQuery['select'])) {
			$this->db->select($config['key']);
			$this->db->select($config['value']);
			if (!empty($config['optgroup']))
				$this->db->select($config['optgroup']);
		}
		if (!isset($userQuery['order_by'])) {
			$this->db->order_by($config['value'], "asc");
		}
		$this->parse_user_calls($userQuery);
		$result = $this->db->get($this->_table);
		$data = [];
		if (!empty($config['firstLine'])) {
			foreach ($config['firstLine'] as $__k => $__v) {
				$first['key'] = $__k;
				$first['value'] = $__v;
				break;
			}
			unset($__k, $__v);
			if (empty($config['optgroup'])) {
				$data[$first['key']] = $first['value'];
			} else {
				$data[''][$first['key']] = $first['value'];
			}
		}
		if ($result->num_rows() > 0) {
			if (empty($config['optgroup'])) {
				foreach ($result->result_array() as $row) {
					$data[(string) $row[$config['key']]] = $row[$config['value']];
				}
			} else {
				foreach ($result->result_array() as $row) {
					$data[(string) $row[$config['optgroup']]][(string) $row[$config['key']]] = $row[$config['value']];
				}
			}
		}
		if (!empty($config['lastLine'])) {
			foreach ($config['lastLine'] as $__k => $__v) {
				$last['key'] = $__k;
				$last['value'] = $__v;
				break;
			}
			unset($__k, $__v);
			if (empty($config['optgroup'])) {
				$data[$last['key']] = $last['value'];
			} else {
				$data[''][$last['key']] = $last['value'];
			}
		}
		return $data;
	}

	public function pagination_config_all($fields = NULL) {
		return empty($fields) ? $this->paginate_defaultConfig : array_intersect_key($this->paginate_defaultConfig, array_fill_keys($fields, ''));
	}

	public function pagination_config_set_all($values) {
		if (is_array($values)) {
			$values = array_intersect_key($values, $this->paginate_defaultConfig);
			$this->paginate_defaultConfig = array_merge($this->paginate_defaultConfig, $values);
		}
	}

	public function pagination_config_set($field, $value, $add = FALSE) {
		if (array_key_exists($field, $this->paginate_defaultConfig) || $add) {
			$this->paginate_defaultConfig[$field] = $value;
		} else {
			trigger_error($this->modelName . " :: Unkown pagination config item \"" . $field);
		}
	}

	public function pagination_config($field) {
		if (array_key_exists($field, $this->paginate_defaultConfig)) {
			return $this->paginate_defaultConfig[$field];
		}
		trigger_error($this->modelName . " :: Unkown pagination config item \"" . $field);
		return NULL;
	}

	/**
	 * A member function,make a relationship between the tables and it's return the pages numbres .
	 * @access public
	 * @param $userQueryParts,$config
	 * @return array, an associative array
	 */
	function paginate($userQueryParts = [], $config = []) {
		$CI = & get_instance();
		$config = array_intersect_key($config, $this->paginate_defaultConfig);
		$config = array_merge($this->paginate_defaultConfig, $config);
		// current page
		$page = isset($config['page']) ? $config['page'] : _gnv($CI->uri->rsegment($config['uri_segment']));
		$this->pagination_config_set('page', $page);
		$queryString = [];
		$config['urlPrefix'];
		$segments = $CI->uri->rsegments;
		for ($i = 1, $j = count($segments); $j >= $i; $i++) {
			$queryString[] = $i != $config['uri_segment'] ? str_replace('%', '%%', $segments[$i]) : '%d';
		}
		if ($config['uri_segment'] > count($segments)) {
			$queryString[] = '%d';
		}
		$href = site_url(implode('/', $queryString));
		//$href = site_url($queryString);
		$ignoredQueryParts = ['select' => 'select', 'order_by' => 'order_by', 'limit' => 'limit']; // to be ignored while counting
		$countingQueryParts = array_diff_key($userQueryParts, $ignoredQueryParts);
		$this->parse_user_calls($countingQueryParts);
		$this->db->select('COUNT(*) total_rows');
		$totalRowsQ = $this->db->get($this->_table);
		if (isset($countingQueryParts['group_by'])) {
			$totalRows = $totalRowsQ->num_rows();
		} else {
			$totalRows = $totalRowsQ->row();
			$totalRows = $totalRows->total_rows;
		}
		unset($totalRowsQ);
		if ($totalRows < $config['inPage'] || 0 === $config['inPage']) {
			$this->paginationLinks = '';
			$this->paginationTotalRows = $totalRows;
			return $this->load_all($userQueryParts);
		}
		$padding = $config['padding'];
		$this->paginationTotalRows = $totalRows;
		$lastPage = ceil($totalRows / $config['inPage']);
		$pages = '';
		$attrStr = '';
		$currAttr = '';
		// set additional attributes /// to be added to config
		if (!empty($config['anchorAttributes']) && is_array($config['anchorAttributes'])) {
			foreach ($config['anchorAttributes'] as $key => $value) {
				$attrStr .= $key . "=\"" . $value . "\" ";
			}
		}
		if (!empty($config['currAttr']) && is_array($config['currAttr'])) {
			foreach ($config['currAttr'] as $key => $value) {
				$currAttr .= $key . "=\"" . $value . "\" ";
			}
		}
		if ($lastPage > $padding) {
			if ($page > 1) {
				$offset = $padding > $page ? $page : ( ($page + $padding) < $lastPage ? $page : $lastPage - $padding );

				if ($offset != 1) {
					if ($page != 1) {
						$pages .= $config['htmlWrapBefore'] . "<a " . $attrStr . ' href="' . sprintf($href, '1') . '"' . ">1</a>{$config['htmlWrapAfter']}";
					} else {
						$pages .= $config['htmlCurrBefore'] . "<a " . $currAttr . ' href="' . sprintf($href, '1') . '"' . ">1</a>{$config['htmlCurrAfter']}";
					}
					$pages .= "{$config['htmlWrapBefore']}<a {$attrStr}" . ' href="' . sprintf($href, ($page - 1)) . '"> ' . $config['prevText'] . "</a>{$config['htmlWrapAfter']}";
				}
			} else {
				$offset = 1;
			}
			$limit = ($limit = $padding + $offset) > $lastPage ? $lastPage + 1 : $limit;
			if (($limit - $offset) < $padding)
				$offset = $limit - $padding;
		} else {
			$offset = 1;
			$limit = $lastPage;
		}
		if (($offset - ($padding / 2)) > 1)
			$offset = $offset - floor($padding / 2);
		if ($page == 0)
			$page++;
		for ($i = $offset; $i <= $limit; $i++) {
			if ($page != $i) {
				$pages .= $config['htmlWrapBefore'] . "<a " . $attrStr . ' href="' . sprintf($href, $i) . '"' . ">{$i}</a>{$config['htmlWrapAfter']}";
			} else {
				$pages .= $config['htmlCurrBefore'] . "<a " . $currAttr . ' href="' . sprintf($href, $i) . '"' . ">{$i}</a>{$config['htmlCurrAfter']}";
			}
		}
		if ($limit < $lastPage) {
			$pages .= " {$config['htmlWrapBefore']}<a " . $attrStr . ' href="' . sprintf($href, ($page + 1)) . '"> ' . $config['nextText'] . "</a>{$config['htmlWrapAfter']}";
			$pages .= " {$config['htmlWrapBefore']}<a " . $attrStr . ' href="' . sprintf($href, $lastPage) . '"';
			if ($page == $lastPage)
				$pages .= ' id="current"';
			$pages .= '> ' . $lastPage . "</a>{$config['htmlWrapAfter']}";
		}
		$this->paginationLinks = $pages;
		$this->db->limit($config['inPage'], (($page - 1) * $config['inPage']));
		return isset($config['retNumArr']) ? $this->load_all_numeric($userQueryParts, $config['return'], false) : $this->load_all($userQueryParts, $config['return'], false);
	}

	public function pagination_showing($totalRows, $take, $currPage) {
		return ((($currPage - 1) * $take) + 1) . ' - ' .
				(ceil($totalRows / $take) == $currPage ? ($totalRows) : ($currPage * $take)) .
				' ' . $this->lang->line('of') . ' ' . $totalRows . ' ' . $this->lang->line('items');
	}

	/**
	 * A member function, insert a query in table specific.
	 * @access public
	 * @param $skipValidation
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function insert($skipValidation = false) {
		if ($this->builtInLogs) {
			$this->_fields['createdOn'] = date("Y-m-d H:i:s");
			$this->_fields['createdBy'] = $this->iz_auth->get_user_id();
			$this->_fields['modifiedOn'] = date("Y-m-d H:i:s");
			$this->_fields['modifiedBy'] = $this->iz_auth->get_user_id();
		}
		if ($skipValidation || $this->validate()) {
			array_walk($this->_fields, [$this, 'set_mysql_nulls_fix_dates']);
			if ($this->_fields[$this->_pk] < 1)
				unset($this->_fields[$this->_pk]);
			$this->db->set($this->_fields)->insert($this->_table);
			$this->_fields[$this->_pk] = $this->db->insert_id();
			if ($this->db->affected_rows() > 0) {
				$this->log_action(__FUNCTION__);
				$this->log_action('update');
				return true;
			}
			return false;
		}
		return false;
	}

	/**
	 * A member function, insert a multiple row in table.
	 * @access public
	 * @param $set
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function insert_batch($set) {
		return $this->db->insert_batch($this->_table, $set);
	}

	protected function reset_write() {
		$ar_reset_items = [
			'qb_set' => [],
			'qb_from' => [],
			'qb_where' => [],
			'qb_like' => [],
			'qb_orderby' => [],
			'qb_keys' => [],
			'qb_limit' => FALSE,
			'qb_order' => FALSE
		];
		foreach ($ar_reset_items as $item => $reset) {
			$this->db->{$item} = $reset;
		}
	}

	/**
	 * A member function, insert a multiple row in table and ignores duplicate lines.
	 * @access public
	 * @param $set
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function insert_batch_ignore($set) {
		if (!is_null($set)) {
			$this->db->set_insert_batch($set);
		}
		$sql = "INSERT IGNORE INTO " . $this->db->protect_identifiers($this->_table) . " (" . implode($this->db->g('qb_keys'), ', ') . ") VALUES " . implode($this->db->g('qb_set'), ', ');
		$this->db->reset_query();
		return $this->db->query($sql);
	}

	/**
	 * Insert_on_duplicate_update_batch statement
	 *
	 * Generates a platform-specific insert string from the supplied data
	 * MODIFIED to include ON DUPLICATE UPDATE
	 *
	 * @access public
	 * @param string the table name
	 * @param array the insert keys
	 * @param array the insert values
	 * @return string
	 */
	private function _insert_on_duplicate_update_batch($table, $keys, $values) {
		foreach ($keys as $key) {
			$update_fields[] = $key . '=VALUES(' . $key . ')';
		}
		return "INSERT INTO " . $table . " (" . implode(', ', $keys) . ") VALUES " . implode(', ', $values);
	}

	/**
	 * Insert_On_Duplicate_Update
	 *
	 * Compiles insert string and runs the query
	 *
	 * @access public
	 * @param array an associative array of insert values
	 * @return boolean
	 */
	public function insert_on_duplicate_key_update($dataSet = NULL, $keys = NULL) {
		$method = $this->db->dbdriver . '_insert_on_duplicate_key_update';
		return $this->{$method}($dataSet, $keys);
	}

	private function mysql_insert_on_duplicate_key_update($dataSet) {
		ksort($dataSet);
		$columns = array_keys($dataSet);
		$sql = "INSERT INTO {$this->_table} (" . implode(', ', $columns) . ")" .
				"VALUES(";
		foreach ($dataSet as $value)
			$sql .= $this->db->escape($value) . ", ";
		$sql = substr($sql, 0, -2) . ") ON DUPLICATE KEY UPDATE ";
		foreach ($dataSet as $k => $v)
			$sql .= $k . " = " . $this->db->escape($v) . ", ";
		$sql = substr($sql, 0, -2);
		return $this->db->query($sql);
	}

	private function mysqli_insert_on_duplicate_key_update($dataSet) {
		return $this->mysql_insert_on_duplicate_key_update($dataSet);
	}

	private function sqlsrv_insert_on_duplicate_key_update($dataSet, $keys) {
		$where = [];
		foreach ($keys as $k)
			$where[] = [$k, $dataSet[$k]];
		$oldRow = $this->load(compact('where'));
		if (is_array($oldRow)) {
			$oldRow = array_merge($oldRow, $dataSet);
			$this->parse_user_calls(compact('where'));
			foreach ($keys as $k)
				unset($oldRow[$k]);
			if (isset($oldRow[$this->_pk]))
				unset($oldRow[$this->_pk]);
			return $this->db->update($this->_table, $oldRow);
		} else {
			return $this->db->set($dataSet)->insert($this->_table);
		}
	}

	private function sqlsrv08_insert_on_duplicate_key_update($dataSet, $keys) {
		$where = [];
		foreach ($keys as $k)
			$where[] = [$k, $dataSet[$k]];
		$oldRow = $this->load(compact('where'));
		if (is_array($oldRow)) {
			$oldRow = array_merge($oldRow, $dataSet);
			$this->parse_user_calls(compact('where'));
			foreach ($keys as $k)
				unset($oldRow[$k]);
			if (isset($oldRow[$this->_pk]))
				unset($oldRow[$this->_pk]);
			return $this->db->update($this->_table, $oldRow);
		} else {
			return $this->db->set($dataSet)->insert($this->_table);
		}
	}

	/**
	 * Insert_On_Duplicate_Update_Batch
	 *
	 * Compiles batch insert strings and runs the queries
	 * MODIFIED to do a MySQL 'ON DUPLICATE KEY UPDATE'
	 *
	 * @access public
	 * @param string the table to retrieve the results from
	 * @param array an associative array of insert values
	 * @return object
	 */
	public function insert_on_duplicate_update_batch($dataSet = NULL, $keys = NULL) {
		if (!is_array($dataSet) || empty($dataSet)) {
			return FALSE;
		}
		$method = $this->db->dbdriver . '_insert_on_duplicate_update_batch';
		return $this->{$method}($dataSet, $keys);
	}

	private function sqlsrv08_insert_on_duplicate_update_batch($dataSet, $keys) {
		return $this->sqlsrv_insert_on_duplicate_update_batch($dataSet, $keys);
	}

	private function sqlsrv_insert_on_duplicate_update_batch($dataSet, $keys) {
		if (is_string($keys)) {
			$key = $keys;
			$newDataSet = [];
			$keyValues = [];
			while ($row = array_shift($dataSet)) {
				$keyValues[] = $row[$key];
				$newDataSet[md5($row[$key])] = $row;
			}
			unset($row);
			$oldDataSetQuery = $this->db->select(implode(', ', array_keys(current($newDataSet))))->where_in($key, $keyValues)->get($this->_table);
			foreach ($oldDataSetQuery->result_array() as $row) {
				$currentKeyIndex = md5($row[$key]);
				$newDataSet[$currentKeyIndex] = array_merge($row, $newDataSet[$currentKeyIndex]);
			}
			$oldDataSetQuery = $this->db->where_in($key, $keyValues)->delete($this->_table);
			return $this->insert_batch($newDataSet);
		} elseif (is_array($keys)) {
			$newDataSet = [];
			$keyValues = [];
			while ($row = array_shift($dataSet)) {
				$hashKey = "";
				foreach ($keys as $key) {
					$keyValues[$key][] = $row[$key];
					$hashKey .= md5($row[$key]);
				}
				$newDataSet[$hashKey] = $row;
			}
			unset($row);
			$whereIn = ['where_in' => []];
			foreach ($keys as $key) {
				$whereIn['where_in'][] = [$key, array_unique($keyValues[$key])];
			}
			$this->parse_user_calls($whereIn);
			$oldDataSetQuery = $this->db->select(implode(', ', array_keys(current($newDataSet))))->get($this->_table);
			foreach ($oldDataSetQuery->result_array() as $row) {
				$currentKeyIndex = "";
				foreach ($keys as $key) {
					$currentKeyIndex .= md5($row[$key]);
				}
				$newDataSet[$currentKeyIndex] = array_merge($row, $newDataSet[$currentKeyIndex]);
			}
			$this->parse_user_calls($whereIn);
			$oldDataSetQuery = $this->db->delete($this->_table);
			return $this->insert_batch($newDataSet);
		}
		return FALSE;
	}

	private function mysqli_insert_on_duplicate_update_batch($set) {
		return $this->mysql_insert_on_duplicate_update_batch($set);
	}

	private function mysql_insert_on_duplicate_update_batch($set) {
		$this->db->reset_query();
		$table = $this->_table;
		if (!is_null($set)) {
			$this->db->set_insert_batch($set);
		}
		if (count($this->db->g('qb_set')) == 0) {
			if ($this->db->db_debug) {
				//No valid data array.  Folds in cases where keys and values did not match up
				return $this->db->display_error('db_must_use_set');
			}
			return FALSE;
		}
		if ($table == '') {
			$qb_from = $this->db->g('qb_from');
			if (!isset($qb_from[0])) {
				if ($this->db->db_debug) {
					return $this->db->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $qb_from[0];
		}
		// Batch this baby
		for ($i = 0, $total = count($this->db->g('qb_set')); $i < $total; $i = $i + 100) {
			$sql = $this->_insert_on_duplicate_update_batch(
					$this->db->protect_identifiers($table, TRUE, NULL, FALSE), $this->db->g('qb_keys'), array_slice($this->db->g('qb_set'), $i, 100)
			);
			$this->db->query($sql);
		}
		$this->db->reset_query();
		return TRUE;
	}

	/**
	 * A member function, update a query in table specific.
	 * @access public
	 * @param $skipValidation,$where
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function update($skipValidation = false, $where = []) {
		$data = $this->_fields;
		if ($this->builtInLogs) {
			$data['modifiedOn'] = date("Y-m-d H:i:s");
			$data['modifiedBy'] = $this->iz_auth->get_user_id();
			$this->_fields['modifiedOn'] = $data['modifiedOn'];
			$this->_fields['modifiedBy'] = $data['modifiedBy'];
			unset($data['createdOn'], $data['createdBy']);
		}
		unset($data[$this->_pk]);
		if ($skipValidation || $this->validate()) {
			array_walk($data, [$this, 'set_mysql_nulls_fix_dates']);
			if (empty($where)) {
				if ($this->db->where([$this->_pk => $this->_fields[$this->_pk]])->update($this->_table, $data)) {
					$this->log_action(__FUNCTION__);
					$this->set_fields($data);
					return true;
				}
				return false;
			} else {
				$this->parse_user_calls(compact('where'));
				if ($this->db->update($this->_table, $data)) {
					$this->log_action(__FUNCTION__);
					$this->set_fields($data);
					return true;
				}
				return false;
			}
		}
		return false;
	}

	/**
	 * A member function, delete a row in table.
	 * @access public
	 * @param $userQueryParts can be an integer in which case the where condition will be applied on the primary key
	 * If $userQueryParts is supplied as an array, then it should have the where conditions of the query.
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function delete($userQueryParts = []) {
		$result = FALSE;
		if (is_numeric($userQueryParts)) {
			if ($result = $this->db->delete($this->_table, [$this->_pk => $userQueryParts], 1)) {
				$result &= $this->db->affected_rows();
				$this->log_action(__FUNCTION__, $userQueryParts);
			}
		} else {
			$this->parse_user_calls($userQueryParts);
			$result = $this->db->delete($this->_table);
		}
		if ($result) {
			return $this->db->affected_rows();
		}
		return false;
	}

	/**
	 * A member function, insert an audit logs action on database.
	 * @access protected
	 * @param $action,$id
	 * @see get_action_logs()
	 */
	protected function log_action($action, $id = '') {
		$id = empty($id) ? $this->_fields[$this->_pk] : $id;
		if (isset($this->db->action_logging) && $this->db->action_logging && $this->iz_auth->get_user_id() && $id > 0) {
			$log = [
				'user_id' => $this->iz_auth->get_user_id(),
				'model' => $this->modelName,
				'action' => $action,
				'recordId' => $id
			];
			$this->db->insert('audit_logs', $log);
		}
	}

	public function log_built_in_last_action($id) {
		$data = [
			'modifiedOn' => date("Y-m-d H:i:s"),
			'modifiedBy' => $this->iz_auth->get_user_id()
		];
		$this->db->reset_query();
		$this->db->where('id', $id);
		$this->db->update($this->_table, $data);
	}

	public function load_last_action_log($id) {
		$method = $this->db->dbdriver . '_load_last_action_log';
		return $this->{$method}($id);
	}

	private function mysql_load_last_action_log($id) {
		$this->mysqli_load_last_action_log($id);
	}

	private function mysqli_load_last_action_log($id) {
		$actionLogs = [];
		$_defaultLog = [
			'on' => '---',
			'by' => '---',
			'email' => '---',
			'user_id' => '---'
		];
		if ($this->builtInLogs) {
			if ($id != $this->_fields[$this->_pk]) {
				$usersTbl = $this->iz_auth->users_table;
				$userProfilesTbl = $this->iz_auth->user_profile_table;
				$sql = "SELECT {$userProfilesTbl}.user_id, 'insert' as action, {$this->_table}.createdOn as created, " .
						"CONCAT({$userProfilesTbl}.firstName, ' ', {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$this->_table} ";
				$sql .= "LEFT JOIN {$usersTbl} ON {$usersTbl}.id = {$this->_table}.createdBy ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$this->_table}.createdBy ";
				$sql .= "WHERE {$this->_table}.{$this->_pk} = '{$id}' ";
				$sql .= "UNION ";
				$sql .= "SELECT {$userProfilesTbl}.user_id, 'update' as action, {$this->_table}.modifiedOn as created, " .
						"CONCAT({$userProfilesTbl}.firstName, ' ', {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$this->_table} ";
				$sql .= "LEFT JOIN {$usersTbl} ON {$usersTbl}.id = {$this->_table}.modifiedBy ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$this->_table}.modifiedBy ";
				$sql .= "WHERE{$this->_table}.{$this->_pk} = '{$id}'";
			} else {
				$usersTbl = $this->iz_auth->users_table;
				$userProfilesTbl = $this->iz_auth->user_profile_table;
				$sql = "SELECT {$userProfilesTbl}.user_id, 'insert' as action, '{$this->_fields['createdOn']}' as created, " .
						"CONCAT({$userProfilesTbl}.firstName, ' ', {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$usersTbl} ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$usersTbl}.id ";
				$sql .= "WHERE {$usersTbl}.id = '{$this->_fields['createdBy']}'";
				$sql .= " UNION ";
				$sql .= "SELECT {$userProfilesTbl}.user_id, 'update' as action, '{$this->_fields['modifiedOn']}' as created, " .
						"CONCAT({$userProfilesTbl}.firstName, ' ', {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$usersTbl} ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$usersTbl}.id ";
				$sql .= "WHERE {$usersTbl}.id = '{$this->_fields['modifiedBy']}'";
			}
			$query = $this->db->query($sql);
		} else {
			$vName = $this->db->last_action_log_view; //View Name
			$this->db->select("{$vName}.user_id, {$vName}.action, {$vName}.created, {$vName}.fullName, {$vName}.username, {$vName}.email");
			$this->db->where('model', $this->modelName)->where('recordId', $id);
			$query = $this->db->get("audit_log_last_action");
		}
		foreach ($query->result_array() as $log) {
			$actionLogs[$log['action']] = [
				'on' => date("Y-m-d H:i", strtotime($log['created'])),
				'by' => $log['fullName'],
				'email' => $log['email'],
				'user_id' => $log['user_id']
			];
		}
		if (!isset($actionLogs['insert']))
			$actionLogs['insert'] = $_defaultLog;
		if (!isset($actionLogs['update']))
			$actionLogs['update'] = $_defaultLog;
		return $actionLogs;
	}

	public function sqlsrv_load_last_action_log($id) {
		$actionLogs = [];
		$_defaultLog = [
			'on' => '---',
			'by' => '---',
			'email' => '---',
			'user_id' => '---'
		];
		if ($this->builtInLogs) {
			if ($id != $this->_fields[$this->_pk]) {
				$usersTbl = $this->iz_auth->users_table;
				$userProfilesTbl = $this->iz_auth->user_profile_table;
				$sql = "SELECT {$userProfilesTbl}.user_id, 'insert' as action, {$this->_table}.createdOn as created, " .
						"({$userProfilesTbl}.firstName + ' ' + {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$this->_table} ";
				$sql .= "LEFT JOIN {$usersTbl} ON {$usersTbl}.id = {$this->_table}.createdBy ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$this->_table}.createdBy ";
				$sql .= "WHERE {$this->_table}.{$this->_pk} = '{$id}' ";
				$sql .= "UNION ";
				$sql .= "SELECT {$userProfilesTbl}.user_id, 'update' as action, {$this->_table}.modifiedOn as created, " .
						"({$userProfilesTbl}.firstName + ' ' + {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$this->_table} ";
				$sql .= "LEFT JOIN {$usersTbl} ON {$usersTbl}.id = {$this->_table}.modifiedBy ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$this->_table}.modifiedBy ";
				$sql .= "WHERE{$this->_table}.{$this->_pk} = '{$id}'";
			} else {
				$usersTbl = $this->iz_auth->users_table;
				$userProfilesTbl = $this->iz_auth->user_profile_table;
				$sql = "SELECT {$userProfilesTbl}.user_id, 'insert' as action, '{$this->_fields['createdOn']}' as created, " .
						"({$userProfilesTbl}.firstName + ' ' + {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$usersTbl} ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$usersTbl}.id ";
				$sql .= "WHERE {$usersTbl}.id = '{$this->_fields['createdBy']}'";
				$sql .= " UNION ";
				$sql .= "SELECT {$userProfilesTbl}.user_id, 'update' as action, '{$this->_fields['modifiedOn']}' as created, " .
						"({$userProfilesTbl}.firstName + ' ' + {$userProfilesTbl}.lastName) as fullName, {$usersTbl}.username, {$usersTbl}.email ";
				$sql .= "FROM {$usersTbl} ";
				$sql .= "LEFT JOIN {$userProfilesTbl} ON {$userProfilesTbl}.user_id = {$usersTbl}.id ";
				$sql .= "WHERE {$usersTbl}.id = '{$this->_fields['modifiedBy']}'";
			}
			$query = $this->db->query($sql);
		} else {
			$vName = $this->db->last_action_log_view; //View Name
			$this->db->select("{$vName}.user_id, {$vName}.action, {$vName}.created, {$vName}.fullName, {$vName}.username, {$vName}.email");
			$this->db->where('model', $this->modelName)->where('recordId', $id);
			$query = $this->db->get("audit_log_last_action");
		}
		foreach ($query->result_array() as $log) {
			$actionLogs[$log['action']] = [
				'on' => date("Y-m-d H:i", strtotime($log['created'])),
				'by' => $log['fullName'],
				'email' => $log['email'],
				'user_id' => $log['user_id']
			];
		}
		if (!isset($actionLogs['insert']))
			$actionLogs['insert'] = $_defaultLog;
		if (!isset($actionLogs['update']))
			$actionLogs['update'] = $_defaultLog;
		return $actionLogs;
	}

	public function sqlsrv08_load_last_action_log($id) {
		return $this->sqlsrv_load_last_action_log($id);
	}

	/**
	 * A member function, test a validation rules on multiple columns.
	 * @access public
	 * @param $check,$combinees
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function combinedUnique($check, $combinees) {
		$_combinees = [];
		if (is_array($combinees)) {
			$combinees = array_intersect($combinees, $this->_fieldsNames);
			foreach ($combinees as $combinee) {
				$_combinees[$combinee] = $this->get_field($combinee);
			}
		} elseif (in_array((string) $combinees, $this->_fieldsNames)) {
			$_combinees[(string) $combinees] = $this->get_field($combinees);
		}
		return $this->isUnique(array_merge($check, $_combinees));
	}

	/**
	 * A member function, test the fields isUnique or not.
	 * @access public
	 * @param $fields
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function isUnique($fields) {
		if (is_array($fields) && count($fields) > 0) {
			$fields = array_intersect_key($fields, $this->_fields);
			foreach ($fields as $key => $value) {
				if ($value === NULL) {
					$this->db->where("{$key} IS NULL", NULL, TRUE);
				} else {
					$this->db->where($key, $value);
				}
			}
			$qb_where = $this->db->g('qb_where');
			if (empty($qb_where)) {
				return true;
			} elseif (!is_null($this->_fields[$this->_pk])) {
				$this->db->where($this->_pk . ' != \'' . $this->_fields[$this->_pk] . '\'');
			}
		}
		return $this->db->count_all_results($this->_table) == 0;
	}

	public function dispatchMethod($method, $params = []) {
		switch (count($params)) {
			case 0: return $this->{$method}();
			case 1: return $this->{$method}($params[0]);
			case 2: return $this->{$method}($params[0], $params[1]);
			case 3: return $this->{$method}($params[0], $params[1], $params[2]);
			case 4: return $this->{$method}($params[0], $params[1], $params[2], $params[3]);
			case 5: return $this->{$method}($params[0], $params[1], $params[2], $params[3], $params[4]);
			default: return call_user_func_array([&$this, $method], $params);
				break;
		}
	}

	/**
	 * A member function, set validation errors.
	 * @access public
	 * @param $validationErrors
	 */
	public function set_validation_errors($validationErrors) {
		$this->validationErrors = $validationErrors;
	}

	/**
	 * A member function, get errors of fields.
	 * @access public
	 * @param $fields,$options
	 * @return string, a formatted string of error
	 */
	public function get_error($fields, $openingTag = '', $closingTag = '') {
		if (!empty($openingTag)) {
			if (is_array($openingTag)) {
				$output = $openingTag['before'] . "%s" . $openingTag['after'];
			} else {
				$output = $openingTag . "%s" . $closingTag;
			}
		} else {
			$output = '<p class="alert alert-danger paddings-less pointer" onclick="jQuery(this).fadeOut(300, \'swing\');">' . "%s" . ' <a href="javascript:;"><i class="fa fa-times-circle text-danger pull-right"></i></a></p>';
		}
		if (is_array($fields)) {
			$fields = array_intersect($fields, $this->_fieldsNames);
			foreach ($fields as $field) {
				if (isset($this->validationErrors[$field])) {
					echo sprintf($output, $this->validationErrors[$field]);
				}
			}
		} elseif (in_array((string) $fields, $this->_fieldsNames) && isset($this->validationErrors[$fields])) {
			return sprintf($output, $this->validationErrors[$fields]);
		}
	}

	/**
	 * A member function, set validation error on a specific field.
	 * @access public
	 * @param $field,$value
	 */
	public function invalidate($field, $value = true) {
		if (!is_array($this->validationErrors)) {
			$this->validationErrors = [];
		}
		$this->validationErrors[$field] = $value;
	}

	/**
	 * A member function, check if the model have an validation errors.
	 * @access public
	 * @see get_error()
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function is_valid() {
		return (count($this->validationErrors) < 1);
	}

	/**
	 * A member function, run validation on fields.
	 * @access public
	 * @see get_error(),is_valid(),invalidate()
	 * @return bool, TRUE on success or FALSE on failure
	 */
	public function validate() {
		//reset validation errors
		$this->validationErrors = [];
		$data = $this->get('_fields');
		if (!class_exists('Validation')) {
			$this->load->library('Validation');
		}
		$validation = Validation::getInstance();
		foreach ($this->validate as $fieldName => $ruleSet) {
			if (!is_array($ruleSet) || (is_array($ruleSet) && isset($ruleSet['rule']))) {
				$ruleSet = [$ruleSet];
			}
			$default = [
				'allowEmpty' => null,
				'required' => null,
				'rule' => 'blank',
				'last' => false,
				'on' => null
			];
			foreach ($ruleSet as $index => $validator) {
				if (!is_array($validator)) {
					$validator = ['rule' => $validator];
				}
				$validator = array_merge($default, $validator);
				if (isset($validator['message'])) {
					$message = $validator['message'];
				} else {
					$message = $this->lang->line('cannot_be_blank_rule');
				}
				if (empty($validator['on']) || ($validator['on'] == 'create' && !$exists) || ($validator['on'] == 'update' && $exists)) {
					$required = (
							(!isset($data[$fieldName]) && $validator['required'] === true) ||
							(isset($data[$fieldName]) && (empty($data[$fieldName]) && !is_numeric($data[$fieldName])) && $validator['allowEmpty'] === false)
							);
					if ($required) {
						$this->invalidate($fieldName, $message);
						if ($validator['last']) {
							break;
						}
					} elseif (array_key_exists($fieldName, $data)) {
						if (empty($data[$fieldName]) && $data[$fieldName] != '0' && $validator['allowEmpty'] === true) {
							break;
						}
						if (is_array($validator['rule'])) {
							$rule = $validator['rule'][0];
							unset($validator['rule'][0]);
							$ruleParams = array_merge([$data[$fieldName]], array_values($validator['rule']));
						} else {
							$rule = $validator['rule'];
							$ruleParams = [$data[$fieldName]];
						}
						$valid = true;
						if (method_exists($this, strtolower($rule))) {
							$ruleParams[] = $validator;
							$ruleParams[0] = [$fieldName => $ruleParams[0]];
							$valid = $this->dispatchMethod($rule, $ruleParams);
						} elseif (method_exists($validation, $rule)) {
							$valid = $validation->dispatchMethod($rule, $ruleParams);
						} else {
							$valid = sprintf($this->lang->line('cannot_find_validation_rule'), $rule, $fieldName);
						}
						if (!$valid || (is_string($valid) && strlen($valid) > 0)) {
							if (is_string($valid) && strlen($valid) > 0) {
								$validator['message'] = $valid;
							} elseif (!isset($validator['message'])) {
								if (is_string($index)) {
									$validator['message'] = $index;
								} elseif (is_numeric($index) && count($ruleSet) > 1) {
									$validator['message'] = $index + 1;
								} else {
									$validator['message'] = $message;
								}
							}
							$this->invalidate($fieldName, $validator['message']);
							if ($validator['last']) {
								break;
							}
						}
					}
				}
			}
		} // end foreach
		return $this->is_valid();
	}

// end validate

	public function sanitize($data) {
		if (is_array($data)) {
			foreach ($data as $k => $v) {
				$data[$k] = $this->sanitize($v);
			}
			return $data;
		}
		$data = trim($data);
		if (get_magic_quotes_gpc()) {
			$data = stripslashes($data);
		}
		$data = mysql_real_escape_string($data);
		return $data;
	}

	public function prep_k_filter(&$filter, &$query, $logic = '') {
		$logics = [
			'' => 'where',
			'and' => 'where',
			'AND' => 'where',
			'or' => 'or_where',
			'OR' => 'or_where'
		];
		if (isset($filter['field'])) {
			extract($filter);
			$query[$logics[$logic]][] = [$field . $this->get_k_operator($operator, $value), $value];
		} elseif (is_array($filter) && isset($filter['filters'][1], $filter['filters'][0])) {
			$filter['filters'] = (array) $filter['filters'];
			$filter0 = (array) $filter['filters'][0];
			$filter1 = (array) $filter['filters'][1];
			$whereString = "({$filter0['field']} {$this->get_k_operator($filter0['operator'], $filter0['value'])} {$this->db->escape($filter0['value'])} ";
			$whereString .= $filter['logic'];
			$whereString .= " {$filter1['field']} {$this->get_k_operator($filter1['operator'], $filter1['value'])} {$this->db->escape($filter1['value'])})";
			$query[$logics[$logic]][] = [$whereString, NULL, FALSE];
		} elseif (isset($filter['filters'][0])) {
			$filter['filters'] = (array) $filter['filters'];
			extract((array) $filter['filters'][0]);
			$query[$logics[$logic]][] = [$field . $this->get_k_operator($operator, $value), $value];
		}
	}

	public function get_k_operator($operator, &$value) {
		$operators = [
			'eq' => ' =',
			'neq' => ' !=',
			'lt' => ' <',
			'lte' => ' <=',
			'gt' => ' >',
			'gte' => ' >=',
			'startswith' => ' LIKE',
			'endswith' => ' LIKE',
			'contains' => ' LIKE'
		];
		switch ($operator) {
			case 'startswith':
				$value = $value . '%';
				break;
			case 'endswith':
				$value = '%' . $value;
				break;
			case 'contains':
				$value = '%' . $value . '%';
				break;
			default :
				return $operators[$operator];
				break;
		}
		return $operators[$operator];
	}

	/**
	 * check if the value is numeric or not NULL.
	 * @access public
	 * @return bool, TRUE on success or FALSE on failure
	 */
	function set_mysql_nulls_fix_dates(&$val, $key) {
		if (empty($val) && !is_numeric($val) && in_array($key, $this->allowedNulls)) {
			$val = NULL;
		} elseif (!empty($val) && in_array($key, $this->_dateFields)) {
			$val = $this->date_to_mysql($val);
		}
	}

	/**
	 * Similar to PHP's implode function.
	 * This function doesn't require a string glue, instead it prepares the string for a select clause.
	 * If one of the array elements is an array, it will imploded as a column having an alias.
	 *
	 * @access public
	 * @param $field
	 * @see get_fields()
	 * @return Value, value field on success or NULL on failure
	 */
	function columns_arra_to_str(&$cols) {
		$str = "";
		foreach ($cols as $col) {
			$str .= (is_array($col) ? implode(' as ', $col) : $col) . ', ';
		}
		return substr($str, 0, -2);
	}

	function columns_arr2arr(&$cols) {
		$arr = [];
		foreach ($cols as $col) {
			$arr[] = (is_array($col) ? [["{$col[0]} AS {$col[1]}"]] : [$col]);
		}
		return $arr;
	}

	protected function remove_indexes_if_search_not_date(&$searchIn, $indexes) {
		$sSearch = trim((string) $this->input->post('sSearch', TRUE));
		if (!$this->can_compare_to_date($sSearch)) {
			for ($i = 0, $j = count($indexes); $i < $j; $i++) {
				unset($searchIn[$indexes[$i]]);
			}
		}
	}

	protected static function can_compare_to_date(&$sSearch) {
		return preg_match('/^[0-9 \-]+$/i', $sSearch);
	}

	public function load_datatables_pagedata($config = []) {
		$conf = array_intersect_key($config, ['query' => [], 'columns' => [], 'search_in' => []]);
		$dt = array_merge([], $conf);
		unset($config, $conf);
		$request = $this->input->get(['columns', 'draw', 'length', 'order', 'search', 'start'], true);
		// Build Query Clauses
		$dt['query'] = ['select' => $this->columns_arr2arr($dt['columns'])] + (isset($dt['query']) ? $dt['query'] : []);
		$userSearch = $this->get_datatables_pagedata_query_search($request['search'], $dt['search_in']);
		$userColSearch = $this->get_datatables_pagedata_query_col_search($request['columns'], $dt['columns']);
		$this->set_datatables_pagedata_query_order($request['order'], $dt['columns'], $dt['query']);
		// Build Response
		$response = [];
		$response['draw'] = (is_numeric($request['draw']) ? $request['draw'] : 0);
		$this->prep_query($dt['query']);
		$response['recordsTotal'] = $this->db->count_all_results($this->_table);
		if (0 < count($userSearch) || 0 < count($userColSearch)) {
			$this->prep_query($dt['query']);
			$this->bind_datatables_pagedata_query_search($userSearch, 'or_like');
			$this->bind_datatables_pagedata_query_search($userColSearch, 'like');
			$response['recordsFiltered'] = $this->db->count_all_results($this->_table);
		} else {
			$response['recordsFiltered'] = $response['recordsTotal'];
		}
		$this->prep_query($dt['query']);
		$this->bind_datatables_pagedata_query_search($userSearch, 'or_like');
		$this->bind_datatables_pagedata_query_search($userColSearch, 'like');
		( isset($request['start']) && $request['length'] != -1 ) AND $this->db->limit($request['length'], $request['start']);
		$response['data'] = $this->get_datatables_query_data($dt['columns'], $this->db->get($this->_table));
		// $response['error'] = '';
		return $response;
	}

	private function get_datatables_pagedata_query_search($search, $search_in) {
		$or_like = [];
		if (0 < count($search_in) && is_array($search) && array_key_exists('value', $search) && '' !== trim($search['value'])) {
			$v = trim($search['value']);
			foreach ($search_in as $fld) {
				$or_like[] = [$fld, $v];
			}
		}
		return $or_like;
	}

	private function get_datatables_pagedata_query_col_search($rCols, $qCols) {
		$where = [];
		if (0 < count($rCols) && is_array($rCols)) {
			foreach ($rCols as $i => $column) {
				if (array_key_exists('search', $column) && '' !== trim($column['search']['value'])) {
					$s = trim($column['search']['value']);
					if (isset($qCols[$i])) {
						if (is_array($qCols[$i])) {
							$c = $qCols[$i][0];
							$where[] = [$c, $s, FALSE];
						} else {
							$c = $qCols[$i];
							$where[] = [$c, $s];
						}
					}
				}
			}
		}
		return $where;
	}

	private function bind_datatables_pagedata_query_search($where, $clause) {
		if (is_array($where) && 0 < count($where)) {
			$this->db->group_start();
			$this->prep_query(["{$clause}" => $where]);
			$this->db->group_end();
		}
	}

	private function set_datatables_pagedata_query_order($order, $columns, &$query) {
		if (is_array($order) && 0 < count($order)) {
			$query['order_by'] = [];
			foreach ($order as $orderBy) {
				$col = is_array($columns[$orderBy['column']]) ? $columns[$orderBy['column']][1] : $columns[$orderBy['column']];
				$query['order_by'][] = [$col, $orderBy['dir']];
			}
		}
	}

	/**
	 *
	 * @param ArrayObject $columns
	 * @param CI_DB_result $query
	 */
	private function get_datatables_query_data($columns, $query) {
		$data = [];
		if ($query && 0 < $query->num_rows()) {
			return $query->result_array();
		}
		return $data;
	}

}
