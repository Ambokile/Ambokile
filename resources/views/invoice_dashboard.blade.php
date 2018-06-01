<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row">
    <div class="col-sm-10 offset-1">

        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Invoices</h2>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-info" style="float: right" onclick="LoadContent('invoices')">Create New Invoice</button>
            </div>
        </div>

        <div class="row" style="width: 100%;margin-left: 0%;font-size: 14px;color: #3C4858">
            <fieldset class="col-sm-12" style="padding: 20px;background-color: white">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div style="font-weight: bold;color: dodgerblue">OVERDUE</div>
                                <div style="font-weight: bold">
                                    <?php
                                        $sum = ProductsAndServicesController::OverDueAmountInvoices();
                                        echo "Sh ".ProductsAndServicesController::money($sum);
                                    ?>
                                </div>
                                <div><small>Last updated: just a moment ago.</small></div>
                            </div>
                            <div class="col-sm-4">
                                <div style="font-weight: bold;color: dodgerblue">COMING DUE WITHIN 30 DAYS</div>
                                <div style="font-weight: bold">
                                    <?php
                                    $from  = date('Y-m-d',time());
                                    $days = cal_days_in_month(CAL_GREGORIAN, date('m',time()), date('Y'));
                                    $remain_days = $days - date('d',time());
                                    $to = date('Y-m-d', strtotime($from .' +'.$remain_days.' day'));

                                    $number= ProductsAndServicesController::DueAmountCommingFromTo($from,$to);
                                        echo "Sh ".ProductsAndServicesController::money($number);
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div style="font-weight: bold;color: dodgerblue">AVERAGE TIME TO GET PAID</div>
                                <div style="font-weight: bold">
                                    <?php

                                    $from  = date('Y-m-d',time());
                                    $days = cal_days_in_month(CAL_GREGORIAN, date('m',time()), date('Y'));
                                    $remain_days = date('d',time()) - $days;
                                    $to = date('Y-m-d', strtotime($from .' +'.$remain_days.' day'));

                                    $days = ProductsAndServicesController::AvarageDueDateInvoices($from,$payment_due);
                                        if($days > 0)
                                            echo $days."days";
                                        else
                                            echo "No day left".$from." ".$payment_due;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>


        <div class="row" style="margin-top: 3%">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group" style="width: 100%;font-size: 14px">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input type="name" class="form-control" id="search_name" placeholder="invoice #" style="font-size: 14px" onkeyup="mySearchFunctionInvoice()">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <select class="form-control js-example-basic-multiple" name="customer" data-placeholder="customer" style="width: 100%;font-size: 14px" onchange="InvoiceDashBoard()" id="customer">
                            <option value="" style="font-size: 14px;"></option>
                            @foreach($customers as $customer)
                                <option style="font-size: 14px;" value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control js-example-basic-multiple" name="status" style="width: 100%;font-size: 14px" onchange="InvoiceDashBoard()" id="status" style="width: 100%" data-placeholder="status">
                            <option tyle="font-size: 12px;" value=""></option>
                            <option tyle="font-size: 12px;" value="1"><small>unsent</small></option>
                            <option tyle="font-size: 12px;" value="2"><small>sent</small></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="input-group" style="width: 100%;float: right">
                                        <input type='text' style="width: 88%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" class="datepicker-here" id="from_invoice" data-position="right top" data-language='en' placeholder="from" data-date-format="yyyy-mm-dd"/>
                                        <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group" style="width: 100%">
                                        <input type='text' class="datepicker-here" id="to_invoice" data-position="left top" data-language='en' style="width: 88%;margin-left:0%;font-size: 13px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" placeholder="to" data-date-format="yyyy-mm-dd"/>
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

        <div class="row">
            <div class="col-sm-12" id="tab">
                <div class="tab" style="margin-top: 1%;font-size: 14px;color: darkslategrey;">
                    <button class="tablinks" id="openDefault" onclick="openTabInvoice(event, 'allinvoice')" style="font-size: 14px;color: #000;">All invoices</button>
                    <button class="tablinks" id="unpaid_btn" onclick="openTabInvoice(event, 'unpaid')" style="font-size: 14px;color: #000;">Unpaid({{count($invoice_unpaid)}})</button>
                    <button class="tablinks" id="draft_btn" onclick="openTabInvoice(event, 'draft')" style="font-size: 14px;color: #000;">Draft({{count($invoice_draft)}})</button>
                </div>

                <div id="allinvoice" class="tabcontent">
                    <div class="row" style="margin-top: 1%;padding: 0px;">
                        <div class="col-sm-10 offset-1" style="padding: 0px;overflow-y: auto;height: 300px;">
                            <div class="table-responsive">
                                <table class="table borderless" style="font-size: 14px;" id="allinvoice_table">
                                    <thead class="thead-dark">
                                    <tr style="">
                                        <th scope="col">Status</th>
                                        <th scope="col">Due</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Number</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Amount Due</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sum = 0; ?>
                                    @foreach($invoice_all as $all)
                                        <tr style="border-top: solid 0.5px #C0C0C0">
                                            <td>

                                                <?php
                                                    $now = time(); // or your date as well
                                                    $your_date = strtotime($all->payment_due);
                                                    $datediff = $your_date - $now;
                                                $days = floor($datediff / (60 * 60 * 24));
                                                $status = "";
                                                if ($days < 0){
                                                    if ($all->status > 3)
                                                        $status = "PAID";
                                                    else $status = "OVERDUE";
                                                }
                                                else{

                                                    if($all->status == 0)
                                                        $status ="DRAFT";
                                                    else if ($all->status == 1)
                                                        $status = "UNSENT";
                                                    elseif($all->status == 2)
                                                        $status = "SENT";
                                                    else if ($all->status > 3 )
                                                        $status = "PAID";
                                                }

                                                ?>
                                                @if($status == "OVERDUE")
                                                    <button type="button" class="btn btn-danger btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @elseif($status == "SENT" || $status == "UNSENT")
                                                    <button type="button" class="btn btn-success btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @else
                                                    <button type="button" class="btn btn-info btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @endif
                                            </td>
                                            <td>
                                                <?php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($all->payment_due);
                                                $datediff = $your_date - $now;
                                                $days = floor($datediff / (60 * 60 * 24));
                                                if ($days < 0){
                                                    if (abs($days) <= 1)
                                                        echo "Due ". abs($days)." day ago";
                                                    else
                                                        echo "Due in ". abs($days)." days ago";

                                                }
                                                else{
                                                    if (abs($days) <= 1)
                                                        echo "Due on ". abs($days)." day";
                                                    else
                                                        echo "Due on ". abs($days)." days";
                                                }

                                                ?>
                                            </td>
                                            <td>{{$all->invoice_date}}</td>
                                            <td>{{$all->invoice_num}}</td>
                                            <td>
                                                <?php
                                                $name = ProductsAndServicesController::customerName($all->customer_id);
                                                echo $name;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sum = ProductsAndServicesController::DueAmount($all->invoice_num);
                                                echo "<span>Tsh. </span>". ProductsAndServicesController::money($sum);
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" style="background-color: transparent"
                                                            class="btn btn-default"></button>
                                                    <button type="button" style="background-color: transparent"
                                                            class="btn btn-default dropdown-toggle dropdown-toggle-split"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" style="color: blue;">
                                                        <a class="dropdown-item" href="#"></a>
                                                        <a class="dropdown-item" href="#" onclick="loadsavedInvoice({{$all->invoice_num}})" style="font-size: 13px;">View</a>
                                                        <a class="dropdown-item" href="#" onclick="LoadContent('edit_invoice/{{$all->invoice_num}}/{{$all->customer_id}}')" style="font-size: 13px;">Edit</a>
                                                        <a class="dropdown-item" href="#" onclick="LoadContent('addPayment/{{$all->invoice_num}}/invoice_dashboard')" style="font-size: 13px;">Add Payment</a>
                                                        <a class="dropdown-item" href="{{route('sendmail')}}" style="font-size: 13px;">Send</a>
                                                        <a class="dropdown-item" href="{{route('print', ['num' => $all->invoice_num])}}" style="font-size: 13px;">Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#" onclick="DeleteInvoice({{$all->invoice_num}})" style="font-size: 13px;">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="unpaid" class="tabcontent">
                    <div class="row" style="margin-top: 1%;padding: 0px;overflow-y: auto;height: 300px;;min-height: 100px">
                        <div class="col-sm-10 offset-1" style="padding: 0px;">
                            <div class="table-responsive">
                                <table class="table borderless" style="font-size: 14px" id="unpaid_table">
                                    <thead class="thead-dark">
                                    <tr style="">
                                        <th scope="col">Status</th>
                                        <th scope="col">Due</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Number</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Amount Due</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sum = 0; ?>
                                    @foreach($invoice_unpaid as $all)
                                        <tr style="border-top: solid 0.5px #C0C0C0">
                                            <td>

                                                <?php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($all->payment_due);
                                                $datediff = $now - $your_date;
                                                $days = floor($datediff / (60 * 60 * 24));
                                                $status = "";
                                                if ($days < 0){
                                                    if($all->status == 0)
                                                        $status ="DRAFT";
                                                    else if ($all->status == 1)
                                                        $status = "UNSENT";
                                                    elseif($all->status == 2)
                                                        $status = "SENT";
                                                }
                                                else{
                                                    if ($all->status == 4 || $all->status == 5)
                                                        $status = "PAID";
                                                    else $status = "OVERDUE";
                                                }

                                                ?>
                                                @if($status == "OVERDUE")
                                                    <button type="button" class="btn btn-danger btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @elseif($status == "SENT" || $status == "UNSENT")
                                                    <button type="button" class="btn btn-success btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @else
                                                    <button type="button" class="btn btn-info btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @endif
                                            </td>
                                            <td>
                                                <?php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($all->payment_due);
                                                $datediff = $now - $your_date;
                                                $days = floor($datediff / (60 * 60 * 24));
                                                if ($days < 0){
                                                    if (abs($days) <= 1)
                                                        echo "Due on ". abs($days)." day";
                                                    else
                                                        echo "Due on ". abs($days)." days";
                                                }
                                                else{
                                                    if (abs($days) <= 1)
                                                        echo "Due ". abs($days)." day ago";
                                                    else
                                                        echo "Due in ". abs($days)." days ago";
                                                }

                                                ?>
                                            </td>
                                            <td>{{$all->payment_due}}</td>
                                            <td>{{$all->invoice_num}}</td>
                                            <td>
                                                <?php
                                                $name = ProductsAndServicesController::customerName($all->customer_id);
                                                echo $name;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sum = ProductsAndServicesController::DueAmount($all->invoice_num);
                                                echo "<span>Tsh. </span>".  ProductsAndServicesController::money($sum);
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" style="background-color: transparent"
                                                            class="btn btn-default"></button>
                                                    <button type="button" style="background-color: transparent"
                                                            class="btn btn-default dropdown-toggle dropdown-toggle-split"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" style="color: blue;z-index: 5">
                                                        <a class="dropdown-item" href="#"></a>
                                                        <a class="dropdown-item" href="#" onclick="loadsavedInvoice({{$all->invoice_num}})">View</a>
                                                        <a class="dropdown-item" href="#" onclick="LoadContent('edit_invoice/{{$all->invoice_num}}/{{$all->customer_id}}')">Edit</a>
                                                        <a class="dropdown-item" href="#" onclick="LoadContent('addPayment/{{$all->invoice_num}}')" >Add Payment</a>
                                                        <a class="dropdown-item" href="{{route('sendmail')}}">Send</a>
                                                        <a class="dropdown-item" href="#" onclick="printPDF({{$all->invoice_num}})">Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#" onclick="DeleteInvoice({{$all->invoice_num}})">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="draft" class="tabcontent">
                    <div class="row" style="margin-top: 1%;padding: 0px;;overflow-y: auto;height: 300px;;min-height: 100px">
                        <div class="col-sm-10 offset-1" style="padding: 0px">
                            <div class="table-responsive">
                                <table class="table borderless" style="font-size: 14px" id="draft_table">
                                    <thead class="thead-dark">
                                    <tr style="">
                                        <th scope="col">Status</th>
                                        <th scope="col">Due</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Number</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Amount Due</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sum = 0; ?>
                                    @foreach($invoice_draft as $all)
                                        <tr style="border-top: solid 0.5px #C0C0C0">
                                            <td>

                                                <?php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($all->payment_due);
                                                $datediff = $now - $your_date;
                                                $days = floor($datediff / (60 * 60 * 24));
                                                $status = "";
                                                if ($days < 0){
                                                    if($all->status == 0)
                                                        $status ="DRAFT";
                                                    else if ($all->status == 1)
                                                        $status = "UNSENT";
                                                    elseif($all->status == 2)
                                                        $status = "SENT";
                                                }
                                                else{
                                                    if ($all->status == 4 || $all->status == 5)
                                                        $status = "PAID";
                                                    else $status = "OVERDUE";
                                                }

                                                ?>
                                                @if($status == "OVERDUE")
                                                    <button type="button" class="btn btn-danger btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @elseif($status == "SENT" || $status == "UNSENT")
                                                    <button type="button" class="btn btn-success btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @else
                                                    <button type="button" class="btn btn-info btn-sm" style="width: 90%;font-size: 12px" onclick="loadsavedInvoice({{$all->invoice_num}})">{{$status}}</button>
                                                @endif
                                            </td>
                                            <td>
                                                <?php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($all->payment_due);
                                                $datediff = $now - $your_date;
                                                $days = floor($datediff / (60 * 60 * 24));
                                                if ($days < 0){
                                                    if (abs($days) <= 1)
                                                        echo "Due on ". abs($days)." day";
                                                    else
                                                        echo "Due on ". abs($days)." days";
                                                }
                                                else{
                                                    if (abs($days) <= 1)
                                                        echo "Due ". abs($days)." day ago";
                                                    else
                                                        echo "Due in ". abs($days)." days ago";
                                                }

                                                ?>
                                            </td>
                                            <td>{{$all->payment_due}}</td>
                                            <td>{{$all->invoice_num}}</td>
                                            <td>
                                                <?php
                                                $name = ProductsAndServicesController::customerName($all->customer_id);
                                                echo $name;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sum = ProductsAndServicesController::DueAmount($all->invoice_num);
                                                echo "<span>Tsh. </span>".  ProductsAndServicesController::money($sum);
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" style="background-color: transparent"
                                                            class="btn btn-default"></button>
                                                    <button type="button" style="background-color: transparent"
                                                            class="btn btn-default dropdown-toggle dropdown-toggle-split"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" style="color: blue;z-index: 5">
                                                        <a class="dropdown-item" href="#"></a>
                                                        <a class="dropdown-item" href="#" onclick="loadsavedInvoice({{$all->invoice_num}})">View</a>
                                                        <a class="dropdown-item" href="#" onclick="LoadContent('edit_invoice/{{$all->invoice_num}}/{{$all->customer_id}}')">Edit</a>
                                                        <a class="dropdown-item" href="{{route('sendmail')}}">Send</a>
                                                        <a class="dropdown-item" href="#" onclick="printPDF({{$all->invoice_num}})">Print</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#" onclick="DeleteInvoice({{$all->invoice_num}})">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

