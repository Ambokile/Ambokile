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
<div class="wrapper" id="invoicefill">
    <div class="row" style="padding-left: 11.5%; padding-right: 6%">
        <div class="col-sm-6">
            <h2>Edit Invoice</h2>
        </div>
        <div class="col-sm-6">
            <div class="btn-group" style="margin-left: 10%;">
                <button class="btn btn-info" onclick="invoicePreview('invoice_preview_edit')" id="btn_preview" style="margin-left: 20%">Preview</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary" onclick="editInvoice(1)">Save and continue</button>
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="editInvoice(0)">Save</a>
                        <a class="dropdown-item" href="#" onclick="editInvoice(1)">Save and Continue</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-left: 5%; padding-right: 5%; margin-top: 2%;">
        <div class="col-sm-10 offset-1" style="border: solid 1px lightblue;border-bottom-style:none">
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="text-decoration: none;font-size: 15px">
                                Business Address and Contact Details, Title, Summary and Logo
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>
                                        <img src="{{asset('img/1.png')}}" class="thumbnail" width="320px" height="200px" id="preview"/>
                                    </p>
                                    <p>
                                    <span class="btn btn-info btn-file">
                                            Upload <input type="file" id="invoice_upload_logo" onchange="imagesPreview(this);">
                                    </span>
                                        <button class="btn btn-outline-danger " id="upload">Remove</button>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="invoice_head" placeholder="Enter invoice"
                                                       value="{{$title}}" style="font-size: 16px;text-align: right">
                                            </div>
                                            <div class="form-group">
                                                @if(!empty($subtitle))
                                                <input type="text" class="form-control" id="summ"
                                                       placeholder="{{$subtitle}}" style="font-size: 16px;text-align: right">
                                                @else
                                                    <input type="text" class="form-control" id="summ"
                                                           placeholder="sub title" style="font-size: 16px;text-align: right">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="text-right">
                                                <p class="font-weight-bold" id="company">Yaptrue Tz</p>
                                            </div>
                                            <div class="text-right">
                                                <small id="location">Tanzania, United Republic Of</small>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-10 offset-1" style="border: solid 1px lightblue;">
            <div class="row" style="margin-top: 1%;">
                <div class="col-sm-6" id="myDiv">
                    <div id="selectCustomerBillTo" style="display: none;margin-top: 15%;margin-left: 15%">
                        <select class="form-control js-example-basic-multiple" name="" id="billToSelectCustomer" style="width: 50%;" >
                            <option value=""> </option>
                            @foreach($customer_all as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="font-size: 14px;display: block" id="bill_to">
                        <div>BILL TO</div>
                        @foreach($customers as $customer)
                            <input id="customer_id" type="hidden" value="{{$customer->id}}">
                            <div id="bill_to_bus_name" style="font-weight: bold">{{$customer->name}}</div>
                            <div id="bill_to_name">{{$customer->last_name}} {{$customer->first_name}}</div>
                            <div id="bill_to_email" style="margin-top: 8%">{{$customer->email}}</div>
                        @endforeach
                        <div id="bill_to_diff">
                            <a href="#" id="mytn" style="background-color: white; font-size: 14px" onclick='addCustomerSelect()'>
                                <i class="fa fa-user-plus"> choose different Customer</i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="invoiceNumber" class="col-6 col-form-label" style="font-size: 14px;text-align: right">Invoice Number</label>
                        <div class="col-6">
                            <input class="form-control" type="text" min="0" value="{{$invoice_num}}" id="invoiceNumber" style="font-size: 14px;text-align: left" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pos-number" class="col-6 col-form-label" style="font-size: 14px;text-align: right">P.O./S.O. Number</label>
                        <div class="col-6">
                            <input class="form-control" type="text" value="{{$po}}" id="pos-number" style="font-size: 14px;text-align: left">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-email-input" class="col-6 col-form-label" style="font-size: 14px;text-align: right">Invoice Date</label>
                        <div class="col-6">
                            <div class="input-group">
                                <input type='text' style="width: 80%;font-size: 14px;text-align: left;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" datepicker-here id="invoice_date" data-position="left top"
                                       data-language='en' data-date-format="yyyy-mm-dd" value="{{$invoice_date }}"/>
                                <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-url-input" class="col-6 col-form-label" style="text-align: right;font-size: 14px">Payment Due</label>
                        <div class="col-6">
                            <div class="input-group">
                                <input type='text' style="width: 80%;font-size: 14px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" datepicker-here id="due_date" data-position="left top"
                                       data-language='en' data-date-format="yyyy-mm-dd" value="{{$due_date}}"/>
                                <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 2.5%">
                <div class="col-sm-12" id="myTable">
                    <table class="table table-striped table-bordered table order-list" id="myTable_in">
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
                                $counter = 0;
                                $total = 0;
                                $total_tax = 0;
                                $quantity = 0;
                                $count = count($invoice_items);
                                $tax_array = array();
                            ?>
                        @foreach($invoice_items as $item)
                            <?php $quantity =  $item->item_quantity; $x = 0;
                            $num_row = 0;
                            if (strpos($item->tax,",") !== false){
                                 $taxes_array = explode(",",$item->tax);
                                 $z = 0;
                                 foreach ($taxes_array as $arr){
                                     $product = substr($taxes_array[$z],strpos($taxes_array[$z],"_")+1);
                                     if(strpos($item->item_name,$product) !== false){
                                         $num_row++;
                                     }
                                     $z++;
                                 }
                            }
                            ?>
                            <tr id="tr_{{$counter}}">
                                <td id="product{{$counter}}" name ="product[]" style="font-size: 14px">{{$item->item_name}}<td>
                                    <textarea class="form-control" rows="5" name="desc[]" id="desc{{$counter}}" style="font-size: 14px">{{$item->item_description}}</textarea></td>

                                <td><input type="text" class="form-control" name="quantity[]" id="quantity{{$counter}}" value="{{$item->item_quantity}}" onkeyup=calculateRowTotal("{{$counter}}","{{$num_row}}")  style="font-size: 14px"/></td>

                                <td><input type="text" onkeyup=calculateRowTotal("{{$counter}}","{{$num_row}}") class="form-control" name="price[]" id="price{{$counter}}" value="{{$item->item_price}}" style="font-size: 14px"/></td>

                                <td style="text-align: right" ><input id="subtotal_viewed{{$counter}}"  value="{{$item->item_price*$item->item_quantity}}" readonly style="text-align: right;background-color: transparent;border-style: none;font-size: 14px" /><input type="hidden" name="subtotal[]" id="subtotal{{$counter}}" value="{{$item->item_price*$item->item_quantity}}"  style="font-size: 14px"></td>

                                <td><button type="button" class="ibtnDel btn btn-sm btn-default" onclick=deleteRowInvoice("tr_{{$counter}}","") style="background-color: transparent"><i class="fa fa-trash" aria-hidden="true"></i></button><input type="hidden" value="<?php

                                    $tax_arr = explode(",",$item->tax);
                                    $tax_statement ="";
                                    $t=0;
                                    foreach ($tax_arr as $arr){
                                        if(strpos($arr,$item->item_name) > 0){
                                            if ($t == 0) $tax_statement = $arr;
                                            else $tax_statement = $tax_statement."&".$arr;
                                            $t++;
                                        }
                                    }

                                    echo str_replace(",","&",$tax_statement); ?>" id="tax_{{$counter}}"/></td>
                        <?php $total = $total + ($item->item_price*$item->item_quantity); ?>
                            </tr>
                            @if(!empty($item->tax))
                                @if(strpos($item->tax,",") !== false)
                                    <?php
                                        $tax_arr = explode(",",$item->tax);
                                        $x = 0;
                                        $u = 0;
                                    ?>

                                        @while($x < count($tax_arr))
                                            <?php
                                            $percent = ProductsAndServicesController::GetBetween("(",")",$tax_arr[$x]);
                                            $p = ($percent/100) * ($item->item_price*$item->item_quantity);
                                            $item_product = substr($tax_arr[$x],strpos($tax_arr[$x],"_")+1);
                                            $tax_ = str_replace(" ","&",$tax_arr[$x]);

                                            ?>
                                                @if(!in_array($tax_arr[$x],$tax_array) && strpos($item->item_name,$item_product) !== false)
                                                    <tr id="trs_{{$counter}}_{{$u}}">
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="1">&nbsp;</td>
                                                        <td colspan="1"><input type="text" class="form-control" id="tax_{{$counter}}_{{$u}}" value="{{substr($tax_arr[$x],0,strpos($tax_arr[$x],")")+1)}}" readonly style="background-color:transparent;border-style:solid;text-align: left;font-size: 14px" /><input type="hidden" value="{{$tax_arr[$x]}}" name="tax[]"/></td>
                                                        <td style="text-align: right"><input type="text" name="subtotaltax[]" id="subtotaltax_{{$counter}}_{{$u}}" value="<?php echo ProductsAndServicesController::money($p); ?>" readonly style="background-color:transparent;border-style:none;text-align: right;font-size: 14px"></td>
                                                        <td><button type="button" class="ibtnDel btn btn-sm btn-default " style="background-color: transparent" onclick=deleteRowInvoice("trs_{{$counter}}_{{$u}}","{{$tax_}}")><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                                        <?php
                                                            array_push($tax_array,$tax_arr[$x]);
                                                            $total_tax = $total_tax + $p;
                                                            $u++;
                                                        ?>
                                            </tr>
                                                @endif
                                            <?php
                                                $x++;
                                                if ($u >= count($tax_arr) - 1){$u = 0;}
                                            ?>
                                        @endwhile
                                @else
                                    <?php
                                        $percent = ProductsAndServicesController::GetBetween("(",")",$item->tax);
                                        $p = ($percent/100) * ($item->item_price*$item->item_quantity);
                                        $tax_ = str_replace(" ","&",$item->tax);
                                    ?>
                                    <tr id="trs_{{$counter}}_0">
                                        <td colspan="2">&nbsp;</td>
                                        <td colspan="1">&nbsp;</td>
                                        <td colspan="1"><input type="text" class="form-control" id="tax_{{$counter}}_0" value="{{substr($item->tax,0,strpos($item->tax,")")+1)}}" style="background-color:transparent;border-style:none;font-size: 14px" /><input type="hidden" value="{{$item->tax}}" name="tax[]"/></td>
                                        <td style="text-align: right"><input type="text" name="subtotaltax[]" id="subtotaltax_{{$counter}}_0" value="<?php echo ProductsAndServicesController::money($p); ?>" readonly style="background-color:transparent;border-style:none;text-align: right;font-size: 14px"></td>
                                        <td><button type="button" class="ibtnDel btn btn-sm btn-default " style="background-color: transparent" onclick=deleteRowInvoice("trs_{{$counter}}","{{$tax_}}")><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                    </tr>
                                    <?php
                                        $total_tax = $total_tax + $p;
                                    ?>
                                @endif
                                <tr id="add{{$counter}}">
                                    <td colspan="2">&nbsp;</td>
                                    <td colspan="1">&nbsp;</td>
                                    <td colspan="1">
                                        <?php
                                            $product = $item->item_name;
                                            if (strpos($item->item_name," ") > -1){
                                                $product = str_replace(" ","&",$item->item_name);
                                            }

                                        ?>
                                        <select class="js-example-basic-multiple" name="" id="select{{$counter}}" onchange=addTaxRowEdit({{$counter}},this.value,"{{$item->item_price}}","add{{$counter}}","{{$product}}","{{$quantity}}")  style="width: 100%;" data-placeholder="tax">
                                            <option value=""></option>
                                            @foreach($taxes as $tax)
                                                <option value="{{$tax->abbreviation}}({{$tax->tax_rate}})">{{$tax->abbreviation}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <?php
                                    $counter++;
                                ?>
                            @else
                                <tr id="add{{$counter}}">
                                    <td colspan="2">&nbsp;</td>
                                    <td colspan="1">&nbsp;</td>
                                    <td colspan="1">
                                        <?php
                                            $product = $item->item_name;
                                            if (strpos($item->item_name," ") > -1){
                                                $product = str_replace(" ","&",$item->item_name);
                                            }
                                        ?>
                                        <select class="js-example-basic-multiple" name="" id="select{{$counter}}" onchange="addTaxRowEdit({{$counter}},this.value,'{{$item->item_price}}','add{{$counter}}','{{$product}}','{{$quantity}}','{{$num_row}}')"  style="width: 100%;">
                                            <option value=""></option>
                                            @foreach($taxes as $tax)
                                                <option value="{{$tax->abbreviation}}({{$tax->tax_rate}})">{{$tax->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            @endif
                            <?php
                                $x = 0;
                            ?>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr id="t1" style="visibility: visible">
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" style="text-align: right;font-size: 14px" >SubTotal : Tsh. </td>
                                <td style="text-align: right" id="second_subtotal">{{$total}}</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr id="t2" style="visibility: visible">
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" style="text-align: right;font-size: 14px">Total Tax : Tsh. </td>
                                <td style="text-align: right" id="second_subtotaltax">{{$total_tax}}</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr style="background-color: #cccccc;visibility: visible" id="t3">
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" style="text-align: right;font-size: 14px">Total : Tsh. </td>
                                <td style="text-align: right" id="second_total">{{$total + $total_tax}}</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <a class="btn btn-outline-primary" id="btn" style=" display: flex;align-items: center;justify-content: center;" href="#" role="button">Add an item</a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div>
                        <!-- Modal -->
                        <div id="invoiceModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <select class="form-control js-example-basic-multiple" name="" id="invoceSelectorProducts" style="width: 90%;" onchange="productInvoceFetch(this.value)">
                                            <option value=""> </option>
                                            @foreach($products as $item)
                                                <?php
                                                $tax_rate = 0;
                                                $i = 0;
                                                $str_tx = "";
                                                foreach ($taxes as $taxs) {
                                                    if (strpos($item->tax, $taxs->abbreviation) !== false) {
                                                        if ($i < 1){
                                                            $str_tx = $taxs->abbreviation."(".$taxs->tax_rate.")";
                                                            $i++;
                                                        }
                                                        else{
                                                            if (strpos($str_tx, $taxs->abbreviation) === false) {
                                                                $str_tx = $str_tx."&".$taxs->abbreviation."(".$taxs->tax_rate.")";
                                                                $i++;
                                                            }
                                                        }

                                                    }
                                                }
                                                ?>
                                                <option value="{{$item->id .',' .$item->name . ','.$item->description.','.$str_tx.','.$item->Price}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-10 offset-1">
            <div class="row" style="border: solid 1px lightblue; border-top-style:none ">
                <div class="col-sm-12" >
                    <div class="form-group">
                        <label for="email" style="font-size: 16px"><small>NOTES:</small></label>
                        <textarea class="form-control" rows="5" id="notes" style="border-style: none;font-size: 14px" placeholder="Enter notes that are visible to customer" >{{$notes}}</textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>