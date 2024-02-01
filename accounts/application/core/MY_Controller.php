<?php

defined('BASEPATH') or exit('No direct script access allowed');

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
 * 
 * @property Violet_auth $violet_auth
 */
class MY_Controller extends CI_Controller {

	protected function _render_json($response) {
		$this->output->set_content_type('application/json')
				->set_output(
						json_encode($response)
		);
	}

}
