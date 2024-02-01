<?php
defined('BASEPATH') or die('No direct script access allowed');
/**
 * @property Account $Account
 * @property Item $Item
 */
class Suppliers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Item');
    }

    public function autopartner_search()
    {
        $data = [];
        $data['item'] = '';
        $data['artical_number'] = '';
        $data['rows'] = array();
        $post = $this->input->post(null, true);
        if ($post) {
            $artical_nb = $post['items'];
            if ($artical_nb) {
                $url = "https://customerapi.autopartner.dev/CustomerAPI.svc/rest/ProductAvailability";

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $headers = array(
                    "Content-Type: application/json",
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                $data = <<<DATA
                        {
                            "clientCode": "3125005", 
                            "wsPassword": "hg6%^hbnjku5FG():j", 
                            "clientPassword": "cbc462e27100dad71cdbf606d396ddad", 
                            "productCode": "$artical_nb", 
                            "ammount": "1", 
                            "currencyCode": "EUR" 
                        }
                        DATA;

                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                //for debug only!
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                $resp = curl_exec($curl);
                curl_close($curl);
                $data = [];
                $data['item'] = $post['items'];
                $data['rows'] = array();
                if ($resp) {
                    $result = json_decode($resp, true);
                    if (array_key_exists("RestProductAvailabilityResult", $result)) {
                        if (array_key_exists("SupplierStatus", $result['RestProductAvailabilityResult'])) {
                            $data['rows'] = $result['RestProductAvailabilityResult']['SupplierStatus'];
                        }
                    }
                }
            }
        }
        $data['title'] = "Amigo Search Product";
        $this->load->view('templates/header', [
            '_moreCss' => [],
            '_page_title' => $data['title']
        ]);
        $this->load->view('suppliers/autopartner_search', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'jquery.autocomplete.min', 'suppliers/autopartner_search'
            ]
        ]);
    }
}
