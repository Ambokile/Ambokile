<?php use App\Http\Controllers\ProductsAndServicesController; ?>

        <div class="tab" style="margin-top: 1%">
            <button class="tablinks" id="openDefault" onclick="openTabInvoice(event, 'allinvoice')">All invoices</button>
            <button class="tablinks" id="unpaid_btn" onclick="openTabInvoice(event, 'unpaid')">Unpaid({{count($invoice_unpaid)}})</button>
            <button class="tablinks" id="draft_btn" onclick="openTabInvoice(event, 'draft')">Draft({{count($invoice_draft)}})</button>
        </div>

        <div id="allinvoice" class="tabcontent">
            <div class="row" style="margin-top: 3%;padding: 0px">
                <div class="col-sm-10 offset-1" style="padding: 0px">
                    <div class="table-responsive">
                        <table class="table borderless" style="font-size: 14px" id="allinvoice_table">
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
                                        $datediff = $now - $your_date;
                                        $days = floor($datediff / (60 * 60 * 24));
                                        $status = "";
                                        if ($days < 0){
                                            if($all->status == 0)
                                                $status ="DRAFT";
                                            else if ($all->status == 1 || $all->status == 4)
                                                $status = "UNSENT";
                                            elseif($all->status == 2 || $all->status == 5)
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
                                            <div class="dropdown-menu" style="color: blue">
                                                <a class="dropdown-item" href="#"></a>
                                                <a class="dropdown-item" href="#" onclick="loadsavedInvoice({{$all->invoice_num}})">View</a>
                                                <a class="dropdown-item" href="#" onclick="LoadContent('edit_invoice/{{$all->invoice_num}}/{{$all->customer_id}}')">Edit</a>
                                                <a class="dropdown-item" href="#" onclick="LoadContent('addPayment/{{$all->invoice_num}}')" >Add Payment</a>
                                                <a class="dropdown-item" href="#">Send</a>
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

        <div id="unpaid" class="tabcontent">
            <div class="row" style="margin-top: 3%;padding: 0px">
                <div class="col-sm-10 offset-1" style="padding: 0px">
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
                                            <div class="dropdown-menu" style="color: blue">
                                                <a class="dropdown-item" href="#"></a>
                                                <a class="dropdown-item" href="#" onclick="loadsavedInvoice({{$all->invoice_num}})">View</a>
                                                <a class="dropdown-item" href="#" onclick="LoadContent('edit_invoice/{{$all->invoice_num}}/{{$all->customer_id}}')">Edit</a>
                                                <a class="dropdown-item" href="#" onclick="LoadContent('addPayment/{{$all->invoice_num}}')" >Add Payment</a>
                                                <a class="dropdown-item" href="#">Send</a>
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
            <div class="row" style="margin-top: 3%;padding: 0px">
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
                                            <div class="dropdown-menu" style="color: blue">
                                                <a class="dropdown-item" href="#"></a>
                                                <a class="dropdown-item" href="#" onclick="loadsavedInvoice({{$all->invoice_num}})">View</a>
                                                <a class="dropdown-item" href="#" onclick="LoadContent('edit_invoice/{{$all->invoice_num}}/{{$all->customer_id}}')">Edit</a>
                                                <a class="dropdown-item" href="#">Send</a>
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

