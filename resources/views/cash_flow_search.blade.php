<?php use App\Http\Controllers\ProductsAndServicesController;
    $cash_inflow = 0;
    $cash_outflow = 0;

    $cash_inflow_sum = 0;
    $cash_outflow_sum = 0;
?>
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
                $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCashInflow($start_,$end_,3);

                $sum_income = 0;
                foreach ($data as $item) {
                    if (strpos($item->operation,"add") !== false){
                        $sum_income = $sum_income + $item->amount;
                        $cash_inflow_sum = $cash_inflow_sum + $item->amount;
                    }
                    else{
                        $sum_income = $sum_income - $item->amount;
                        $cash_outflow_sum = $cash_outflow_sum + $item->amount;
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
                    $data = ProductsAndServicesController::getAmountTransactedBasedOnTypeCashInflow($start_,$end_,3);

                   // dd($data);

                    $account_arr = array();
                    $sum_account_income = 0;
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_income = $sum_account_income + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_income = $sum_account_income - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                        else{

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
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_expenses = $sum_account_expenses - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArrayCategory($data,$item->category);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_inventory = $sum_account_inventory + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_inventory = $sum_account_inventory - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_payroll = $sum_account_payroll + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_payroll = $sum_account_payroll - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_salestax = $sum_account_salestax + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_salestax = $sum_account_salestax - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_other = $sum_account_other + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_other = $sum_account_other - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArrayCategory($data,$item->category);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_ppe = $sum_account_ppe + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_ppe = $sum_account_ppe - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
                        }

                        $val = "ledger/".$item->invoice_num."/".$item->account;
                        if (!in_array(strtolower($item->category),$account_arr)){

                            echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                            echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                            echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                            echo '</tr>';
                            array_push($account_arr,strtolower($item->category));
                        }
                        else{

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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArrayCategory($data,$item->category);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_longasset_other = $sum_account_longasset_other + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_longasset_other = $sum_account_longasset_other - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
                        }

                        $val = "ledger/".$item->invoice_num."/".$item->account;
                        if (!in_array($item->category,$account_arr)){

                            echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">';
                            echo '<td><a href="#" style="text-decoration: none;" onclick=LoadContentLedger("'.$val.'")>'.$item->category.'</a></td>';
                            echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                            echo '</tr>';
                            array_push($account_arr,$item->category);
                        }
                        else{

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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_loans_lines = $sum_account_loans_lines + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_loans_lines = $sum_account_loans_lines - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                        else{

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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_equity = $sum_account_equity + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_equity = $sum_account_equity - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                        else{

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
                    foreach ($data as $item){
                        $sum = ProductsAndServicesController::getSumArray($data,$item->account);
                        if (strpos($item->operation,"add") !== false){
                            $sum_account_equity_other = $sum_account_equity_other + $item->amount;
                            $cash_inflow = $cash_inflow + $item->amount;
                        }
                        else{
                            $sum_account_equity_other = $sum_account_equity_other - $item->amount;
                            $cash_outflow = $cash_outflow + $item->amount;
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
                        else{

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
                        <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_inflow); ?></td>
                    </tr>
                    <tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;">
                        <td style="font-weight: bold">Cash Outflow</td>
                        <td style="text-align: right;font-weight: bold"><?php echo ProductsAndServicesController::money($cash_outflow); ?></td>
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