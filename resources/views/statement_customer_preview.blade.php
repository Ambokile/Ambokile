<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<?php
    $month = array();
    $i = 0;
    $total_due = 0;
    $begin_balance = 0;
    $payment_balance = 0;
    $invoice_balance = 0;
    $invoice_num_array = array();
    $payments_id_array = array();
    foreach ($invoices_all as $item){
        array_push($invoice_num_array,$item->invoice_num);
    }
?>
<div class="container" style="width: 100%;">
    <div class="col-sm-8 offset-2" style="background-color: white;margin-top: 5%;margin-bottom: 5%;box-shadow: 5px 10px 8px #888888;border: solid 1px lightblue;">
        <div class="row" style="margin-top: 2%;">
            <div class="col-sm-12">
                <div style="width: 100%;text-align: center;font-size: 24px;font-weight: bold;display: none;color: #138496;" id="statement_g">STATEMENT OF ACCOUNT</div>
                <div style="width: 100%;text-align: center;font-size: 14px;display: none" id="date_g">(Generated on {{date("M d, Y",time())}})</div>
            </div>
        </div>
        <div class="row" style="border-bottom: solid 1px #cccccc;">
            <div class="col-sm-6">
                <img src="{{asset('img/1.png')}}" id="invoice_logo" class="thumbnail" style="margin-top: 5%;padding-bottom: 2%" width="144px" height="144px" id="preview"/>
            </div>
            <div class="col-sm-6" style="text-align: right;font-family: 'Times New Roman'">
                <div style="margin-top: 5%;font-size: 18px;font-weight: bold" id="title"></div>
                <div style="font-size: 14px;" id="summary"></div>
                <div style="font-size: 14px; font-weight: bold" id="company_prev">yaptrue</div>
                <div style="font-size: 14px;" ><small id="location_prev"></small></div>

            </div>
        </div>

        @if($unpaid == 0)
            @foreach($invoices as $item)
                <div class="row">
                    <div class="col-sm-6">
                        <div style="margin-top: 10%;font-size: 14px;font-weight: bold">BILL TO</div>
                        <div style="font-size: 14px; font-weight: bold" id="name_in_prev">{{$customer->name}}</div>
                        <div style="font-size: 14px" id="full_name">{{$customer->last_name." ".$customer->first_name}}</div>
                        <div style="margin-top: 5%;font-size: 14px" id="email_in_prev">{{$customer->email}}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="width: 100%;text-align: right;margin-top: 10%;font-size: 14px;font-weight: bold">Account Summary</div>

                        <div class="row" style="font-size: 14px;text-align: right;margin-top: 5%;">
                            <div class="col-sm-8">
                                <?php

                                $arr = explode("-", $from);
                                $m = $arr[1];
                                $d = $arr[2];
                                $y = $arr[0];

                                $dt = DateTime::createFromFormat('!m', $m);
                                $mo = $dt->format('F');
                                $mo_sh = substr($mo,0,3);
                                $begin_date = $mo_sh." ".$d.", ".$y;
                                ?>
                                Beginning balance {{$begin_date}}:
                            </div>
                            <div class="col-sm-4">
                                <?php
                                $begin = date('Y-m-d', strtotime('-36 month', strtotime($from)));
                                $begin_balance = ProductsAndServicesController::money(ProductsAndServicesController::getBegginingBalance($begin,$from));
                                echo "Sh ".$begin_balance;
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;margin-top: 1%;">
                            <div class="col-sm-7">
                                Invoiced:
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $money = ProductsAndServicesController::InvoicedAmountFromTo($from,$to,$customer_id);
                                $invoice_balance = ProductsAndServicesController::money($money);
                                echo "Sh ".$invoice_balance;
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;margin-top: 1%;">
                            <div class="col-sm-7">
                                Payments:
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $payment_balance = ProductsAndServicesController::money(ProductsAndServicesController::PaymentAmountFromTo($from,$to,$customer_id));

                                echo "Sh ".$payment_balance;
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;margin-top: 1%;">
                            <div class="col-sm-7">
                                Refunds:
                            </div>
                            <div class="col-sm-5">
                                <?php
                                echo "Sh ".ProductsAndServicesController::money(0);
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;margin-top: 1%;">
                            <div class="col-sm-8">
                                <?php

                                $arr = explode("-", $to);
                                $m = $arr[1];
                                $d = $arr[2];
                                $y = $arr[0];

                                $dt = DateTime::createFromFormat('!m', $m);
                                $mo = $dt->format('F');
                                $mo_sh = substr($mo,0,3);
                                $ending_date = $mo_sh." ".$d.", ".$y;
                                ?>
                                Ending balance {{$ending_date}}:
                            </div>
                            <div class="col-sm-4">
                                <?php
                                $begin = date('Y-m-d', strtotime('-36 month', strtotime($from)));
                                $ending_balance = ProductsAndServicesController::money(($begin_balance+$invoice_balance) - $payment_balance);
                                echo "Tsh ".$ending_balance;
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row" style="margin-top: 3%;padding: 0px;">
                    <div class="col-sm-12" style="padding: 0px">
                        <div class="table-responsive">
                            <table class="table borderless" style="font-size: 14px" id="myTable_prev">
                                <tr style="background-color: lightblue">
                                    <th colspan="6" scope="col" style="text-align: center;text-transform: uppercase" id="head_invoice">
                                        @if($unpaid == 1) Showing all outstanding invoices between {{date("M j, Y", strtotime($from))}} and {{date("M j, Y", strtotime($to))}}
                                        @else Showing all invoices and payments between {{date("M j, Y", strtotime($from))}} and {{date("M j, Y", strtotime($to))}} @endif</th>
                                </tr>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Balance</th>
                                </tr>
                                <tbody>
                                <?php
                                $total = 0;
                                $begin = date('Y-m-d', strtotime('-36 month', strtotime($from)));
                                $begin_balance = ProductsAndServicesController::getBegginingBalance($begin,$from);
                                if ($begin_balance < 1)
                                    $total_balance = 0;
                                else $total_balance = $begin_balance;
                                ?>
                                <tr>
                                    <td>{{$from}}</td>
                                    <td style="width: 40%">Beginning balance</td>
                                    <td>&nbsp;</td>
                                    <td><?php echo "Sh ".ProductsAndServicesController::money($begin_balance) ?></td>
                                </tr>
                                @foreach($invoices_all as $invoice)
                                    <tr>
                                        <td>{{$invoice->invoice_date}}</td>
                                        <td style="width: 40%"><span style="color: blueviolet">{{$invoice->title."#".$invoice->invoice_num}}</span> (<?php
                                            $arr = explode("-", $invoice->payment_due);
                                            $m = $arr[1];
                                            $d = $arr[2];
                                            $y = $arr[0];

                                            $dt = DateTime::createFromFormat('!m', $m);
                                            $mo = $dt->format('F');
                                            $mo_sh = substr($mo,0,3);
                                            $due_date = $mo_sh." ".$d.", ".$y;
                                            echo "due ".$due_date;
                                            ?>)</td>
                                        <td >
                                            <?php

                                            $number = ProductsAndServicesController::InvoiceAmount($invoice->invoice_num);
                                            echo ProductsAndServicesController::money($number)
                                            ?>
                                        </td>
                                        <?php
                                        $bd = ProductsAndServicesController::InvoiceAmount($invoice->invoice_num);
                                        $total_balance = $total_balance + $bd;
                                        ?>
                                        <td><?php echo "Sh ".ProductsAndServicesController::money($total_balance); ?></td>

                                    </tr>
                                    @foreach($payments as $payment)
                                        @if(in_array($payment->invoice_num,$invoice_num_array) && !in_array($payment->id,$payments_id_array))
                                            @if($payment->invoice_num == $invoice->invoice_num)
                                                <tr>
                                                    <td>{{$payment->date}}</td>
                                                    <td>Payment to <span style="color: blueviolet;">{{"invoice #".$payment->invoice_num}}</span></td>
                                                    <td><?php echo ProductsAndServicesController::money($payment->amount) ?></td>
                                                    <?php
                                                    $total_balance = $total_balance - $payment->amount; ?>
                                                    <td><?php echo "Sh ".ProductsAndServicesController::money($total_balance) ?></td>
                                                </tr>
                                                <?php
                                                array_push($payments_id_array,$payment->id);
                                                ?>
                                            @endif
                                        @endif
                                    @endforeach
                                    <?php
                                    $total = $total_balance;
                                    ?>
                                @endforeach
                                <tr>
                                    <td>{{$to}}</td>
                                    <td style="width: 40%">Ending balance</td>
                                    <td>&nbsp;</td>
                                    <td><?php echo "Sh ".ProductsAndServicesController::money($total_balance) ?></td>
                                </tr>
                                </tbody>
                                <tfoot  style="padding: 0px; border-top: solid 1px #C0C0C0">
                                <td colspan="6">
                                    <div style="width: 100%;text-align: right">Amount due (TZS)</div>
                                    <input type="hidden" id="total_in" value="{{$total}}" />
                                    <div style="width: 100%;text-align: right" id="total">
                                        <?php echo "Tsh " .ProductsAndServicesController::money($total) ?>
                                    </div>
                                </td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                array_push($month,substr($item->invoice_date,5,2));
                $i++;
                $total_due = 0;
                ?>
                @break
            @endforeach
        @else
            @foreach($invoices as $item)
                <div class="row">
                    <div class="col-sm-6">
                        <div style="margin-top: 10%;font-size: 14px;font-weight: bold">BILL TO</div>
                        <div style="font-size: 14px; font-weight: bold" id="name_in_prev">{{$customer->name}}</div>
                        <div style="font-size: 14px" id="full_name">{{$customer->last_name." ".$customer->first_name}}</div>
                        <div style="margin-top: 5%;font-size: 14px" id="email_in_prev">{{$customer->email}}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="width: 100%;text-align: right;margin-top: 10%;font-size: 14px;font-weight: bold">Account Summary</div>

                        <div class="row" style="font-size: 14px;text-align: right;margin-top: 5%;">
                            <div class="col-sm-7">
                                Not yet due:
                            </div>
                            <div class="col-sm-5">
                                Sh0.00
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;">
                            <div class="col-sm-7">
                                1-30 days overdue:
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $currency = $item->payment_due;
                                $previous_1_30 = date("Y-m-d",strtotime("-30 days",strtotime($currency)));
                                $number_1_30 = ProductsAndServicesController::DueAmountFromTo($currency,$previous_1_30,$customer->id,$unpaid);
                                $total_due = $total_due + $number_1_30;
                                //echo $currency." | ".$previous_1_30." | ".$number_1_30 ;
                                if ($number_1_30 < 1)
                                    echo "Sh ". money_format('%i', 0);
                                else echo "Sh ". money_format('%i', $number_1_30);
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;">
                            <div class="col-sm-7">
                                31-60 days overdue:
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $currency = date("Y-m-d",strtotime("-1 days",strtotime($previous_1_30)));;
                                $previous_31_60 = date("Y-m-d",strtotime("-30 days",strtotime($currency)));
                                $number_31_60 = ProductsAndServicesController::DueAmountFromTo($currency,$previous_31_60,$customer->id,$unpaid);
                                $total_due = $total_due + $number_31_60;

                                //echo $currency." | ".$previous_31_60." | ".$number_31_60 ;
                                if ($number_31_60 < 1)
                                    echo "Sh ". money_format('%i', 0);
                                else echo "Sh ". money_format('%i', $number_31_60);
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;">
                            <div class="col-sm-7">
                                61-90 days overdue:
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $currency = date("Y-m-d",strtotime("-1 days",strtotime($previous_31_60)));;
                                $previous_61_90 = date("Y-m-d",strtotime("-30 days",strtotime($currency)));
                                $number_61_90 = ProductsAndServicesController::DueAmountFromTo($currency,$previous_61_90,$customer->id,$unpaid);
                                $total_due = $total_due + $number_61_90;

                                //echo $currency." | ".$previous_61_90." | ".$number_61_90 ;
                                if ($number_61_90 < 1)
                                    echo "Sh ". money_format('%i', 0);
                                else echo "Sh ". money_format('%i', $number_61_90);
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right;">
                            <div class="col-sm-7">
                                > 90 days overdue:
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $currency = date("Y-m-d",strtotime("-1 days",strtotime($previous_61_90)));;
                                $previous_91_ = date("Y-m-d",strtotime("-1095 days",strtotime($currency)));
                                $number_91_ = ProductsAndServicesController::DueAmountFromTo($currency,$previous_91_,$customer->id,$unpaid);
                                $total_due = $total_due + $number_91_;

                                //echo $currency." | ".$previous_91_." | ".$number_91_ ;
                                if ($number_91_ < 1)
                                    echo "Sh ". money_format('%i', 0);
                                else echo "Sh ". money_format('%i', $number_91_);
                                ?>
                            </div>
                        </div>
                        <div class="row" style="font-size: 14px;text-align: right">
                            <div class="col-sm-7">
                                Total due:
                            </div>
                            <div class="col-sm-5" id="amount_due">
                                <?php
                                if ($total_due < 1)
                                    echo $mon = "Sh ". money_format('%i', 0);
                                else echo "Sh ". money_format('%i', $total_due);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 3%;padding: 0px;">
                    <div class="col-sm-12" style="padding: 0px">
                        <div class="table-responsive">
                            <table class="table borderless" style="font-size: 14px" id="myTable_prev">
                                <tr style="background-color: lightblue">
                                    <th colspan="6" scope="col" style="text-align: center;text-transform: uppercase" id="head_invoice">
                                        @if($unpaid == 1) Showing all outstanding invoices between {{date("M j, Y", strtotime($from))}} and {{date("M j, Y", strtotime($to))}}
                                        @else Showing all invoices and payments between {{date("M j, Y", strtotime($from))}} and {{date("M j, Y", strtotime($to))}} @endif</th>
                                </tr>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Due date</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Total Paid</th>
                                    <th scope="col">Amount due</th>
                                </tr>
                                <tbody>
                                <?php $total = 0; ?>
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{$invoice->invoice_date}}</td>
                                        <td>{{$invoice->payment_due}}</td>
                                        <td><span style="color:blueviolet">{{$invoice->title."#".$invoice->invoice_num}}</span></td>
                                        <td >
                                            <?php

                                            $number = ProductsAndServicesController::InvoiceAmount($invoice->invoice_num);
                                            setlocale(LC_MONETARY, 'en_US');
                                            echo money_format('%i', $number);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $number = ProductsAndServicesController::GetPayment($invoice->invoice_num);
                                            echo ProductsAndServicesController::money($number);
                                            ?>
                                        </td>
                                        <td >
                                            <?php
                                            $number = ProductsAndServicesController::DueAmount($invoice->invoice_num);

                                            echo ProductsAndServicesController::money($number);
                                            ?>
                                            <input type="hidden" name="subtotal[]" value="{{$number}}">
                                        </td>
                                    </tr>
                                    <?php
                                    $number = ProductsAndServicesController::DueAmount($invoice->invoice_num);
                                    $total = $total + $number;
                                    ?>
                                @endforeach
                                </tbody>
                                <tfoot  style="padding: 0px; border-top: solid 1px #C0C0C0">
                                <td colspan="6">
                                    <div style="width: 100%;text-align: right">Total amount due (TZS)</div>
                                    <input type="hidden" id="total_in" value="{{$total}}" />
                                    <div style="width: 100%;text-align: right" id="total">
                                        <?php echo "Tsh ". ProductsAndServicesController::money($total) ?>
                                    </div>
                                </td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @break
            @endforeach
        @endif
    </div>
</div>