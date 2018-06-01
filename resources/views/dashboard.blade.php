<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>DaftariPlus</title>
  <link rel="stylesheet" href="{{asset('css/datepicker.min.css')}}">
  <!-- Bootstrap core CSS-->
  <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('css/bootstrap-select.css')}}" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="{{asset('css/js/dataTables.bootstrap4.css')}}" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">
  <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
  <link rel="stylesheet" href="{{asset('css//select2.min.css')}}" />
 {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/css/mdb.min.css" />--}}
  <style>
    fieldset
    {
      border: 1px solid #ddd !important;
      margin: 0;
      xmin-width: 0;
      padding: 10px;
      position: relative;
      border-radius:4px;
      background-color:#f5f5f5;
      padding-left:10px!important;
    }

    legend
    {
      font-size:14px;
      font-weight:bold;
      margin-bottom: 0px;
      width: 35%;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 5px 5px 5px 10px;
      background-color: #ffffff;
    }

    textarea {
       height: 2.3em;
       width: 50%;
       padding: 1px;
       transition: all 0.5s ease;
    }

    textarea:focus {
      height: 4em;
    }

    .panel-heading [data-toggle="collapse"]:after
    {
      content: "\f053";
      font-family: FontAwesome;
      float: right;
      color: #b0c5d8;
      font-size: 13px;
      line-height: 22px;
      text-decoration: none;

      -webkit-transform: rotate(-90deg);
      -moz-transform:    rotate(-90deg);
      -ms-transform:     rotate(-90deg);
      -o-transform:      rotate(-90deg);
      transform:         rotate(-90deg);
    }
    .panel-heading [data-toggle="collapse"].collapsed:after
    {
      -webkit-transform: rotate(90deg);
      -moz-transform:    rotate(90deg);
      -ms-transform:     rotate(90deg);
      -o-transform:      rotate(90deg);
      transform:         rotate(90deg);
    }

    #myTable
    {
      position:relative;// so that .modal & .modal-backdrop gets positioned relative to it
    }

    .modal, .modal-backdrop {
      position: absolute !important;
    }

    .btn-file {
      position: relative;
      overflow: hidden;
    }
    .btn-file input[type=file] {
      position: absolute;
      top: 0;
      right: 0;
      min-width: 100%;
      min-height: 100%;
      font-size: 100px;
      text-align: right;
      filter: alpha(opacity=0);
      opacity: 0;
      outline: none;
      background: white;
      cursor: inherit;
      display: block;
    }

    .table-no-border>thead>tr>th,
    .table-no-border>tbody>tr>th,
    .table-no-border>tfoot>tr>th,
    .table-no-border>thead>tr>td,
    .table-no-border>tbody>tr>td,
    .table-no-border>tfoot>tr>td {
      border-top: none;
    }

    .borderless td, .borderless th, .borderless tbody, .borderless thead {
      border: none;
    }
    body {font-family: Arial;}

    /* Style the tab */
    div.tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    div.tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
      font-size: 17px;
    }

    /* Change background color of buttons on hover */
    div.tab button:hover {
      background-color: #ddd;
    }

    /* Create an active/current tablink class */
    div.tab button.active {
      background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
      display: none;
      padding: 6px 12px;
      border: 1px solid #ccc;
      border-top: none;
    }
    .vl {
      border-left: 6px solid green;
      height: 500px;
    }
    .progress {
        margin: 15px;
    }

    .progress .progress-bar.active {
        font-weight: 700;
        animation: progress-bar-stripes .5s linear infinite;
    }

    .dotdotdot:after {
        font-weight: 300;
        content: '...';
        display: inline-block;
        width: 20px;
        text-align: left;
        animation: dotdotdot 1.5s linear infinite;
    }

    @keyframes dotdotdot {
        0%   { content: '...'; }
        25% { content: ''; }
        50% { content: '.'; }
        75% { content: '..'; }
    }

    input[type=radio] {
      border: 0px;
      width: 100%;
      height: 2em;
    }
    .loader {
      border: 10px solid #f3f3f3; /* Light grey */
      border-top: 10px solid #3498db; /* Blue */
      border-radius: 50%;
      width: 100px;
      height: 100px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    #balance_date{
      position: relative;
      z-index:9999 !important;
    }
    .datepicker{z-index:1151 !important;}
    .select2-selection__rendered {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 14px;
    }

    .my-container{
        font-size: 14px;
    }
    .numberCircle {
      border-radius: 50%;
      behavior: url(PIE.htc); /* remove if you don't care about IE8 */

      width: 36px;
      height: 36px;
      padding: 8px;

      background: #fff;
      border: 2px solid #666;
      color: #666;
      text-align: center;

      font: 32px Arial, sans-serif;
      box-sizing: initial
    }
    .myFont {
      font-size:small;
    }
    th {
      cursor: pointer;
    }
  </style>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top" onload="LoadContent('invoice_dashboard')">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{url('dashboard')}}" style="text-indent: 0%;">DAFTARI<sup><i class="fa fa-plus-circle"></i></sup></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="#" onclick="LoadContent('invoice_dashboard')">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
          <a class="nav-link nav-link-collapse collapsed co" data-toggle="collapse" href="#collapseComponents1">
            <i class="fa fa-fw fa-credit-card-alt"></i>
            <span class="nav-link-text">Sales</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents1">
            <li>
              <a href="#" onclick="LoadContent('invoice_dashboard')">Invoices</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('customersStatement')">Customer Statements</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('customers')">Customer</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('productsandservicespurchases/10')">Products & Services</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link nav-link-collapse collapsed co" data-toggle="collapse" href="#collapseComponents2">
            <i class="fa fa-fw fa-shopping-cart"></i>
            <span class="nav-link-text">Purchases</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents2">
            <li>
              <a href="#" onclick="LoadContent('bills')">Bills</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('receipts')">Receipts</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('vendor')">Vendors</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('productsandservicespurchases/01')">Products & Services</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
          <a class="nav-link nav-link-collapse collapsed co" data-toggle="collapse" href="#collapseComponents3" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-balance-scale"></i>
            <span class="nav-link-text">Accounting</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents3">
            <li>
              <a href="#" onclick="LoadContent('transaction_dashboard/0')">Transactions</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('reconciling')">Reconciliation</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('account_chart_dashboard')">Chart of Accounts</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
          <a class="nav-link nav-link-collapse collapsed co" data-toggle="collapse" href="#collapseComponents4" data-parent="#exampleAccordion" style="display: none;">
            <i class="fa fa-fw fa-balance-scale"></i>
            <span class="nav-link-text">Payroll</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents4">
            <li>
              <a href="#" onclick="LoadContent('transaction_dashboard/0')">Run Payroll</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('reconciling')">Employees</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('account_chart_dashboard')">Timesheets</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('account_chart_dashboard')">Taxes</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('account_chart_dashboard')">Tax Forms</a>
            </li>
            <li>
              <a href="#" onclick="LoadContent('account_chart_dashboard')">Direct Deposit</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
          <a class="nav-link" href="#" onclick="LoadContent('reports')">
            <i class="fa fa-fw fa-bar-chart"></i>
            <span class="nav-link-text">Reports</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
          <a class="nav-link" data-toggle="modal" data-target="#logoutModal">
            <i class="fa fa-fw fa-sign-out"></i>
            <span class="nav-link-text">Logout</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#logoutModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="container-fluid">

      <div class="row" id="content_wrap_fluid">
          <div class="col-sm-12">
              <div id="msg" class="breadcrumb" style="padding: 1%;display: none"><span class="breadcrumb-item active" style="margin-left: 40%;color: blueviolet"></span></div>
              <div class="progress" id="progress" style="display: none">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                      <span>Please wait<span class="dotdotdot"></span></span>
                  </div>
              </div>
          </div>
        <div class="col-sm-12" id="pre_load" style="background: #A6000000;position: fixed;top: 0%;left:0%;height: 100%;z-index:4">
          <div class="loader" style="display: block;vertical-align: middle;margin-top:25%;margin-left: 50%;"></div>
        </div>
        <div class="col-sm-12" id="content">
        </div>
      </div>

    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © Your Website 2017</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top" id="top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="{{url('logout')}}">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="addTaxModal" tabindex="-1" role="dialog" aria-labelledby="addTaxModal" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addTaxModal">Add Tax</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" style="max-height: 400px;overflow-y: auto;font-size: 14px;background-color: #f0f0f0" id="containerDiv">
            <div class="row">
              <div class="col-sm-10 offset-md-1">
                <div class="progress" id="tax_progress" style="widows: 80%;display:none">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span>Please wait<span class="dotdotdot"></span></span>
                  </div>
                </div>
              </div>
              <div class="col-sm-10 offset-md-1">
                <div id="tax_errormsg" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;">
                </div>
              </div>
            </div>

              <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Tax Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="tname" placeholder="Tax Name" style="font-size:14px; width:80%">
                </div>
              </div>
              <div class="form-group row">
                <label for="abbr" class="col-sm-4 col-form-label">Abbreviation</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="abbr" placeholder="Abbreviation" style="font-size:14px; width:80%">
                </div>
              </div>
              <div class="form-group row">
                <label for="rate" class="col-sm-4 col-form-label"> Tax rate (%) * </label>
                <div class="col-sm-8">
                  <input type="number" class="form-control" id="rate" placeholder="Tax Rate" min="0" style="font-size:14px; width:80%">
                </div>
              </div>
              <div class="form-group row">
                <label for="desc" class="col-sm-4 col-form-label">Description</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="tdesc" placeholder="Description" style="font-size:14px; width:80%">
                </div>
              </div>
              <div class="form-group row">
                <label for="num" class="col-sm-4 col-form-label"> Tax Number </label>
                <div class="col-sm-8">
                  <input type="number" class="form-control" id="num" placeholder="Tax Number" min="0" style="font-size:14px; width:80%">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">Show tax number on invoices</div>
                <div class="col-sm-8">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="checkbox" id="invoice">
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">Is this tax recoverable?</div>
                <div class="col-sm-8">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="checkbox" id="recov">
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">Is this a compound tax?</div>
                <div class="col-sm-8">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="checkbox" id="compound">
                    </label>
                    <p style="margin-top: 5%;"><small>Is this a compound tax?</small></p>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="#" onclick="addTax()">Add Tax</a>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal -->
    <div id="updateModal" class="modal" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addTaxModal">UPDATE PRODUCT AND SERVICE</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" style="font-size: 14px;;background-color: #f0f0f0">
            <div class="row" >
              <div class="col-sm-10 offset-md-1">
                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span>Please wait<span class="dotdotdot"></span></span>
                  </div>
                </div>
              </div>
              <div class="col-sm-10 offset-md-1">
                <div id="errormsg" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;">
                </div>
              </div>
              <div class="col-sm-10 offset-md-1">
                <div class="form-group row">
                  <label class="control-label col-sm-4" for="name">Name:</label>
                  <div class="col-sm-8">
                    <input type="name" name="name" class="form-control" id="name" placeholder="" style="font-size:14px">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-sm-4" for="desc">Description:</label>
                  <div class="col-sm-8">
                    <textarea type="text" class="form-control" id="desc" placeholder="" name="desc" style="font-size:14px"></textarea>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="control-label col-sm-4" for="price">Price:</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" min="0" value="0.00" style="font-size:14px">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-sm-4" for="sales">Sell this:</label>
                  <div class="col-sm-8">
                    <input type="checkbox" id="sales" name="sales" onchange="openSelectExpensesIncomeAccount()" />
                    <p><small>Allow this product or service to be added to Invoices.</small></p>
                  </div>
                </div>
                <div class="form-group row" style="display: none" id="select_income">
                  <label for="sel1" class="col-sm-4">Income account*:</label>
                  <div class="row col-sm-5" id="dynamicInput">
                    <div class="col-sm-12">
                      <select class="form-control js-example-basic-multiple" id="income" name="income" data-placeholder="------------------------------------" style="width: 100%">
                        <option></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-sm-4" for="buy">Buy this:</label>
                  <div class="col-sm-8">
                    <input type="checkbox"  id="buy" name="buy" onchange="openSelectExpensesIncomeAccount()" />
                    <p>
                      <small>Allow this product or service to be added to Bills</small>
                    </p>
                  </div>
                </div>
                <div class="form-group row" style="display: none" id="select_expense">
                  <label for="sel1" class="col-sm-4">Expense account*:</label>
                  <div class="row col-sm-5" id="dynamicInput">
                    <div class="col-sm-12">
                      <select class="form-control js-example-basic-multiple" id="expense" name="expense" data-placeholder="---------------------------------" style="width: 100%">
                        <option></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="sel1" class="col-sm-4">Select tax:</label>
                  <div class="row col-sm-7" id="dynamicInput">
                    <div class="col-sm-10">
                      <select class="form-control js-example-basic-multiple" id="taxe" name="taxes[]" multiple="multiple" style="font-size:14px;width:75%;">
                      </select>
                    </div>
                    <div class="col-sm-1">
                      <div class="btn-group-xs">
                        <button type="button" data-toggle="modal" data-target="#addTaxModal" class="btn btn-xs" style="background: transparent"><i class="fa fa-fw fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="button" id="updateProduct" class="btn btn-info" onclick="updateProduct()">UPDATE</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div id="updateModalVendor" class="modal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addVendorModal">UPDATE VENDOR</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" style="font-size: 14px;background-color: #f0f0f0">
            <div class="row" >
              <div class="col-sm-10 offset-md-1">
                <div class="progress" id="update_vendor_progress" style="display:none;">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span>Please wait<span class="dotdotdot"></span></span>
                  </div>
                </div>
              </div>
              <div class="col-sm-10 offset-md-1">
                <div id="errormsg_vendor_update" style="display:none;width: 100%;text-align: center;margin-bottom: 3%;">
                </div>
              </div>
              <div class="col-sm-10 offset-md-1">
                <div class="form-group row">
                  <label class="control-label col-sm-5" for="email">Name:</label>
                  <div class="col-sm-7">
                    <input type="name" class="form-control" id="vname" placeholder="Enter vendor name" style="font-size: 14px">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-sm-5" for="email">Email:</label>
                  <div class="col-sm-7">
                    <input type="email" class="form-control" id="email" placeholder="Enter email" style="font-size: 14px">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-sm-5" for="fname">First Name:</label>
                  <div class="col-sm-7">
                    <input type="name" class="form-control" id="fname" placeholder="Enter name" style="font-size: 14px">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="control-label col-sm-5" for="lname">Last Name:</label>
                  <div class="col-sm-7">
                    <input type="name" class="form-control" id="lname" placeholder="Enter name" style="font-size: 14px">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="control-label col-sm-5" for="phone">Phone:</label>
                  <div class="col-sm-7">
                    <input type="phone" class="form-control" id="phone" placeholder="Enter phone" style="font-size: 14px">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="sel1" class="col-sm-5">Select currency:</label>
                  <div class="col-sm-7">
                    <select class="form-control js-example-basic-multiple" name="currency" id="currency">
                      <option value="Tshs" selected>Tshs</option>
                      <option value="USD">USD</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="button" id="updateVendor" class="btn btn-info" onclick="updateVendor()">Submit</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>


    <div class="modal" id="reconcileModal" tabindex="-1" role="dialog" aria-labelledby="reconcileModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reconcileModal_head" style="font-weight:bold;">Ending Balance for <span id="ending_date" style="font-weight: bold"></span> Statement</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" id="reconcileclosed">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" style="max-height: 400px;overflow-y: auto;font-size: 14px;background-color: #f0f0f0" id="containerDiv">

            <div class="col-sm-12" id="load_reconcile" style="background: #A6000000;position: fixed;top: 0%;left:0%;height: 100%;z-index:9999999999;display: none">
              <div class="loader" style=";vertical-align: middle;margin-top:25%;margin-left: 37%;">
              </div>
            </div>

            <div class="row">
              <div class="col-sm-10 offset-md-1">
                <div id="errormsg_reconcile" style="display: none;width: 100%;margin-bottom: 4%;text-align: center;">

                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12" style="text-align: center;font-size: 15px">
                Enter the ending balance up to <span id="date_head" style="font-weight: bold"></span> as it appears on your statement for <span id="account" style="font-weight:bold;"></span>.
              </div>
            </div>

            <div class="form-group row">
              <label for="balance_amount" class="col-sm-6 col-form-label">Ending Balance Amount
              *</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="balance_amount" style="width: 80%;">
              </div>
            </div>
            <div class="form-group row">
              <label for="balance_date" class="col-sm-6 col-form-label">Ending Balance Date*</label>
              <div class="col-sm-6">
                <div class="input-group" style="width: 100%;float: right;">
                  <input type='text' style="width: 62%;font-size: 13px;z-index: 3000;background-color: white;border: solid 1px #C0C0C0;text-align: center;" datepicker-here id="balance_date" data-position="right top" data-language='en' data-date-format="yyyy-mm-dd" />
                  <div class="input-group-addon" style="background-color: white">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
              <input type="hidden" class="form-control" id="reconcile_id">
              <input type="hidden" class="form-control" id="reconcile_account">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal" style="background-color: #fff; border: solid 1px darkblue;">Cancel</button>
            <a class="btn btn-primary" href="#" onclick="addReconcile()">Save</a>
          </div>
        </div>
      </div>
    </div>



    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/select2.full.min.js')}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset('js/jquery.easing.min.js')}}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{asset('js/Chart.min.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin.min.js')}}"></script>
    <!-- Custom scripts for this page-->
    <script src="{{asset('js/sb-admin-datatables.min.js')}}"></script>
    <script src="{{asset('js/sb-admin-charts.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-progressbar.min.js')}}"></script>
    <script src="{{asset('js/numeral.min.js')}}"></script>
    <script src="{{asset('js/app_.js')}}"></script>
    <script>
      function LoadBalanceSheetReport(str){

          var active = 0;
          if($('.active.tab-pane').hasClass('one')){
              active = 1;
          } else if($('.active.tab-pane').hasClass('two')){
              active = 2;
          }

          var http = new XMLHttpRequest();
          var url = "balancereport";
          var params = "start="+str+"&end="+str+"&active="+active;
          http.open("POST", url, true);
          http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          http.onreadystatechange = function() {
              if(http.readyState == 4 && http.status == 200) {
                  $('.tab-content').html(http.responseText);
              }
          };
          http.send(params);
      }
    </script>
    <!-- Latest compiled and minified JavaScript -->
      <script src="{{asset('js/bootstrap-select.js')}}"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.full.js"></script>
      <script src="{{asset('js/datepicker.min.js')}}"></script>
      <script src="{{asset('js/datepicker.en.js')}}"></script>
      <script src="{{asset('js/progressbar.min.js')}}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/js/mdb.min.js"></script>
  </div>
</body>

</html>
