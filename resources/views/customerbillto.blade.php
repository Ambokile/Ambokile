
    <div>BILL TO</div>
    @foreach($customers as $customer)
        <input id="customer_id" type="hidden" value="{{$customer->id}}">
        <div id="bill_to_bus_name" style="font-weight: bold">{{$customer->name}}</div>
        <div id="bill_to_name">{{$customer->last_name}} {{$customer->first_name}}</div>
        <div id="bill_to_email" style="margin-top: 8%">{{$customer->email}}</div>
    @endforeach
    <div id="bill_to_diff">
        <a href="#" id="mytn" style="background-color: white; font-size: 14px" onclick='addCustomerSelect()'>
            <i class="fa fa-user-plus"> choose different Customer</i>
        </a>
    </div>