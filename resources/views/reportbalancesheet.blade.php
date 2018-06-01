<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row">
    <div class="col-sm-8 offset-2">

        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Balance sheet</h2>
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
            <fieldset class="col-sm-12" style="padding: 10px;background-color: white">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="email">As of</label>
                                    <div class="dropdown show" style="font-size: 14px;width: 100%">
                                        <a class="btn btn-default dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="default_all_report" style="background-color: transparent; border: solid 1px #C0C0C0;color: black;width: 100%;font-size: 14px" >
                                            {{date("Y",time())}}
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="overflow-y: auto;height: 350px;" id="selectRange">
                                            <h6 class="dropdown-header">CALENDER YEAR</h6>
                                            @for($i = 0;$i< 3;$i++)
                                                <?php
                                                $year = date('Y',time()) - $i;
                                                $start = $year.'-01-01';
                                                $end = $year.'-12-31';
                                                ?>
                                                <a class="dropdown-item" onclick='dateRange3("{{$start}}", "{{$end}}","{{$year}}")' style="font-size: 14px">{{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange3("{{$start}}", "{{$end}}","Q{{$z}} {{date("Y",time()) - $i}}")' style="font-size: 14px">Q{{$z}} {{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange3("{{$start}}", "{{$end}}","{{$month}} {{date("Y",time()) - $i}}")' style="font-size: 14px">{{$month}} {{date("Y",time()) - $i}}</a>
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
                                            <a class="dropdown-item" onclick='dateRange3("{{$monday}}", "{{$friday}}","This week")' style="font-size: 14px">This week</a>
                                            <a class="dropdown-item" onclick='dateRange3("{{$date_last_week_monday}}", "{{$date_last_week_friday}}","Previous week")' style="font-size: 14px">Previous week</a>
                                            <a class="dropdown-item" onclick='dateRange3("{{$date_4_weeks_back}}", "{{date('Y-m-d',time())}}","Last 4 weeks")' style="font-size: 14px">Last 4 weeks</a>
                                            <a class="dropdown-item" onclick='dateRange3("{{$last_30_day_back}}", "{{date('Y-m-d',time())}}","Last 30 days")' style="font-size: 14px">Last 30 days</a>
                                            <a class="dropdown-item" onclick='dateRange3("{{$last_60_day_back}}", "{{date('Y-m-d',time())}}","Last 60 days")' style="font-size: 14px">Last 60 days</a>
                                            <a class="dropdown-item" onclick='dateRange3("{{$last_90_day_back}}", "{{date('Y-m-d',time())}}","Last 90 days")' style="font-size: 14px">Last 90 days</a>
                                            <div class="dropdown-divider"></div>
                                            <h6 class="dropdown-header">CUSTOM</h6>
                                            <a class="dropdown-item" onclick='dateRange3("", "","Custom")' style="font-size: 14px">Custom</a>
                                        </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-4">
                                <div>
                                    <div class="btn-group" style="margin-top: 12%">
                                        <div class="input-group" style="width: 50%;float: right">
                                            <input type='text' style="width: 75%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%;" class="datepicker-here" id="end_report" data-position="right top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d",time())}}" />
                                            <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="row" style="margin-top: 5%">
            <div class="col-sm-4">
                <hr>
            </div>
            <div class="col-sm-4" >
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="margin-left: 20%">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="true">Summary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-details" role="tab" aria-controls="pills-details" aria-selected="false">Details</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-4">
                <hr>
            </div>

            <div class="col-sm-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade one show active" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
                        <table class="table table-bordered borderless" style="font-size: 14px;width: 100%">

                            <tr style="background-color: #FFFFFF;" class="border-0">
                                <th style="text-align: left">ACCOUNTS</th>
                                <th style="text-align: right">{{substr(date("M",time()),0,3)}} {{date("d",time())}}, {{date("Y",time())}}</th>
                            </tr>

                        <!-- assets -->
                            <tr style="background-color: #E0E7EB">
                                <th style="text-align: left">Assets</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left">Total Cash and Bank</td>
                                <td style="text-align: right">
                                    <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCashAndBank($start_,$end_,0);
                                        $sum_cash_and_bank = 0;
                                        foreach ($data as $item) {
                                            if (strpos("add",$item->operation) > -1){
                                                $sum_cash_and_bank = $sum_cash_and_bank + $item->amount;;
                                            }
                                            else if (strpos("less",$item->operation) > -1){
                                                $sum_cash_and_bank = $sum_cash_and_bank - $item->amount;
                                            }
                                        }
                                        echo ProductsAndServicesController::money($sum_cash_and_bank);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left">Total Other Current Assets</td>
                                <td style="text-align: right">
                                    <?php

                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCurrentAssets($start_,$end_,0);
                                    $sum_current_assets = 0;
                                    foreach ($data as $item) {
                                        if (strpos("add",$item->operation) > -1){
                                            $sum_current_assets = $sum_current_assets + $item->amount;
                                        }
                                        else if (strpos("less",$item->operation) > -1){
                                            $sum_current_assets = $sum_current_assets - $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_current_assets);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left">Total Long-term Assets</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeLongAssets($start_,$end_,0);
                                    $sum_long_assets = 0;
                                    foreach ($data as $item) {
                                        if (strpos("add",$item->operation) > -1){
                                            $sum_long_assets = $sum_long_assets + $item->amount;
                                        }
                                        else if (strpos("less",$item->operation) > -1){
                                            $sum_long_assets = $sum_long_assets - $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_long_assets);
                                    ?>
                                </td>
                            </tr>

                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left;font-weight: bold">Total Assets</td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                        $total_asset = $sum_cash_and_bank + $sum_current_assets + $sum_long_assets;
                                    echo ProductsAndServicesController::money($total_asset);
                                    ?>
                                </td>
                            </tr>

                        <!-- liabilities -->

                            <tr style="background-color: #E0E7EB">
                                <th style="text-align: left">Liabilities</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left">Total Current Liabilities</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCurrentLiabilities($start_,$end_,1);
                                    $sum_current_liabilities = 0;
                                    foreach ($data as $item) {
                                        if (strpos("add",$item->operation) > -1){
                                            $sum_current_liabilities = $sum_current_liabilities + $item->amount;
                                        }
                                        else if (strpos("less",$item->operation) > -1){
                                            $sum_current_liabilities = $sum_current_liabilities - $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_current_liabilities);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left">Total Long-term Liabilities</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeLongLiabilities($start_,$end_,1);
                                    $sum_long_liabilities = 0;
                                    foreach ($data as $item) {
                                        if (strpos("add",$item->operation) > -1){
                                            $sum_long_liabilities = $sum_long_liabilities + $item->amount;
                                        }
                                        else if (strpos("less",$item->operation) > -1){
                                            $sum_long_liabilities = $sum_long_liabilities - $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_long_liabilities);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left;font-weight: bold">Total Liabilities</td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                            $total_liabilities = $sum_long_liabilities + $sum_current_liabilities;
                                    echo ProductsAndServicesController::money($total_liabilities);
                                    ?>
                                </td>
                            </tr>

                        <!-- Equity -->

                            <tr style="background-color: #E0E7EB">
                                <th style="text-align: left">Equity</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left">Total Other Equity</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeOtherEquity($start_,$end_,1);
                                    $sum_other_equity = 0;
                                    foreach ($data as $item) {
                                        if (strpos("add",$item->operation) > -1){
                                            $sum_other_equity = $sum_other_equity + $item->amount;
                                        }
                                        else if (strpos("less",$item->operation) > -1){
                                            $sum_other_equity = $sum_other_equity - $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_other_equity);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left">Total Retained Earnings</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,3);


                                    $sum_income = 0;
                                    foreach ($data as $item) {
                                        $sum_income = $sum_income + $item->amount;
                                    }

                                    $COGS = 0;

                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,4);

                                    $sum_expenses = 0;
                                    foreach ($data as $item) {
                                        $sum_expenses = $sum_expenses + $item->amount;
                                    }

                                    $net_profit = $sum_income - $COGS - $sum_expenses;

                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeRetainedEquity($start_,$end_,2);
                                    $sum_owner_equity = 0;
                                    foreach ($data as $item){
                                        $sum_owner_equity = $sum_owner_equity + $item->amount;
                                    }

                                    $retain_earning = $net_profit + $sum_owner_equity;
                                    echo ProductsAndServicesController::money($retain_earning);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left;font-weight: bold">Total Equity</td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                        $total_equity = $retain_earning + $sum_other_equity;
                                        echo ProductsAndServicesController::money($total_equity);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade two" id="pills-details" role="tabpanel" aria-labelledby="pills-home-tab">
                        <table class="table table-bordered borderless" style="font-size: 14px;width: 100%">

                            <tr style="background-color: #FFFFFF;" class="border-0">
                                <th style="text-align: left">ACCOUNTS</th>
                                <th style="text-align: right">{{substr(date("M",time()),0,3)}} {{date("d",time())}}, {{date("Y",time())}}</th>
                            </tr>

                            <!-- assets -->
                            <tr style="background-color: #E0E7EB">
                                <th style="text-align: left">Assets</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <tr style="border-bottom: 0px solid #E0E7EB;background-color: #ECF0F3;">
                                <th style="text-align: left;text-indent: 20px">Cash and Bank</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCashAndBank($start_,$end_,0);
                            //dd($data);
                            $data_arr = array();
                            foreach ($data as $item){
                                array_push($data_arr,$item->account);
                            }
                            $account_cash_bank_arr = array();
                            $sum_cash_and_bank = 0;
                            $d = 0;

                            foreach ($data as $item){
                                $d = 0;
                               $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,0);
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                                if (!in_array(strtolower($item->account),$account_cash_bank_arr)){
                                    $sum_cash_and_bank = $sum_cash_and_bank + $sum;
                                    echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                                    echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>';
                                    if ($d == 1) echo "Sales";
                                    else echo $item->account;
                                    echo '</a></td>';
                                    echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                    echo '</tr>';
                                    array_push($account_cash_bank_arr,strtolower($item->account));
                                }
                            }
                            echo '<tr>';
                            echo '<td style="font-weight: bold;text-indent: 20px">Total Cash and Bank</td>';
                            echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_cash_and_bank).'</td>';
                            echo '</tr>';
                            ?>
                            <tr style="border-bottom: 0px solid #E0E7EB;background-color: #ECF0F3;">
                                <th style="text-align:left;text-indent: 20px">Current Assets</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCurrentAssets($start_,$end_,0);

                            $data_arr = array();
                            foreach ($data as $item){
                                array_push($data_arr,$item->account);
                            }
                            $account_current_asset_arr = array();
                            $sum_current_assets = 0;

                            foreach ($data as $item){
                                $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                                if (!in_array(strtolower($item->account),$account_current_asset_arr)){
                                    $sum_current_assets = $sum_current_assets + $sum;
                                    echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                                    echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                    echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                    echo '</tr>';
                                    array_push($account_current_asset_arr,strtolower($item->account));
                                }
                            }
                            echo '<tr>';
                            echo '<td style="font-weight: bold;text-indent: 20px">Total Other Current Assets</td>';
                            echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_current_assets).'</td>';
                            echo '</tr>';
                            ?>
                            <tr style="border-bottom: 0px solid #E0E7EB;;background-color: #ECF0F3;">
                                <th style="text-align: left;text-indent: 20px">Long-term Assets</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeLongAssets($start_,$end_,0);

                            $data_arr = array();
                            foreach ($data as $item){
                                array_push($data_arr,$item->account);
                            }
                            $account_long_asset_arr = array();
                            $sum_long_assets = 0;

                            foreach ($data as $item){
                                $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,0);
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                                if (!in_array($item->account,$account_long_asset_arr)){
                                    $sum_long_assets = $sum_long_assets + $sum;
                                    echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                                    echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                    echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                    echo '</tr>';
                                    array_push($account_long_asset_arr,$item->account);
                                }
                            }
                            echo '<tr>';
                            echo '<td style="font-weight: bold;text-indent: 20px">Total Long Assets</td>';
                            echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_long_assets).'</td>';
                            echo '</tr>';
                            ?>
                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left;font-weight: bold">Total Assets</td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $total_asset = $sum_cash_and_bank + $sum_current_assets + $sum_long_assets;
                                    echo ProductsAndServicesController::money($total_asset);
                                    ?>
                                </td>
                            </tr>

                            <!-- liabilities -->

                            <tr style="background-color: #E0E7EB">
                                <th style="text-align: left">Liabilities</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <tr style="border-bottom: 0px solid #E0E7EB;background-color: #ECF0F3;">
                                <th style="text-align: left;text-indent: 20px">Current Liabilities</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCurrentLiabilities($start_,$end_,1);

                            $data_arr = array();
                            foreach ($data as $item){
                                array_push($data_arr,$item->account);
                            }
                            $account_current_liabilities_arr = array();
                            $sum_current_liabilities = 0;
                            foreach ($data as $item){
                                $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,1);
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                                if (!in_array(strtolower($item->account),$account_current_liabilities_arr)){
                                    $sum_current_liabilities = $sum_current_liabilities + $sum;
                                    echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                                    echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                    echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                    echo '</tr>';
                                    array_push($account_current_liabilities_arr,strtolower($item->account));
                                }
                            }
                            echo '<tr>';
                            echo '<td style="font-weight: bold;text-indent: 20px">Total Current Liabilities</td>';
                            echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_current_liabilities).'</td>';
                            echo '</tr>';
                            ?>

                            <tr style="border-bottom: 0px solid #E0E7EB;background-color: #ECF0F3;">
                                <th style="text-align: left;text-indent: 20px">Long-term Liabilities</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeLongLiabilities($start_,$end_,1);
                            $data_arr = array();
                            foreach ($data as $item){
                                array_push($data_arr,$item->account);
                            }
                            $account_long_liabilities_arr = array();
                            $sum_long_liabilities = 0;

                            foreach ($data as $item){
                                $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,1);
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                                if (!in_array(strtolower($item->account),$account_long_liabilities_arr)){
                                    $sum_long_liabilities = $sum_long_liabilities + $sum;
                                    echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                                    echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                    echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                    echo '</tr>';
                                    array_push($account_long_liabilities_arr,strtolower($item->account));
                                }
                            }
                            echo '<tr style="text-indent: 20px">';
                            echo '<td style="font-weight: bold;text-indent: 20px">Total Long-term Liabilities</td>';
                            echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_long_liabilities).'</td>';
                            echo '</tr>';
                            ?>

                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left;font-weight: bold">Total Liabilities</td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $total_liabilities = $sum_long_liabilities + $sum_current_liabilities;
                                    echo ProductsAndServicesController::money($total_liabilities);
                                    ?>
                                </td>
                            </tr>

                            <!-- Equity -->

                            <tr style="background-color: #E0E7EB">
                                <th style="text-align: left">Equity</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <tr style="border-bottom: 0px solid #E0E7EB;;background-color: #ECF0F3;">
                                <th style="text-align: left;text-indent: 20px">Retained Earnings</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,3);

                            $sum_income = 0;
                            $val="";
                            foreach ($data as $item) {
                                $sum_income = $sum_income + $item->amount;
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                            }

                            $COGS = 0;

                            $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,4);

                            $sum_expenses = 0;
                            foreach ($data as $item) {
                                $sum_expenses = $sum_expenses + $item->amount;
                            }

                            $net_profit = $sum_income - $COGS - $sum_expenses;
                            $retain_earning = $net_profit;
                            $start_array = explode("-",$start_);
                            $end_array = explode("-",$end_);
                            $dateObj_month   = DateTime::createFromFormat('!m', $start_array[1]);
                            $dateObj_end   = DateTime::createFromFormat('!m', $end_array[1]);

                            echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                            echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>Profit between '.substr($dateObj_month->format('F'),0,3).' '.$start_array[2].', '.$start_array[0].' and '.substr($dateObj_end->format('F'),0,3).' '.$end_array[2].', '.$end_array[0].'</a></td>';
                            echo '<td style="text-align: right">'.ProductsAndServicesController::money($net_profit).'</td>';
                            echo '</tr>';
                            ?>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeRetainedEquity($start_,$end_,2);
                            $data_arr = array();
                            foreach ($data as $item){
                                array_push($data_arr,$item->account);
                            }
                            $account_retained_equity_arr = array();
                            $sum_retained_equity = 0;

                            foreach ($data as $item){
                                $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,2);
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                                if (!in_array(strtolower($item->account),$account_retained_equity_arr)){
                                    $sum_retained_equity = $sum_retained_equity + $sum;
                                    echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                                    echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                    echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                    echo '</tr>';
                                    array_push($account_retained_equity_arr,strtolower($item->account));
                                }
                            }
                            echo '<tr style="text-indent: 20px">';
                            echo '<td style="font-weight: bold;text-indent: 20px">Total Retained Earnings</td>';
                            echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_retained_equity+$retain_earning).'</td>';
                            echo '</tr>';
                            ?>
                            <tr style="border-bottom: 1px solid #E0E7EB;background-color: #ECF0F3;">
                                <th style="text-align: left;text-indent: 20px">Other Equity</th>
                                <th style="text-align: right"></th>
                            </tr>
                            <?php
                            $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeOtherEquity($start_,$end_,2);
                            $data_arr = array();
                            foreach ($data as $item){
                                array_push($data_arr,$item->account);
                            }
                            $account_other_equity_arr = array();
                            $sum_other_equity = 0;

                            foreach ($data as $item){
                                $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,2);
                                $val = "ledger/".$item->invoice_num."/".$item->account;
                                if (!in_array(strtolower($item->account),$account_long_liabilities_arr)){
                                    $sum_other_equity = $sum_other_equity + $sum;
                                    echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                                    echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                    echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                    echo '</tr>';
                                    array_push($account_other_equity_arr,strtolower($item->account));
                                }
                            }
                            echo '<tr style="text-indent: 20px">';
                            echo '<td style="font-weight: bold;text-indent: 20px">Total Other Equity</td>';
                            echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_other_equity).'</td>';
                            echo '</tr>';
                            ?>

                            <tr style="border-bottom: 1px solid #E0E7EB">
                                <td style="text-align: left;font-weight: bold">Total Equity</td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $total_equity = $retain_earning + $sum_other_equity+ $sum_retained_equity;
                                    echo ProductsAndServicesController::money($total_equity);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>