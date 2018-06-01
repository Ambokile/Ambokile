<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<?php
$bill_currency = array();
?>
<div class="row" style="margin-left: 11%; margin-right: 5%;margin-top: 3%">
    <div class="col-sm-8">
        <div class="btn-group" style="width: 45%">
            <button type="button" class="btn btn-default btn-lg  dropdown-toggle dropdown-toggle-split" style="border: solid 1px darkblue;background-color: white;font-size: 15px;width: 100%;border-right-style: none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="default_all_transaction">All Accounts</button>
            <button type="button" class="btn btn-default" style="border: solid 1px darkblue;background-color: white;border-left-style: none">
                <span style="" id="sum_transaction">
                    <?php
                        $sum = ProductsAndServicesController::TransactionAmount("All Account");
                        echo "Sh ".ProductsAndServicesController::money($sum);
                    ?>
                </span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>

            <div class="dropdown-menu">
                <h6 class="dropdown-header">CASH AND BANK</h6>
                @foreach($cash_bank as $item)
                    <a class="dropdown-item" style="font-size: 14px;" onclick="SetNameTransaction('{{$item->account_name}}')"><div>{{$item->account_name}}</div><div style="width: 100%;text-align: right"><small><?php
                                $sum_ = ProductsAndServicesController::TransactionAmount($item->account_name);
                                echo "Sh. ".ProductsAndServicesController::money($sum_);
                                ?></small></div></a>
                @endforeach
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" onclick="SetNameTransaction('All Account')">All Account</a>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button class="btn btn-default" style="border: solid 1px darkblue;background-color: white" onclick="income_expense_account_open(3)">Add Income</button>
            <button class="btn btn-default" style="border: solid 1px darkblue;background-color: white" onclick="income_expense_account_open(4)">Add Expense</button>
            <div class="btn-group" style="margin-left: 0%;">
                <button type="button" class="btn btn-default" style="border: solid 1px darkblue;background-color: white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More</button>
                <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: solid 1px darkblue;background-color: white;border-left-style: none">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" style="font-size: 14px;">
                    <a class="dropdown-item" href="#" onclick="income_expense_account_open(5)">Add journal transaction</a>
                    <a class="dropdown-item" href="#">Connect your bank</a>
                    <a class="dropdown-item" href="#">Upload a bank Statement</a>
                </div>
            </div>
            <button class="btn btn-default" style="border: solid 1px darkblue;background-color: white" onclick="openTransactionFilter();"><i class="fa fa-filter"></i> </button>
        </div>
    </div>
</div>


