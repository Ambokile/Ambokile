<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row">
    <div class="col-sm-8 offset-2">

        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Account Transactions</h2>
            </div>
            <div class="col-sm-4">
                <div class="btn-group" style="margin-left: 65%;">
                    <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: solid 1px darkblue;background-color: white;">
                        Export<span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" style="font-size: 14px;">
                        <a class="dropdown-item" onclick="">CSV</a>
                        <a class="dropdown-item" onclick="">PDF</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="width: 100%;margin-left: 0%;font-size: 14px;color: #3C4858">
            <fieldset class="col-sm-12" style="padding: 20px;background-color: white">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                           <div class="col-sm-3">
                               <div style="font-size: 15px">
                                   Account
                               </div>
                               <div style="margin-top: 2%;">
                                   <select class="form-control js-example-basic-multiple" id="acccount_reconcile_transaction" style="width: 100%;" onchange="LoadReconcileTransaction()">
                                       <optgroup label="ALL ACCOUNTS">
                                           <option value="all account">all account</option>
                                       </optgroup>

                                       <optgroup label="ASSETS" style="font-size: 14px">
                                           @foreach($assets as $asset)
                                               <option @if (strpos($asset->account_name,$account) !== false) selected @endif value="{{$asset->account_name}}">{{$asset->account_name}}</option>
                                           @endforeach
                                       </optgroup>

                                       <optgroup label="LIABILITIES & CREDIT CARDS" style="font-size: 14px">
                                           @foreach($liabilities as $liability)
                                               <option @if (strpos($asset->account_name,$account) !== false) selected @endif value="{{$liability->account_name}}">{{$liability->account_name}}</option>
                                           @endforeach
                                       </optgroup>

                                       <optgroup label="INCOME" style="font-size: 14px">
                                           @foreach($incomes as $income)
                                               <option @if (strpos($asset->account_name,$account) !== false) selected @endif value="{{$income->account_name}}">{{$income->account_name}}</option>
                                           @endforeach
                                       </optgroup>

                                       <optgroup label="EXPENSES" style="font-size: 14px">
                                           @foreach($expenses as $expense)
                                               <option @if (strpos($asset->account_name,$account) !== false) selected @endif value="{{$expense->account_name}}">{{$expense->account_name}}</option>
                                           @endforeach
                                       </optgroup>

                                       <optgroup label="EQUITY" style="font-size: 14px">
                                           @foreach($equities as $equity)
                                               <option @if (strpos($asset->account_name,$account) !== false) selected @endif value="{{$equity->account_name}}">{{$equity->account_name}}</option>
                                           @endforeach
                                       </optgroup>
                                   </select>
                               </div>
                           </div>
                            <div class="col-sm-3">
                                <div style="font-size: 15px">
                                    Date Range
                                </div>
                                <div>
                                    <div class="dropdown show" style="font-size: 14px;width: 100%">
                                        <a class="btn btn-default dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="default_all_transaction_reconcile" style="background-color: transparent; border: solid 1px #C0C0C0;color: black;width: 100%;font-size: 14px" >
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
                                                <a class="dropdown-item" onclick='dateRange("{{$start}}", "{{$end}}","{{$year}}")' style="font-size: 14px">{{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange("{{$start}}", "{{$end}}","Q{{$z}} {{date("Y",time()) - $i}}")' style="font-size: 14px">Q{{$z}} {{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange("{{$start}}", "{{$end}}","{{$month}} {{date("Y",time()) - $i}}")' style="font-size: 14px">{{$month}} {{date("Y",time()) - $i}}</a>
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
                                                <a class="dropdown-item" onclick='dateRange("{{$monday}}", "{{$friday}}","This week")' style="font-size: 14px">This week</a>
                                                <a class="dropdown-item" onclick='dateRange("{{$date_last_week_monday}}", "{{$date_last_week_friday}}","Previous week")' style="font-size: 14px">Previous week</a>
                                                <a class="dropdown-item" onclick='dateRange("{{$date_4_weeks_back}}", "{{date('Y-m-d',time())}}","Last 4 weeks")' style="font-size: 14px">Last 4 weeks</a>
                                                <a class="dropdown-item" onclick='dateRange("{{$last_30_day_back}}", "{{date('Y-m-d',time())}}","Last 30 days")' style="font-size: 14px">Last 30 days</a>
                                                <a class="dropdown-item" onclick='dateRange("{{$last_60_day_back}}", "{{date('Y-m-d',time())}}","Last 60 days")' style="font-size: 14px">Last 60 days</a>
                                                <a class="dropdown-item" onclick='dateRange("{{$last_90_day_back}}", "{{date('Y-m-d',time())}}","Last 90 days")' style="font-size: 14px">Last 90 days</a>
                                            <div class="dropdown-divider"></div>
                                            <h6 class="dropdown-header">CUSTOM</h6>
                                            <a class="dropdown-item" onclick='dateRange("", "","Custom")' style="font-size: 14px">Custom</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div>
                                    <div class="btn-group" style="margin-top: 10%">
                                        <div class="input-group" style="width: 50%;float: right">
                                            <input type='text' style="width: 65%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%;" class="datepicker-here" id="from_reconcile" data-position="right top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="{{$from}}" onkeyup="dateRange($('#from_reconcile').val(),  $('#to_reconcile').val(),'Custom')"/>
                                            <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </div>
                                        </div>

                                        <div class="input-group" style="width: 50%;float: right">
                                            <input type='text' style="width: 65%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" class="datepicker-here" id="to_reconcile" data-position="left top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="{{$to}}" onkeyup="dateRange($('#from_reconcile').val(), $('#to_reconcile').val(),'Custom')" />
                                            <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 2%">
                            <div class="col-sm-3">
                                <div style="font-size: 15px">
                                    Report Type
                                </div>
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="report_type_reconcile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent; border: solid 1px #C0C0C0;color: black;font-size: 14px;width: 100%;">Accrual (Paid & Unpaid)</button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="report_reconcile_transaction">
                                            <a class="dropdown-item" onclick="ReportType('Accrual (Paid & Unpaid)')" style="font-size: 14px;width: 100%;">Accrual (Paid & Unpaid)</a>
                                            <a class="dropdown-item" onclick="ReportType('Cash & Cash Equivalents')" style="font-size: 14px;width: 100%;">Cash & Cash Equivalents</a>
                                            <a class="dropdown-item" onclick="ReportType('Cash Only')" style="font-size: 14px;width: 100%;">Cash Only</a>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-sm-4">
                                <div style="font-size: 15px">
                                    Contact
                                </div>
                                <div style="margin-top: 2%;">
                                    <select class="form-control js-example-basic-multiple" id="contact_reconcile_transaction" onchange="LoadReconcileTransaction()">
                                        <optgroup label="ALL CONTACTS" style="font-size: 14px">
                                            <option ="all contacts">all contacts</option>
                                        </optgroup>

                                        <optgroup label="CUSTOMERS" >
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </optgroup>

                                        <optgroup label="VENDORS">
                                            @foreach($vendors as $vendor)
                                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                            @endforeach
                                        </optgroup>

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div style="margin-top: 8%;float: right">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="row" style="margin-top: 2.5%;">
            <div class="col-sm-12" style="" id="account_transaction_list_div">
                @if(count($transactions) < 1)
                        <div style="width: 100%;text-align: center;color: #138496;font-size: 14px;font-weight: bold;margin-top: 5%;">
                            <i class="fa fa-thumbs-o-up" style="font-size: 120px"></i> <br />
                            <span style="font-size: 18px;">No results were found.</span><br />
                            Try choosing a different date range or account.
                        </div>
                @else
                            <table class="table table-striped table-bordered borderless" style="font-size: 14px">
                            <tr style="background-color: transparent;border-style: none;">
                                <th scope="col" style="text-align: left">DATE</th>
                                <th scope="col" style="text-align: right">DESCRIPTION</th>
                                <th scope="col" style="text-align: right">DEBIT</th>
                                <th scope="col" style="text-align: right">CREDIT</th>
                                <th scope="col" style="text-align: right">BALANCE</th>
                            </tr>
                            <tr style="background-color: #E0E7EB">
                                <td colspan="5">
                                    <h6 style="color: black;text-indent: 12px">{{$account}}</h6>
                                    <div style="text-indent: 12px"><small>Under: <?php
                                            echo ProductsAndServicesController::getAccountChart($account)
                                            ?> > {{$account}}</small></div>
                                </td>
                            </tr>
                            <?php
                            $total = 0; $total_dr = 0; $total_cr = 0;
                            $start_balance = ProductsAndServicesController::getStartingBalance($date,$from,$account);
                            if ($start_balance != 0)
                                $total = $start_balance;
                            ?>
                            <tr style="background-color: #ECF0F3;border-style: none;">
                                <td colspan="4">Starting Balance</td>
                                <td style="text-align: right"><?php echo ProductsAndServicesController::money($start_balance); ?></td>
                            </tr>
                            @foreach($transactions as $transaction)
                                <tr style="background-color: transparent;border-style: none;">
                                    <td>
                                        <?php
                                        $arr = explode("-", $transaction->date);
                                        $m = $arr[1];
                                        $d = $arr[2];
                                        $y = $arr[0];

                                        $dt = DateTime::createFromFormat('!m', $m);
                                        $mo = $dt->format('F');
                                        $mo_sh = substr($mo,0,3);
                                        echo $mo_sh." ".$d.", ".$y;
                                        ?>
                                    </td>

                                    <td>{{$transaction->description}}</td>
                                    @if(strpos($transaction->operation,"withdrawal") !== false || strpos($transaction->operation,"payment_out") !== false)
                                        <?php
                                        $total = $total - $transaction->amount;
                                        $total_cr =  $total_cr + $transaction->amount;
                                        ?>
                                        <td style="text-align: right"></td>

                                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                                    @elseif(strpos($transaction->operation,"Deposit") !== false || strpos($transaction->operation,"payment_in") !== false)
                                        <?php
                                        $total = $total + $transaction->amount;
                                        $total_dr =  $total_dr + $transaction->amount;
                                        ?>
                                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                                        <td style="text-align: right"></td>
                                    @endif
                                    <td style="text-align: right"><?php echo ProductsAndServicesController::money($total); ?></td>
                                </tr>
                            @endforeach
                            <tr style="background-color: #ECF0F3;border-style: none;">
                                <td colspan="2">Totals and Ending Balance</td>
                                <td style="text-align: right"><?php echo ProductsAndServicesController::money($total_dr); ?></td>
                                <td style="text-align: right"><?php echo ProductsAndServicesController::money($total_cr) ?></td>
                                <td style="text-align: right"><?php echo ProductsAndServicesController::money($total); ?></td>
                            </tr>
                            <tr style="background-color: #E0E7EB">
                                <td colspan="4">
                                    <h6 style="color: black;text-indent: 12px">Balance Change</h6>
                                    <div style="text-indent: 12px"><small>Difference between starting and ending balances</small></div>
                                </td>
                                <td style="text-align: right"><?php echo ProductsAndServicesController::money($total); ?></td>
                            </tr>
                        </table>
                @endif
            </div>
        </div>

    </div>
</div>