<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function view($page = 'home') {
		if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
			show_404();
		}
	}

	public function index() {
		$this->load->view('templates/header');
		$this->load->view('pages/home');
		$this->load->view('templates/footer');
	}

	function fetch() {
		$output = '';
		$query = '';
		$this->load->model('Account');
		if ($this->input->post('query')) {
			$query = $this->input->post('query');
		}
		$data = $this->Account->fetch_data($query);
		$output .= '
	 <div class="table-responsive">
		<table class="table table-bordered table-striped">
		 <tr>
		 <th>id</th>
		 <th>name</th>
		 <th>address</th>
		 <th>type</th>
		 <th>phone_a</th>
		 <th>phone_b</th>
		 <th>debit</th>
		 <th>credit</th>
		 <th>balance</th>
		 <th>open balance</th>
		 <th>delete</th>
		 <th>update</th>
		 </tr>
	 ';

		if ($data->num_rows() > 0) {
			foreach ($data->result() as $row) {
				//$ar_ac['A_id']=1 ;
				//$from_to=$this->session->userdata('from_to');
				//  echo $from_to;
				$output .= '
		 <tr>
		  <td>' . $row->A_id . '</td>
		  <td>' . $row->A_name . '</td>
		  <td>' . $row->address . '</td>
		  <td>' . $row->A_type . '</td>
		  <td>' . $row->phone_a . '</td>
		  <td>' . $row->phone_b . '</td>
		  <td>' . $row->debit . '</td>
		  <td>' . $row->credit . '</td>
		  <td>' . $row->balance . '</td>
		  <td>' . $row->open_balance . '</td>
		  <td>
		  <form  method="post" action="' . base_url() . '/account/update/' . $row->A_id . '" >
		  <input type="submit" value="update" class="btn btn-primary">    
		  </form></td>
		  
		   <td>
		   <form  method="post" action="' . base_url() . '/account/delete/' . $row->A_id . '" >
		   <input type="submit" value="delete" class="btn btn-danger">    
		   </form></td>


		  </tr>';
?>
				<?php

			}
		} else {
			$output .= '<tr>
		  <td colspan="5">No Data Found</td>
		 </tr>';
		}
		$output .= '</table>';
		echo $output;
	}

}