<div class="row" id="transaction_filter" style="margin-top: 2%;margin-left: 11%; margin-right: 5%;">
    <div class="col-sm-6">
            <div class="btn-group" style="margin-top: 0%">
                <div class="input-group" style="width: 80%;float: right;">
                    <div class="dropdown show" style="font-size: 14px;width: 100%">
                        <a class="btn btn-default dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="default_all_transaction_transaction" style="background-color: transparent; border: solid 1px #C0C0C0;color: black;width: 100%;font-size: 14px" >
                            {{date("Y",time())}}
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="overflow-y: auto;height: 350px;">
                            <h6 class="dropdown-header">CALENDER YEAR</h6>
                            @for($i = 0;$i< 3;$i++)
                                <?php
                                $year = date('Y',time()) - $i;
                                $start = $year.'-01-01';
                                $end = $year.'-12-31';
                                ?>
                                <a class="dropdown-item" onclick='dateRange2("{{$start}}", "{{$end}}","{{$year}}")' style="font-size: 14px">{{date("Y",time()) - $i}}</a>
                            @endfor
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">CALENDER QUARTER</h6>
                            @for($i = 0;$i< 3;$i++)
                                <?php $q = 1; $q1 = 3; ?>
                                @for($z = 1;$z< 5;$z++)
                                    <?php
                                    $year = date('Y',time()) - $i;
                                    if ($z > 1){
                                        if (strlen((string)($q+1)) < 2){
                                            $start = $year.'-0'.($q+1).'-01';
                                        }
                                        else{
                                            $start = $year.'-'.($q+1).'-01';
                                        }

                                    }
                                    else{
                                        if (strlen((string)($q)) < 2){
                                            $start = $year.'-0'.$q.'-01';
                                        }
                                        else{
                                            $start = $year.'-'.$q.'-01';
                                        }

                                    }

                                    if (strlen((string)$q1) < 2){
                                        $end = $year.'-0'.$q1.'-'.cal_days_in_month(CAL_GREGORIAN,$q1,$year);
                                    }
                                    else{
                                        $end = $year.'-'.$q1.'-'.cal_days_in_month(CAL_GREGORIAN,$q1,$year);
                                    }


                                    ?>
                                    <a class="dropdown-item" onclick='dateRange2("{{$start}}", "{{$end}}","Q{{$z}} {{date("Y",time()) - $i}}")' style="font-size: 14px">Q{{$z}} {{date("Y",time()) - $i}}</a>
                                    <?php
                                    $q = $q1;
                                    $q1 = $q1 + 3;
                                    ?>
                                @endfor
                            @endfor
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">MONTH</h6>
                            @for($i = 0;$i< 3;$i++)
                                @for($z = 1;$z< 13;$z++)
                                    <?php
                                    $year = date('Y',time()) - $i;
                                    $start = $year.'-0'.$z.'-01';
                                    if (strlen((string)$z) < 2)
                                        $end = $year.'-0'.$z.'-'.cal_days_in_month(CAL_GREGORIAN,$z,$year);
                                    else
                                        $end = $year.'-'.$z.'-'.cal_days_in_month(CAL_GREGORIAN,$z,$year);
                                    $dt = DateTime::createFromFormat('!m', $z);
                                    $month = $dt->format('F');
                                    ?>
                                    <a class="dropdown-item" onclick='("{{$start}}", "{{$end}}","{{$month}} {{date("Y",time()) - $i}}")' style="font-size: 14px">{{$month}} {{date("Y",time()) - $i}}</a>
                                @endfor
                            @endfor
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">OTHER</h6>
                            <?php
                            $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
                            $friday = date( 'Y-m-d', strtotime( 'friday this week' ) );
                            $date_4_weeks_back = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-14, date("Y")));
                            $date_last_week_monday =  date("Y-m-d", strtotime("last week monday"));
                            $date_last_week_friday =  date("Y-m-d", strtotime("last week friday"));
                            $last_30_day_back = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y")));

                            $last_60_day_back = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-60, date("Y")));

                            $last_90_day_back = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-90, date("Y")));

                            ?>
                            <a class="dropdown-item" onclick='dateRange2("{{$monday}}", "{{$friday}}","This week")' style="font-size: 14px">This week</a>
                            <a class="dropdown-item" onclick='dateRange2("{{$date_last_week_monday}}", "{{$date_last_week_friday}}","Previous week")' style="font-size: 14px">Previous week</a>
                            <a class="dropdown-item" onclick='dateRange2("{{$date_4_weeks_back}}", "{{date('Y-m-d',time())}}","Last 4 weeks")' style="font-size: 14px">Last 4 weeks</a>
                            <a class="dropdown-item" onclick='dateRange2("{{$last_30_day_back}}", "{{date('Y-m-d',time())}}","Last 30 days")' style="font-size: 14px">Last 30 days</a>
                            <a class="dropdown-item" onclick='dateRange2("{{$last_60_day_back}}", "{{date('Y-m-d',time())}}","Last 60 days")' style="font-size: 14px">Last 60 days</a>
                            <a class="dropdown-item" onclick='dateRange2("{{$last_90_day_back}}", "{{date('Y-m-d',time())}}","Last 90 days")' style="font-size: 14px">Last 90 days</a>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">CUSTOM</h6>
                            <a class="dropdown-item" onclick='dateRange2("", "","Custom")' style="font-size: 14px">Custom</a>
                        </div>
                    </div>
                </div>
                <div class="input-group" style="width: 50%;float: right">
                    <input type='text' style="width: 75%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%;" class="datepicker-here" id="from_transaction" data-position="right top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="" onkeyup="dateRange2($('#from_transaction').val(),  $('#to_transaction').val(),'Custom')"/>
                    <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="input-group" style="width: 50%;float: right">
                    <input type='text' style="width: 75%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" class="datepicker-here" id="to_transaction" data-position="left top" data-language='en' placeholder="to" data-date-format="yyyy-mm-dd" value="" onkeyup="dateRange2($('#from_transaction').val(), $('#to_transaction').val(),'Custom')" />
                    <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
    </div>
    <div class="col-sm-2">
        &nbsp;
    </div>
    <div class="col-sm-2" >
        <select class="form-control js-example-basic-multiple" id="reviewed_transaction" onchange="SelectTransactionAccording()" style="width: 100%"  data-placeholder="All statuses">
                <option value=""></option>
                <option value="2">Reviewed</option>
                <option value="1">Not Reviewed</option>
        </select>
    </div>

    <div class="col-sm-2">
        <select class="form-control js-example-basic-multiple" id="type_transaction" onchange="SelectTransactionAccording()" style="width: 100%"  data-placeholder="All Types">
                <option value=""></option>
                <option value="1">Deposit</option>
                <option value="2">withdrawal</option>
                <option value="3">Journal</option>
        </select>
    </div>
