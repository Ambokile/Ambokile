<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<?php
    $bill_currency = array();
?>
        <table class="table table-striped table-bordered" style="font-size: 14px">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Number</th>
                <th scope="col">Vendor</th>
                <th scope="col">Account Due</th>
                <th scope="col">Total</th>
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
                    $dats = ProductsAndServicesController::sumItemVendor($bill->bill_no);
                    $total   = 0;
                    foreach ($dats as $dat){
                        $total = $total  + ($dat->price*$dat->quantity);
                    }
                    ?>
                    <tr>
                        <td>{{$bill->date}}</td>
                        <td>{{$bill->bill_no}}</td>
                        <td>{{$bill->name}}</td>
                        <td>
                            @if($bill->account == 1)
                                Accounting Fee
                            @elseif(strcmp($bill->account,2))
                                Advertising & Promotion
                            @elseif(strcmp($bill->account,3))
                                Bank Service Charges
                            @elseif(strcmp($bill->account,4))
                                Computer - Hardware
                            @else
                            @endif
                        </td>
                        <td>
                            {{$bill->cur}} <?php echo ProductsAndServicesController::money($total); ?>
                        </td>
                        <td>
                            <!-- Example split danger button -->
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
                                    <a class="dropdown-item" href="#" onclick="editbills(<?php echo $bill->bill_no.','.count( $dats)?>)">View/Edit</a>
                                    <a class="dropdown-item" href="#" onclick="LoadContent('addPaymentBill/{{$bill->bill_no}}')">Add payment</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="deletebill({{$bill->bill_no}})">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <?php
                        array_push($bill_,$bill->bill_no);
                    ?>
                @endif
            @endforeach
            </tbody>
        </table>


