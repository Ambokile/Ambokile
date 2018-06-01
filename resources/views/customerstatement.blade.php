<div class="row" style="padding-left: 16%; padding-right: 2%">
    <div class="col-sm-9">
        <h2>Customer Statements</h2>
    </div>
    <div class="col-sm-3"></div>
</div>
<div class="panel panel-default offset-1" style="margin-top: 1%;width: 89%">
    <div class="panel-body">
        <fieldset class="col-md-10 offset-1" style="background-color: #FFFFFF">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="sel1" class="col-sm-4" style="font-size: 14px;float: right">Select a customer</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-example-basic-multiple" name="customer" style="width: 70%;font-size: 14px" onchange="GenerateStatement()" id="customer">
                                        <option value="" style="font-size: 14px;"></option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <label class="control-label col-sm-2" for="from" style="text-align: right;font-size: 14px;">from:</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group" style="width: 100%;float: right">
                                                                <input type='text' style="width: 50%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%;" datepicker-here id="from" data-position="right top" data-language='en' data-date-format="yyyy-mm-dd" onchange="GenerateStatement()" value="<?php
                                                                // One month from a specific date
                                                                $date = date('Y-m-d', strtotime('-1 month'));
                                                                $arr = explode("-", $date);
                                                                $m = $arr[1];
                                                                $d = $arr[2];
                                                                $y = $arr[0];
                                                                $days = cal_days_in_month(CAL_GREGORIAN, $m, $y);
                                                                $currency = date('Y-m-d', time());
                                                                $previous = date('Y-m-d', strtotime($currency .' -'.$days.' day'));
                                                                echo $previous;
                                                                ?> "/>
                                                                <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <label class="control-label col-sm-2" for="to" style="text-align: right;font-size: 14px">To:</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group" style="width: 100%">
                                                                <input type='text' datepicker-here id="to" data-position="left top" data-language='en' data-date-format="yyyy-mm-dd" style="width: 50%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%;" value="{{date('Y-m-d', time())}}" onchange="GenerateStatement()"/>
                                                                <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <input type="checkbox" id="unpaid" class="col-sm-1" checked="checked" onchange="GenerateStatement()">
                            <label for="unpaid"class="col-sm-10" ><small>Show unpaid invoices only</small></label>
                    </div>
                </div>
            </div>

        </fieldset>

        <div class="clearfix"></div>
    </div>

</div>
<div class="row" style="margin-top: 2%;display: none;padding-right: 10%" id="action">
    <div class="col-sm-6">
    </div>
    <div class="col-sm-6">
        <div style="float: right">
            <button type="button" class="btn btn-info" onclick="customerStatementPreview()">Preview the customer view</button>
            <button type="button" class="btn btn-info">Send</button>
        </div>

    </div>
</div>

<div class="row" style="margin-top: 2%;">
    <div class="col-sm-12">
        <div style="width: 100%;text-align: center;font-size: 24px;font-weight: bold;display: none;color: #138496;" id="statement_g">STATEMENT OF ACCOUNT</div>
        <div style="width: 100%;text-align: center;font-size: 14px;display: none" id="date_g">(Generated on {{date("M d, Y",time())}})</div>
    </div>

    <div class="container" id="preview" style="width: 70%;display: block">
        <img class="img-responsive" src="{{asset('img/statement.png')}}" style="margin-left: 40%;margin-top: 10%;"/>
        <label style="margin-left: 35%;margin-top: 4%;text-align: center;font-size: 14px;color: #138496;font-weight: bold">You haven't generated any statements yet.<br /> Select a customer to generate a statement </label>
    </div>

</div>
