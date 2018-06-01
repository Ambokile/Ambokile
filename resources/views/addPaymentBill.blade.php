<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row" style="padding-left: 7%; padding-right: 5%;">
    <div class="col-sm-9">
        <h2>Add Payment</h2>
    </div>
    <div class="col-sm-3">
        <button class="btn btn-primary" onclick="LoadContent('bills')">Back</button>
    </div>
</div>
<input type="hidden" id="bid" value="{{$num}}">
<div class="row" style="margin-top: 5%;width: 80%;text-align: center">
    <div class="col-sm-12" style="text-align: center;color: #3C4858">
        <h4 style="font-weight: bold">Record a Payment for this Bill</h4>
        <small>Remaining:
            <?php

                $sum = ProductsAndServicesController::totalBill($num);
                $paid = ProductsAndServicesController::GetPayment($num);
                $remain = $sum - $paid;
                if ($remain > 0)
                    echo "<span>Tsh. </span>". ProductsAndServicesController::money($remain);
                else echo "<span>Tsh. </span>"."0.00";

            ?>
        </small>
    </div>
</div>

<div class="row" style="margin-top: 5%;width: 80%">
    <div class="col-sm-8 offset-md-2">
        <div class="progress" id="progress_" style="display: none">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span>Please wait<span class="dotdotdot"></span></span>
            </div>
        </div>
    </div>
    <div class="col-sm-8 offset-md-3" style="margin-top: 5%;">
        <div class="form-group row">
            <label class="control-label col-sm-3" for="from">Payment date:</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type='text' datepicker-here id="pdate" data-position="right top" data-language='en' style="width: 40%;margin-left:0%;font-size: 14px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 2%" data-date-format="yyyy-mm-dd"/>
                    <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3" for="email">Amount:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="amount" placeholder="Enter amount">
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3" for="payment_method">Payment method:</label>
            <div class="col-sm-9">
                <select class="form-control js-example-basic-multiple" name="payment_method" id="payment_method" onchange="" style="width: 48%">
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Payment</option>
                    <option value="cheque">Cheque</option>
                    <option value="credit_card">Credit card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="other">other</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label col-sm-3" for="payment_account">Payment account:</label>
            <div class="col-sm-9">
                <select class="form-control js-example-basic-multiple" name="payment_account" id="payment_account" onchange="" style="width: 48%">
                    @foreach($cash_bank as $method)
                        <option>{{$method->account_name}}</option>
                    @endforeach
                    <option value="other">other</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label col-sm-3" for="notes">Memo / notes
            </label>
            <div class="col-sm-9">
                <textarea class="form-control" id="notes" placeholder="Enter notes"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
                <button type="button" id="addPayment" class="btn btn-info" onclick="addPayment('addPaymentBill',1,{{$user_id}})">Submit</button>
            </div>
        </div>
    </div>
</div>