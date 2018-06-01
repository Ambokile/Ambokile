<?php
    $arr_currency = array();
?>
<div class="row" style="margin-left: 4%;margin-top: 3%;font-size: 14px;">
    <div class="col-sm-9">
        <h3>Add Bill</h3>
    </div>
    <div class="col-sm-3">
        <button class="btn btn-primary" id="back" onclick="LoadContent('bills')" style="margin-left: 55%;">Back</button>
    </div>
</div>
<div class="row" style="padding-left: 5%; margin-top: 2%;font-size: 14px;">
   <div class="col-sm-4">
       <div class="row">
       <div class="col-sm-12" id="vendor_data_currency">
               <div class="form-group row">
                   <label class="control-label col-sm-4" for="vendor">Vendor*:</label>
                   <div class="col-sm-8">
                       <select class="form-control js-example-basic-multiple" name="currency" style="width:65%" id="vendor" onchange="getCurrencyData()">
                           @foreach($vendors as $vendor)
                               <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                           @endforeach
                       </select>
                   </div>
               </div>
               <div class="form-group row">
                   <label class="control-label col-sm-4" for="currency">Currency*:</label>
                   <div class="col-sm-8">
                       <select class="form-control js-example-basic-multiple" name="currency" style="width:65%" id="currency">
                               @foreach($vendors as $vendor)
                                   @if(!in_array($vendor->currency,$arr_currency))
                                       <option value="{{$vendor->currency}}">{{$vendor->currency}}</option>
                                       <?php
                                            array_push($arr_currency,$vendor->currency)
                                       ?>
                                   @endif
                               @endforeach
                       </select>
                   </div>
               </div>
         </div>
       </div>
   </div>
   <div class="col-sm-4">
       <div class="row">
       <div class="col-sm-12">
               <div class="form-group row">
                   <label class="control-label col-sm-4" for="from">Date*:</label>

                   <div class="col-sm-8">
                       <div class="input-group">
                           <input type='text' style="width: 50%;margin-left:5%;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" datepicker-here id="from" data-position="right top" data-language='en' data-date-format="yyyy-mm-dd" value="{{date("Y-m-d",time())}}"/>
                           <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                               <i class="fa fa-calendar" aria-hidden="true"></i>
                           </div>
                       </div>

                   </div>
               </div>
               <div class="form-group row">
                   <label class="control-label col-sm-4" for="to">Due Date*:</label>
                   <div class="col-sm-8">
                       <div class="input-group">
                           <input type='text' datepicker-here id="to" data-position="right top" data-language='en' style="width: 50%;margin-left:5%;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d",strtotime("+5 days"))}}"/>
                           <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                               <i class="fa fa-calendar" aria-hidden="true"></i>
                           </div>
                       </div>

                   </div>
               </div>
               <div class="form-group row">
                   <label class="control-label col-sm-4" for="po">P.O./S.O.*:</label>
                   <div class="col-sm-8">
                       <input type="name" class="form-control" style="width:66%;margin-left:5%" id="po">
                   </div>
               </div>
       </div>
       </div>
   </div>
   <div class="col-sm-4">
       <div class="row">
           <div class="col-sm-12">
               <form class="form-horizontal">
                   <div class="form-group row">
                       <label class="control-label col-sm-2" for="bill_num">Bill#*</label>
                       <div class="col-sm-10">
                           <input type="name" class="form-control" id="bill_num" placeholder="####" style="width:65%">
                       </div>
                   </div>
                   <div class="form-group row">
                       <label class="control-label col-sm-2" for="notes">Notes:</label>
                       <div class="col-sm-10">
                           <textarea type="name" class="form-control" style="width:65%" id="notes"></textarea>
                       </div>
                   </div>
               </form>
           </div>
       </div>
   </div>
</div>

<div class="row" style="margin-top: 2.5%">
    <div class="col-sm-12" style="padding-left: 5%; padding-right: 5%">
        <table class="table table-striped borderless" style="border: solid 1px #CCCCCC;font-size: 14px;">
            <thead>
            <tr >
                <th scope="col">Item</th>
                <th scope="col">Expenses Category</th>
                <th scope="col">Description</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Tax</th>
                <th scope="col">Amount(Tsh.)</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody id="dataTable">
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td colspan="2">
                        <span>Subtotal: </span><span style="float: right;" id="subtotal">0</span><br />
                        <div style="border-bottom: solid 1px #C0C0C0;padding-top: 5%;" id="tax_space"></div>
                        <span style="padding-top: 5%">Total: </span><span  style="float: right;padding-top: 5%" id="total">Tsh 0.00</span>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row" style="padding-left: 4%;margin-bottom: 5%;">
    <div class="col-sm-12">
        <button  type="button" id="addrowbtn" onclick=addRowBill() class="btn btn-primary">ADD Row</button>         <button  type="button" id="addbillbtn" class="btn btn-primary" onclick="addBill();">ADD BILL</button>
    </div>
</div>