<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Render extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->library('Ciqrcode');
    $this->load->library('Zend');

    $this->load->database();
  }

  public function index($id)
  {
    $data['title'] = " Barcode PHP CodeIgniter 3";
    $this->load->view('render', $data); 
  }

  public function QRcode($kodenya)
  {
    //render  qr code dengan format gambar PNG
    QRcode::png(
      $kodenya,
      $outfile = false,
      $level = QR_ECLEVEL_H,
      $size  = 6,
      $margin = 2
    );
    
  }

  public function Barcode($id)
  {

    $this->load->model('Item');  
    $barcode_number = $this->Item->get_barcode_by_id($id);
    $this->zend->load('Zend/Barcode');

    $barcodeOptions = array(
        'text' => $barcode_number['barcode'],
        'barHeight'=> 8, 
        'factor'=>2,
    );


    $file = Zend_Barcode::draw('code128', 'image',$barcodeOptions, array());
    $code = time().$barcode_number['barcode'];
    $store_image = imagepng($file,"assets/barcode/{$barcode_number['barcode']}.png");
    $data['image']=$barcode_number['barcode'].'.png';
    $this->load->view('render', $data); 
    
  }

}

/* End of file Render.php */
/* Location: ./application/controllers/Render.php */