</div>


<div class="row" style="margin-left: 12%; margin-right: 5%;margin-top: 1%;margin-bottom: 1%;border-top: solid 1px #C0C0C0;">

    <div class="col-sm-8 offset-2">
        <div class="progress" id="progress_transaction" style="display: none">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span>Please wait<span class="dotdotdot"></span></span>
            </div>
        </div>
        <div class="col-sm-10 offset-md-1">
            <div id="errormsg_transaction" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
            </div>
        </div>
    </div>

    <div class="list-group" style="width: 100%;;overflow-y: auto;height: 550px;" id="transaction_list">
        <?php $a = 0;$sum = 0; $arr_trans = array();?>
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
                <div class="row">
                    <div class="col-sm-1">
                        <div style="margin-top: 20%">
                            <input type="radio" name="edit" style="width: 100%;height: 50px" onclick='updateAgainTransaction(["{{$transaction->description}}","{{$transaction->date}}","{{$transaction->account}}","{{$transaction->category}}","{{$transaction->amount}}","{{$transaction->id}}","{{$transaction->notes}}","{{$transaction->operation}}","{{$transaction->transaction_type}}"])' >
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-12">
                                @if(empty($transaction->description))
                                                   <span style="font-size: 15px;font-weight: 500">Write Description</span>
                                @else
                                    <span style="font-size:15px;font-weight: 500">{{$transaction->description}}</span>

                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6" style="font-size: 14px;color:#3C4858">
                                <i class="fa fa-calendar"></i>
                                <span style="font-size: 13px">{{$transaction->date}}</span>
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
                                             @if(strcmp($transaction->operation,"journal") == 0)
                                                <span style="font-size: 13px">journal entry</span>                                @else
                                                <span style="font-size: 13px">select category</span>                             @endif
                                            @else
                                            <span style="font-size: 13px">{{$transaction->category}}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <span style="font-size: 13px">
                            <?php
                                echo "sh ".ProductsAndServicesController::money($transaction->amount)
                            ?>
                        </span>
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
            <?php $a++; ?>
        @endforeach
            <input type="hidden" id="sum_transaction_input" value="{{$sum}}"/>
    </div>
</div>

