<?php use App\Http\Controllers\ProductsAndServicesController; $a = 0;$sum = 0; $arr_trans = array(); ?>
@foreach($transactions as $transaction)
    <?php
        $sum = $sum + $transaction->amount;
    if (strpos($transaction->category,"journal statement") !== false && in_array($transaction->invoice_num,$arr_trans))
        continue;
    else if(strpos($transaction->category,"journal statement") !== false){
        array_push($arr_trans,$transaction->invoice_num);
    }
    ?>
    <a class="list-group-item flex-column align-items-start" @if($a == 0) style="margin-top: 2%;" @endif>
        <input type="hidden" value="{{$transaction->id}}" id="transaction_id">
        <div class="row">
            <div class="col-sm-1">
                <div style="margin-top: 20%">
                    <input type="radio" name="edit" style="" onclick="updateAgainTransaction(['{{$transaction->description}}','{{$transaction->date}}','{{$transaction->account}}','{{$transaction->category}}','{{$transaction->amount}}','{{$transaction->id}}','{{$transaction->notes}}','{{$transaction->operation}}','{{$transaction->operation}}'])" >
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        @if(empty($transaction->description))
                            Write Description
                        @else
                            {{$transaction->description}}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="font-size: 14px;color:#3C4858">
                        <i class="fa fa-calendar"></i> {{$transaction->date}}
                    </div>
                    <div class="col-sm-6" style="font-size: 14px;color:#3C4858">
                        @if(strpos($transaction->category,"journal statement") === false)
                            <i class="fa fa-home"></i>
                            <span style="font-size: 13px;">{{$transaction->account}}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 offset-3" style="font-size: 14px;color:#3C4858">
                        <div class="row">
                            <div class="col-sm-3" style="font-size: 14px;color:#3C4858;">
                                <i class="fa fa-list" style="vertical-align: bottom"></i>
                            </div>
                            <div class="col-sm-9" style="font-size: 14px;color:#3C4858">
                                @if(empty($transaction->category))
                                    <span style="font-size: 13px">select category<span>
                                @else
                                    {{$transaction->category}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <?php
                    echo "sh ".ProductsAndServicesController::money($transaction->amount);
                ?>
            </div>
            <div class="col-sm-1">
                <button type="button" onclick="markReviewed({{$transaction->id}},{{$transaction->status}})" class="btn btn-default" style="font-size: 34px;color: #C0C0C0;background-color: transparent">
                    @if($transaction->status == 1)
                        <i class="fa fa-check-circle" ></i>
                    @elseif($transaction->status == 0)
                        <i class="fa fa-check-circle-o" ></i>
                    @else
                        <i class="fa fa-check-circle-o" ></i>
                    @endif
                </button>
            </div>
        </div>
    </a>
    <?php $a = $a + 1; ?>
@endforeach
<input type="hidden" id="sum_transaction_input" value="{{$sum}}"/>

<div class="panel panel-default" style="position: fixed;bottom: 5%;right: 1%;z-index: 2;background-color: #f0f0f0;padding: 1%;display: none;border: solid 1px #C0C0C0;" id="income_expense_account">
    <div class="panel-heading" style="background-color: #f0f0f0;padding-left: 1%;padding-right: 1%;">
        <div class="row" style="margin-bottom: 0%;border-bottom: solid 1px #C0C0C0;">
            <div class="col-sm-9" style="font-size: 15px">
                <span>Edit transaction details</span>
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn btn-sm btn-default" style="float: right;background-color: transparent" onclick="income_expense_account_close('income_expense_account')"><i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
    <div class="panel-body" style="height: 350px; overflow-y: auto;overflow-x: hidden;background-color:#f0f0f0">
        <div class="row" style="margin-top: 5%;margin-bottom: 4%;">
            <div class="col-sm-8 offset-2">
                <div class="progress" id="progress_transaction_" style="display: none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                        <span>Please wait<span class="dotdotdot"></span></span>
                    </div>
                </div>
                <div class="col-sm-10 offset-md-1">
                    <div id="errormsg_transaction_" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
                    </div>
                </div>
            </div>

            <div class="col-sm-11" style="margin-left: 3%;">
                <fieldset style="background-color: #FFFFFF;padding: 0px">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="desc_transaction">Description</label>
                        <input type="text" class="form-control" id="desc_transaction" style="background-color: #FFFFFF;border-style: none;width: 98%;">
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;margin-top: 2%;padding: 0px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="account_transaction">Account</label>
                                <select class="form-control js-example-basic-multiple" id="account_transaction" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                    <optgroup label="CASH AND BANK" style="font-size: 14px;">
                                        @foreach($cash_bank as $item)
                                            <option>{{$item->account_name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" style="border-left: solid 1px #C0C0C0">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="to">Date</label>
                                <div class="input-group" style="width: 90%;">
                                    <input type='text' datepicker-here id="to" data-position="left top" data-language='en' style="width: 90%;margin-left:0%;font-size: 13px;background-color: #FFFFFF;border-style: none" value="{{date('Y-m-d', time())}}" data-date-format="yyyy-mm-dd" />
                                    <div class="input-group-addon" style="background-color: #FFFFFF;border-style: none">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;margin-top: 2%;padding: 0px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="D_W" style="font-size: 13px">Deposit or withdrawal</label>
                                <select class="form-control js-example-basic-multiple" id="D_W" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5" onchange="ChangeDW(this.value)">
                                    <option value="Deposit">Deposit</option>
                                    <option value="withdrawal">withdrawal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" style="border-left: solid 1px #C0C0C0">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="account_transaction_total" style="font-size: 13px">Total Amount</label>
                                <input type="text" class="form-control" id="account_transaction_total" style="background-color: #FFFFFF;border-style: none;width: 98%;text-align: right" value="0">
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;padding: 0px;margin-top: 2%">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="category" style="font-size: 13px">Category</label>
                        <select class="form-control js-example-basic-multiple" id="category" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                            @if($type == 3)
                                <optgroup label="INCOME ACCOUNTS" id="income_" style="visibility: hidden">
                                    @foreach($incomes as $income)
                                        <option value="{{$income->account_name}}">{{$income->account_name}}</option>
                                    @endforeach
                                        <option value="" selected>Uncategorized Income</option>
                                </optgroup>
                            @elseif($type == 4)
                                <optgroup label="EXPENSE ACCOUNTS" id="expense_">
                                    @foreach($incomes as $income)
                                        <option value="{{$income->account_name}}">{{$income->account_name}}</option>
                                    @endforeach
                                        <option value="" selected>Uncategorized Expense</option>
                                </optgroup>
                            @endif

                            <optgroup label="ASSETS ACCOUNTS">
                                @foreach($assets as $asset)
                                    <option value="{{$asset->account_name}}">{{$asset->account_name}}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="EQUITY ACCOUNTS">
                                @foreach($equities as $equity)
                                    <option value="{{$equity->account_name}}">{{$equity->account_name}}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="LIABILITY ACCOUNTS">
                                @foreach($liabilities as $liability)
                                    <option value="{{$liability->account_name}}">{{$liability->account_name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;padding: 0px;margin-top: 2%">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="notes" style="font-size: 13px">Notes</label>
                        <textarea class="form-control" rows="5" id="notes" style="width: 98%;background-color: #FFFFFF;border-style: none"></textarea>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer" style="border-top: solid 1px #C0C0C0">
        <div class="row">
            <div class="col-sm-2">
                <button type="button" class="btn btn-default btn-block" style="margin-top: 15%;background-color: white;border: solid 1px red;color: #343a40" onclick="deleteTransaction()"><i class="fa fa-trash"></i> </button>
            </div>
            <div class="col-sm-5">
                <button type="button" class="btn btn-default btn-block" style="margin-top: 5%;background-color: white;border: solid 1px red;color: #343a40" onclick="income_expense_account_close('income_expense_account')">Cancel</button>
            </div>
            <div class="col-sm-5">
                <button type="button" onclick=updateTransaction() class="btn btn-block btn-info" style="margin-top: 5%;">Save</button>
            </div>
        </div>

    </div>
</div>

