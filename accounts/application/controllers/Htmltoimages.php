<?php

defined('BASEPATH') or die('No direct script access allowed');

class Htmltoimages extends MY_Controller
{

    public function index()
    {
        $this->load->view('templates/header', [
            '_page_title' => "Delivery note",
            '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
        ]);
        $this->load->view('test');
        $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'delivery_notes/index']]);
    }
}
