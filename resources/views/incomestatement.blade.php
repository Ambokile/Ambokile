<?php  use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="tab-pane fade two @if($active == 2) show active @endif" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
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
<div class="tab-pane fade one @if($active == 1) show active @endif" id="pills-details" role="tabpanel" aria-labelledby="pills-home-tab">
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