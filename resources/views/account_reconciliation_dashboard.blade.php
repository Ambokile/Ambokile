<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<?php
    $date_test = $date;
    if (empty($date)){
      $date = date("Y-m-d",time());
    }
    $arr = explode("-",$date);
    $m = $arr[1];
    $d = $arr[2];
    $y = $arr[0];
    $dt = DateTime::createFromFormat('!m', $m);
    $mo = $dt->format('F');
    $mo_sh = substr($mo,0,3);

    $to = $y."-".$m."-".$d;

// One month from a specific date
if(!empty($reconcile_last_date)){
    $arr = explode("-", $reconcile_last_date);
    $m = $arr[1];
    $d = $arr[2];
    $y = $arr[0];
    $dat = $y."-".$m."-01";
    $date = date('Y-m-d', strtotime('+1 month', strtotime($dat)));
}
$arr = explode("-", $date);
$m_3 = $arr[1];
$d_3 = $arr[2];
$y_3 = $arr[0];

$dt_3 = DateTime::createFromFormat('!m', $m_3);
$mo_3_m = $dt_3->format('F');
$mo_3_sh = substr($mo_3_m,0,3);

$day_=cal_days_in_month(CAL_GREGORIAN,$m_3,$y_3);
$date_ = $y_3."-".$m_3."-".$day_;

$str = $date_.",".$account.","."0".","."0".",".$d_3.",".$mo_3_m.",".$y_3;
?>

