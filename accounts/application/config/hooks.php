<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'] = [
	[
		'class' => 'Violet_hook',
		'function' => 'boot_up',
		'filename' => 'Violet_hook.php',
		'filepath' => 'hooks'
	]
];
