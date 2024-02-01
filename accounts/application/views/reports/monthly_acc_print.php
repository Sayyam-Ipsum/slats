<div id="print_actions_div">    <p style="text-align: right;">        <button class="btn btn-primary" onclick="window.print()" style="background-color:#404040;" id="print">Print</button>        <a href="reports/monthly_accounts" class="btn btn-primary" style="background-color:#404040;">Back</a>    </p></div><!-- <div class="page-header" style="text-align: center"> --></div><style>    .table_line_height tbody tr td {        line-height: 12px;    }    /* .display_print {        display: none;    } */    @media print {        .table {            border: 1px solid black !important;        }        .table_dark td {            border: 1px solid black !important;        }        .table_dark th {            border: 1px solid black !important;        }        .tr_underline {            border-bottom: 1px solid black !important;        }        .hide-print {            display: none;        }        body {            font-size: small;        }        .pad0 th {            padding: 0 !important;        }        .pad0 td {            padding: 0 !important;        }        /* .display_print {            display: table-row !important;            width: 100%;        } */        .tbhalf {            width: 50% !important;        }        .tbhalf1 {            width: 50% !important;            float: left;        }        .font-small {            font-size: smaller !important;        }        .body-pad {            padding-bottom: 120px !important        }        .color-light-gray {            background-color: lightgray !important;            padding: 5px;            print-color-adjust: exact;        }        .color-dark-gray th {            background-color: #404040 !important;            color: white !important;            print-color-adjust: exact;        }    }</style><div class="page-footer">    <center>        <span>            Slats Autoteile GmbH <span style="color: red">Gewerbestrasse 38, 5314 Kleindöttingen</span> E-Mail: <span style="color: red">info@slats.ch</span> Telefon: <span style="color: red">+41767011111</span>            Bank: <span style="color: red">UBS Switzerland AG</span> Kontoinhaber: <span style="color: red">Slats Autoteile GmbH</span> BIC: <span style="color: red">UBSWCHZH80A</span> IBAN: <span style="color: red">CH97 0023 1231 1553 9301 W</span>            Umsatzsteuer-Identifikationsnummer: <span style="color: red">CHE-431.524.264</span>        </span>    </center></div><table style="width: 100%;">    <thead>        <tr>            <td>                <!--place holder for the fixed-position header-->                <div class="page-header-space"></div>            </td>        </tr>    </thead>    <tbody>        <tr>            <td>                <div class="page">                    <div class="page-section">                        <?php if ($post_case) { ?>                            <div class="row" style="margin-bottom: 10px;">                                <table style="width: 100%;">                                    <tbody>                                        <tr>                                            <td><img src="assets/img/logos/logo-white-2.png" height="90px"></td>                                            <td>                                                <table style="width: 100%;">                                                    <tr>                                                        <td>                                                            <div class="color-light-gray" style="background-color:lightgray; padding:5px;">                                                                <h5><b>Customer Details</b></h5>                                                            </div>                                                        </td>                                                    </tr>                                                    <tr>                                                        <td style="line-height: 8px;">                                                            <p></p>                                                            <p><b><?php echo $customer['account_name'] ?></b></p>                                                            <p><b><?php echo $customer['address'] ?></b></p>                                                            <p><b><?php echo $customer['phone'] ?></b></p>                                                        </td>                                                    </tr>                                                </table>                                            </td>                                            <td style="width: 60px;"></td>                                            <td style="padding:10px;">                                                <table style="width: 100%;">                                                    <tr>                                                        <td>                                                            <div class="color-light-gray" style="background-color:lightgray; padding:5px;">                                                                <h5><b>General Details</b></h5>                                                            </div>                                                        </td>                                                    </tr>                                                    <tr>                                                        <td style="line-height: 8px;">                                                            <p></p>                                                            <p><b><?php echo date('d-m-Y') ?></b></p>                                                            <p><b><?php echo $customer['address'] ?></b></p>                                                            <p><b><?php echo $customer['phone'] ?></b></p>                                                        </td>                                                    </tr>                                                </table>                                            </td>                                            <td style="width: 20px;"></td>                                        </tr>                                    </tbody>                                </table>                                </hr>                            </div>                            <div class="col-sm-12" hidden>                                <table class="table table-bordered table-striped table-hover table-condensed table-responsive table_dark" style="width: 100%;">                                    <thead>                                        <tr class="color-dark-gray" style="background-color: #404040; color:white;">                                            <th><?php echo $this->lang->line('customer'); ?></th>                                            <th><?php echo $this->lang->line('from_date'); ?></th>                                            <th><?php echo $this->lang->line('to_date'); ?></th>                                            <th><?php echo $this->lang->line('unpaid_invoices_count'); ?></th>                                            <th><?php echo $this->lang->line('unpaid_invoices_total'); ?></th>                                            <th><?php echo $this->lang->line('old_unpaid_invoices_total'); ?></th>                                            <th><?php echo $this->lang->line('unpaid_invoices_total') . " (VAT)"; ?></th>                                        </tr>                                    </thead>                                    <tbody>                                        <tr>                                            <td><?php echo $customer_name ?></td>                                            <td><?php echo date("d-m-Y", strtotime($first_date)) ?></td>                                            <td><?php echo date("d-m-Y", strtotime($last_date)) ?></td>                                            <td><?php echo $unpaid_invoices_count . " (+ Old Invoice: " . count($old_invoices) . ")" ?></td>                                            <td><?php echo $unpaid_invoices_total_no_tva ?></td>                                            <td><?php echo $old_invoices_tot ?></td>                                            <td><?php echo $unpaid_invoices_total ?></td>                                        </tr>                                    </tbody>                                </table>                            </div>                            <div class="col-sm-12">                                <table class="table table-striped table-hover" style="border: 0px solid #ecf0f1 !important;">                                    <thead>                                        <tr class="tr_underline color-dark-gray" style="background-color: #404040; color:white;">                                            <th scope="col">#</th>                                            <th scope="col">Invoice</th>                                            <th scope="col">Items</th>                                            <th scope="col">Qty</th>                                            <th scope="col">Delivery Charge</th>                                            <th scope="col">Pfand</th>                                            <th scope="col" class="text-right">Sub Total</th>                                        </tr>                                    </thead>                                    <tbody>                                        <?php if ($post_case) {                                            $count = 0;                                            foreach ($invoices as $k => $invoce) {                                                $count++; ?>                                                <tr class="tr_underline">                                                    <th scope="row"><?php echo $count ?></th>                                                    <td>                                                        <table>                                                            <tr>                                                                <td><b><?php echo '#' . $invoce['auto_no'] ?></b></td>                                                            </tr>                                                            <tr>                                                                <td><b><?php echo $invoce['trans_date'] ?></b></td>                                                            </tr>                                                            <tr>                                                                <td><b><?php echo $invoce['VIN'] ?></b></td>                                                            </tr>                                                            <tr>                                                                <td><b><?php echo $invoce['model'] ?></b></td>                                                            </tr>                                                        </table>                                                    </td>                                                    <td>                                                        <table>                                                            <?php foreach ($trans_items[$k] as $record) { ?>                                                                <tr>                                                                    <td><?php echo $record['description'] ?></td>                                                                </tr>                                                            <?php } ?>                                                        </table>                                                    </td>                                                    <td>                                                        <table>                                                            <?php foreach ($trans_items[$k] as $record) { ?>                                                                <tr>                                                                    <td><?php echo $record['qty'] ?></td>                                                                </tr>                                                            <?php } ?>                                                        </table>                                                    </td>                                                    <td><?php echo $invoce['delivery_charge'] ?></td>                                                    <td><?php echo $invoce['pfand']  ?></td>                                                    <td class="text-right"><?php echo $invoce['subtot'] . ' ' . $invoce['currency_code'] ?></td>                                                </tr>                                            <?php } ?>                                            <?php foreach ($old_invoices as $k => $invoce) {                                                $count++; ?>                                                <tr class="tr_underline display_print">                                                    <th scope="row"><?php echo $count ?><span style="color:red"> (Overdue)</span></th>                                                    <td>                                                        <table>                                                            <tr>                                                                <td><b><?php echo '#' . $invoce['auto_no'] ?></b></td>                                                            </tr>                                                            <tr>                                                                <td><b><?php echo $invoce['trans_date'] ?></b></td>                                                            </tr>                                                            <tr>                                                                <td><b><?php echo $invoce['VIN'] ?></b></td>                                                            </tr>                                                            <tr>                                                                <td><b><?php echo $invoce['model'] ?></b></td>                                                            </tr>                                                        </table>                                                    </td>                                                    <td>                                                        <table>                                                            <?php foreach ($trans_items_old[$k] as $record) { ?>                                                                <tr>                                                                    <td><?php echo $record['description'] ?></td>                                                                </tr>                                                            <?php } ?>                                                        </table>                                                    </td>                                                    <td>                                                        <table>                                                            <?php foreach ($trans_items_old[$k] as $record) { ?>                                                                <tr>                                                                    <td><?php echo $record['qty'] ?></td>                                                                </tr>                                                            <?php } ?>                                                        </table>                                                    </td>                                                    <td><?php echo $invoce['delivery_charge'] ?></td>                                                    <td><?php echo $invoce['pfand']  ?></td>                                                    <td class="text-right"><?php echo $invoce['subtot'] . ' ' . $invoce['currency_code'] ?></td>                                                </tr>                                            <?php } ?>                                        <?php } ?>                                        <tr class="pad0">                                            <th colspan="2">Net SubTotal</th>                                            <td colspan="3"></td>                                            <th colspan="2" class="text-right"><?php echo $subtot . " CHF" ?></th>                                        </tr>                                        <tr class="pad0 tr_underline">                                            <th><i class="glyphicon glyphicon-plus"></i></th>                                            <th>Old Invoices</th>                                            <td></td>                                            <td></td>                                            <td><?php echo $old_delivery ?></td>                                            <td><?php echo $old_pfand ?></td>                                            <th class="text-right"><?php echo '+' . $old_subtot . " CHF" ?></th>                                        </tr>                                        <tr class="pad0">                                            <th colspan="2">Net Total</th>                                            <td colspan="3"></td>                                            <th colspan="2" class="text-right"><?php echo ($subtot + $old_subtot) . " CHF" ?></th>                                        </tr>                                    </tbody>                                </table>                                <div class="row">                                    <div class="col-sm-6 tbhalf1 font-small" style="font-size: small;">                                        <span>                                            Within ten days of provision. <br>                                            Electrical parts, parts with an expiry date, damaged or dirty packages, coded or painted parts, parts specially ordered for you, and parts under CHF 50.00 net cannot be returned.                                        </span>                                        <br>                                        <span>                                            Innerhalb von Zehn Tagen nach Bereitstellung. <br>                                            Elektrikteile, Teile mit Haltbarkeitsdatum, Beschädigtes oder schmutziges Paket .codierte bzw. lackierte Teile, extra für Sie bestellte Teile, sowie Teile unter 50,00 CHF Netto sind von einer Rücknahme ausgeschlossen.                                        </span>                                    </div>                                    <div class="col-sm-6">                                        <!-- <div style="border: 1px solid black !important; padding:10px;"> -->                                        <table class="tbhalf" style="width: 100%;">                                            <tbody>                                                <tr style="border: 1px solid black !important;"></tr>                                                <tr>                                                    <th colspan="2"><?php echo 'VAT 7.7%' ?></th>                                                    <td colspan="3"></td>                                                    <th colspan="2" class="text-right"><?php echo $TVA_amount ?></th>                                                </tr>                                                <tr class="">                                                    <th colspan="2">Delivery Charge Total</th>                                                    <td colspan="3"></td>                                                    <th colspan="2" class="text-right"><?php echo $total_delivery_charge . " CHF" ?></th>                                                </tr>                                                <tr class="">                                                    <th colspan="2">Pfand Total</th>                                                    <td colspan="3"></td>                                                    <th colspan="2" class="text-right"><?php echo $total_pfand . " CHF" ?></th>                                                </tr>                                                <tr class="">                                                    <th colspan="2">Total (VAT)</th>                                                    <td colspan="3"></td>                                                    <th colspan="2" class="text-right"><?php echo $unpaid_invoices_total ?></th>                                                </tr>                                                <?php foreach ($receipts as $r) { ?>                                                    <tr class="">                                                        <th><i class="glyphicon glyphicon-minus"></i></th>                                                        <td><b><?php echo date('d-m-Y', strtotime($r['journal_date'])) ?></b></td>                                                        <td></td>                                                        <td></td>                                                        <td></td>                                                        <td></td>                                                        <th class="text-right"><?php echo "-" . number_format($r['amount'], 2) . ' ' . $invoices[0]['currency_code'] ?></th>                                                    </tr>                                                <?php } ?>                                                <tr>                                                    <th colspan="2">Net Total (VAT)</th>                                                    <td colspan="3"></td>                                                    <th colspan="2" class="text-right"><?php echo $net_tot_vat ?></th>                                                </tr>                                                <tr style="border: 1px solid black !important;"></tr>                                            </tbody>                                        </table>                                        <!-- </div> -->                                    </div>                                </div>                            <?php } ?>                            </div>                    </div>            </td>        </tr>    </tbody>    <tfoot>        <tr>            <td>                <!--place holder for the fixed-position footer-->                <div class="page-footer-space"></div>            </td>        </tr>    </tfoot></table><script src="assets/js/jquery213.min.js"></script><script>    jQuery(document).ready(function($) {        $('#total_text').on('change', function(e) {            $('#total_words').text($('#total_text').val())        })    });</script>