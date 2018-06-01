<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row" style="font-size: 14px">
    <div class="col-sm-10 offset-1">
<input type="hidden" value="{{$receipts->id}}" id="me">
        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Receipt Details</h2>
            </div>
            <div class="col-sm-4">
            </div>
        </div>

        <div class="row" style="margin-top: 3%;padding: 0px;margin-bottom: 5%">

            <div class="col-sm-4">
                <img src="{{asset($receipts->path)}}" class="img-rounded img-responsive" alt="{{$receipts->path}}" width="100%">
                <p><a href="">View Original Receipt</a></p>
            </div>
            <div class="col-sm-8">
                <div class="container">
                    <form class="form-horizontal" action="/action_page.php">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-2" for="merchant">Merchant*:</label>
                                    <div class="col-sm-10">
                                        <input type="name" class="form-control" id="merchant" value="@if(strcmp($receipts->merchant, 0) == 0)@else {{$receipts->merchant}}@endif
"  name="merchant">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-2" for="date">Date*:</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="date" name="date" value="{{$receipts->date}}" name="date">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="notes">Notes*:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="10" id="notes">{{$receipts->notes}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-4" for="merchant">Category*:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-multiple" name="category" id="category">

                                            <option value=""></option>
                                            @foreach($expenses as $expense)
                                                <option value="{{$expense->account_name}}" @if(strcmp($expense->account_name,$receipts->category) == 0) selected="selected" @endif >{{$expense->account_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-4" for="date" style="font-size: 14px">Account*:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-multiple" id="account" style="font-size: 14px">
                                            <option value="" style="font-size: 14px"></option>
                                            @foreach($cash_bank as $item)
                                                <option value="{{$item->account_name}}" @if(strcmp($item->account_name,$receipts->account) == 0) selected="selected" @endif >{{$item->account_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">

                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-4" for="subtotal">subtotal*:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="subtotal" name="subtotal" value="<?php if (!empty($receipts->subtotal)) echo ProductsAndServicesController::money($receipts->subtotal); else echo "0.00"; ?>" name="subtotal">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-4" for="currency" style="font-size: 14px">Currency*:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-multiple" name="currency[]" id="currency" style="font-size: 14px">
                                            <option @if(strcmp($receipts->currency,"Tsh.") == 0) selected="selected" @endif value="Tshs." style="font-size: 14px">Tshs.</option>
                                            <option @if(strcmp($receipts->currency,"USD") == 0) selected="selected" @endif value="USD" style="font-size: 14px">USD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">

                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-4" for="total">Total*:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="total" name="total" value="<?php if (!empty($receipts->total)) echo ProductsAndServicesController::money($receipts->total); else echo "0.00"; ?>" name="total">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <table class="table borderless" style="font-size: 14px">
                                    <tbody>
                                    <?php

                                        $a = 0;
                                        $tax_arr  = explode("&",$receipts->tax);
                                        $abbrev_array =[];
                                        $amount_array =[];
                                        if (count($tax_arr) > 1){
                                            $abbrev_array = explode(",",$tax_arr[count($tax_arr) - 2]);
                                            $amount_array = explode(",",$tax_arr[count($tax_arr)-1]);
                                        }


                                    ?>
                                    @foreach($taxes as $tax)
                                        <tr>
                                            <td>
                                                <p>&nbsp;</p>
                                                @if(in_array($tax->abbreviation,$abbrev_array))
                                                <input type="checkbox" checked="checked" value="{{$tax->abbreviation}}" name="tax[]" onchange="EnableInput({{$a}})">
                                                    @else
                                                    <input type="checkbox" value="{{$tax->abbreviation}}" name="tax[]" onchange="EnableInput({{$a}})">
                                                    @endif
                                            </td>
                                            <td>
                                                <p>Tax</p>
                                                <p>{{$tax->abbreviation}}</p>
                                            </td>
                                            <td style="width: 40%">
                                                <p>Amount</p>
                                                @if(in_array($tax->abbreviation,$abbrev_array))
                                                <input type="number" min="0" class="form-control" id="taxamount{{$a}}" name="taxamount[]" value="<?php echo $amount_array[array_search($tax->abbreviation, $abbrev_array)] ?>">
                                                @else
                                                    <input type="number" min="0" class="form-control" id="taxamount{{$a}}" name="taxamount[]" disabled="disabled">
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $a++; ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="button" class="btn btn-info" style="float: right" onclick="updateReceipt()">Verify Receipt</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>