<div class="row">
    <div class="col-sm-8 offset-2">

        <div class="row" style=" border-bottom: solid 1px #C0C0C0;padding-bottom: 20px;">
            <div class="col-sm-8">
                <h2>Account Reconciliation</h2>
            </div>
            <div class="col-sm-4">
                <div class="btn-group" style="margin-left: 50%;">

                    <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: solid 1px darkblue;background-color: white;">
                        {{$account}}<span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" style="font-size: 14px;">
                        <h6 class="dropdown-header">CASH AND BANK</h6>
                        @foreach($cash_bank as $item)
                            <a class="dropdown-item" onclick="LoadContent('reconciling_search/{{$item->account_name}}')">{{$item->account_name}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-sm-8 offset-2" style="overflow-y: auto;height: 550px;">

        <div class="row" style="margin-top: 5%;border-bottom: solid 1px #C0C0C0;padding-bottom: 1%">
            <div class="col-sm-2" style="font-size: 14px;">
                <div style="margin-top: 10%">{{$mo_3_sh}} {{$y_3}}</div>
            </div>
            <div class="col-sm-2" style="border-right: solid 1px #C0C0C0;height: 50px">
                <button class="btn btn-info btn-xs" disabled="disabled" style="font-size: 10px;margin-top: 10%">NOT STARTED</button>
            </div>
            <div class="col-sm-6" style="font-size: 14px">
                <div style="margin-top: 4%">Start reconciling by getting your account statement for {{$mo_3_sh}} {{$y_3}}.
                </div>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-info btn-lg" style="font-size: 14px;margin-top: 4%;width: 80%" data-toggle="modal" data-str="{{$str}}" data-target="#reconcileModal">Start</button>
            </div>
        </div>



        @foreach($reconciles as $reconcile)
            <div class="row" style="margin-top: 5%;border-bottom: solid 1px #C0C0C0;padding-bottom: 1%">
                <div class="col-sm-2" style="font-size: 14px;">
                    <div style="margin-top: 10%">
                        <?php
                            $arr = explode("-",$reconcile->ending_balance_date);
                            $m_ = $arr[1];
                            $d_ = $arr[2];
                            $y_ = $arr[0];
                            $dt_ = DateTime::createFromFormat('!m', $m_);
                            $mo_ = $dt_->format('F');
                            $mo_sh_ = substr($mo_,0,3);
                            echo $mo_sh_." ". $y_;
                        ?>
                    </div>
                </div>
                <div class="col-sm-2" style="border-right: solid 1px #C0C0C0;height: 80px">
                    <button class="btn btn-danger btn-xs" disabled="disabled" style="font-size: 10px;margin-top: 10%">
                        @if($reconcile->status == 0)
                                    UNRECONCILE
                            @else
                                    RECONCILE
                        @endif
                    </button>
                </div>
                <div class="col-sm-6" style="font-size: 14px">
                    <div style="margin-top: 0%">
                        <div class="row">
                            <div class="col-sm-5" style="border-right: dashed 1px #C0C0C0">
                                <span style="font-size: 14px"><?php echo ProductsAndServicesController::money($reconcile->ending_balance_amount); ?></span> <a href="#reconcileModal" data-toggle="modal" data-target="#reconcileModal"><i class="fa fa-edit"></i></a><br>
                                <span style="font-size: 12px;font-weight: bold">Statement Balance</span><br /><span style="font-size: 12px;color: #3C4858"> Ending <?php
                                    $arr = explode("-",$reconcile->ending_balance_date);
                                    $m_1 = $arr[1];
                                    $d_1 = $arr[2];
                                    $y_1 = $arr[0];
                                    $dt_1 = DateTime::createFromFormat('!m', $m_1);
                                    $mo_1 = $dt_1->format('F');
                                    $mo_sh_1 = substr($mo_1,0,3);
                                    echo $mo_1." ".$d_1.", ". $y_1;
                                    ?></span>
                            </div>

                            <div class="col-sm-4" style="border-right: dashed 1px #C0C0C0">
                                <span style="font-size: 14px">
                                    <?php
                                        //$to = $reconcile->ending_balance_date;
                                        $arr = explode("-",$reconcile->ending_balance_date);
                                        $m_2 = $arr[1];
                                        $d_2 = $arr[2];
                                        $y_2 = $arr[0];

                                        //$from = $m_2."/"."01/".$y_2;
                                        $from = $reconcile->ending_balance_date;
                                        $amount_2 = ProductsAndServicesController::BalanceAmount($from,$to,$account);
                                        echo ProductsAndServicesController::money($amount_2);
                                    ?>
                                </span><br>
                                <span style="font-size: 12px;font-weight: bold">wave Balance</span><br /><span style="font-size: 12px;color: #3C4858"> Ending <?php
                                    $arr = explode("-",$reconcile->ending_balance_date);
                                    $m_1 = $arr[1];
                                    $d_1 = $arr[2];
                                    $y_1 = $arr[0];
                                    $dt_1 = DateTime::createFromFormat('!m', $m_1);
                                    $mo_1 = $dt_1->format('F');
                                    $mo_sh_1 = substr($mo_1,0,3);
                                    echo $mo_1." ".$d_1.", ". $y_1;
                                    ?></span>
                            </div>

                            <div class="col-sm-3">
                                <div style="margin-top: 10%">
                                    <span style="font-size: 14px">
                                         <?php
                                        //$to = $reconcile->ending_balance_date;
                                        $arr = explode("-",$reconcile->ending_balance_date);
                                        $m_2 = $arr[1];
                                        $d_2 = $arr[2];
                                        $y_2 = $arr[0];

                                        //$from = $m_2."/"."01/".$y_2;
                                        $from = $reconcile->ending_balance_date;
                                        $amount_wave = ProductsAndServicesController::BalanceAmount($from,$to,$account);
                                        $amount_statement =ProductsAndServicesController::money($reconcile->ending_balance_amount);
                                        $diff =  (int)$amount_wave - (int)$amount_statement;
                                        echo ProductsAndServicesController::money($diff);
                                        ?>
                                    </span><br>
                                    <span style="font-size: 12px;color: #3C4858">Difference</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-info btn-lg" style="font-size: 14px;margin-top: 4%;width: 80%" onclick="LoadContent('reconciling_account/{{$reconcile->id}}/{{ $reconcile->ending_balance_date}}/{{$to}}/{{$account}}')">Reconcile</button>
                </div>
            </div>

        @endforeach
    </div>
</div>

<div class="row">
    <div class="col-sm-8 offset-2">
        @if(!empty($date_test))
            <?php
            $arr = explode("-",$date_test);
            $m = $arr[1];
            $d = $arr[2];
            $y = $arr[0];
            $dt = DateTime::createFromFormat('!m', $m);
            $mo = $dt->format('F');
            $mo_sh = substr($mo,0,3);

            ?>
            <div class="row" style="margin-top: 2%;padding-bottom: 5%;">
                <div class="col-sm-12" style="text-align: center;font-size: 14px;">
                    The starting balance for this account was
                    <span style="color: cornflowerblue;font-weight: bold"><?php echo ProductsAndServicesController::money($amount) ?> on {{$mo_sh}} {{$d}}, {{$y}}</span>
                </div>
            </div>
        @endif
    </div>
</div>