<div class="panel panel-default" style="position: fixed;bottom: 5%;right: 1%;z-index: 2;background-color: #f0f0f0;padding: 1%;display: none;border: solid 1px #C0C0C0;width: 50%;" id="journal_account">
                <div class="panel-heading" style="background-color: #f0f0f0;padding-left: 1%;padding-right: 1%;">
                    <div class="row" style="margin-bottom: 0%;border-bottom: solid 1px #C0C0C0;">
                        <div class="col-sm-9" style="font-size: 15px">
                            <span>Edit transaction details</span>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-sm btn-default" style="float: right;background-color: transparent" onclick="income_expense_account_close('journal_account')"><i class="fa fa-times"></i> </button>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height: 350px; overflow-y: auto;overflow-x: hidden;background-color:#f0f0f0">
                    <div class="row" style="margin-top: 5%;margin-bottom: 4%;">
                        <div class="col-sm-8 offset-2">
                            <div class="progress" id="progress_transaction_journal" style="display: none">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                    <span>Please wait<span class="dotdotdot"></span></span>
                                </div>
                            </div>
                            <div class="col-sm-10 offset-md-1">
                                <div id="errormsg_transaction_journal" style="display: none;width: 80%;margin-bottom: 4%;text-align: center;font-size: 12px">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-11" style="margin-left: 3%;">
                            <fieldset style="background-color: #FFFFFF;padding: 0px">
                                <div class="form-group" style="font-size: 14px;">
                                    <label for="desc_transaction">Description</label>
                                    <input type="text" class="form-control" id="desc_transaction_journal" style="background-color: #FFFFFF;border-style: none;width: 98%;">
                                </div>
                            </fieldset>
                            <fieldset style="background-color: #FFFFFF;margin-top: 2%;padding: 0px;">
                                <div class="row">
                                    <div class="col-sm-6" style="border-right: solid 5px #f0f0f0">
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
                                    <div class="col-sm-6" style="">
                                        <div class="form-group" style="font-size: 14px;">
                                            <label for="account_transaction_total" style="font-size: 13px">Total</label>
                                            <input type="text" class="form-control" id="account_transaction_total_journal" style="background-color: #FFFFFF;border-style: none;width: 98%;text-align: right" value="0" disabled="disabled">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset style="background-color: #FFFFFF;padding: 0px;margin-top: 2%">
                                <div class="form-group" style="font-size: 14px;padding-right: 2%;">
                                    <label for="category" style="font-size: 13px">Account</label>
                                    <table class="table table-striped borderless" style="border: solid 1px #CCCCCC;font-size: 14px;">
                                        <thead>
                                        <tr >
                                            <th scope="col">Account</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Debit</th>
                                            <th scope="col">Credit</th>
                                            <th scope="col">&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody id="dataTable">
                                        <tr>
                                            <td width="20%;">
                                                <select class="form-control js-example-basic-multiple" id="category_edit_journal" style="width: 100%;background-color: #FFFFFF;border-style: none;z-index: 5" name="category[]">
                                                    <optgroup label="ASSETS" style="font-size: 14px;">
                                                        @foreach($assets as $asset)
                                                            <option value="{{$asset->account_name}}" style="font-size: 14px;">{{$asset->account_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="LIABILITY" style="font-size: 14px;">
                                                        @foreach($liabilities as $liability)
                                                            <option value="{{$liability->account_name}}" style="font-size: 14px;">{{$liability->account_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="INCOME" id="income_" style="font-size: 14px;">
                                                        @foreach($incomes as $income)
                                                            <option value="{{$income->account_name}}" style="font-size: 14px;">{{$income->account_name}}</option>
                                                        @endforeach
                                                        <option value="Uncategorized Income" selected style="font-size: 14px;">Uncategorized Income</option>
                                                    </optgroup>
                                                    <optgroup label="EXPENSES" style="font-size: 14px;">
                                                        @foreach($expenses as $expense)
                                                            <option value="{{$expense->account_name}}" style="font-size: 14px;">{{$expense->account_name}}</option>
                                                        @endforeach
                                                        <option value="" style="font-size: 14px;">Uncategorized Expense</option>
                                                    </optgroup>

                                                    <optgroup label="EQUITY" style="font-size: 14px;">
                                                        @foreach($equities as $equity)
                                                            <option value="{{$equity->account_name}}" style="font-size: 14px;">{{$equity->account_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea class="form-control" rows="5" id="desc_journal" style="width: 78%;background-color: #FFFFFF;" name="desc_journal[]"></textarea>
                                            </td>
                                            <td style="vertical-align: middle"><input type="text" id="debit" name="debit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="0"></td>
                                            <td style="vertical-align: middle"><input type="text" id="credit" name="credit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="0"></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td width="20%;">
                                                <select class="form-control js-example-basic-multiple" id="category_edit_journal" style="width: 100%;background-color: #FFFFFF;border-style: none;z-index: 5" name="category[]">
                                                    <optgroup label="ASSETS" style="font-size: 14px;">
                                                        @foreach($assets as $asset)
                                                            <option value="{{$asset->account_name}}" style="font-size: 14px;">{{$asset->account_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="LIABILITY" style="font-size: 14px;">
                                                        @foreach($liabilities as $liability)
                                                            <option value="{{$liability->account_name}}" style="font-size: 14px;">{{$liability->account_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="INCOME" id="income_" style="font-size: 14px;">
                                                        @foreach($incomes as $income)
                                                            <option value="{{$income->account_name}}" style="font-size: 14px;">{{$income->account_name}}</option>
                                                        @endforeach
                                                        <option value="" style="font-size: 14px;">Uncategorized Income</option>
                                                    </optgroup>
                                                    <optgroup label="EXPENSES" style="font-size: 14px;">
                                                        @foreach($expenses as $expense)
                                                            <option value="{{$expense->account_name}}" style="font-size: 14px;">{{$expense->account_name}}</option>
                                                        @endforeach
                                                        <option value="Uncategorized Expense" selected style="font-size: 14px;">Uncategorized Expense</option>
                                                    </optgroup>

                                                    <optgroup label="EQUITY" style="font-size: 14px;">
                                                        @foreach($equities as $equity)
                                                            <option value="{{$equity->account_name}}" style="font-size: 14px;">{{$equity->account_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea class="form-control" rows="5" id="desc_journal" style="width: 78%;background-color: #FFFFFF;" name="desc_journal[]"></textarea>
                                            </td>
                                            <td style="vertical-align: middle"><input type="text" id="debit" name="debit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="0"></td>
                                            <td style="vertical-align: middle"><input type="text" id="credit" name="credit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="0"></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style="text-align: right">Total: </td>
                                            <td><span id="debit_side" style="margin-left: 5px">0</span></td>
                                            <td><span id="credit_side" style="margin-left: 5px">0</span></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row" style="padding-left: 0%;margin-bottom: 1%;">
                                    <div class="col-sm-12">
                                        <button  type="button" id="addrowbtn" onclick=addRowAccount() class="btn btn-default" style="background-color: transparent; border: dotted 1px blueviolet">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset style="background-color: #FFFFFF;padding: 0px;margin-top: 2%">
                                <div class="form-group" style="font-size: 14px;">
                                    <label for="notes" style="font-size: 13px">Notes</label>
                                    <textarea class="form-control" rows="5" id="notes_journal" style="width: 98%;background-color: #FFFFFF;border-style: none"></textarea>
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
                            <button type="button" class="btn btn-default btn-block" style="margin-top: 5%;background-color: white;border: solid 1px red;color: #343a40" onclick="income_expense_account_close('journal_account')">Cancel</button>
                        </div>
                        <div class="col-sm-5">
                            <button type="button" onclick="updateTransactionJournal()" class="btn btn-block btn-info" style="margin-top: 5%;">Save</button>
                        </div>
                    </div>

                </div>
            </div>

<div class="panel panel-default" style="position: fixed;bottom: 5%;right: 1%;z-index: 2;background-color: #f0f0f0;padding: 1%;display: none;border: solid 1px #C0C0C0;" id="edit_transaction">
    <div class="panel-heading" style="background-color: #f0f0f0;padding-left: 1%;padding-right: 1%;">
        <div class="row" style="margin-bottom: 0%;border-bottom: solid 1px #C0C0C0;">
            <div class="col-sm-9" style="font-size: 15px">
                <span>Edit transaction details</span>
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn btn-sm btn-default" style="float: right;background-color: transparent" onclick="income_expense_account_close('edit_transaction')"><i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
    <div class="panel-body" style="height: 350px; overflow-y: auto;overflow-x: hidden;background-color:#f0f0f0">
        <div class="row" style="margin-top: 5%;margin-bottom: 4%;">
            <div class="col-sm-8 offset-2">
                <div class="progress" id="progress_transaction_edit" style="display: none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                        <span>Please wait<span class="dotdotdot"></span></span>
                    </div>
                </div>
                <div class="col-sm-10 offset-md-1">
                    <div id="errormsg_transaction_edit" style="display: none;width: 80%;margin-bottom: 4%;text-align: center;font-size: 12px">
                    </div>
                </div>
            </div>

            <div class="col-sm-11" style="margin-left: 3%;">
                <fieldset style="background-color: #FFFFFF;padding: 0px">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="desc_transaction_edit">Description</label>
                        <input type="text" class="form-control" id="desc_transaction_edit" style="background-color: #FFFFFF;border-style: none;width: 98%;">
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;margin-top: 2%;padding: 0px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="account_transaction">Account</label>
                                <select class="form-control js-example-basic-multiple" id="account_transaction_edit" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                    <optgroup label="CASH AND BANK" style="font-size: 14px;">
                                        @foreach($cash_bank as $item)
                                            <option value="{{$item->account_name}}">{{$item->account_name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" style="border-left: solid 1px #C0C0C0">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="to">Date</label>
                                <div class="input-group" style="width: 90%;">
                                    <input type='text' datepicker-here id="to_edit" data-position="left top" data-language='en' style="width: 90%;margin-left:0%;font-size: 13px;background-color: #FFFFFF;border-style: none" value="{{date('Y-m-d', time())}}" data-date-format="yyyy-mm-dd" />
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
                                <select class="form-control js-example-basic-multiple" id="D_W_edit" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5" onchange="ChangeDWEdit(this.value)">
                                    <option value="Deposit">Deposit</option>
                                    <option value="withdrawal">withdrawal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" style="border-left: solid 1px #C0C0C0">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="account_transaction_total" style="font-size: 13px">Total Ambount</label>
                                <input type="text" class="form-control" id="account_transaction_total_edit" style="background-color: #FFFFFF;border-style: none;width: 98%;text-align: right" value="0">
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;padding: 0px;margin-top: 2%">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="category" style="font-size: 13px">Category</label>
                        <select class="form-control js-example-basic-multiple" id="category_edit" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                            @if($type == 0)
                                <option value="" selected>Uncategorized Income</option>
                                <optgroup label="INCOME ACCOUNTS" id="income_" style="visibility: hidden">
                                    @foreach($incomes as $income)
                                        <option value="{{$income->account_name}}">{{$income->account_name}}</option>
                                    @endforeach
                                </optgroup>
                            @elseif($type == 1)
                                <option value="" selected>Uncategorized Expense</option>
                                <optgroup label="EXPENSE ACCOUNTS" id="expense_">
                                    @foreach($incomes as $income)
                                        <option value="{{$income->account_name}}">{{$income->account_name}}</option>
                                    @endforeach
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
                        <label for="notes_edit" style="font-size: 13px">Notes</label>
                        <textarea class="form-control" rows="5" id="notes_edit" style="width: 98%;background-color: #FFFFFF;border-style: none"></textarea>
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
                <button type="button" class="btn btn-default btn-block" style="margin-top: 5%;background-color: white;border: solid 1px red;color: #343a40" onclick="income_expense_account_close('edit_transaction')">Cancel</button>
            </div>
            <div class="col-sm-5">
                <button type="button" id="EditSaveBtn" onclick="updateEdtTransaction()" class="btn btn-block btn-info" style="margin-top: 5%;">Save</button>
            </div>
        </div>

    </div>
</div>

<div class="panel panel-default" style="position: fixed;bottom: 5%;right: 1%;z-index: 2;background-color: #f0f0f0;padding: 1%;display: none;border: solid 1px #C0C0C0;" id="edit_transaction_payment">
    <input type="hidden" id="payment_id">
    <div class="panel-heading" style="background-color: #f0f0f0;padding-left: 1%;padding-right: 1%;">
        <div class="row" style="margin-bottom: 0%;border-bottom: solid 1px #C0C0C0;">
            <div class="col-sm-9" style="font-size: 15px">
                <span>Edit transaction details</span>
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn btn-sm btn-default" style="float: right;background-color: transparent" onclick="income_expense_account_close('edit_transaction_payment')"><i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
    <div class="panel-body" style="height: 350px; overflow-y: auto;overflow-x: hidden;background-color:#f0f0f0">
        <div class="row" style="margin-top: 5%;margin-bottom: 4%;">
            <div class="col-sm-6 offset-3">
                <div class="progress" id="progress_transaction_edit_payment" style="display: none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="">
                        <span>Please wait<span class="dotdotdot"></span></span>
                    </div>
                </div>
                <div class="col-sm-6 offset-3">
                    <div id="errormsg_transaction_edit_payment" style="display: none;margin-bottom: 4%;text-align: center;font-size: 12px">
                    </div>
                </div>
            </div>

            <div class="col-sm-11" style="margin-left: 3%;">
                <fieldset style="background-color: #FFFFFF;padding: 0px">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="desc_transaction_edit">Description</label>
                        <input type="text" class="form-control" id="desc_transaction_edit_payment" style="background-color: #FFFFFF;border-style: none;width: 98%;">
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;margin-top: 2%;padding: 0px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="account_transaction">Account</label>
                                <select class="form-control js-example-basic-multiple" id="account_transaction_edit_payment" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                    <optgroup label="CASH AND BANK" style="font-size: 14px;">
                                        @foreach($cash_bank as $item)
                                            <option value="{{$item->account_name}}">{{$item->account_name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" style="border-left: solid 1px #C0C0C0">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="to">Date</label>
                                <div class="input-group" style="width: 90%;">
                                    <input type='text' datepicker-here id="to_edit_payment" data-position="left top" data-language='en' style="width: 90%;margin-left:0%;font-size: 13px;background-color: #FFFFFF;border-style: none" value="{{date('Y-m-d', time())}}" data-date-format="yyyy-mm-dd" />
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
                                <select class="form-control js-example-basic-multiple" id="D_W_edit_payment" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5" onchange="ChangeDWEdit(this.value)">
                                    <option value="Deposit">Deposit</option>
                                    <option value="withdrawal">withdrawal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" style="border-left: solid 1px #C0C0C0">
                            <div class="form-group" style="font-size: 14px;">
                                <label for="account_transaction_total" style="font-size: 13px">Total Amount</label>
                                <input type="text" class="form-control" id="account_transaction_total_edit_payment" style="background-color: #FFFFFF;border-style: none;width: 98%;text-align: right" value="0">
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;padding: 0px;margin-top: 2%">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="category" style="font-size: 13px">Category</label>
                        <select class="form-control js-example-basic-multiple" id="category_edit_payment" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                        </select>
                    </div>
                </fieldset>
                <fieldset style="background-color: #FFFFFF;padding: 0px;margin-top: 2%">
                    <div class="form-group" style="font-size: 14px;">
                        <label for="notes_edit_payment" style="font-size: 13px">Notes</label>
                        <textarea class="form-control" rows="5" id="notes_edit" style="width: 98%;background-color: #FFFFFF;border-style: none"></textarea>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer" style="border-top: solid 1px #C0C0C0">
        <div class="row">
            <div class="col-sm-8">
                <button type="button" class="btn btn-default btn-sm" style="margin-top: 5%;background-color: white;border: solid 1px red;color: #343a40" onclick="deleteTransaction()"><i class="fa fa-trash"></i> </button>
            </div>
            <div class="col-sm-4">
                <button type="button" class="btn btn-default btn-sm" style="margin-top: 10%;background-color: white;border: solid 1px red;color: #343a40" onclick="income_expense_account_close('edit_transaction_payment')">Cancel</button>
                <button type="button" id="EditSaveBtn" onclick="updateEdtTransactionPayment()" class="btn btn-sm btn-info" style="margin-top: 10%;">Save</button>
            </div>
        </div>