<div class="card mb-3">    <div class="bg-holder d-none d-lg-block bg-card"         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">    </div>    <!--/.bg-holder-->    <div class="card-body position-relative">        <div class="row">            <div class="col-lg-12">                <h3>Preview</h3>                <p class="mb-0">                </p>            </div>        </div>    </div></div><div class="row g-0">    <div class="col-lg-12 pe-lg-2">        <input name="id" id="id" type="hidden" value="<?php echo $trans['id'] ?>"/>        <div class="card mb-3">            <div class="card-body">                <div class="row justify-content-between align-items-center">                    <div class="col-md">                        <h5 class="mb-2 mb-md-0"><?php echo $title ?> # <?php echo $trans["auto_no"] ?></h5>                    </div>                    <div class="col-auto">                        <button id="btn_convert" class="btn btn-falcon-default btn-sm me-1 mb-2 mb-sm-0" type="button">                            <span class="fas fa-arrow-down me-1"> </span>Download (.pdf)                        </button>                        <button id="print" class="btn btn-falcon-default btn-sm me-1 mb-2 mb-sm-0" type="button"><span                                    class="fas fa-print me-1"> </span>Print                        </button>                        <button id="bgback" class="btn btn-falcon-success btn-sm mb-2 mb-sm-0" type="button"><span                                    class="fas fa-arrow-left me-1"></span>Back                        </button>                    </div>                </div>            </div>        </div>        <div id="myHtml" class="card mb-3">            <div class="card-body">                <div class="row align-items-center text-center mb-3">                    <div class="col-sm-6 text-sm-start"><img src="<?php echo site_url('assets/img/logos/logo-white-2.png') ?>"                                                             alt="invoice" width="150"/></div>                    <div class="col text-sm-end mt-3 mt-sm-0">                        <h2 class="mb-3"><?php echo $title ?></h2>                        <h5><?php echo $company_name ?></h5>                        <p class="fs--1 mb-0"><?php echo $company_phone ?><br/><?php echo $company_address ?>                            <br/><?php echo $company_email ?><br/><?php echo $company_website ?></p>                    </div>                    <div class="col-12">                        <hr/>                    </div>                </div>                <div class="row align-items-center">                    <div class="col">                        <h6 class="text-500"><?php echo $title ?> Info</h6>                        <h5> <?php echo $customer_info["account_name"] ?></h5>                        <p class="fs--1"><?php echo $customer_info["account_number"] ?>                            <br/><?php echo $customer_info["address"] ?></p>                        <p class="fs--1"><a                                    href="mailto:<?php echo $customer_info["email"] ?>"><?php echo $customer_info["email"] ?></a><br/><a                                    href="tel:<?php echo $customer_info["phone"] ?>"><?php echo $customer_info["phone"] ?></a>                        </p>                    </div>                    <div class="col-sm-auto ms-auto">                        <div class="table-responsive">                            <table class="table table-sm table-borderless fs--1">                                <tbody>                                <tr>                                    <th class="text-sm-end"><?php echo $title ?> No:</th>                                    <td> <?php echo $trans["auto_no"] ?></td>                                </tr>                                <tr>                                    <th class="text-sm-end">VIN Number:</th>                                    <td><?php echo $trans["VIN"] ?></td>                                </tr>                                <tr>                                    <th class="text-sm-end"><?php echo $title ?> Date:</th>                                    <td> <?php echo $trans["trans_date"] ?></td>                                </tr>                                <tr>                                    <th class="text-sm-end">Model:</th>                                    <td><?php echo $trans["model"] ?></td>                                </tr>                                </tbody>                            </table>                        </div>                    </div>                </div>                <div class="table-responsive scrollbar mt-4 fs--1">                    <table class="table table-striped border-bottom">                        <thead data-bs-theme="light">                        <tr class="bg-primary text-white dark__bg-1000">                            <th class="border-0">Pos.</th>                            <th class="border-0 text-center">Beschreibung</th>                            <th class="border-0 text-end">Marke</th>                            <th class="border-0 text-end">Barcode</th>                            <th class="border-0 text-end">Menge</th>                            <th class="border-0 text-end">Eienzelpreis</th>                            <th class="border-0 text-end">Total</th>                            <th class="border-0 text-end">Rabatt</th>                            <th class="border-0 text-end"><?php echo "Preis in " . $currency ?></th>                        </tr>                        </thead>                        <tbody>                        <?php $count = 1;                        foreach ($trans_items as $x => $transItem) : ?>                            <tr>                                <td class="align-middle">                                    <h6 class="mb-0 text-nowrap"><?php echo $count ?></h6>                                    <p class="mb-0">***</p>                                </td>                                <td class="align-middle text-center"><?php echo $transItem['description'] ?></td>                                <td class="align-middle text-end"><?php echo $transItem['brand'] ?></td>                                <td class="align-middle text-end"><?php echo $transItem['barcode'] ?></td>                                <td class="align-middle text-end"><?php echo number_format($transItem['qty'], 2, '.', '') ?></td>                                <td class="align-middle text-end"><?php echo number_format($transItem['price'], 2, '.', '') ?></td>                                <td class="align-middle text-end"><?php echo number_format($transItem['sub_total'], 2, '.', '') ?></td>                                <td class="align-middle text-end"><?php echo $transItem['discount'] . "%" ?></td>                                <td class="align-middle text-end"><?php echo number_format($transItem['total'], 2, '.', '')  ?></td>                            </tr>                            <?php $count++;                        endforeach ?>                        </tbody>                    </table>                </div>                <div class="row justify-content-end">                    <div class="col-auto">                        <table class="table table-sm table-borderless fs--1 text-end">                            <tr>                                <th class="text-900">Subtotal:</th>                                <td class="fw-semi-bold"><?php echo $sub_total ?></td>                            </tr>                            <tr <?php echo ($trans["delivery_charge"] == '0') ? 'hidden' : ''; ?>>                                <th class="text-900">Lieferung:</th>                                <td class="fw-semi-bold"><?php echo number_format($trans["delivery_charge"], 2, '.', '')  ?></td>                            </tr>                            <tr class="border-top">                                <th class="text-900">Zzgl. MWST <?php echo $trans["TVA"] . "% " ?> :</th>                                <td class="fw-semi-bold"><?php echo $tva_amount ?></td>                            </tr>                            <tr <?php echo ($trans["pfand"] == '0') ? 'hidden' : ''; ?> class="border-top">                                <th class="text-900">Pfand :</th>                                <td class="fw-semi-bold"><?php echo number_format($trans["pfand"], 2, '.', '') ?></td>                            </tr>                            <tr hidden class="border-top">                                <th class="text-900">Rabatt :</th>                                <td class="fw-semi-bold"><?php echo number_format($trans["discount"], 2, '.', '') ?></td>                            </tr>                            <tr class="border-top border-top-2 fw-bolder text-900">                                <th>Betrag inkl. MWST:</th>                                <td><?php echo $total . " " . $currency ?></td>                            </tr>                        </table>                    </div>                </div>            </div>            <div class="card-footer bg-light">                <?php if ($trans["pfand"] !== "0") {  ?>                    <p class="fs--1 mb-0"><strong>Notes: </strong><?php echo $pfand_note ?></p>                <?php }  ?>            </div>        </div>    </div></div>