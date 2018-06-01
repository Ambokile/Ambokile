<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<?php
    $bill_currency = array();
?>
<div class="row" style="padding-left: 12%; padding-right: 5%;margin-top: 3%">
    <div class="col-sm-9">
        <h2>Bill</h2>
    </div>
    <div class="col-sm-3">
        <button class="btn btn-primary" onclick="LoadContent('addbills')">Create a Bill</button>
    </div>
</div>
<div class="row" style="padding-left: 13%;margin-top: 3%; ">
    <div col-sm-6>
        <div class="row">
            <div class="col-sm-6">
                <select class="js-example-basic-multiple" data-placeholder="currency" name="" id="currency" onchange="FilterBillByCurrency('1')"  style="width: 60%;">
                    <option value=""></option>
                    @foreach($bills as $bill)
                        @if(!in_array($bill->cur,$bill_currency))
                            <option value="{{$bill->cur}}">{{$bill->cur}}</option>
                            <?php
                                array_push($bill_currency,$bill->cur);
                            ?>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type='text' placeholder="from" class="datepicker-here" id="from"
                                   data-position="right top" data-language='en' data-date-format="yyyy-mm-dd" style="width: 88%;font-size: 14px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" onkeypress="" onchange="FilterBillByCurrency('1')"/>
                            <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type='text' placeholder="to" class="datepicker-here" id="to"
                                   data-position="right top" data-language='en' data-date-format="yyyy-mm-dd" style="width: 88%;font-size: 14px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" onchange="FilterBillByCurrency('1')" />
                            <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6"></div>
</div>
<div class="row" style="margin-top: 2.5%;width: 100%">
    <div class="col-sm-10 offset-1" style="padding-left: 5%; padding-right: 5%;overflow:auto;height: 450px" id="myTable">
        <table class="table table-striped table-bordered" style="font-size: 14px">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Number</th>
                <th scope="col">Vendor</th>
                <th scope="col">Total</th>
                <th scope="col">Amount Due</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $bill_ = array();
            ?>
            @foreach($bills as $bill)
                @if(!in_array($bill->bill_no,$bill_))
                    <?php
                        $return_array = ProductsAndServicesController::sumItemVendor($bill->bill_no);
                        $dats = $return_array[0];
                        $total = $return_array[1];
                        $paid = $return_array[2];
                    ?>
                <tr>
                    <td>{{$bill->date}}</td>
                    <td>{{$bill->bill_no}}</td>
                    <td>{{$bill->name}}</td>
                    <td>
                        {{$bill->cur}} <?php echo ProductsAndServicesController::money($total); ?>
                    </td>
                    <td>
                        {{$bill->cur}} <?php $due = $total - $paid; echo $due; ?>
                    </td>
                    <td>
                        <!-- Example split danger button -->
                        <div class="btn-group" style="font-size: 14px">
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
                                <a class="dropdown-item" style="font-size: 14px" href="#" onclick="editbills(<?php echo $bill->bill_no.','.count( $dats).','.$bill->id?>)">View/Edit</a>
                                <a class="dropdown-item" href="#" onclick="LoadContent('addPaymentBill/{{$bill->bill_no}}')" style="font-size: 14px">Add payment</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" style="font-size: 14px" href="#" onclick="deletebill({{$bill->bill_no}})">Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>

                    <?php
                        array_push($bill_,$bill->bill_no)
                    ?>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>


