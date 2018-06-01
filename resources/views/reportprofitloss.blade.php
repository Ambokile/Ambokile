<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row">
    <div class="col-sm-8 offset-2">

        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Profit & Loss</h2>
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
                                    <label for="email">Date Range</label>
                                    <div class="dropdown show" style="font-size: 14px;width: 100%">
                                        <a class="btn btn-default dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="default_all_report_profit_loss" style="background-color: transparent; border: solid 1px #C0C0C0;color: black;width: 100%;font-size: 14px" >
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
                                                <a class="dropdown-item" onclick='dateRange4("{{$start}}", "{{$end}}","{{$year}}")' style="font-size: 14px">{{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange4("{{$start}}", "{{$end}}","Q{{$z}} {{date("Y",time()) - $i}}")' style="font-size: 14px">Q{{$z}} {{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange4("{{$start}}", "{{$end}}","{{$month}} {{date("Y",time()) - $i}}")' style="font-size: 14px">{{$month}} {{date("Y",time()) - $i}}</a>
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
                                            <a class="dropdown-item" onclick='dateRange4("{{$monday}}", "{{$friday}}","This week")' style="font-size: 14px">This week</a>
                                            <a class="dropdown-item" onclick='dateRange4("{{$date_last_week_monday}}", "{{$date_last_week_friday}}","Previous week")' style="font-size: 14px">Previous week</a>
                                            <a class="dropdown-item" onclick='dateRange4("{{$date_4_weeks_back}}", "{{date('Y-m-d',time())}}","Last 4 weeks")' style="font-size: 14px">Last 4 weeks</a>
                                            <a class="dropdown-item" onclick='dateRange4("{{$last_30_day_back}}", "{{date('Y-m-d',time())}}","Last 30 days")' style="font-size: 14px">Last 30 days</a>
                                            <a class="dropdown-item" onclick='dateRange4("{{$last_60_day_back}}", "{{date('Y-m-d',time())}}","Last 60 days")' style="font-size: 14px">Last 60 days</a>
                                            <a class="dropdown-item" onclick='dateRange4("{{$last_90_day_back}}", "{{date('Y-m-d',time())}}","Last 90 days")' style="font-size: 14px">Last 90 days</a>
                                            <div class="dropdown-divider"></div>
                                            <h6 class="dropdown-header">CUSTOM</h6>
                                            <a class="dropdown-item" onclick='dateRange4("", "","Custom")' style="font-size: 14px">Custom</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <?php
                                    $year_ = date('Y',time());
                                    $start_ = $year_.'-01-01';
                                    $end_ = date('Y-m-d',time());
                                ?>
                                <div>
                                    <div class="btn-group" style="margin-top: 11%">
                                        <div class="input-group" style="width: 50%;float: right">
                                            <input type='text' style="width: 65%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%;" class="datepicker-here" id="from_report_profit_loss" data-position="right top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="{{$start_}}" onkeyup="" onchange=""/>
                                            <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="input-group" style="width: 50%;float: right">
                                            <input type='text' style="width: 65%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" class="datepicker-here" id="to_report_profit_loss" data-position="left top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="{{$end_}}" onkeyup="" onchange="" />
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
            <div class="col-sm-12" >
               <div class="row">
                   <div class="col-sm-4">
                       <hr>
                   </div>
                   <div class="col-sm-4">
                       <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="margin-left: 20%">
                           <li class="nav-item">
                               <a class="nav-link active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="false">Summary</a>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-details" role="tab" aria-controls="pills-details" aria-selected="true">Details</a>
                           </li>
                       </ul>
                   </div>
                   <div class="col-sm-4">
                       <hr>
                   </div>
               </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade one show active" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
                        <table class="table table-striped table-bordered borderless" style="font-size: 14px;width: 100%">
                            <tr style="background-color: transparent;">
                                <th scope="col" style="text-align: left;vertical-align: bottom">ACCOUNTS</th>
                                <th scope="col" style="text-align: right"><?php
                                    $arr = explode("-",$start_);
                                    $m = $arr[1];
                                    $d = $arr[2];
                                    $y = $arr[0];
                                    $dt = DateTime::createFromFormat('!m', $m);
                                    $mo = $dt->format('F');
                                    $mo_sh = substr($mo,0,3);

                                    echo "<div>".$mo_sh." ".$d.", ".$y."</div>";

                                    $arr = explode("-",$end_);
                                    $m = $arr[1];
                                    $d = $arr[2];
                                    $y = $arr[0];
                                    $dt = DateTime::createFromFormat('!m', $m);
                                    $mo = $dt->format('F');
                                    $mo_sh = substr($mo,0,3);

                                    echo "<div> to ".$mo_sh." ".$d.", ".$y."</div>"
                                    ?></th>
                            </tr>
                            <tr>
                                <td>Income</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,3);


                                    $sum_income = 0;
                                    foreach ($data as $item) {
                                        $sum_income = $sum_income + $item->amount;
                                    }
                                    echo ProductsAndServicesController::money($sum_income);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: transparent">
                                <td>Cost of Goods Sold</td>
                                <td style="text-align: right">
                                    <?php
                                    $COGS = 0;
                                    echo ProductsAndServicesController::money($COGS);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td><span style="font-weight: bold">Gross Profit</span><div><small>As a percentage of Total Income</small></div></td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $Gross_profit = $sum_income - $COGS;
                                    echo ProductsAndServicesController::money($Gross_profit);
                                    ?>
                                    <div><small><?php
                                            if ($sum_income < 1){
                                                $percent = 0;
                                            }
                                            else{
                                                $percent = ($Gross_profit/$sum_income)*100;
                                            }
                                            echo number_format($percent, 2, '.', '')."%";
                                            ?></small></div></td>
                            </tr>

                            <tr style="background-color: transparent">
                                <td>Operating Expenses</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,4);

                                    $sum_expenses = 0;
                                    foreach ($data as $item) {
                                        $sum_expenses = $sum_expenses + $item->amount;
                                    }
                                    echo ProductsAndServicesController::money($sum_expenses);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td><span style="font-weight: bold">Net @if($sum_income - $sum_expenses > 0) Profit @else Loss @endif</span><div><small>As a percentage of Total Income</small></div></td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $net_profit  = $sum_income - $sum_expenses;
                                    echo ProductsAndServicesController::money(abs($net_profit));
                                    ?><div><small><?php
                                            if ($sum_income < 1){
                                                $percent = 0;
                                            }
                                            else{
                                                $percent = ($net_profit/$sum_income)*100;
                                            }
                                            echo number_format($percent, 2, '.', '')."%";
                                            ?></small></div></td>
                            </tr>

                        </table>
                    </div>
                    <div class="tab-pane fade two" id="pills-details" role="tabpanel" aria-labelledby="pills-home-tab">
                        <table class="table table-striped table-bordered borderless" style="font-size: 14px;width: 100%">
                            <tr style="background-color: transparent;">
                                <th scope="col" style="text-align: left;vertical-align: bottom">ACCOUNTS</th>
                                <th scope="col" style="text-align: right"><?php
                                    $arr = explode("-",$start_);
                                    $m = $arr[1];
                                    $d = $arr[2];
                                    $y = $arr[0];
                                    $dt = DateTime::createFromFormat('!m', $m);
                                    $mo = $dt->format('F');
                                    $mo_sh = substr($mo,0,3);

                                    echo "<div>".$mo_sh." ".$d.", ".$y."</div>";

                                    $arr = explode("-",$end_);
                                    $m = $arr[1];
                                    $d = $arr[2];
                                    $y = $arr[0];
                                    $dt = DateTime::createFromFormat('!m', $m);
                                    $mo = $dt->format('F');
                                    $mo_sh = substr($mo,0,3);

                                    echo "<div> to ".$mo_sh." ".$d.", ".$y."</div>"
                                    ?></th>
                            </tr>
                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Income</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,3);
                                        $account_arr = array();
                                        $sum_account_income = 0;
                                        foreach ($data as $item){
                                            $sum_account_income = $sum_account_income + $item->amount;
                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money(ProductsAndServicesController::sumInvoiceItem($data,$item->account)).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }
                                        }
                                        echo '<tr>';
                                        echo '<td style="font-weight: bold">Total Income</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_income).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: transparent">
                                <?php
                                $COGS = 0;
                                ?>
                                <td>Cost of Goods Sold</td>
                                <td style="text-align: right">0.00</td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td><span style="font-weight: bold">Gross Profit</span><div><small>As a percentage of Total Income</small></div></td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $Gross_profit_sumary = $sum_account_income - $COGS;
                                    echo ProductsAndServicesController::money($Gross_profit_sumary);
                                    ?>
                                    <div><small><?php
                                            if ($sum_account_income < 1){
                                                $percent = 0;
                                            }
                                            else{
                                                $percent = ($Gross_profit_sumary/$sum_account_income)*100;
                                            }
                                            echo number_format($percent, 2, '.', '')."%";
                                            ?></small></div></td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Operating Expenses</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,4);
                                        $account_arr = array();
                                        $sum_account_expenses = 0;
                                        foreach ($data as $item){
                                            $sum_account_expenses = $sum_account_expenses + $item->amount;
                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money(ProductsAndServicesController::sumBillItem($data,$item->account)).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }
                                        }
                                        echo '<tr style="background-color: transparent">';
                                        echo '<td style="font-weight: bold">Total Operating Expenses</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_expenses).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td><span style="font-weight: bold">Net @if($sum_account_income - $sum_account_expenses < 0) Loss @else Profit @endif</span><div><small>As a percentage of Total Income</small></div></td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $net_profit  = $sum_account_income - $sum_account_expenses;
                                    echo ProductsAndServicesController::money(abs($net_profit));
                                    ?>
                                    <div><small>
                                            <?php
                                            if ($sum_account_income < 1){
                                                $percent = 0;
                                            }
                                            else{
                                                $percent = ($net_profit/$sum_account_income)*100;
                                            }
                                            echo number_format($percent, 2, '.', '')."%";
                                            ?>
                                        </small></div></td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>