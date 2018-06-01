<div class="row" style="padding-left: 5%; padding-right: 5%;margin-top: 2%">
    <div class="col-sm-9">
        <h3>Add a Product or Service</h3>
    </div>
    <div class="col-sm-3">
        <button class="btn btn-primary" onclick="LoadContent('productsandservicespurchases/{{$tax}}')">Back</button>
    </div>
</div>
</div>
<input type="hidden" value="{{$tax}}" id="htax">
<div class="row" style="margin-top: 4%;width: 80%;font-size: 14px;margin-left: 5%">
    <div class="col-sm-8 offset-md-2">
            <div class="form-group row">
                <label class="control-label col-sm-4" for="name">Name*:</label>
                <div class="col-sm-5">
                    <input type="name" name="name" class="form-control" id="name" placeholder="" autocomplete="off" style="font-size: 14px">
                    @if ($errors->has('name')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('name') }}</p> @endif
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-4" for="desc">Description*:</label>
                <div class="col-sm-8">
                    <textarea type="text" class="form-control" id="desc" placeholder="" name="desc" autocomplete="off" style="font-size: 14px"></textarea>
                    @if ($errors->has('desc')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('desc') }}</p> @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-4" for="price">Price*:</label>
                <div class="col-sm-5">
                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" min="0" value="0.00" autocomplete="off" style="font-size: 14px">
                    @if ($errors->has('price')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('price') }}</p> @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-4" for="sales">Sell this:</label>
                <div class="col-sm-8">
                    <input type="checkbox" id="sales" name="sales" value="sales" onchange="openSelectExpensesIncomeAccount()" style="font-size: 14px" />
                    <p><small>Allow this product or service to be added to Invoices.</small></p>
                    @if ($errors->has('sales')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('sales') }}</p> @endif
                </div>
            </div>
            <div class="form-group row" style="display: none" id="select_income">
                <label for="sel1" class="col-sm-4">Income account*:</label>
                <div class="row col-sm-5" id="dynamicInput">
                    <div class="col-sm-12">
                        <select class="form-control js-example-basic-multiple" id="income" name="income" data-placeholder="------------------------------------" style="width: 100%">
                            <option></option>
                            @foreach($incomes as $income)
                                <option value="{{$income->account_name}}">{{$income->account_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-4" for="buy">Buy this:</label>
                <div class="col-sm-8">
                    <input type="checkbox"  id="buy" name="buy" value="buy" onchange="openSelectExpensesIncomeAccount()" style="font-size: 14px" />
                    <p>
                        <small>Allow this product or service to be added to Bills</small>
                    </p>
                    @if ($errors->has('buy')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('buy') }}</p> @endif
                </div>
            </div>

            <div class="form-group row" style="display: none" id="select_expense">
                <label for="sel1" class="col-sm-4">Expense account*:</label>
                <div class="row col-sm-5" id="dynamicInput">
                    <div class="col-sm-12">
                        <select class="form-control js-example-basic-multiple" id="expense" name="expense" data-placeholder="---------------------------------" style="width: 100%">
                            <option></option>
                            @foreach($expenses as $expense)
                                <option value="{{$expense->account_name}}">{{$expense->account_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="sel1" class="col-sm-4">Select tax:</label>
                <div class="row col-sm-7" id="dynamicInput">
                    <div class="col-sm-12">
                        <select class="form-control js-example-basic-multiple" id="tax" name="tax[]" multiple="multiple" data-placeholder="select tax">
                            <option></option>
                            @foreach($taxes as $tax)
                                <option value="{{$tax->abbreviation}}">{{$tax->abbreviation}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="btn-group-xs">
                        <button type="button" data-role="{{$role}}" data-toggle="modal" data-target="#addTaxModal" class="btn btn-xs" style="background: transparent"><i class="fa fa-fw fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-info" id="product_send" onclick="insertProduct()">ADD</button>
                </div>
            </div>
    </div>
</div>