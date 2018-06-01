<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<?php $item_arr = array(); ?>
@if($account != 'Cash on Hand')
    @if($account == 'all account')
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
                <h6 style="color: black;text-indent: 12px">Cash on Hand</h6>
                <div style="text-indent: 12px"><small>Under: <?php
                        echo "Asset";
                        ?> > Cash and Bank</small></div>
            </td>
        </tr>
        <?php
        $total = 0; $total_dr = 0; $total_cr = 0;
        $start_balance = ProductsAndServicesController::getStartingBalance($date,$from,'Cash on Hand');
        if ($start_balance != 0)
            $total = $start_balance;
        ?>
        <tr style="background-color: #ECF0F3;border-style: none;">
            <td colspan="4">Starting Balance</td>
            <td style="text-align: right"><?php echo ProductsAndServicesController::money($start_balance); ?></td>
        </tr>
        <?php
            $transactions_items =  ProductsAndServicesController::reconcileTransactionSearchTwo($from,$to,'Cash on Hand')
        ?>
        @foreach($transactions_items as $transaction)
            <?php
                $account_chart = ProductsAndServicesController::getAccountChartIndex('Cash on Hand');
            ?>
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

                @if($account_chart == 1 |$account_chart == 2 || $account_chart == 4)
                    @if(strpos($transaction->operation,"Deposit") !== false || strpos($transaction->operation,"payment_in") !== false)
                        <?php
                        $total = $total + $transaction->amount;
                        $total_dr =  $total_dr + $transaction->amount;
                        ?>
                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                        <td style="text-align: right"></td>

                    @elseif(strpos($transaction->operation,"withdrawal") !== false || strpos($transaction->operation,"payment_out") !== false)
                        <?php
                        $total = $total - $transaction->amount;
                        $total_cr =  $total_cr + $transaction->amount;
                        ?>
                        <td style="text-align: right"></td>

                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>

                    @endif


                @elseif($account_chart == 0)

                    @if(strpos($transaction->operation,"Deposit") !== false || strpos($transaction->operation,"payment_in") !== false)
                        <?php
                            $total = $total + $transaction->amount;
                            $total_dr =  $total_dr + $transaction->amount;
                        ?>
                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                        <td style="text-align: right"></td>
                    @elseif(strpos($transaction->operation,"withdrawal") !== false || strpos($transaction->operation,"payment_out") !== false)
                        <?php
                        $total = $total - $transaction->amount;
                        $total_cr =  $total_cr + $transaction->amount;
                        ?>
                        <td style="text-align: right"></td>

                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                    @endif

                @elseif($account_chart == 3)

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
    @foreach($transactions as $item)
        @if(!empty($item->category))
            @if(!in_array($item->category,$item_arr))
                <?php  ?>
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
                            @if(strpos($item->category,"payment from") > -1)
                                <h6 style="color: black;text-indent: 12px">Accounts Receivable</h6>
                                <div style="text-indent: 12px"><small>Under: <?php
                                        echo ProductsAndServicesController::getAccountChart($item->category)
                                        ?> > Accounts Receivable</small></div>
                            @else
                                <h6 style="color: black;text-indent: 12px">{{$item->category}}</h6>
                                <div style="text-indent: 12px"><small>Under: <?php
                                        echo ProductsAndServicesController::getAccountChart($item->category)
                                        ?> > {{$item->category}}</small></div>
                            @endif
                        </td>
                    </tr>
                    <?php
                    $total = 0; $total_dr = 0; $total_cr = 0;
                    $start_balance = ProductsAndServicesController::getStartingBalance($date,$from,$item->category);
                    if ($start_balance != 0)
                    $total = $start_balance;
                    ?>
                    <tr style="background-color: #ECF0F3;border-style: none;">
                        <td colspan="4">Starting Balance</td>
                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($start_balance); ?></td>
                    </tr>
                    <?php $transactions_items =  ProductsAndServicesController::reconcileTransactionSearchOne($from,$to,$item->category) ?>
                    @foreach($transactions_items as $transaction)

                        <?php
                            $account_chart = ProductsAndServicesController::getAccountChartIndex($item->category)
                        ?>
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

                            @if($account_chart == 0)
                                @if(strpos($transaction->category,"payment from") === false)
                                    <?php
                                        $total = $total + $transaction->amount;
                                        $total_dr =  $total_dr + $transaction->amount;
                                    ?>
                                    <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                                    <td style="text-align: right"></td>

                                @else
                                    <?php
                                        $total = $total - $transaction->amount;
                                        $total_cr =  $total_cr + $transaction->amount;
                                    ?>
                                    <td style="text-align: right"></td>

                                    <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>

                                @endif


                            @elseif($account_chart == 1 |$account_chart == 2 || $account_chart == 4)

                                @if(strpos($transaction->operation,"Deposit") !== false || strpos($transaction->operation,"payment_in") !== false)
                                    <?php
                                        $total = $total - $transaction->amount;
                                        $total_cr =  $total_cr + $transaction->amount;
                                    ?>
                                    <td style="text-align: right"></td>

                                    <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>

                                @elseif(strpos($transaction->operation,"withdrawal") !== false || strpos($transaction->operation,"payment_out") !== false)

                                        <?php
                                            $total = $total + $transaction->amount;
                                            $total_dr =  $total_dr + $transaction->amount;
                                        ?>
                                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                                        <td style="text-align: right"></td>
                                @endif

                            @elseif($account_chart == 3)

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

                            @endif
                            <td style="text-align: right"><?php echo ProductsAndServicesController::money($total); ?></td>
                        </tr>
                            <?php
                                    if(!in_array($transaction->category,$item_arr))
                                    array_push($item_arr,$transaction->category);
                            ?>
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
                <?php array_push($item_arr,$item->category);?>
            @endif
        @endif
    @endforeach
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
                <h6 style="color: black;text-indent: 12px">Cash on Hand</h6>
                <div style="text-indent: 12px"><small>Under: <?php
                        echo "Asset";
                        ?> > Cash and Bank</small></div>
            </td>
        </tr>
        <?php
        $total = 0; $total_dr = 0; $total_cr = 0;
        $start_balance = ProductsAndServicesController::getStartingBalance($date,$from,'Cash on Hand');
        if ($start_balance != 0)
        $total = $start_balance;
        ?>
        <tr style="background-color: #ECF0F3;border-style: none;">
            <td colspan="4">Starting Balance</td>
            <td style="text-align: right"><?php echo ProductsAndServicesController::money($start_balance); ?></td>
        </tr>
        <?php
        $transactions_items =  ProductsAndServicesController::reconcileTransactionSearchTwo($from,$to,'Cash on Hand')
        ?>
        @foreach($transactions_items as $transaction)
            <?php
            $account_chart = ProductsAndServicesController::getAccountChartIndex('Cash on Hand');
            ?>
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

                @if($account_chart == 0)
                    @if(strpos($transaction->operation,"withdrawal") !== false || strpos($transaction->operation,"payment_out") !== false)
                        <?php
                        $total = $total + $transaction->amount;
                        $total_dr =  $total_dr + $transaction->amount;
                        ?>
                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                        <td style="text-align: right"></td>

                    @elseif(strpos($transaction->operation,"Deposit") !== false || strpos($transaction->operation,"payment_in") !== false)
                        <?php
                        $total = $total - $transaction->amount;
                        $total_cr =  $total_cr + $transaction->amount;
                        ?>
                        <td style="text-align: right"></td>

                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>

                    @endif


                @elseif($account_chart == 1 |$account_chart == 2 || $account_chart == 4)

                    @if(strpos($transaction->operation,"withdrawal") !== false || strpos($transaction->operation,"payment_out") !== false)
                        <?php
                        $total = $total + $transaction->amount;
                        $total_dr =  $total_dr + $transaction->amount;
                        ?>
                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                        <td style="text-align: right"></td>
                    @elseif(strpos($transaction->operation,"Deposit") !== false || strpos($transaction->operation,"payment_in") !== false)
                        <?php
                        $total = $total - $transaction->amount;
                        $total_cr =  $total_cr + $transaction->amount;
                        ?>
                        <td style="text-align: right"></td>

                        <td style="text-align: right"><?php echo ProductsAndServicesController::money($transaction->amount); ?></td>
                    @endif

                @elseif($account_chart == 3)

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
