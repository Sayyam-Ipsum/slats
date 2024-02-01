<?php

class Authentication extends MY_Model {

	protected $modelName = 'Authentication';
	protected $_table = 'authentications';
	protected $_listFieldName = 'id';
	
	protected $_fieldsNames = [ 'user_name_login', 'user_password_login'];


	public function __construct()
	{
		parent::__construct();
		$this->validate = [

				'user_name_login' =>
				 [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => ['maxLength', 255],
				'message' => sprintf($this->lang->line('required__max_length_rule'), 'user_name_login', 255)
			],
			'user_password_login' => [
				'required' => FALSE,
				'allowEmpty' => TRUE,
				'rule' => ['maxLength', 255],
				'message' => sprintf($this->lang->line('max_length_rule'), 'user_password_login', 255)
			]
			
					
		];
	}


    public function authenticate()
    {
        $post = $this->input->post(null, true);
        var_dump($post['user_name_login']);


        $this->db->where('user_name',$post['user_name_login']);
        $this->db->where('user_password',$post['user_password_login']);
        $query = $this->db->get('users');
    
        if($query->num_rows()>0)
        {
           
            foreach($query->result() as $row)
            {
                //add all data to session
                $userdata = array(
                    'id'        => $row->id,
                    'username'  => $row->user_name,
                    'email'     => $row->user_email,
                    'password'  => $row->user_password,
                    'fiscal_year'  => $row->user_fiscal_year,
                    'logged_in' => TRUE,
                );
            }
 
            $this->session->set_userdata($userdata);
            return true;
        }
        return false;
    }





}
