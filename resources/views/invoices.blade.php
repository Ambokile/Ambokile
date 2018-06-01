<?php
    $i = 0;
    $str ="";
    foreach ($customers as $item) {
        if ($i <= 0){
            $str = " ".",".$item->name;
        }
        else{
            $str = $str .",".$item->name;
        }
        $i++;
    }
?>
<div class="wrapper" id="invoicefill">
    <div class="row" style="padding-left: 11.5%; padding-right: 6%">
        <div class="col-sm-6">
            <h2>New Invoice</h2>
        </div>
        <div class="col-sm-6">
            <div class="btn-group" style="margin-left: 10%;">
                <button class="btn btn-info" onclick="invoicePreview('invoice_preview')" id="btn_preview" style="margin-left: 20%">Preview</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary" onclick="saveInvoice(1)">Save and continue</button>
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="saveInvoice(0)">Save</a>
                        <a class="dropdown-item" href="#" onclick="saveInvoice(1)">Save and Continue</a>
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
                                                       value="Invoice" style="font-size: 16px;text-align: right">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="summ"
                                                       placeholder="Summary (e.g Project name,description of invoive)" style="font-size: 16px;text-align: right">
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
                    <button type="button" class="btn btn-default" id="mybtn" style="width: 75%;height: 200px; background-color: white; border: solid 1px cornflowerblue;font-size: 18px" onclick='addCustomerSelect()'>
                        <i class="fa fa-user-plus"> Add a Customer</i>
                    </button>

                    <div id="selectCustomerBillTo" style="display: none;margin-top: 15%;margin-left: 15%">
                        <select class="form-control js-example-basic-multiple" name="" id="billToSelectCustomer" style="width: 50%;" >
                            <option value=""> </option>
                            @foreach($customers as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="font-size: 14px;display: none" id="bill_to">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="invoiceNumber" class="col-6 col-form-label" style="font-size: 14px;text-align: right">Invoice Number</label>
                        <div class="col-6">
                            <input class="form-control" type="text" min="0" value="" id="invoiceNumber" style="font-size: 14px;text-align: left">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pos-number" class="col-6 col-form-label" style="font-size: 14px;text-align: right">P.O./S.O. Number</label>
                        <div class="col-6">
                            <input class="form-control" type="text" value="" id="pos-number" style="font-size: 14px;text-align: left">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-email-input" class="col-6 col-form-label" style="font-size: 14px;text-align: right">Invoice Date</label>
                        <div class="col-6">
                            <div class="input-group">
                                <input type='text' style="width: 80%;font-size: 14px;text-align: left;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" datepicker-here id="invoice_date" data-position="left top"
                                       data-language='en' data-date-format="yyyy-mm-dd"/>
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
                                       data-language='en' data-date-format="yyyy-mm-dd"/>
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
                        <tfoot>
                        <tr id="t1" style="visibility: collapse">
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2" style="text-align: right;font-size: 14px" >SubTotal : Tsh. </td>
                            <td style="text-align: right" id="second_subtotal">0</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr id="t2" style="visibility: collapse">
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2" style="text-align: right;font-size: 14px">Total Tax : Tsh. </td>
                            <td style="text-align: right" id="second_subtotaltax">0</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr style="background-color: #cccccc;visibility: collapse" id="t3">
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2" style="text-align: right;font-size: 14px">Total : Tsh. </td>
                            <td style="text-align: right" id="second_total">0</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <a class="btn btn-outline-primary" id="btn" style=" display: flex;align-items: center;justify-content: center;" href="#" role="button">Add an item</a>
                            </td>
                        </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
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
                        <textarea class="form-control" rows="5" id="notes" style="border-style: none;font-size: 14px" placeholder="Enter notes that are visible to customer" ></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>