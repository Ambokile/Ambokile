<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row">
    <div class="col-sm-10 offset-1">

        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Chart of Accounts</h2>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-info" style="float: right" onclick="add_account_form_open()">Add New Account</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12" id="tab">
                <div class="tab" style="margin-top: 1%">
                    <button class="tablinks" id="openDefault" onclick="openChartAccount(event, 'assets')">Assets ({{count($account_assets)}})</button>
                    <button class="tablinks" id="openDefault_liabilities" onclick="openChartAccount(event, 'liabilities')">Liabilities & Credit Card ({{count($account_liabilities)}})</button>
                    <button class="tablinks" id="openDefault_income" onclick="openChartAccount(event, 'income')">Income ({{count($account_incomes)}})</button>

                    <button class="tablinks" id="openDefault_expenses" onclick="openChartAccount(event, 'expenses')">Expenses ({{count($account_expenses)}})</button>
                    <button class="tablinks" id="openDefault_equity" onclick="openChartAccount(event, 'equity')">Equity ({{count($account_equities)}})</button>
                </div>

                <div id="assets" class="tabcontent">

                    <div class="row">
                        <div class="col-12 first_part" id="first_part" style="overflow-y: auto;height: 550px;">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($account_assets as $account_asset)
                                    <a class="list-group-item list-group-item-action" id="list-home-list{{$account_asset->id}}" data-toggle="list" onclick=expandAccountList("{{$account_asset->id}}",0)  role="tab" aria-controls="home">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <i class="fa fa-dot-circle-o" style="margin-top: 25%;font-size: 34px"></i>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{$account_asset->account_name}}</h5>
                                                    <small>{{$account_asset->currency}}</small>
                                                </div>
                                                <small>{{$account_asset->account_type}}</small>
                                            </div>
                                        </div>

                                    </a>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-12 second_part" id="second_part" style="background-color: #f0f0f0">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($account_assets as $account_asset)
                                    <div class="tab-pane fade" id="{{$account_asset->id}}" role="tabpanel" aria-labelledby="list-home-list">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h5 style="font-size: 15px;margin-top: 5%;">Update Chart of Account</h5>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-outline-danger btn-xs" style="margin-top: 10px;" onclick=deleteAccount("{{$account_asset->id}}")><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="col-sm-8 offset-2">
                                            <div class="progress" id="progress_account{{$account_asset->id}}" style="display: none">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    <span>Please wait<span class="dotdotdot"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 offset-md-1">
                                                <div id="errormsg_account{{$account_asset->id}}" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_type_edit{{$account_asset->id}}" class="col-sm-4 col-form-label">Account Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_type_edit{{$account_asset->id}}" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <optgroup label="ASSETS">
                                                        <option @if(strcmp($account_asset->account_type, "Cash and Bank") == 0) selected="selected" @endif>Cash and Bank</option>
                                                        <option @if(strcmp($account_asset->account_type, "Money in Transit") == 0) selected="selected" @endif>Money in Transit</option>
                                                        <option @if(strcmp($account_asset->account_type, "Expected Payment from customers") == 0) selected="selected" @endif>Expected Payment from customers</option>
                                                        <option @if(strcmp($account_asset->account_type,"Inventory") == 0) selected="selected" @endif>Inventory</option>
                                                        <option @if(strcmp($account_asset->account_type, "Property, Plant and Equipment") == 0) selected="selected" @endif>Property, Plant and Equipment</option>
                                                        <option @if(strcmp($account_asset->account_type, "Depreciation and Amortization") == 0) selected="selected" @endif>Depreciation and Amortization</option>
                                                        <option @if(strcmp($account_asset->account_type, "Vendor Prepayment and Vendor Credits") == 0) selected="selected" @endif>Vendor Prepayment and Vendor Credits</option>
                                                        <option @if(strcmp($account_asset->account_type,"Other short-Term Asset") == 0) selected="selected" @endif>Other short-Term Asset</option>
                                                        <option @if(strcmp($account_asset->account_type,"Other Long-Term Asset") == 0) selected="selected" @endif>Other Long-Term Asset</option>
                                                    </optgroup>
                                                    <optgroup label="LIABILITIES & CREDIT CARD">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"LIABILITIES & CREDIT CARD") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                            @endforeach

                                                    </optgroup>
                                                    <optgroup label="INCOME">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"INCOME") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EXPENSES">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EXPENSES") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EQUITY">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EQUITY") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_name_edit{{$account_asset->id}}" class="col-sm-4 col-form-label">Account name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_name_edit{{$account_asset->id}}" value="{{$account_asset->account_name}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_id_edit{{$account_asset->id}}" class="col-sm-4 col-form-label" >Account ID</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_id_edit{{$account_asset->id}}" value="{{$account_asset->account_id}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_currency_edit{{$account_asset->id}}" class="col-sm-4 col-form-label">Currency</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_currency_edit{{$account_asset->id}}" style="width: 78%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <option @if(strcmp($account_asset->currency,"Tsh") == 0) selected="selected" @endif>Tsh</option>
                                                    <option @if(strcmp($account_asset->currency,"USD") == 0) selected="selected" @endif>USD</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_desc_edit{{$account_asset->id}}" class="col-sm-4 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" rows="5" id="account_desc_edit{{$account_asset->id}}" style="width: 98%;font-size: 14px;" style="font-size: 14px;">{{$account_asset->description}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <div class="col-sm-6">
                                                <button type="button" id="cancel_account_edit{{$account_asset->id}}" class="btn btn-default btn-block" style="margin-top: 0%;background-color: white;border: solid 1px lightblue" onclick=edit_account_form_close("{{$account_asset->id}}",0)>Cancel</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" id="save_account_edit{{$account_asset->id}}" class="btn btn-info btn-block" style="margin-top: 0%;" onclick=editAccount("{{$account_asset->id}}")>Save</button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                </div>

                <div id="liabilities" class="tabcontent">


                    <div class="row" >
                        <div class="col-12" id="third_part" style="overflow-y: auto;height: 550px;">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($account_liabilities as $account_liability)
                                    <a class="list-group-item list-group-item-action" id="list-home-list{{$account_liability->id}}" data-toggle="list" onclick=expandAccountList("{{$account_liability->id}}",1)  role="tab" aria-controls="home">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <i class="fa fa-dot-circle-o" style="margin-top: 25%;font-size: 34px"></i>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{$account_liability->account_name}}</h5>
                                                    <small>{{$account_liability->currency}}</small>
                                                </div>
                                                <small>{{$account_liability->account_type}}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-12" id="fourth_part" style="background-color: #f0f0f0">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($account_liabilities as $account_liability)
                                    <div class="tab-pane fade" id="{{$account_liability->id}}" role="tabpanel" aria-labelledby="list-home-list">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h5 style="font-size: 15px;margin-top: 5%;">Update Chart of Account</h5>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-outline-danger btn-xs" style="margin-top: 10px;" onclick=deleteAccount("{{$account_liability->id}}")><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="col-sm-8 offset-2">
                                            <div class="progress" id="progress_account{{$account_liability->id}}" style="display: none">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    <span>Please wait<span class="dotdotdot"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 offset-md-1">
                                                <div id="errormsg_account{{$account_liability->id}}" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_type_edit{{$account_liability->id}}" class="col-sm-4 col-form-label">Account Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_type_edit{{$account_liability->id}}" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <optgroup label="ASSETS">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"ASSETS") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="LIABILITIES & CREDIT CARD">
                                                        <option @if(strcmp($account_liability->account_type,"Credit Card") == 0) selected="selected" @endif>Credit Card</option>
                                                        <option @if(strcmp($account_liability->account_type,"Loan and Line of Credit") == 0) selected="selected" @endif>Loan and Line of Credit</option>
                                                        <option @if(strcmp($account_liability->account_type,"Expected Payments to Vendors") == 0) selected="selected" @endif>Expected Payments to Vendors</option>
                                                        <option @if(strcmp($account_liability->account_type,"Due For Payroll") == 0) selected="selected" @endif>Due For Payroll</option>
                                                        <option @if(strcmp($account_liability->account_type,"Due to You and Other Business Owners") == 0) selected="selected" @endif>Due to You and Other Business Owners</option>
                                                        <option @if(strcmp($account_liability->account_type,"Customer Prepayments and Customer Credits") == 0) selected="selected" @endif>Customer Prepayments and Customer Credits</option>
                                                        <option @if(strcmp($account_liability->account_type,"Other Short-Term Liability") == 0) selected="selected" @endif>Other Short-Term Liability</option>
                                                        <option @if(strcmp($account_liability->account_type,"Other Long-Term Liability") == 0) selected="selected" @endif>Other Long-Term Liability</option>
                                                    </optgroup>
                                                    <optgroup label="INCOME">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"INCOME") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EXPENSES">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EXPENSES") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EQUITY">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EQUITY") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_name_edit{{$account_liability->id}}" class="col-sm-4 col-form-label">Account name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_name_edit{{$account_liability->id}}" value="{{$account_liability->account_name}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_id_edit{{$account_liability->id}}" class="col-sm-4 col-form-label" >Account ID</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_id_edit{{$account_liability->id}}" value="{{$account_liability->account_id}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_currency_edit{{$account_liability->id}}" class="col-sm-4 col-form-label">Currency</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_currency_edit{{$account_liability->id}}" style="width: 78%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <option @if(strcmp($account_liability->currency,"Tsh") == 0) selected="selected" @endif>Tsh</option>
                                                    <option @if(strcmp($account_liability->currency,"USD") == 0) selected="selected" @endif>USD</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_desc_edit{{$account_liability->id}}" class="col-sm-4 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" rows="5" id="account_desc_edit{{$account_liability->id}}" style="width: 98%;font-size:14px" style="font-size: 14px;">{{$account_liability->description}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <div class="col-sm-6">
                                                <button type="button" id="cancel_account_edit{{$account_liability->id}}" class="btn btn-default btn-block" style="margin-top: 0%;background-color: white;border: solid 1px lightblue" onclick=edit_account_form_close("{{$account_liability->id}}",1)>Cancel</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" id="save_account_edit{{$account_liability->id}}" class="btn btn-info btn-block" style="margin-top: 0%;" onclick=editAccount("{{$account_liability->id}}")>Save</button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                <div id="income" class="tabcontent">


                    <div class="row">
                        <div class="col-12 first_part" id="fifth_part" style="overflow-y: auto;height: 550px;">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($account_incomes as $account_income)
                                    <a class="list-group-item list-group-item-action" id="list-home-list{{$account_income->id}}" data-toggle="list" onclick=expandAccountList("{{$account_income->id}}",2)  role="tab" aria-controls="home">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <i class="fa fa-dot-circle-o" style="margin-top: 25%;font-size: 34px"></i>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{$account_income->account_name}}</h5>
                                                    <small>{{$account_income->currency}}</small>
                                                </div>
                                                <small>{{$account_income->account_type}}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-12" id="sixth_part" style="background-color: #f0f0f0">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($account_incomes as $account_income)
                                    <div class="tab-pane fade" id="{{$account_income->id}}" role="tabpanel" aria-labelledby="list-home-list">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h5 style="font-size: 15px;margin-top: 5%;">Update Chart of Account</h5>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-outline-danger btn-xs" style="margin-top: 10px;" onclick=deleteAccount("{{$account_income->id}}")><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="col-sm-8 offset-2">
                                            <div class="progress" id="progress_account{{$account_income->id}}" style="display: none">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    <span>Please wait<span class="dotdotdot"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 offset-md-1">
                                                <div id="errormsg_account{{$account_income->id}}" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_type_edit{{$account_income->id}}" class="col-sm-4 col-form-label">Account Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_type_edit{{$account_income->id}}" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <optgroup label="ASSETS">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"ASSETS") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="LIABILITIES & CREDIT CARD">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"LIABILITIES & CREDIT CARD") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="INCOME">
                                                        <option @if(strcmp($account_income->account_type, "Income") == 0) selected="selected" @endif>Income</option>
                                                        <option @if(strcmp($account_income->account_type, "Discount") == 0) selected="selected" @endif>Discount</option>
                                                        <option @if(strcmp($account_income->account_type, "Other Income") == 0) selected="selected" @endif>Other Income</option>
                                                        <option @if(strcmp($account_income->account_type, "Uncategorized Income") == 0) selected="selected" @endif>Uncategorized Income</option>
                                                    </optgroup>
                                                    <optgroup label="EXPENSES">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EXPENSES") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EQUITY">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EQUITY") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_name_edit{{$account_income->id}}" class="col-sm-4 col-form-label">Account name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_name_edit{{$account_income->id}}" value="{{$account_income->account_name}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_id_edit{{$account_income->id}}" class="col-sm-4 col-form-label" >Account ID</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_id_edit{{$account_income->id}}" value="{{$account_income->account_id}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_currency_edit{{$account_income->id}}" class="col-sm-4 col-form-label">Currency</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_currency_edit{{$account_income->id}}" style="width: 78%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <option @if(strcmp($account_income->currency,"Tsh") == 0) selected="selected" @endif>Tsh</option>
                                                    <option @if(strcmp($account_income->currency,"USD") == 0) selected="selected" @endif>USD</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_desc_edit{{$account_income->id}}" class="col-sm-4 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" rows="5" id="account_desc_edit{{$account_income->id}}" style="width: 98%;font-size:14px" style="font-size: 14px;">{{$account_income->description}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <div class="col-sm-6">
                                                <button type="button" id="cancel_account_edit{{$account_income->id}}" class="btn btn-default btn-block" style="margin-top: 0%;background-color: white;border: solid 1px lightblue" onclick=edit_account_form_close("{{$account_income->id}}",2)>Cancel</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" id="save_account_edit{{$account_income->id}}" class="btn btn-info btn-block" style="margin-top: 0%;" onclick=editAccount("{{$account_income->id}}")>Save</button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                </div>

                <div id="expenses" class="tabcontent">
                    <div class="row">
                        <div class="col-12 first_part" id="seventh_part" style="overflow-y: auto;height: 550px;">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($account_expenses as $account_expense)
                                    <a class="list-group-item list-group-item-action" id="list-home-list{{$account_expense->id}}" data-toggle="list" onclick=expandAccountList("{{$account_expense->id}}",3)  role="tab" aria-controls="home">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <i class="fa fa-dot-circle-o" style="margin-top: 25%;font-size: 34px"></i>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{$account_expense->account_name}}</h5>
                                                    <small>{{$account_expense->currency}}</small>
                                                </div>
                                                <small>{{$account_expense->account_type}}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-12" id="eighth_part" style="background-color: #f0f0f0">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($account_expenses as $account_expense)
                                    <div class="tab-pane fade" id="{{$account_expense->id}}" role="tabpanel" aria-labelledby="list-home-list">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h5 style="font-size: 15px;margin-top: 5%;">Update Chart of Account</h5>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-outline-danger btn-xs" style="margin-top: 10px;" onclick=deleteAccount("{{$account_expense->id}}")><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="col-sm-8 offset-2">
                                            <div class="progress" id="progress_account{{$account_expense->id}}" style="display: none">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    <span>Please wait<span class="dotdotdot"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 offset-md-1">
                                                <div id="errormsg_account{{$account_expense->id}}" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_type_edit{{$account_expense->id}}" class="col-sm-4 col-form-label">Account Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_type_edit{{$account_expense->id}}" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <optgroup label="ASSETS">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"ASSETS") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="LIABILITIES & CREDIT CARD">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"LIABILITIES & CREDIT CARD") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="INCOME">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"INCOME") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EXPENSES">
                                                        <option @if(strcmp($account_expense->account_type, "Operating Expenses") == 0) selected="selected" @endif>Operating Expenses</option>
                                                        <option @if(strcmp($account_expense->account_type, "Cost of Good Sold") == 0) selected="selected" @endif>Cost of Good Sold</option>
                                                        <option @if(strcmp($account_expense->account_type, "Payment Processing Fee") == 0) selected="selected" @endif>Payment Processing Fee</option>
                                                        <option @if(strcmp($account_expense->account_type, "Payroll Expenses") == 0) selected="selected" @endif>Payroll Expenses</option>
                                                        <option @if(strcmp($account_expense->account_type, "Uncategorized Expense") == 0) selected="selected" @endif>Uncategorized Expense</option>
                                                    </optgroup>
                                                    <optgroup label="EQUITY">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EQUITY") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_name_edit{{$account_expense->id}}" class="col-sm-4 col-form-label">Account name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_name_edit{{$account_expense->id}}" value="{{$account_expense->account_name}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_id_edit{{$account_expense->id}}" class="col-sm-4 col-form-label" >Account ID</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_id_edit{{$account_expense->id}}" value="{{$account_expense->account_id}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_currency_edit{{$account_expense->id}}" class="col-sm-4 col-form-label">Currency</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_currency_edit{{$account_expense->id}}" style="width: 78%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <option @if(strcmp($account_expense->currency,"Tsh") == 0) selected="selected" @endif>Tsh</option>
                                                    <option @if(strcmp($account_expense->currency,"USD") == 0) selected="selected" @endif>USD</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_desc_edit{{$account_expense->id}}" class="col-sm-4 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" rows="5" id="account_desc_edit{{$account_expense->id}}" style="width: 98%;font-size: 14px;">{{$account_expense->description}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <div class="col-sm-6">
                                                <button type="button" id="cancel_account_edit{{$account_expense->id}}" class="btn btn-default btn-block" style="margin-top: 0%;background-color: white;border: solid 1px lightblue" onclick=edit_account_form_close("{{$account_expense->id}}",3)>Cancel</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" id="save_account_edit{{$account_expense->id}}" class="btn btn-info btn-block" style="margin-top: 0%;" onclick=editAccount("{{$account_expense->id}}")>Save</button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div id="equity" class="tabcontent">


                    <div class="row">
                        <div class="col-12" id="nineth_part" style="overflow-y: auto;height: 550px;">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($account_equities as $account_equity)
                                    <a class="list-group-item list-group-item-action" id="list-home-list{{$account_equity->id}}" data-toggle="list" onclick=expandAccountList("{{$account_equity->id}}",4)  role="tab" aria-controls="home">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <i class="fa fa-dot-circle-o" style="margin-top: 25%;font-size: 34px"></i>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{$account_equity->account_name}}</h5>
                                                    <small>{{$account_equity->currency}}</small>
                                                </div>
                                                <small>{{$account_equity->account_type}}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-12" id="tenth_part" style="background-color: #f0f0f0">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($account_equities as $account_equity)
                                    <div class="tab-pane fade" id="{{$account_equity->id}}" role="tabpanel" aria-labelledby="list-home-list">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h5 style="font-size: 15px;margin-top: 5%;">Update Chart of Account</h5>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-outline-danger btn-xs" style="margin-top: 10px;" onclick=deleteAccount("{{$account_equity->id}}")><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="col-sm-8 offset-2">
                                            <div class="progress" id="progress_account{{$account_equity->id}}" style="display: none">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    <span>Please wait<span class="dotdotdot"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 offset-md-1">
                                                <div id="errormsg_account{{$account_equity->id}}" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_type_edit{{$account_equity->id}}" class="col-sm-4 col-form-label">Account Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_type_edit{{$account_equity->id}}" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">

                                                    <optgroup label="ASSETS">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"ASSETS") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="LIABILITIES & CREDIT CARD">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"LIABILITIES & CREDIT CARD") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="INCOME">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"INCOME") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EXPENSES">
                                                        @foreach($account_category as $category)
                                                            @if(strpos($category->account_category,"EXPENSES") !== false)
                                                                <option>{{$category->account_type}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="EQUITY">
                                                        <option @if(strcmp($account_equity->account_type, "Business Owner Contribution") == 0) selected="selected" @endif>Business Owner Contribution</option>
                                                        <option @if(strcmp($account_equity->account_type, "Retain Earning - Profit and Business") == 0) selected="selected" @endif>Retain Earning - Profit and Business</option>
                                                        <option @if(strcmp($account_equity->account_type, "Owner Drawing") == 0) selected="selected" @endif>Owner Drawing</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_name_edit{{$account_equity->id}}" class="col-sm-4 col-form-label">Account name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_name_edit{{$account_equity->id}}" value="{{$account_equity->account_name}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_id_edit{{$account_equity->id}}" class="col-sm-4 col-form-label" >Account ID</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="account_id_edit{{$account_equity->id}}" value="{{$account_equity->account_id}}" style="font-size: 14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_currency_edit{{$account_equity->id}}" class="col-sm-4 col-form-label">Currency</label>
                                            <div class="col-sm-8">
                                                <select class="form-control js-example-basic-multiple" id="account_currency_edit{{$account_equity->id}}" style="width: 78%;background-color: #FFFFFF;border-style: none;z-index: 5">
                                                    <option @if(strcmp($account_equity->currency,"Tsh") == 0) selected="selected" @endif>Tsh</option>
                                                    <option @if(strcmp($account_equity->currency,"USD") == 0) selected="selected" @endif>USD</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="font-size: 14px;">
                                            <label for="account_desc_edit{{$account_equity->id}}" class="col-sm-4 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" rows="5" id="account_desc_edit{{$account_equity->id}}" style="width: 98%;font-size: 14px;">{{$account_equity->description}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="font-size: 14px;">
                                            <div class="col-sm-6">
                                                <button type="button" id="cancel_account_edit{{$account_equity->id}}" class="btn btn-default btn-block" style="margin-top: 0%;background-color: white;border: solid 1px lightblue" onclick=edit_account_form_close("{{$account_equity->id}}",4)>Cancel</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" id="save_account_edit{{$account_equity->id}}" class="btn btn-info btn-block" style="margin-top: 0%;" onclick=editAccount("{{$account_equity->id}}")>Save</button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>

<div class="panel panel-default" style="position: fixed;bottom: 5%;right: 1%;z-index: 2;background-color: #f0f0f0;padding: 1%;display: none;border: solid 1px #C0C0C0" id="add_account_form">
    <div class="panel-heading" style="background-color: #f0f0f0;padding-left: 1%;padding-right: 1%;">
        <div class="row" style="margin-bottom: 0%;border-bottom: solid 1px #C0C0C0;">
            <div class="col-sm-9" style="font-size: 15px">
                <span>Add an Account</span>
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn btn-sm btn-default" style="float: right;background-color: transparent" onclick="add_account_form_close()"><i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
    <div class="panel-body" style="height: 350px; overflow-y: auto;background-color:#f0f0f0;overflow-x: hidden;">
        <div class="row" style="margin-top: 5%;margin-bottom: 4%;">
            <div class="col-sm-11" style="margin-left: 3%;">
                <div class="col-sm-8 offset-2">
                    <div class="progress" id="progress_account" style="display: none">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            <span>Please wait<span class="dotdotdot"></span></span>
                        </div>
                    </div>
                    <div class="col-sm-10 offset-md-1">
                        <div id="errormsg_account" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;font-size: 12px">
                        </div>
                    </div>
                </div>
                <div class="form-group row" style="font-size: 14px;">
                    <label for="account_type_add" class="col-sm-5 col-form-label">Account Type*:</label>
                    <div class="col-sm-7">
                        <select class="form-control js-example-basic-multiple" id="account_type_add" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                            <optgroup label="ASSETS">
                                <option>Cash and Bank</option>
                                <option>Money in Transit</option>
                                <option>Expected Payment from customers</option>
                                <option>Inventory</option>
                                <option>Property, Plant and Equipment</option>
                                <option>Depreciation and Amortization</option>
                                <option>Vendor Prepayment and Vendor Credits</option>
                                <option>Other short-Term Asset</option>
                                <option>Other Long-Term Asset</option>
                            </optgroup>
                            <optgroup label="LIABILITIES & CREDIT CARD">
                                <option>Credit Card</option>
                                <option>Loan and Line of Credit</option>
                                <option>Expected Payments to Vendors</option>
                                <option>Due For Payroll</option>
                                <option>Due to You and Other Business Owners</option>
                                <option>Customer Prepayments and Customer Credits</option>
                                <option>Other Short-Term Liability</option>
                                <option>Other Long-Term Liability</option>
                            </optgroup>
                            <optgroup label="INCOME">
                                <option>Income</option>
                                <option>Discount</option>
                                <option>Other Income</option>
                                <option>Uncategorized Income</option>
                            </optgroup>
                            <optgroup label="EXPENSES">
                                <option>Operating Expenses</option>
                                <option>Cost of Good Sold</option>
                                <option>Payment Processing Fee</option>
                                <option>Payroll Expenses</option>
                                <option>Uncategorized Expense</option>
                            </optgroup>
                            <optgroup label="EQUITY">
                                <option>Business Owner Contribution</option>
                                <option>Retain Earning - Profit and Business Owner Drawing</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="font-size: 14px;">
                    <label for="account_name_add" class="col-sm-5 col-form-label">Account name*</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="account_name_add" placeholder="">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 14px;">
                    <label for="account_currency_add" class="col-sm-5 col-form-label">Account Currency</label>
                    <div class="col-sm-7">
                        <select class="form-control js-example-basic-multiple" id="account_currency_add" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">
                            <option>Tsh</option>
                            <option>USD</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="font-size: 14px;">
                    <label for="account_id_add" class="col-sm-5 col-form-label">Account ID</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="account_id_add" placeholder="">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 14px;">
                    <label for="account_desc_add" class="col-sm-5 col-form-label">Description</label>
                    <div class="col-sm-7">
                        <textarea class="form-control" rows="5" id="account_desc_add" style="width: 98%;font-size:14px"></textarea>
                    </div>
               </div>
                <div class="form-group row" style="font-size: 14px;">
                    <div class="col-sm-6">
                        <button type="button" id="cancel_account" class="btn btn-default btn-block" style="margin-top: 0%;background-color: white;border: solid 1px lightblue" onclick="add_account_form_close()">Cancel</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" id="save_account" class="btn btn-info btn-block" style="margin-top: 0%;" onclick="addAccount()">Save</button>
                    </div>
                </div>
        </div>
    </div>
</div>