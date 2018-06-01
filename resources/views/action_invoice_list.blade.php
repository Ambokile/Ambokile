<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<?php
$customer_id;
$status_all;
$due_date;
$invoice_num;
$invoice_date;
$notes;
$title;
$subtitle;
$po;

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

<div class="row">
    <div class="col-sm-10 offset-1">

        <div class="row" style=" border-bottom: solid 1px #C0C0C0">
            <div class="col-sm-8">
                <h2>Invoice</h2>
            </div>
            <div class="col-sm-4">
                <div class="row btn-group" style="float: right;">
                    <button class="btn btn-default col-sm-6 dropdown-toggle dropdown-toggle-split"
                            style="border: solid 1px darkseagreen; background-color: white" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">More Action
                        <span class="sr-only">Toggle Dropdown</span></button>
                    <div class="dropdown-menu" style="color: blue">
                        <a class="dropdown-item" href="#"></a>
                        <a class="dropdown-item" style="color:#303030;font-size: 14px" onclick="LoadContent('edit_invoice/{{$invoice_num}}/{{$customer_id}}')" disabled="disabled">Edit Invoice</a>
                        @if($status_all == 0)
                            <a class="dropdown-item" href="#" style="color:#303030;font-size: 14px" disabled="disabled">Add payment</a>
                        @elseif($status_all == 1)
                            <a class="dropdown-item" href="#" style="color:#303030;font-size: 14px" onclick="LoadContent('addPayment/{{$invoice_num}}/action_invoice_list')">Add payment</a>
                        @elseif($status_all == 2)
                            <a class="dropdown-item" href="#" style="color:#303030;font-size: 14px" onclick="LoadContent('addPayment/{{$invoice_num}}')">Add payment</a>
                        @else
                            <a class="dropdown-item" href="#" style="color:#303030;font-size: 14px" disabled="disabled">Add payment</a>
                        @endif
                        <a class="dropdown-item" href="#" style="color:#303030;font-size: 14px" onclick="openInNewTab('pdfview/{{$invoice_num}}');">Print Invoice</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" style="color:#303030;font-size: 14px" onclick="DeleteInvoice({{$invoice_num}})">Delete</a>
                    </div>

                    <button class="btn btn-success col-sm-6" onclick="LoadContent('invoices')">New Invoice</button>
                </div>
            </div>
        </div>

        <div class="row" style="width: 87%; margin: 0 auto;margin-top: 5%;">
            <div class="col-sm-6">
                <table class="table borderless" style="width: 60%;font-weight: bold;font-size: 14px">
                    <tr>
                        <th>STATUS</th>
                        <th>CUSTOMER</th>
                    </tr>
                    <tr>

                        <td>
                            <?php
                            $now = time(); // or your date as well
                            $your_date = strtotime($due_date);
                            $datediff = $now - $your_date;
                            $days = floor($datediff / (60 * 60 * 24));
                            $status = "";
                            if ($days < 0) {
                                if ($status_all == 0)
                                    $status = "DRAFT";
                                else if ($status_all == 1 || $status_all == 4)
                                    $status = "UNSENT";
                                elseif ($status_all == 2 || $status_all == 5)
                                    $status = "SENT";
                            } else {
                                if ($status_all == 4 || $status_all == 5)
                                    $status = "PAID";
                                else $status = "OVERDUE";
                            }

                            ?>
                            @if($status == "OVERDUE")
                                <button type="button" class="btn btn-danger btn-sm" style="width: 90%;font-size: 12px"
                                        disabled="disabled">{{$status}}</button>
                            @elseif($status == "SENT" || $status == "UNSENT")
                                <button type="button" disabled="disabled" class="btn btn-success btn-sm"
                                        style="width: 90%;font-size: 12px">{{$status}}</button>
                            @else
                                <button type="button" class="btn btn-info btn-sm" style="width: 90%;font-size: 12px"
                                        disabled="disabled">{{$status}}</button>
                             @endif
                        <td>
                            <?php
                            $name = ProductsAndServicesController::customerName($customer_id);
                            echo $name;
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <table class="table borderless" style="width: 50%;float: right;font-weight: bold;font-size: 14px;">
                    <tr>
                        <th>AMOUNT DUE</th>
                        <th>
                            <?php
                            $now = time();
                            $your_date = strtotime($due_date);
                            $datediff = $now - $your_date;
                            $days = floor($datediff / (60 * 60 * 24));
                            if ($days < 0) {
                                echo 'DUE ON';
                            } else {
                                echo 'DUE IN';
                            }
                            ?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $sum = ProductsAndServicesController::DueAmount($num);
                            echo "<span>Tsh. </span>" . $sum;
                            ?>
                        </td>
                        <td>
                            <?php
                            $now = time();
                            $your_date = strtotime($due_date);
                            $datediff = $now - $your_date;
                            $days = floor($datediff / (60 * 60 * 24));
                            if ($days < 0) {
                                if (abs($days) <= 1)
                                    echo abs($days) . " day";
                                else
                                    echo abs($days) . " days";
                            } else {
                                if (abs($days) <= 1)
                                    echo abs($days) . " day";
                                else
                                    echo abs($days) . " days";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row" style="margin-top: 2%; width: 87%;margin: 0 auto;margin-top: 5%;">
            <div class="col-sm-12">

                <div class="list-group">

                    <a class="list-group-item flex-column align-items-start" style="">
                        <div class="row">
                            <div class="col-sm-1">
                                <div class="numberCircle" style="">
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                            <div class="col-sm-11">
                                <div class="d-flex w-100 justify-content-between">
                                    <h3 class="mb-1">create invoice</h3>
                                    <small>
                                        <button type="button" class="btn btn-info btn-lg" style="width: 100%;font-size: 12px"
                                                onclick="LoadContent('invoices')">NEW INVOICE
                                        </button>
                                    </small>
                                </div>
                                <p class="mb-1" style="font-size: 14px">already created</p>
                                <small class="text-muted">
                                    <?php
                                    $now = time();
                                    $your_date = strtotime($invoice_date);
                                    $datediff = $now - $your_date;
                                    $days = floor($datediff / (60 * 60 * 24));
                                    if ($days < 0) {
                                        if (abs($days) <= 1)
                                            echo " Created: " . abs($days) . " day ago";
                                        else {
                                            if (abs($days) < 30)
                                                echo " Created: " . abs($days) . " days ago";
                                            else
                                                echo " Created: " . $invoice_date;
                                        }
                                    } else {
                                        if (abs($days) <= 1)
                                            echo " Will be created: " . abs($days) . " day";
                                        else {
                                            if (abs($days) < 30)
                                                echo "Will be created: " . abs($days) . " days";
                                            else
                                                echo "Will be created: " . $invoice_date;
                                        }
                                    }
                                    ?>
                                </small>
                            </div>
                        </div>
                    </a>
                    <div>&nbsp;</div>
                        <a class="list-group-item flex-column align-items-start" style="">
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="numberCircle" style="background-color: #029EB6;color: white"><i class="fa fa-check"></i>
                                    </div>
                                </div>
                                <div class="col-sm-11">
                            <div class="d-flex w-100 justify-content-between">
                                <h3 class="mb-1">Edit invoice</h3>
                                <small>
                                    <button type="button" class="btn btn-outline-secondary btn-lg" style="width: 100%;font-size: 12px"
                                            onclick="LoadContent('edit_invoice/{{$invoice_num}}/{{$customer_id}}')">EDIT INVOICE
                                    </button>
                                </small>
                            </div>
                            <p class="mb-1" style="font-size: 14px">already created</p>
                            <small class="text-muted">
                                <?php
                                $now = time();
                                $your_date = strtotime($invoice_date);
                                $datediff = $now - $your_date;
                                $days = floor($datediff / (60 * 60 * 24));
                                if ($days < 0) {
                                    if (abs($days) <= 1)
                                        echo " Created: " . abs($days) . " day ago";
                                    else {
                                        if (abs($days) < 30)
                                            echo " Created: " . abs($days) . " days ago";
                                        else
                                            echo " Created: " . $invoice_date;
                                    }
                                } else {
                                    if (abs($days) <= 1)
                                        echo " Will be created: " . abs($days) . " day";
                                    else {
                                        if (abs($days) < 30)
                                            echo "Will be created: " . abs($days) . " days";
                                        else
                                            echo "Will be created: " . $invoice_date;
                                    }
                                }
                                ?>
                            </small>
                                </div>
                            </div>
                        </a>
                        <div>&nbsp;</div>
                    <a class="list-group-item flex-column align-items-start">
                        <div class="row">
                            <div class="col-sm-1">
                                <div class="numberCircle" @if($status_all != 0) style="background-color: #029EB6;color: white" @endif>
                                    @if($status_all == 0)
                                        2
                                    @else
                                        <i class="fa fa-check"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-11">
                        <div class="d-flex w-100 justify-content-between">
                            <h3 class="mb-1">Approve invoice</h3>
                            <small>
                                @if($status_all == 0)
                                    <button type="button" class="btn btn-default btn-lg"
                                            style="width: 100%;font-size: 12px;border: dashed 1px darkseagreen;"
                                            onclick="ApproveInvoice({{$invoice_num}})">APPROVE INVOICE
                                    </button>
                                @elseif($status_all == 1 || $status_all == 4)
                                    <button type="button" class="btn btn-info btn-lg"
                                            style="width: 100%;font-size: 12px;border: dashed 1px darkseagreen;"
                                            disabled="disabled">APPROVED
                                    </button>
                                @elseif($status_all == 2 || $status_all == 5)
                                    <button type="button" class="btn btn-info btn-lg"
                                            style="width: 100%;font-size: 12px;border: dashed 1px darkseagreen;"
                                            disabled="disabled">APPROVED
                                    </button>

                                @endif
                            </small>
                        </div>
                        <p class="mb-1" style="font-size: 14px">
                            @if($status_all == 0)
                                invoice waiting to be approved
                            @elseif($status_all == 1 || $status_all == 4)
                                already approved, wait to be sending
                            @elseif($status_all == 2 || $status_all == 5)
                                already approved
                            @endif
                        </p>
                        <small class="text-muted">Approved:
                            @if($status_all == 0)
                                not yet
                            @elseif($status_all == 1 || $status_all == 4)
                                already approved
                            @elseif($status_all == 2  || $status_all == 5)
                                already approved
                            @endif
                        </small>
                            </div>
                        </div>
                    </a>
                    <div>&nbsp;</div>
                        <a class="list-group-item flex-column align-items-start" style="">
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="numberCircle" @if($status_all > 3) style="background-color: #029EB6;color: white" @endif>
                                        @if($status_all < 4)
                                            3
                                        @else
                                            <i class="fa fa-check"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-11">
                            <div class="d-flex w-100 justify-content-between">
                                <h3 class="mb-1">Payment</h3>
                                <small>
                                    <button type="button"
                                            @if($status_all > 3 || $status_all == 0)
                                                    disabled="disabled"
                                            @else
                                            @endif
                                            class="btn btn-info btn-lg" style="width: 100%;font-size: 12px"
                                            onclick="LoadContent('addPayment/{{$invoice_num}}/action_invoice_list')" >
                                        @if($status_all > 3)
                                            PAID
                                        @else
                                            ADD PAYMENT
                                        @endif

                                    </button>
                                </small>
                            </div>
                            <p class="mb-1" style="font-size: 14px">
                                @if($status_all > 3)
                                    already paid
                                @else
                                   not yet paid
                                @endif
                            </p>
                            <small class="text-muted">
                                @if($status_all > 3)
                                    payment: already paid
                                @else
                                    payment: not yet paid
                                @endif

                            </small>
                                </div>
                            </div>
                        </a>
                        <div>&nbsp;</div>
                    <a class="list-group-item flex-column align-items-start" style="">
                        <div class="row">
                            <div class="col-sm-1">
                                <div class="numberCircle" @if($status_all == 2 || $status_all == 5) style="background-color: #029EB6;color: white" @endif>
                                    @if($status_all != 2 || $status_all != 5)
                                        4
                                    @else
                                        <i class="fa fa-check"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-11">
                        <div class="d-flex w-100 justify-content-between">
                            <h3 class="mb-1">send invoice</h3>
                            <small>
                                @if($status_all == 0)
                                    <button type="button" class="btn btn-info btn-lg"
                                            disabled="disabled"
                                            style="width: 100%;font-size: 12px" onclick="sendMail()">SEND INVOICE
                                    </button>
                                @elseif($status_all == 1 || $status_all == 4)
                                    <button type="button" class="btn btn-info btn-lg"
                                            style="width: 100%;font-size: 12px" onclick="sendMail()">SEND INVOICE
                                    </button>
                                @elseif($status_all == 2 || $status_all == 5)
                                    <button type="button" class="btn btn-info btn-lg"
                                            style="width: 100%;font-size: 12px">RESEND INVOICE
                                    </button>
                                @endif
                            </small>
                        </div>
                        <p class="mb-1" style="font-size: 14px">
                            @if($status_all == 0)
                                invoice waiting to be sending
                            @elseif($status_all == 1 || $status_all == 4)
                                already sent, wait to be resending
                            @elseif($status_all == 2 || $status_all == 5)
                                already sent
                            @endif
                        </p>
                        <small class="text-muted">Sent:
                            @if($status_all == 0)
                                not yet
                            @elseif($status_all == 1 || $status_all == 4)
                                not yet
                            @elseif($status_all == 2 || $status_all == 5)
                                already sent
                            @endif
                        </small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container" id="preview"
     style="width: 70%;border: solid 1px lightblue; box-shadow: 5px 10px 8px #888888; margin-top: 2%;margin-bottom: 2%">
    <div class="row" style="border-bottom: solid 1px #cccccc;">
        <div class="col-sm-6">
            <img src="{{asset('img/1.png')}}" id="invoice_logo" class="thumbnail"
                 style="margin-top: 5%;padding-bottom: 2%" width="144px" height="144px" id="preview"/>
        </div>
        <div class="col-sm-6" style="text-align: right;font-family: 'Times New Roman'; margin-top: 2%">
            <div style="margin-top: 5%;font-size: 18px;font-weight: bold" id="title">{{$title}}</div>
            @if(!empty($subtitle))
                <div style="font-size: 14px;" id="summary">{{$subtitle}}</div>
            @endif
            <div style="font-size: 14px; font-weight: bold" id="company_prev">yaptrue</div>
            <div style="font-size: 14px;">
                <small id="location_prev">United Republic of, Tanzania</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div style="margin-top: 10%;font-size: 14px;font-weight: bold">BILL TO</div>
            <div style="font-size: 14px; font-weight: bold" id="name_in_prev">
                <?php
                $name = ProductsAndServicesController::customerName($customer_id);
                echo $name;
                ?>
            </div>
            <div style="font-size: 14px" id="full_name">
                <?php
                $fname = ProductsAndServicesController::customerFullName($customer_id);
                echo $fname;
                ?>
            </div>
            <div style="margin-top: 5%;font-size: 14px" id="email_in_prev">
                <?php
                $email = ProductsAndServicesController::customerEmail($customer_id);
                echo $email;
                ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row" style="margin-top: 10%;font-size: 14px;">
                <label class="control-label col-sm-9" for="invoice_num" style="font-weight: bold;text-align: right">Invoice
                    Number:</label>
                <div class="col-sm-3" id="invoice_num" style=";text-align: right">{{$invoice_num}}</div>
            </div>
            <div class="row" style="font-size: 14px;;text-align: right">
                <label class="control-label col-sm-9" for="invoice_date" style="font-weight: bold">Invoice Date:</label>
                <div class="col-sm-3" id="invoiceDate">{{$invoice_date}}</div>
            </div>
            <div class="row" style="font-size: 14px;;text-align: right">
                <label class="control-label col-sm-9" for="payment_due" style="font-weight: bold">Payment Due:</label>
                <div class="col-sm-3" id="payment_due">{{$due_date}}</div>
            </div>
            <div class="row" style="font-size: 14px;;text-align: right">
                <label class="control-label col-sm-8" for="amount_due" style="font-weight: bold">Amount Due
                    (TZS):</label>
                <div class="col-sm-4" id="amount_due">
                    <?php
                        $sum = ProductsAndServicesController::DueAmount($invoice_num);
                        $sum = ProductsAndServicesController::money($sum);
                        echo "<span>Tsh. </span>" . $sum;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 3%;padding: 0px">
        <div class="col-sm-12" style="padding: 0px">
            <table class="table borderless" style="font-size: 14px;width: 100%">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Products</th>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Amount</th>
                    <th scope="col">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $tax_total = 0;
                ?>
                @foreach($invoice_items as $item)
                    <tr>
                        <td>{{$item->item_name}}</td>
                        <td>{{$item->item_description}}</td>
                        <td>{{$item->item_quantity}}</td>
                        <td>{{$item->item_price}}</td>
                        <td style="text-align: right"><?php
                               echo ProductsAndServicesController::money($item->item_price* $item->item_quantity);
                            ?></td>
                    </tr>

                    @if(!empty($item->tax))
                        <?php

                        if(strpos($item->tax,",") > -1){
                            $array_taxes = explode(",", $item->tax);
                            for ($i = 0; $i < count($array_taxes); $i++) {
                                 $str = substr($array_taxes[$i], 0, stripos($array_taxes[$i], "_"));
                                $str_ = substr($array_taxes[$i], stripos($array_taxes[$i], "_")+1);
                               if (strcmp($str_,$item->item_name) == 0){
                                    echo '
                                                     <tr>
                                                        <td colspan="3">&nbsp;</td>
                                                        <td colspan="1" style="text-align: left">' . $str . '</td>
                                                        <td style="text-align: right">';

                                            $percent = ProductsAndServicesController::GetBetween("(", ")", $array_taxes[$i]);
                                            $tax = ($item->item_price*$item->item_quantity) * ($percent / 100);
                                            $tax_total = $tax_total + $tax;
                                            echo ProductsAndServicesController::money($tax);
                                            echo '</td></tr>';
                                }
                            }
                        }
                        else{
                            $str = substr($item->tax, 0, stripos($item->tax, "_"));
                            $str_ = substr($item->tax, stripos($item->tax, "_")+1);
                            if (strcmp($str_,$item->item_name) == 0){
                                echo '
                                                     <tr>
                                                        <td colspan="3">&nbsp;</td>
                                                        <td colspan="1" style="text-align: left">' . $str . '</td>
                                                        <td style="text-align: right">';

                                        $percent = ProductsAndServicesController::GetBetween("(", ")", $item->tax);
                                        $tax = ($item->item_price*$item->item_quantity) * ($percent / 100);
                                        $tax_total = $tax_total + $tax;
                                        echo ProductsAndServicesController::money($tax);
                                        echo '</td>
                                        </tr>';
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
                        <td colspan="1" style="text-align: left;font-size: 14px">Sub Total : Tsh.</td>
                        <td style="text-align: right">
                            <?php
                                $sum = ProductsAndServicesController::DueAmountExcludeTax($invoice_num);
                                $sum = ProductsAndServicesController::money($sum);
                                echo $sum;
                            ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr style="background-color: transparent;visibility: visible;">
                        <td colspan="3">&nbsp;</td>
                        <td colspan="1" style="text-align: left;font-size: 14px">Tax Total : Tsh.</td>
                        <td style="text-align: right;" id="subtotal_due">
                            <?php
                                echo ProductsAndServicesController::money($tax_total);
                            ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>

                @endif
                <tr style="background-color: #cccccc;visibility: visible;font-weight: bold">
                    <td colspan="3">&nbsp;</td>
                    <td colspan="1" style="text-align: left;font-size: 14px">Total : Tsh.</td>
                    <td style="text-align: right">
                        <?php
                            $sum = ProductsAndServicesController::DueAmountExcludeTax($invoice_num);
                            echo ProductsAndServicesController::money($tax_total + $sum);
                        ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>

                <tr style="background-color: transparent;visibility: visible">
                    <td colspan="3">&nbsp;</td>
                    <td colspan="1" style="text-align: left;font-size: 14px;font-weight: bold"><input type="hidden" value="{{$tax_total + $sum}}" id="due_total">Amount Due : Tsh.</td>
                    <td style="text-align: right">
                        <?php
                            $sum = ProductsAndServicesController::DueAmountExcludeTax($invoice_num);
                            echo ProductsAndServicesController::money($tax_total + $sum);
                        ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>

                </tfoot>
            </table>
        </div>
        <div class="col-sm-12" style="padding: 2%;" id="notes_prev_pare">
            @if(!empty($notes))
                <div style="font-size: 14px;font-weight: bold">Notes:</div>
                <div id="notes_prev">
                    <small>{{$notes}}</small>
                </div>
            @endif
        </div>
    </div>
</div>

