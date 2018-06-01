<?php
use App\Http\Controllers\ProductsAndServicesController;
$customer_id = "";
$status_all = "";
$due_date = "";
$invoice_num = "";
$invoice_date = "";
$notes = "";
$title = "";
$subtitle = "";
$po = "";

foreach ($invoices as $invoice) {
    $customer_id = $invoice->customer_id;
    $status_all = $invoice->status;
    $due_date = $invoice->payment_due;
    $invoice_date = $invoice->invoice_date;
    $invoice_num = $invoice->invoice_num;
    $notes = $invoice->notes;
    $title = $invoice->title;
    $subtitle = $invoice->subtitle;
    $po = $invoice->po_os;
}
?>
        <!DOCTYPE html>
<html lang="en">
<head></head>
<body>
<div id="preview"
     style="width: 100%;border: solid 1px lightblue; box-shadow: 5px 10px 8px #888888; margin-top: 2%;margin-bottom: 2%">
    <div style="border-bottom: solid 1px #cccccc;width: 100%">
        <table style="width: 100%">
            <tr style="width: 100%">
                <td width="60%">
                    <img src="{{asset('img/1.png')}}" id="invoice_logo" class="thumbnail"
                         style="margin-top: 5%;padding-bottom: 2%" width="144px" height="144px" id="preview"/>
                </td>
                <td width="40%">
                    <table style="width: 100%;font-size: 11px">
                        <tr style="width: 100%">
                            <td style="width: 100%;text-align: right">
                                <span style="width: 100%;text-align: right;font-size: ">{{$title}}</span>
                            </td>
                        </tr>
                        @if(!empty($subtitle))
                            <tr style="width: 100%">
                                <td style="width: 100%;text-align: right">
                                    <span style="width: 100%;text-align: right">{{$subtitle}}</span>
                                </td>
                            </tr>
                        @endif
                        <tr style="width: 100%">
                            <td style="width: 100%;text-align: right">
                                <span style="width: 100%;text-align: right">yaptrue</span>
                            </td>
                        </tr>
                        <tr style="width: 100%">
                            <td style="width: 100%;text-align: right">
                                <span style="width: 100%;text-align: right"><small id="location_prev">United Republic of, Tanzania</small></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div style="width: 100%">
        <table style="width: 100%">
            <tr style="width: 100%">
                <td width="60%">
                    <table style="width: 100%;font-size: 11px">
                        <tr style="width: 100%">
                            <td style="width: 100%">
                                <div style="margin-top: 0%;font-size: 11px;font-weight: bold">BILL TO</div>
                            </td>
                        </tr>
                        <tr style="width: 100%">
                            <td style="width: 100%">
                                <div style="font-size: 11px; font-weight: bold" id="name_in_prev">
                                    <?php
                                    $name = ProductsAndServicesController::customerName($customer_id);
                                    echo $name;
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr style="width: 100%">
                            <td style="width: 100%">
                                <div style="font-size: 11px" id="full_name">
                                    <?php
                                    $fname = ProductsAndServicesController::customerFullName($customer_id);
                                    echo $fname;
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr style="width: 100%">
                            <td style="width: 100%">
                                <div style="margin-top: 5%;font-size: 11px" id="email_in_prev">
                                    <?php
                                    $email = ProductsAndServicesController::customerEmail($customer_id);
                                    echo $email;
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%">
                    <table style="width: 100%;font-size: 11px;">
                        <tr style="width: 100%">
                            <td style="width: 50%">
                                <div style="width: 100%;text-align: right">Invoice Number:</div>
                            </td>
                            <td style="width: 50%"><div id="invoice_num" style=";text-align:right">{{$invoice_num}}</div></td>
                        </tr>
                        <tr style="width: 100%">
                            <td style="width: 50%">
                                <div style="width: 100%;text-align: right">Invoice Date:</div>
                            </td>
                            <td style="width: 50%"><div style=";text-align:right">{{$invoice_date}}</div></td>
                        </tr>
                        <tr style="width: 100%">
                            <td style="width: 50%">
                                <div style="width: 100%;text-align: right">Payment Due:</div>
                            </td>
                            <td style="width: 50%"><div style=";text-align:right">{{$due_date}}</div></td>
                        </tr>
                        <tr style="width: 100%">
                            <td style="width: 50%">
                                <div style="width: 100%;text-align: right">Amount Due (TZS):</div>
                            </td>
                            <td style="width: 50%">
                                <div id="amount_due" style="width: 100%;text-align: right">
                                    <?php
                                    $sum = ProductsAndServicesController::DueAmount($invoice_num);
                                    echo "<span>Tsh. </span>" . $sum;
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 3%;padding: 0px;width: 100%">
        <div style="padding: 0px;width: 100%;">
            <table class="table borderless" style="font-size: 11px" width="100%">
                <thead style="color: white;background-color: black" width="100%">
                <tr width="100%">
                    <th scope="col" style="font-size: 12px;">Products</th>
                    <th scope="col" style="font-size: 12px;">Description</th>
                    <th scope="col" style="font-size: 12px;">Quantity</th>
                    <th scope="col" style="font-size: 12px;">Price</th>
                    <th scope="col" style="font-size: 12px;">Amount</th>
                </tr>
                </thead>
                <tbody width="100%">
                <?php
                $tax_total = 0;
                ?>
                @foreach($invoice_items as $item)
                    <tr>
                        <td>{{$item->item_name}}</td>
                        <td>{{$item->item_description}}</td>
                        <td>{{$item->item_quantity}}</td>
                        <td>{{$item->item_price}}</td>
                        <td style="text-align: right">{{ $item->item_price* $item->item_quantity}}</td>
                    </tr>

                    @if(!empty($item->tax))
                        <?php
                        $array_taxes = explode(",", $item->tax);
                        $product_tax_av = ProductsAndServicesController::ProductTaxList($item->item_name);
                        if (strpos($product_tax_av, ',') !== false) {
                            $taxes_arr = explode(",", $product_tax_av);
                            for ($i = 0; $i < count($array_taxes); $i++) {
                                $str = substr($array_taxes[$i], 0, stripos($array_taxes[$i], "_"));
                                $str_ = substr($array_taxes[$i], 0, stripos($array_taxes[$i], "("));

                                $str_1 = substr($array_taxes[$i], stripos($array_taxes[$i], "_") + 1);
                                if (strpos($product_tax_av, $str_) !== false && strcmp($item->item_name, $str_1) == 0) {
                                    echo '
                                              <tr>
                                                 <td colspan="3">&nbsp;</td>
                                                 <td colspan="1" style="text-align: left">' . $str . '</td>
                                                 <td style="text-align: right">';

                                    $percent = ProductsAndServicesController::GetBetween("(", ")", $array_taxes[$i]);
                                    $tax = ($item->item_price * ($percent / 100));
                                    $tax_total = $tax_total + $tax;
                                    echo $tax;
                                    echo '</td>
                                             </tr>
                                             ';
                                }
                            }
                        } else {
                            for ($i = 0; $i < count($array_taxes); $i++) {
                                $str = substr($array_taxes[$i], 0, stripos($array_taxes[$i], "_"));
                                $str_ = substr($array_taxes[$i], 0, stripos($array_taxes[$i], "("));

                                $str_1 = substr($array_taxes[$i], stripos($array_taxes[$i], "_") + 1);
                                if (strpos($product_tax_av, $str_) !== false && strcmp($item->item_name, $str_1) == 0) {
                                    echo '
                                              <tr>
                                                 <td colspan="3">&nbsp;</td>
                                                 <td colspan="1">' . $str . '</td>
                                                  <td style="text-align: right">';
                                    $percent = ProductsAndServicesController::GetBetween("(", ")", $str);
                                    $tax = $item->item_price * ($percent / 100);
                                    $tax_total = $tax_total + $tax;
                                    echo $tax;
                                    echo '</td>
                                             </tr>
                                             ';
                                }
                            }

                        }

                        ?>

                    @endif
                @endforeach
                </tbody>
                <tfoot>
                @if($tax_total != 0)
                    <tr style="background-color: transparent;visibility: visible;border-top: solid 1px #cccccc">
                        <td colspan="3">&nbsp;</td>
                        <td colspan="1" style="text-align: left;font-size: 11px">Sub Total : Tsh.</td>
                        <td style="text-align: right">
                            <?php
                            $sum = ProductsAndServicesController::DueAmount($invoice_num);
                            echo $sum;
                            ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr style="background-color: transparent;visibility: visible;">
                        <td colspan="3">&nbsp;</td>
                        <td colspan="1" style="text-align: left;font-size: 11px">Tax Total : Tsh.</td>
                        <td style="text-align: right;" id="subtotal_due">
                            <?php
                            echo $tax_total;
                            ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>

                @endif
                <tr style="background-color: #cccccc;visibility: visible;font-weight: bold">
                    <td colspan="3">&nbsp;</td>
                    <td colspan="1" style="text-align: left;font-size: 11px">Total : Tsh.</td>
                    <td style="text-align: right">
                        <?php
                        $sum = ProductsAndServicesController::DueAmount($invoice_num);
                        echo ($tax_total + $sum);
                        ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>

                <tr style="background-color: transparent;visibility: visible">
                    <td colspan="3">&nbsp;</td>
                    <td colspan="1" style="text-align: left;font-size: 11px;font-weight: bold"><input type="hidden" value="{{$tax_total + $sum}}" id="due_total">Amount Due : Tsh.</td>
                    <td style="text-align: right">
                        <?php
                        $sum = ProductsAndServicesController::DueAmount($invoice_num);
                        echo ($tax_total + $sum);
                        ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div style="padding: 2%;width: 100%;" id="notes_prev_pare">
            @if(!empty($notes))
                <div style="font-size: 11px;font-weight: bold">Notes:</div>
                <div id="notes_prev">
                    <small>{{$notes}}</small>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>

