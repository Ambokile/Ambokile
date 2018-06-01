<?php  use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="tab-pane fade one @if($active == 1) show active @endif" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
    <table class="table table-bordered borderless" style="font-size: 14px;width: 100%">

        <tr style="background-color: #FFFFFF;" class="border-0">
            <th style="text-align: left">ACCOUNTS</th>
            <?php
                $arr = explode("-",$end_);
                $dateObj   = DateTime::createFromFormat('!m', $arr[2]);
                $monthName = $dateObj->format('F'); // March
            ?>
            <th style="text-align: right">{{$arr[1]}} {{substr($monthName,0,3)}}, {{$arr[0]}}</th>
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
                $retain_earning = $net_profit;

                echo ProductsAndServicesController::money($net_profit);
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
<div class="tab-pane fade two @if($active == 2) show active @endif" id="pills-details" role="tabpanel" aria-labelledby="pills-home-tab">
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
        $data_arr = array();
        foreach ($data as $item){
            array_push($data_arr,$item->account);
        }
        $account_cash_bank_arr = array();
        $sum_cash_and_bank = 0;

        foreach ($data as $item){
            $sum = ProductsAndServicesController::getSumCashAndBank($start_,$end_,$item->account,0);
            $val = "ledger/".$item->invoice_num."/".$item->account;
            if (!in_array(strtolower($item->account),$account_cash_bank_arr)){
                $sum_cash_and_bank = $sum_cash_and_bank + $sum;
                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
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
            if (!in_array(strtolower($item->account),$account_long_asset_arr)){
                $sum_long_assets = $sum_long_assets + $sum;
                echo '<tr style="background-color: transparent;border-bottom: solid 1px #C0C0C0;text-indent: 20px">';
                echo '<td><a href="#" style="text-decoration: none;text-indent: 20px" onclick=LoadContentLedger("'.$val.'")>'.$item->account.'</a></td>';
                echo '<td style="text-align: right">'.ProductsAndServicesController::money( $sum).'</td>';
                echo '</tr>';
                array_push($account_long_asset_arr,strtolower($item->account));
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
        echo '<tr>';
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

        <tr style="border-bottom: 0px solid #E0E7EB;;background-color: #ECF0F3;">
            <th style="text-align: left;text-indent: 20px">Retained Earnings</th>
            <th style="text-align: right"></th>
        </tr>
        <?php
        $data = ProductsAndServicesController::getAmountTransactedBasedOnType($start_,$end_,3);
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

        echo '<tr style="text-indent: 20px">';
        echo '<td style="font-weight: bold;text-indent: 20px">Total Retained Earnings</td>';
        echo '<td style="text-align: right;font-weight: bold">'.ProductsAndServicesController::money($sum_other_equity).'</td>';
        echo '</tr>';
        ?>
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