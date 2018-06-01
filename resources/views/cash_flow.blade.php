<?php use App\Http\Controllers\ProductsAndServicesController;
 $cash_inflow = 0;
 $cash_outflow = 0;

$cash_inflow_sum = 0;
$cash_outflow_sum = 0;
?>
<div class="row">
    <div class="col-sm-8 offset-2">

        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Cash Flow</h2>
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
                                        <a class="btn btn-default dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="default_all_report_cash_flow" style="background-color: transparent; border: solid 1px #C0C0C0;color: black;width: 100%;font-size: 14px" >
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
                                                <a class="dropdown-item" onclick='dateRange5("{{$start}}", "{{$end}}","{{$year}}")' style="font-size: 14px">{{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange5("{{$start}}", "{{$end}}","Q{{$z}} {{date("Y",time()) - $i}}")' style="font-size: 14px">Q{{$z}} {{date("Y",time()) - $i}}</a>
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
                                                    <a class="dropdown-item" onclick='dateRange5("{{$start}}", "{{$end}}","{{$month}} {{date("Y",time()) - $i}}")' style="font-size: 14px">{{$month}} {{date("Y",time()) - $i}}</a>
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
                                            <a class="dropdown-item" onclick='dateRange5("{{$monday}}", "{{$friday}}","This week")' style="font-size: 14px">This week</a>
                                            <a class="dropdown-item" onclick='dateRange5("{{$date_last_week_monday}}", "{{$date_last_week_friday}}","Previous week")' style="font-size: 14px">Previous week</a>
                                            <a class="dropdown-item" onclick='dateRange5("{{$date_4_weeks_back}}", "{{date('Y-m-d',time())}}","Last 4 weeks")' style="font-size: 14px">Last 4 weeks</a>
                                            <a class="dropdown-item" onclick='dateRange5("{{$last_30_day_back}}", "{{date('Y-m-d',time())}}","Last 30 days")' style="font-size: 14px">Last 30 days</a>
                                            <a class="dropdown-item" onclick='dateRange5("{{$last_60_day_back}}", "{{date('Y-m-d',time())}}","Last 60 days")' style="font-size: 14px">Last 60 days</a>
                                            <a class="dropdown-item" onclick='dateRange5("{{$last_90_day_back}}", "{{date('Y-m-d',time())}}","Last 90 days")' style="font-size: 14px">Last 90 days</a>
                                            <div class="dropdown-divider"></div>
                                            <h6 class="dropdown-header">CUSTOM</h6>
                                            <a class="dropdown-item" onclick='dateRange5("", "","Custom")' style="font-size: 14px">Custom</a>
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
                                            <input type='text' style="width: 65%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%;" class="datepicker-here" id="from_report_cash_flow" data-position="right top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="{{$start_}}" onkeyup="" onchange=""/>
                                            <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="input-group" style="width: 50%;float: right">
                                            <input type='text' style="width: 65%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" class="datepicker-here" id="to_report_cash_flow" data-position="left top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd" value="{{$end_}}" onkeyup="" onchange="" />
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
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade one @if($active == 1) show active @endif" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
                        <table class="table table-bordered borderless" style="font-size: 14px;width: 100%">
                            <tr style="background-color: transparent;">
                                <th scope="col" style="text-align: left;vertical-align: bottom">CASH INFLOW AND OUTFLOW</th>
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
                            <tr style="background-color: #E0E7EB">
                                <th colspan="2">Operating Activities</th>
                            </tr>
                            <tr style="border-bottom: 1px solid #C0C0C0">
                                <td>Sales</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeSales($start_,$end_,3);

                                    $sum_income = 0;
                                    foreach ($data as $item) {
                                        $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,0);
                                            $dt = ProductsAndServicesController::contribution_sales($item->invoice_num);
                                            $paid = ProductsAndServicesController::GetPayment($item->invoice_num);
                                            if (ProductsAndServicesController::sales_tax($item->invoice_num) > 0 && $paid > 0 && $paid < $dt[count($dt) - 1]){
                                                if (strpos($item->operation,"add") !== false){
                                                    $sum_income = $sum_income + $item->amount;
                                                    $cash_inflow = $cash_inflow + $item->amount;
                                                }
                                                else{
                                                    $sum_income = $sum_income - $item->amount;
                                                    $cash_outflow = $cash_outflow + $item->amount;
                                                }

                                            }
                                    }

                                    echo ProductsAndServicesController::money($sum_income);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: transparent;border-bottom: 1px solid #C0C0C0">
                                <td>Purchases</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypePurchases($start_,$end_,4);
                                    $sum_expenses = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_expenses = $sum_expenses + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_expenses = $sum_expenses - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_expenses);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: transparent;border-bottom: 1px solid #C0C0C0">
                                <td>Inventory</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeInventory($start_,$end_,0);
                                    $sum_inventory = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_inventory = $sum_inventory + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_inventory = $sum_inventory - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_inventory);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: transparent;border-bottom: 1px solid #C0C0C0">
                                <td>Payroll</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypePayroll($start_,$end_,1);
                                    $sum_payroll = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_payroll = $sum_payroll + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_payroll = $sum_payroll - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_payroll);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: transparent;border-bottom: 1px solid #C0C0C0">
                                <td>Sales Taxes</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeSalesTax($start_,$end_,1);
                                    $sum_salestax = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_salestax = $sum_salestax + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_salestax = $sum_salestax - $item->amount;
                                            $cash_outflow_sum  = $cash_outflow_sum + $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_salestax);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: transparent;border-bottom: 1px solid #C0C0C0">
                                <td>Other</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeOther($start_,$end_,1);
                                    $sum_other = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_other = $sum_other + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_other = $sum_other - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }
                                    }
                                    echo ProductsAndServicesController::money($sum_other);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td>Net Cash from Operating Activities</td>
                                <td style="text-align: right;font-weight: bold;border-bottom: 4px solid #C0C0C0;border-top: 4px solid #C0C0C0">
                                    <?php
                                     $net_operating = $sum_income + $sum_expenses + $sum_inventory + $sum_salestax + $sum_payroll + $sum_other;
                                    echo ProductsAndServicesController::money($net_operating);

                                     ?></td>
                            </tr>

                            <tr style="background-color: #E0E7EB">
                                <th colspan="2">Investing Activities</th>
                            </tr>

                            <tr style="border-bottom: 1px solid #C0C0C0">
                                <td>Property, Plant, Equipment</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypePPE($start_,$end_,0);

                                    $sum_ppe = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_ppe = $sum_ppe + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_ppe = $sum_ppe - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }

                                    }
                                    echo ProductsAndServicesController::money($sum_ppe);
                                    ?>
                                </td>
                            </tr>

                            <tr style="border-bottom: 1px solid #C0C0C0">
                                <td>Other</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeInvestingAssetOther($start_,$end_,0);

                                    $sum_longasset_other = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_longasset_other = $sum_longasset_other + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_longasset_other = $sum_longasset_other - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }

                                    }
                                    echo ProductsAndServicesController::money($sum_longasset_other);
                                    ?>
                                </td>
                            </tr>
                            <tr style="background-color: #F2F2F2">
                                <td>Net Cash from Investing Activities</td>
                                <td style="text-align: right;font-weight: bold;border-bottom: 4px solid #C0C0C0;border-top: 4px solid #C0C0C0">
                                    <?php
                                    $net_investing_profit = $sum_ppe + $sum_longasset_other;
                                    echo ProductsAndServicesController::money($net_investing_profit);

                                    ?></td>
                            </tr>

                            <tr style="background-color: #E0E7EB">
                                <th colspan="2">Financing Activities</th>
                            </tr>
                            <tr style="border-bottom: 1px solid #C0C0C0">
                                <td>Loans and Lines of Credit</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeLoansLinesofCredit($start_,$end_,1);

                                    $sum_loan = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_loan = $sum_loan + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_loan = $sum_loan - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }

                                    }
                                    echo ProductsAndServicesController::money($sum_loan);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #C0C0C0">
                                <td>Owners and Shareholders</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeEquityCashFlow($start_,$end_,2);

                                    $sum_equity = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_equity = $sum_equity + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_equity = $sum_equity - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }

                                    }
                                    echo ProductsAndServicesController::money($sum_equity);
                                    ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #C0C0C0">
                                <td>Other</td>
                                <td style="text-align: right">
                                    <?php
                                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeEquityOther($start_,$end_,2);

                                    $sum_equity_other = 0;
                                    foreach ($data as $item) {
                                        if (strpos($item->operation,"add") !== false){
                                            $sum_equity_other = $sum_equity_other + $item->amount;
                                            $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                                        }
                                        else{
                                            $sum_equity_other = $sum_equity_other - $item->amount;
                                            $cash_outflow_sum = $cash_outflow_sum + $item->amount;
                                        }

                                    }
                                    echo ProductsAndServicesController::money($sum_equity_other);
                                    ?>
                                </td>
                            </tr>
                            <tr style="background-color: #F2F2F2">
                                <td>Net Cash from Financing Activities</td>
                                <td style="text-align: right;font-weight: bold;border-bottom: 4px solid #C0C0C0;border-top: 4px solid #C0C0C0">
                                    <?php
                                    $net_financing_profit = $sum_loan +  $sum_equity + $sum_equity_other;
                                    echo ProductsAndServicesController::money($net_financing_profit);

                                    ?></td>
                            </tr>
                        </table>

                        <table class="table table-striped table-bordered borderless" style="font-size: 14px;width: 100%">
                            <tr style="background-color: transparent;">
                                <th scope="col" style="text-align: left;vertical-align: bottom">OVERVIEW</th>
                                <th scope="col" style="text-align: right">&nbsp;</th>
                            </tr>
                            <tr style="background-color: #E0E7EB;border-bottom: solid 1px #C0C0C0;">
                                <th>Starting Balance</th>
                                <th style="text-align: right">TZS0<div><small>As of 2018-01-01</small></div></th>
                            </tr>
                            <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                <td style="font-weight: bold">Cash Inflow</td>
                                <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_inflow_sum); ?></td>
                            </tr>
                            <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                <td style="font-weight: bold">Cash Outflow</td>
                                <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_outflow_sum); ?></td>
                            </tr>
                            <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                <td style="font-weight: bold">Net Cash Change</td>
                                <td style="text-align: right;font-weight: bold;border-top: solid 2px #C0C0C0;border-bottom: solid 2px #C0C0C0;"><?php echo ProductsAndServicesController::money($cash_inflow_sum - $cash_outflow_sum); ?></td>

                            <tr>
                                <td colspan="2" style="font-weight: bold">Ending Balance</td>
                            </tr>

                            <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                <td style="font-weight: bold">Cash on Hand</td>
                                <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_inflow_sum - $cash_outflow_sum); ?></td>
                            </tr>
                            <tr style="background-color: transparent;">
                                <td style="font-weight: bold">Total Ending Balance</td>
                                <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_inflow_sum - $cash_outflow_sum); ?><div><small style="color: #343a40">As of <?php echo $end_?></small></div></td>
                            </tr>

                        </table>
                    </div>
                    <div class="tab-pane fade two @if($active == 2) show active @endif" id="pills-details" role="tabpanel" aria-labelledby="pills-home-tab">
                        <table class="table table-striped table-bordered borderless" style="font-size: 14px;width: 100%">
                            <tr style="background-color: transparent;">
                                <th scope="col" style="text-align: left;vertical-align: bottom">CASH INFLOW AND OUTFLOW</th>
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
                                <th colspan="2">Operating Activities</th>
                            </tr>
                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Sales</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeSales($start_,$end_,3);

                                        //dd($data);
                                        $account_arr = array();
                                        $sum_account_income = 0;
                                        $sum = 0;$total=0;
                                        $invoice_num = 0;
                                        $cash_inflow_sales = 0;
                                        $cash_outflow_sales = 0;
                                        $cash_inflow = 0;
                                        $cash_outflow = 0;
                                        $sum_arr = array();

                                        foreach ($data as $item){
                                            $invoice_num = $item->invoice_num;
                                            $dt = ProductsAndServicesController::contribution_sales($item->invoice_num);

                                            $paid = ProductsAndServicesController::GetPayment($item->invoice_num);

                                            $total = ProductsAndServicesController::getTotalAccountReceivable($item->invoice_num);

                                            if (ProductsAndServicesController::sales_tax($item->invoice_num) > 0 && $paid > 0 && $paid < $total){

                                                $sum = ProductsAndServicesController::getSumArrayNum($data,$item->account,$item->invoice_num);

                                                $sum = ($sum/$total) * $paid;

                                                $sum_arr = ProductsAndServicesController::getInOutFlow($data,$item->invoice_num);

                                                //dd($sum_arr);
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->invoice_num,$account_arr)){

                                                $sum_account_income = $sum_account_income + $sum;
                                                $cash_inflow_sales = $cash_inflow_sales + (($sum_arr['in']/$total) * $paid);
                                                $cash_outflow_sales = $cash_outflow_sales + (($sum_arr['out']/$total) * $paid);
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                if (strpos($item->account_type,"Expected Payment from customers")  !== false ){
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                                                }
                                                else{
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                }
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money($sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->invoice_num);
                                            }

                                        }
                                        ?>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCashSales($start_,$end_,0);

                                       // dd($data);

                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                            $sum_arr = ProductsAndServicesController::getInOutFlow($data,$item->invoice_num);

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                if (strpos($item->account_type,"Expected Payment from customers")  !== false ){
                                                    $sum_account_income = $sum_account_income + -($sum);
                                                    $cash_inflow_sales = $cash_inflow_sales + ($sum_arr['out']);
                                                    $cash_outflow_sales = $cash_outflow_sales + ($sum_arr['in']);

                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                }
                                                else{
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                }
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money(-($sum)).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }

                                        }
                                        echo '<tr>';
                                        echo '<td style="font-weight: bold">Total Sales</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_income).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>
{{$cash_inflow_sales." ".$cash_outflow_sales}}
                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Purchases</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypePurchases($start_,$end_,4);
                                        // dd($data);
                                        $total = ProductsAndServicesController::getSumMoney($data);
                                        $account_arr = array();
                                        $sum_account_expenses = 0;
                                        $amount_should = 0;
                                        $amount_rem = 0;
                                        $should_paid = 0;
                                        $z = 0;
                                        $billNo = 0;
                                        $cash_outflow_purchases = 0;
                                        $cash_inflow_purchases = 0;
                                        foreach ($data as $item){

                                            if ($item->transaction_type == 4 && !empty($item->invoice_num)){
                                                $billNo = ProductsAndServicesController::getBillItemNo($item->invoice_num);
                                                $paid = ProductsAndServicesController::getAmountPaid($item->invoice_num);
                                                if ($z == 0) $amount_rem = $paid;
                                                $item_sum = ProductsAndServicesController::getSumArray($data,$item->account);

                                                if ($amount_rem < $item->amount){
                                                    if ($billNo < 1){
                                                        $paid_now = 0;
                                                    }
                                                    else{
                                                        $paid_now = $paid/$billNo;
                                                    }
                                                    $should_paid = $amount_rem;
                                                    $amount_rem = $amount_rem - $should_paid;
                                                    $sum = $should_paid;
                                                }
                                                else{
                                                    $sum = $item->amount;
                                                    $amount_rem = $amount_rem - $item->amount;
                                                }
                                            }
                                            else{
                                                $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                            }


                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_expenses = $sum_account_expenses + $item->amount;
                                                $cash_inflow_purchases = $cash_inflow_purchases + $item->amount;
                                            }
                                            else{
                                                $sum_account_expenses = $sum_account_expenses - $item->amount;
                                                $cash_outflow_purchases = $cash_outflow_purchases + $item->amount;
                                            }


                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array(strtolower($item->account),$account_arr)){

                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money($sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,strtolower($item->account));
                                            }

                                            $z++;
                                            if ($billNo == $z) $z = 0;
                                        }
                                        echo '<tr style="background-color: transparent">';
                                        echo '<td style="font-weight: bold">Total Purchases</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_expenses).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Inventory</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeInventory($start_,$end_,0);


                                        $account_arr = array();
                                        $sum_account_inventory = 0;
                                        $cash_inflow_inventory = 0;
                                        $cash_outflow_inventory = 0;
                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArrayCategory($data,$item->category);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_inventory = $sum_account_inventory + $item->amount;
                                                $cash_inflow_inventory = $cash_inflow_inventory + $item->amount;
                                            }
                                            else{
                                                $sum_account_inventory = $sum_account_inventory - $item->amount;
                                                $cash_outflow_inventory = $cash_outflow_inventory + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->category,$account_arr)){
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money($sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->category);
                                            }
                                        }
                                        echo '<tr style="background-color: transparent">';
                                        echo '<td style="font-weight: bold">Total Inventory</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_inventory).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Payrolly</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypePayroll($start_,$end_,1);

                                        $account_arr = array();
                                        $sum_account_payroll = 0;
                                        $cash_outflow_payroll = 0;
                                        $cash_inflow_payroll = 0;
                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_payroll = $sum_account_payroll + $item->amount;
                                                $cash_inflow_payroll = $cash_inflow_payroll + $item->amount;
                                            }
                                            else{
                                                $sum_account_payroll = $sum_account_payroll - $item->amount;
                                                $cash_outflow_payroll = $cash_outflow_payroll + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money($sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }
                                        }
                                        echo '<tr style="background-color: transparent">';
                                        echo '<td style="font-weight: bold">Total Payroll</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_payroll).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Sales Taxes</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeSalesTax($start_,$end_,1);

                                        $account_arr = array();
                                        $sum_account_salestax = 0;
                                        $invoice_num = 0;
                                        $cash_outflow_salestax = 0;
                                        $cash_inflow_salestax = 0;
                                        $cash_inflow = 0;
                                        $cash_outflow = 0;
                                        foreach ($data as $item){
                                            $invoice_num = $item->invoice_num;
                                                $dt = ProductsAndServicesController::contribution_sales($item->invoice_num);
                                                $paid = ProductsAndServicesController::GetPayment($item->invoice_num);

                                            $paid = ProductsAndServicesController::GetPayment($item->invoice_num);

                                            $total = ProductsAndServicesController::getTotalAccountReceivable($item->invoice_num);

                                            if (ProductsAndServicesController::sales_tax($item->invoice_num) > 0 && $paid > 0 && $paid < $total){

                                                $sum = ProductsAndServicesController::getSumArrayNum($data,$item->account,$item->invoice_num);
                                                $sum = ($sum/$total) * $paid;

                                                $sum_arr = ProductsAndServicesController::getInOutFlow($data,$item->invoice_num);

                                                //dd($sum_arr);
                                            }


                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->invoice_num,$account_arr)){

                                                $sum_account_salestax = $sum_account_salestax + $sum;
                                                $cash_inflow_salestax = $cash_inflow_salestax + (($sum_arr['in']/$total) * $paid);
                                                $cash_outflow_salestax = $cash_outflow_salestax + (($sum_arr['out']/$total) * $paid);
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money($sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->invoice_num);
                                            }
                                        }

                                        echo '<tr style="background-color: transparent">';
                                        echo '<td style="font-weight: bold">Total Sales Taxes</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_salestax).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>
                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Other</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeOther($start_,$end_,1);

                                        $account_arr = array();
                                        $sum_account_other = 0;
                                        $cash_inflow_opother = 0;
                                        $cash_outflow_opother = 0;

                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_other = $sum_account_other + $item->amount;
                                                $cash_inflow_opother = $cash_inflow_opother + $item->amount;
                                            }
                                            else{
                                                $sum_account_other = $sum_account_other - $item->amount;
                                                $cash_outflow_opother = $cash_outflow_opother + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){
                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money($sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }
                                        }
                                        echo '<tr style="background-color: transparent">';
                                        echo '<td style="font-weight: bold">Total Payroll</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_other).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td><span style="font-weight: bold">Net Cash from Operating Activities</span></td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $net_profit  = $sum_account_income + $sum_account_expenses + $sum_account_inventory + $sum_account_payroll + $sum_account_salestax + $sum_account_other;
                                    echo ProductsAndServicesController::money($net_profit);
                                    ?>
                                </td>
                            </tr>

                            <tr style="background-color:#ffffff">
                                <th colspan="2">Investing Activities</th>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Property, Plant, Equipment</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypePPE($start_,$end_,0);

                                        $account_arr = array();
                                        $sum_account_ppe = 0;
                                        $cash_inflow_ppe = 0;
                                        $cash_outflow_ppe = 0;
                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArrayCategory($data,$item->category);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_ppe = $sum_account_ppe + $item->amount;
                                                $cash_inflow_ppe = $cash_inflow_ppe + $item->amount;
                                            }
                                            else{
                                                $sum_account_ppe = $sum_account_ppe - $item->amount;
                                                $cash_outflow_ppe = $cash_outflow_ppe + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array(strtolower($item->category),$account_arr)){

                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,strtolower($item->category));
                                            }
                                        }
                                        echo '<tr>';
                                        echo '<td style="font-weight: bold">Total Property, Plant, Equipment</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_ppe).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Other</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeInvestingAssetOther($start_,$end_,0);

                                        $account_arr = array();
                                        $sum_account_longasset_other = 0;
                                        $cash_inflow_ppeother = 0;
                                        $cash_outflow_ppeother = 0;
                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArrayCategory($data,$item->category);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_longasset_other = $sum_account_longasset_other + $item->amount;
                                                $cash_inflow_ppeother = $cash_inflow_ppeother + $item->amount;
                                            }
                                            else{
                                                $sum_account_longasset_other = $sum_account_longasset_other - $item->amount;
                                                $cash_outflow_ppeother = $cash_outflow_ppeother + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->category,$account_arr)){

                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->category);
                                            }
                                        }
                                        echo '<tr>';
                                        echo '<td style="font-weight: bold">Total Other</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_longasset_other).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td><span style="font-weight: bold">Net Cash from Investing Activities</span></td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $net_investing  = $sum_account_ppe + $sum_account_longasset_other;
                                    echo ProductsAndServicesController::money($net_investing);
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <th colspan="2">Financing Activities</th>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Loans and Lines of Credit</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeLoansLinesofCredit($start_,$end_,1);
                                        $account_arr = array();
                                        $sum_account_loans_lines = 0;
                                        $cash_inflow_llc = 0;
                                        $cash_outflow_llc = 0;
                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_loans_lines = $sum_account_loans_lines + $item->amount;
                                                $cash_inflow_llc = $cash_inflow_llc + $item->amount;
                                            }
                                            else{
                                                $sum_account_loans_lines = $sum_account_loans_lines - $item->amount;
                                                $cash_outflow_llc = $cash_outflow_llc + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){

                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                if (strpos($item->account_type,"Expected Payment from customers")  !== false ){
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                                                }
                                                else{
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                }
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }
                                        }
                                        echo '<tr>';
                                        echo '<td style="font-weight: bold">Total Loans and Lines of Credit</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_loans_lines).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Owners and Shareholders</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeEquityCashFlow($start_,$end_,2);

                                        $account_arr = array();
                                        $sum_account_equity = 0;
                                        $cash_inflow_shareholder = 0;
                                        $cash_outflow_shareholder = 0;
                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_equity = $sum_account_equity + $item->amount;
                                                $cash_inflow_shareholder = $cash_inflow_shareholder + $item->amount;
                                            }
                                            else{
                                                $sum_account_equity = $sum_account_equity - $item->amount;
                                                $cash_outflow_shareholder = $cash_outflow_shareholder + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){

                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                if (strpos($item->account_type,"Expected Payment from customers")  !== false ){
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                                                }
                                                else{
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                }
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }

                                        }
                                        echo '<tr>';
                                        echo '<td style="font-weight: bold">Total Owners and Shareholders</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_equity).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Other</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <?php
                                        $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeEquityOther($start_,$end_,2);


                                        $account_arr = array();
                                        $sum_account_equity_other = 0;
                                        $cash_inflow_finother = 0;
                                        $cash_outflow_finother = 0;
                                        foreach ($data as $item){
                                            $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                                            if (strpos($item->operation,"add") !== false){
                                                $sum_account_equity_other = $sum_account_equity_other + $item->amount;
                                                $cash_inflow_finother = $cash_inflow_finother + $item->amount;
                                            }
                                            else{
                                                $sum_account_equity_other = $sum_account_equity_other - $item->amount;
                                                $cash_outflow_finother = $cash_outflow_finother + $item->amount;
                                            }

                                            $val = "ledger/".$item->invoice_num."/".$item->account;
                                            if (!in_array($item->account,$account_arr)){

                                                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                                                if (strpos($item->account_type,"Expected Payment from customers")  !== false ){
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                                                }
                                                else{
                                                    echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                                                }
                                                echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                                                echo '</tr>';
                                                array_push($account_arr,$item->account);
                                            }
                                        }
                                        echo '<tr>';
                                        echo '<td style="font-weight: bold">Total Other</td>';
                                        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_account_equity_other).'</td>';
                                        echo '</tr>';
                                        ?>
                                    </table>
                                </td>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td><span style="font-weight: bold">Net Cash from Financing Activities</span></td>
                                <td style="text-align: right;font-weight: bold">
                                    <?php
                                    $net_financing_activities  = $sum_account_loans_lines + $sum_account_equity + $sum_account_equity_other;
                                    echo ProductsAndServicesController::money($net_financing_activities);
                                    ?>
                                </td>
                            </tr>


                            <tr style="background-color: white">
                                <th colspan="2">OVERVIEW</th>
                            </tr>

                            <tr style="background-color: #F2F2F2">
                                <td colspan="2">
                                    <table class="table borderless" style="font-size: 14px;width: 100%">
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <th>Starting Balance</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("")>Cash on Hand</a></td>
                                            <td style="text-align: right"><?php echo ProductsAndServicesController::money(0); ?><div><small style="color: #343a40">As of <?php echo $start_?></small></div></td>
                                        </tr>
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <td style="font-weight: bold">Total Starting Balance</td>
                                            <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money(0); ?><div><small style="color: #343a40">As of <?php echo $start_?></small></div></td>
                                        </tr>


                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <td style="font-weight: bold">Cash Inflow</td>
                                            <td style="text-align: right;font-weight: bold"><?php
                                                $cash_inflow = $cash_inflow_sales+$cash_inflow_salestax+$cash_inflow_purchases+$cash_inflow_inventory+$cash_inflow_payroll+$cash_inflow_opother+$cash_inflow_ppe+$cash_inflow_ppeother+$cash_inflow_llc+$cash_inflow_shareholder+$cash_inflow_finother;
                                                echo ProductsAndServicesController::money($cash_inflow); ?></td>
                                        </tr>
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <td style="font-weight: bold">Cash Outflow</td>
                                            <td style="text-align: right;font-weight: bold"><?php
                                                $cash_outflow = $cash_outflow_sales+$cash_outflow_salestax+$cash_outflow_purchases+$cash_outflow_inventory+$cash_outflow_payroll+$cash_outflow_opother+$cash_outflow_ppe+$cash_outflow_ppeother+$cash_outflow_llc+$cash_outflow_shareholder+$cash_outflow_finother;

                                                echo ProductsAndServicesController::money($cash_outflow); ?></td>
                                        </tr>
                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <td style="font-weight: bold">Net Cash Change</td>
                                            <td style="text-align: right;font-weight: bold;border-top: solid 2px #C0C0C0;border-bottom: solid 2px #C0C0C0;"><?php echo ProductsAndServicesController::money($cash_inflow - $cash_outflow); ?></td>

                                        <tr>
                                            <td colspan="2" style="font-weight: bold">Ending Balance</td>
                                        </tr>

                                        <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                                            <td style="font-weight: bold">Cash on Hand</td>
                                            <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_inflow - $cash_outflow); ?></td>
                                        </tr>
                                        <tr style="background-color: transparent;">
                                            <td style="font-weight: bold">Total Ending Balance</td>
                                            <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_inflow - $cash_outflow); ?><div><small style="color: #343a40">As of <?php echo $end_?></small></div></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>