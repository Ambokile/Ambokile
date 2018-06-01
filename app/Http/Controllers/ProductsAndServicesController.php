<?php

namespace App\Http\Controllers;

use App\Mail\CompanyVerificationEmails;
use App\User;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\Environment\Console;
use Validator;

class ProductsAndServicesController extends Controller
{

    public function index($role)
    {
        $products = DB::table('products_services')
            ->where('sales_purchases', $role)
            ->orWhere('sales_purchases', 11)
            ->get();
        $taxes = DB::table('tax')->get();
        echo '
                <input type="hidden" value="' . $role . '" id="role">
              <div class="row" style="margin-left: 11%; padding-right: 13%; margin-top: 2%">
                <div class="col-sm-9">
                    <h3>Products & Services </h3>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-primary" onclick="LoadContent(\'addproductsandservicespurchases/' . $role . '\')">Add Products and  Services
                    </button>
                 </div>
                </div>
                
           <div class="row" style="margin-top: 3%;margin-left: 11%">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-8">
                    <div class="input-group" style="width: 90%;font-size: 14px">
                                      <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>
                </span>
                      <input type="name" class="form-control" id="search_name" placeholder="search for name" style="font-size: 14px" onkeyup="mySearchFunctionSingle()">
                    </div>
                       
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
            </div>
        </div>
        
<div class="row" style="margin-top: 3%;width: 100%;font-size: 14px;;overflow-y: auto;height: 400px;">
    <div class="col-sm-10 offset-1" style="padding-left: 5%; padding-right: 5%;">
        <table class="table borderless" style="font-size: 14px;border: solid 1px #C0C0C0" id="myTable_prev">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>';
        $str_taxes = "";
        $a = 0;
        foreach ($taxes as $taxz) {
            if ($a == 0) {
                $str_taxes = $taxz->abbreviation;
            } else {
                $str_taxes = $str_taxes . '&' . $taxz->abbreviation;
            }
            $a++;
        }

        $incomes = DB::table('account')
            ->where('account_chart', '=', 3)
            ->orderBy('id', 'DESC')
            ->orderBy('account_type', 'ASC')
            ->get();

        $str_incomes = "";
        $a = 0;
        foreach ($incomes as $income) {
            if ($a == 0) {
                $str_incomes = $income->account_name;
            } else {
                $str_incomes = $str_incomes . '&' . $income->account_name;
            }
            $a++;
        }

        $expenses = DB::table('account')
            ->where('account_chart', '=', 4)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        $str_expenses = "";
        $a = 0;
        foreach ($expenses as $expense) {
            if ($a == 0) {
                $str_expenses = $expense->account_name;
            } else {
                $str_expenses = $str_expenses . '&' . $expense->account_name;
            }
            $a++;
        }


        foreach ($products as $value) {
            $tax = str_replace(',', '&', $value->tax);
            $str = $value->name . "," . $value->Price . "," . $value->description . "," . $value->sales_purchases . "," . $str_taxes . "," . $tax . "," . $value->income_account . "," . $value->expenses_account . "," . $str_incomes . "," . $str_expenses . "," . $value->id;

            if ($value->sales_purchases == 11) {
                $v = '<span>' . $value->name . '</span><button class="btn btn-sm btn-info" style="margin-left: 5%" disabled="disabled"><small>buy && sale</small></button>';
            } else {
                $v = $value->name;
            }
            echo '
                <tr style="font-size: 14px;border-bottom: solid 1px #C0C0C0">
                    <td>' . $v . '</td>
                    <td>' . $value->Price . '</td>
                    <td>
                        <a href="#" onclick=deleteData("' . $value->id . '","' . $role . '")>
                            <i class="fa fa-fw fa-trash"></i>
                            <span class="d-lg-none">delete</span>
                        </a>
                        <a href="#" data-str="' . $str . '" data-toggle="modal" data-target="#updateModal">
                            <i class="fa fa-fw fa-pencil"></i>
                            <span class="d-lg-none">Edit</span>
                        </a>
                    </td>
                </tr>
                ';
        }
        echo '</tbody>
        </table>
    </div>
</div>';
    }

    public function login(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $validator = Validator::make($request->all(), [
            'username' => 'required|email',
            'password' => 'required|alphaNum|min:3'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $view = View::make('login', compact('errors'))->render();
            return $view;
        } else {
            // $password = Hash::make($password);
            $query = DB::table('users')
                ->where('email', $username)
                ->where('password', $password)
                ->get();
            if (count($query) == 1) {
                \Session::put('username', $username);
                return redirect('dashboard');
            } else {
                return redirect('/');
            }
        }
    }

    public function store(Request $request)
    {
        $name = $request->get('name');
        $desc = $request->get('desc');
        $price = $request->get('price');
        $taz = $request->get('taxes');
        $tax = $request->get('tax');
        $sales = $request->get('sales');
        $purchases = $request->get('buy');
        $income = $request->get('income');
        $expense = $request->get('expense');
        $taxes = DB::table('tax')->get();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $view = View::make('addproductsandservicespurchases', compact('errors', 'tax', 'taxes'))->render();
            return "0&$" . $view;

        } else {
            try {

                $db = DB::table('products_services')->insert(
                    ['name' => $name, 'description' => $desc, 'Price' => $price, 'sales_purchases' => $sales . $purchases, 'tax' => $taz, 'income_account' => $income, 'expenses_account' => $expense]
                );

                if ($db) {
                    $role = $sales . $purchases;
                    $this->index($role);
                } else {
                    echo 0;
                }

            } catch (Exception $exception) {
                echo 0;
            }
        }
    }

    public function delete($id, $role)
    {
        DB::table('products_services')->where('id', $id)->delete();
        $this->index($role);
    }

    public function deleteinvoice($bill)
    {

        $db = DB::table('invoice')->where('invoice_num', $bill)->delete();
        $db_ = DB::table('invoice_item')->where('invoice_num', $bill)->delete();

        if ($db && $db_) {
            $invoice_all = DB::table('invoice')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('status', '=', 0)
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('status', '=', 0)
                ->orWhere('status', '=', 1)
                ->orWhere('status', '=', 2)
                ->get();
            return View::make('invoice_dashboard', compact('invoice_all', 'invoice_draft', 'invoice_unpaid'))->render();
        } else {
            echo 0;
        }

    }

    public function updateProduct(Request $request)
    {
        $name = $request->get('name');
        $desc = $request->get('desc');
        $price = $request->get('price');
        $tax = $request->get('taxes');
        $sales = $request->get('sales');
        $purchases = $request->get('buy');
        $id = $request->get('id');
        $role = $request->get('role');
        $income = $request->get('income');
        $expense = $request->get('expense');

        $validator = Validator::make(Input::all(), [
            'name' => 'required',
            'desc' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            echo 0;
        } else {
            try {

                $db = DB::table('products_services')
                    ->where('id', $id)
                    ->update(['name' => $name, 'description' => $desc, 'Price' => $price, 'sales_purchases' => $sales . $purchases, 'tax' => $tax, 'income_account' => $income, 'expenses_account' => $expense]);
                if ($db) {
                    $this->index($role);
                } else {
                    echo 0;
                }

            } catch (Exception $exception) {
                echo 0;
            }
        }
    }

    public function showVendor()
    {
        $role = 0;
        $vendors = DB::table('vendor_customer')
            ->where('role', $role)
            ->get();

        echo '
        <div class="row" style="margin-left: 11%; margin-right: 13%;font-size: 14px;margin-top: 3%;">
            <div class="col-sm-7">
                <h2>Vendor</h2>
            </div>
            
            <div class="col-sm-5">
                            <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dropdown" >
                                          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                                            Import from...
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                             <span class="btn btn-default btn-file" style="font-size: 14px;background-color: transparent;width: 100%">CSV<input type="file" id="receipt_upload" onchange="uploadCSVFile()"></span>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" >
                                    <button class="btn btn-primary" style="width: 100%;font-size: 14px" onclick="LoadContent(\'addvendor\')">Add Vendor</button>
                            </div>
                        </div>
            </div>
        </div>
        <div class="row" style="margin-left: 11%; padding-right: 14%;font-size: 14px;margin-top: 1%;">
             <div class="col-sm-12">
                <div id="dvProgress" style="width: 100%; min-width: 2em;"></div>
             </div>
        </div>
        
          <div class="row" style="margin-left: 11%; padding-right: 14%;font-size: 14px;margin-top: 1%;">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-8">
                    <div class="input-group" style="width: 90%;font-size: 14px">
                                      <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>
                </span>
                      <input type="name" class="form-control" id="search_name" placeholder="search for name" style="font-size: 14px" onkeyup="mySearchFunctionVendorCustomer()">
                    </div>  
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
            </div>
        </div>
        
        
    <div class="row" style="width: 100%">
    <div class="col-sm-10 offset-1" style="padding-left: 5%; padding-right: 5%;overflow-y: auto;height: 450px;">
        <table class="table" style="margin-top: 5%;border: solid 1px #CCCCCC;font-size: 14px;" id="myTable_prev">
            <thead style="background-color: transparent;border-color: transparent">
            <tr>
                <th scope="col" onclick="sortTable(0)">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            ';
        foreach ($vendors as $vendor) {
            $strVendor = $vendor->name . "," . $vendor->email . "," . $vendor->phone . "," . $vendor->first_name . "," . $vendor->last_name . "," . $vendor->currency . "," . $vendor->id . "," . $role;
            echo '
                   <tr>
                <td>
                    <h6>' . $vendor->name . '</h6>
                    <h6><small>' . $vendor->last_name . '. ' . $vendor->first_name . '</small></h6>
                </td>
                <td>' . $vendor->email . '</td>
                <td>' . $vendor->phone . '</td>
                <td>
                    <a  href="#" onclick=deleteVendor("' . $vendor->id . '",0)>
                        <i class="fa fa-fw fa-trash"></i>
                        <span class="d-lg-none">Delete</span>
                    </a>
                    <a  href="#" data-str="' . $strVendor . '" data-toggle="modal" data-target="#updateModalVendor">
                        <i class="fa fa-fw fa-pencil"></i>
                        <span class="d-lg-none">Edit</span>
                    </a>
                </td>
            </tr>
                ';
        }
        echo '
                   </tbody>
                </table>
            </div>
        </div>
            ';
    }

    public function storeVendor(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');
        $currency = $request->get('currency');
        $role = $request->get('role');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'currency' => 'required',
        ]);

        if ($validator->fails()) {
            $task = $role;
            $errors = $validator->errors();
            if ($task == 0) {
                $view = View::make('addvendor', compact('errors'))->render();
                return "0&$" . $view;
            } else {
                $view = View::make('addCustomers', compact('errors'))->render();
                return "0&$" . $view;
            }
        } else {
            $db = DB::table('vendor_customer')->insert(
                ['name' => $name, 'email' => $email, 'phone' => $phone, 'first_name' => $first_name, 'last_name' => $last_name, 'currency' => $currency, 'role' => $role]
            );

            if ($db) {
                $task = $role;
                if ($task == 0)
                    $this->showVendor();
                else $this->showCustomer();
            } else {
                echo 0;
            }
        }
    }

    public function showCustomer()
    {
        $role = 1;
        $vendors = DB::table('vendor_customer')
            ->where('role', $role)
            ->get();
        echo '
        <div class="row" style="margin-left: 11%; margin-right: 13%;font-size: 14px;margin-top: 3%;">
            <div class="col-sm-7">
                <h3>Customer</h3>
            </div>
            <div class="col-sm-5">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dropdown" >
                          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                            Import from...
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                             <span class="btn btn-default btn-file" style="font-size: 14px;background-color: transparent;width: 100%">CSV<input type="file" id="receipt_upload" onchange="uploadCSVFile()"></span>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6" >
                         <button class="btn btn-primary" style="width: 100%;font-size: 14px" onclick="LoadContent(\'addCustomers\')">Add Customer</button>

                    </div>
                </div>
              </div>
        </div>
        <div class="row" style="margin-left: 11%; padding-right: 14%;font-size: 14px;margin-top: 1%;">
             <div class="col-sm-12">
                <div id="dvProgress" style="width: 100%; min-width: 2em;display: none"></div>
             </div>
        </div>
        
         <div class="row" style="margin-left: 11%; padding-right: 14%;font-size: 14px;margin-top: 1%;">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-8">
                    <div class="input-group" style="width: 90%;font-size: 14px">
                                      <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>
                </span>
                      <input type="name" class="form-control" id="search_name" placeholder="search for name" style="font-size: 14px" onkeyup="mySearchFunctionVendorCustomer()">
                    </div>
                       
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
            </div>
        </div>
        
   <div class="row" style="width: 100%">
    <div class="col-sm-10 offset-1" style="padding-left: 5%; padding-right: 5%;overflow-y: auto;height: 450px;">
        <table class="table" style="margin-top: 5%;border: solid 1px #CCCCCC;font-size: 14px;" id="myTable_prev">
            <thead style="background-color: transparent;border-color: transparent">
            <tr>
                <th scope="col" onclick="sortTable(0)">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            ';
        foreach ($vendors as $vendor) {
            $strVendor = $vendor->name . "," . $vendor->email . "," . $vendor->phone . "," . $vendor->first_name . "," . $vendor->last_name . "," . $vendor->currency . "," . $vendor->id . "," . $role;
            echo '
                   <tr>
                <td>
                    <h6>' . $vendor->name . '</h6>
                    <h6><small>' . $vendor->last_name . '. ' . $vendor->first_name . '</small></h6>
                </td>
                <td>' . $vendor->email . '</td>
                <td>' . $vendor->phone . '</td>
                <td>
                    <a  href="#" onclick=deleteVendor("' . $vendor->id . '",1)>
                        <i class="fa fa-fw fa-trash"></i>
                        <span class="d-lg-none">Delete</span>
                    </a>
                    <a  href="#" data-str="' . $strVendor . '" data-toggle="modal" data-target="#updateModalVendor">
                        <i class="fa fa-fw fa-pencil"></i>
                        <span class="d-lg-none">Edit</span>
                    </a>
                </td>
            </tr>
                ';
        }
        echo '
                   </tbody>
                </table>
            </div>
        </div>
            ';
    }

    public function deleteVendor($id, $task)
    {
        DB::table('vendor_customer')->where('id', $id)->delete();
        if ($task == 0)
            $this->showVendor();
        else $this->showCustomer();
    }

    public function updateVendor(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $first_name = $request->get('fname');
        $last_name = $request->get('lname');
        $currency = $request->get('currency');
        $role = $request->get('role');
        $id = $request->get('id');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'currency' => 'required',
        ]);

        if ($validator->fails()) {
            echo "failed_";
        } else {

            try {
                $db = DB::table('vendor_customer')
                    ->where('id', $id)
                    ->update(
                        ['name' => $name, 'email' => $email, 'phone' => $phone, 'first_name' => $first_name, 'last_name' => $last_name, 'currency' => $currency, 'role' => $role]
                    );

                if ($db) {
                    $task = $role;
                    if ($task == 0) $this->showVendor();
                    else $this->showCustomer();
                } else {
                    echo "failed";
                }

            } catch (Exception $exception) {
                echo "failed";
            }
        }
    }

    public function addTax(Request $request)
    {
        $name = $request->get('name');
        $abbr = $request->get('abbr');
        $desc = $request->get('desc');
        $rate = $request->get('rate');
        $num = $request->get('num');
        $invoce = $request->get('invoice');
        $comp = $request->get('comp');
        $recov = $request->get('recov');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'abbr' => 'required',
            'desc' => 'required',
            'rate' => 'required',
            'num' => 'required',
            'invoice' => 'required',
            'recov' => 'required',
            'comp' => 'required',
        ]);

        if ($validator->fails()) {

        } else {

            $db_ = DB::table('tax')->insert(
                ['name' => $name, 'abbreviation' => $abbr, 'tax_rate' => $rate, 'description' => $desc, 'tax_number' => $num, 'shown_invoices' => $invoce, 'tax_recoverable' => $recov, 'tax_compound' => $comp]
            );

            $type = "Sales Taxes";
            $currency = "Tsh";

            $id_no = rand(10, 10000);
            $account = 1;

            $db = DB::table('account')->insert(
                ['account_name' => $name, 'description' => $desc, 'account_type' => $type, 'currency' => $currency, 'account_id' => $id_no, 'account_chart' => $account]
            );

            if ($db && $db_) echo "1";
            else echo "0";


        }
    }

    public function getProductData(Request $request)
    {
        $id = $request->get('id');
        $quantity = $request->get('q');
        $price = $request->get('p');
        $s = $request->get('s');
        $account = $request->get('account');
        $products_spec = DB::table('products_services')->where('name', $id)->get();
        $expenses = DB::table('account')
            ->where('account_chart', '=', 4)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();
        foreach ($products_spec as $product_spec) {
            $tax = $product_spec->tax;
            $price = $product_spec->Price;
            $desc = $product_spec->description;
        }

        $products = DB::table('products_services')
            ->where('sales_purchases', '=', 1)
            ->orWhere('sales_purchases', '=', 11)
            ->get();
        $taxes = DB::table('tax')->get();

        $i = $s;
        $desc_id = "desc" . $i;
        $quantity_id = "quantity" . $i;
        $price_id = "price" . $i;
        $init_total = $quantity * $price;

        echo '
                <td width="15%">
                    <select class="form-control js-example-basic-multiple" name="product[]" data-placeholder="product" id="product" onchange=getProductData(this.value,"' . $i . '",1) style="width:98%">';
        echo ' <option value=""></option>';
        foreach ($products as $product) {
            if ($id == $product->name) {
                echo '<option value="' . $product->name . '" selected="selected">' . $product->name . '</option>';
            } else {
                echo '<option value="' . $product->name . '">' . $product->name . '</option>';
            }
        }
        echo '</select>
                </td>
                <td width="20%">
                    <select class="form-control js-example-basic-multiple" name="account[]" id="account' . $i . '" data-placeholder="expenses" >
                                <option value=""></option>
                                <optgroup label="EXPENSES">';
        foreach ($expenses as $expense) {
            if ($account == $expense->id) echo '<option value="' . $expense->id . '" selected="selected">' . $expense->account_name . '</option>';
            else
                echo '<option value="' . $expense->id . '">' . $expense->account_name . '</option>';
        }
        echo '</optgroup>
                    </select>
                </td>
                <td width="15%"> <textarea type="text" class="form-control" id="' . $desc_id . '" placeholder="Enter description" name="desc[]" style="font-size:14px">' . $desc . '</textarea></td>
                <td width="10%"> <input type="number" style="font-size:14dp" class="form-control" id="' . $quantity_id . '" name="quantity[]" min="1" value="' . $quantity . '" onchange=getProductData("' . $id . '",' . $i . ',1) onkeyup=getProductData("' . $id . '",' . $i . ',1) /></td>
                <td width="10%"> <input type="number" style="font-size:14px" class="form-control" name="price[]" id="' . $price_id . '" placeholder="Enter price" min="0" value="' . $price . '" onchange=getProductData("' . $id . '",' . $i . ',1) /></td>
                <td width="10%">
                     <select class="form-control js-example-basic-multiple" id="tax' . $i . '" name="tax' . $i . '[]" multiple="multiple" value="' . $tax . '" onchange="getTaxUpdate(' . $i . ')" >';
        echo ' <option value=""></option>';
        foreach ($taxes as $taxs) {
            if (strpos($tax, $taxs->abbreviation) !== false) {
                echo '<option value="' . $taxs->abbreviation . '(' . $taxs->tax_rate . ')" selected="selected">' . $taxs->abbreviation . '</option>';
            } else {
                echo '<option value="' . $taxs->abbreviation . '(' . $taxs->tax_rate . ')">' . $taxs->abbreviation . '</option>';
            }

        }
        echo '</select>
                </td>
                <td>';
        echo '<span style="float: right;" id="t' . $i . '">' . $init_total . '</span>';
        echo '<input type="hidden" id="init_total' . $i . '" value="' . $init_total . '">';
        echo '</td>
            <td>
                            <button type="button" class="btn btn-default" style="background-color: transparent" onclick=deleteRowBill("tr' . $i . '")>
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
          ';
    }

    public function getCurrency(Request $request)
    {
        $arr_currency = array();
        $id = $request->get('id');
        $vendors = DB::table('vendor_customer')->get();
        $cur = DB::table('vendor_customer')->where('id', $id)->first();
        $name = $cur->name;
        $money = $cur->currency;
        echo '
                 <div class="form-group row">
                   <label class="control-label col-sm-4" for="vendor">Vendor*:</label>
                   <div class="col-sm-8">
                       <select class="form-control js-example-basic-multiple" name="vendor" id="vendor" style="width:65%" onchange="getCurrencyData()">';
        foreach ($vendors as $vendor) {
            if ($id == $vendor->id) {
                echo '<option value="' . $vendor->id . '" selected="selected">' . $vendor->name . '</option>';
            } else {
                echo '<option value="' . $vendor->id . '">' . $vendor->name . '</option>';
            }
        }
        echo '</select>
                   </div>
               </div>
               <div class="form-group row">
                   <label class="control-label col-sm-4" for="currency">Currency:</label>
                   <div class="col-sm-8">
                       <select class="form-control js-example-basic-multiple" id="currency" name="currency" style="width:65%">';
        foreach ($vendors as $vendor) {
            if ($id == $vendor->id) {
                if (!in_array($vendor->currency, $arr_currency)) {
                    echo '<option value="' . $vendor->currency . '" selected="selected">' . $vendor->currency . '</option>';
                    array_push($arr_currency, $vendor->currency);
                }

            } else {
                if (!in_array($vendor->currency, $arr_currency)) {
                    echo '<option value="' . $vendor->currency . '">' . $vendor->currency . '</option>';
                    array_push($arr_currency, $vendor->currency);
                }
            }
        }
        echo '</select>
                   </div>
               </div>
        ';
    }

    public function addbillitem(Request $request)
    {

        $vendor = $request->get('vendor');
        $currency = $request->get('currency');
        $from = $request->get('from');
        $to = $request->get('to');
        $po = $request->get('po');
        $num = $request->get('num');
        $notes = $request->get('notes');

        $products = explode(',', $request->get('products'));
        $accounts = explode(',', $request->get('accounts'));
        $desc = explode(',', $request->get('desc'));
        $quantity = explode(',', $request->get('quantity'));
        $price = explode(',', $request->get('prices'));
        $tax = explode(',', $request->get('taxs'));

        //check if bill_no exist, if exist delete available bill and add new one, else if not exist add new one
        $db_expenses = false;

        $da = DB::table('bill')->where('bill_no', '=', $num)->get();
        if (count($da) > 0) {
            $check = 'failed_';
        } else {
            $validator = Validator::make($request->all(), [
                'vendor' => 'required',
                'currency' => 'required',
                'from' => 'required',
                'to' => 'required',
                'po' => 'required',
                'num' => 'required'
            ]);
            $check = 'failed';
            if ($validator->fails()) {
                $check = 'failed_';
            } else {
                $sum = 0;
                $db_bill = DB::table('bill')->insert(['vendor' => $vendor, 'currency' => $currency, 'date' => $from, 'due_date' => $to, 'po_os' => $po, 'bill_no' => $num, 'notes' => $notes]);

                if ($db_bill) {

                    for ($i = 0; $i < count($quantity); $i++) {
                        try {
                            $tax_item = str_replace("$", ",", $tax[$i]);
                            $db_bill_item = DB::table('bill_item')->insert(['item_name' => $products[$i], 'account' => $accounts[$i], 'description' => $desc[$i], 'quantity' => $quantity[$i], 'price' => $price[$i], 'tax' => $tax_item, 'bill_no' => $num]);

                            $sum = $sum + ($quantity[$i] * $price[$i]);
                            $sum_tax = 0;
                            if (count($tax) > 0) {
                                for ($z = 0; $z < count($tax); $z++) {

                                    if (strpos($tax[$z], "$") !== false) {
                                        $tax_arr = explode("$", $tax[$z]);
                                        for ($z = 0; $z < count($tax); $z++) {
                                            $str = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                                            if (strcmp($products[$i], $str) == 0) {
                                                $var1 = "(";
                                                $var2 = ")";
                                                $pool = $tax_arr[$z];
                                                $temp1 = strpos($pool, $var1) + strlen($var1);
                                                $result = substr($pool, $temp1, strlen($pool));
                                                $dd = strpos($result, $var2);
                                                if ($dd == 0) {
                                                    $dd = strlen($result);
                                                }

                                                $percent = substr($result, 0, $dd);
                                                $sum_tax = (($percent / 100) * ($quantity[$i] * $price[$i]));
                                                $sum = $sum + $sum_tax;
                                                /* $pos = strpos($pool,"(");
                                                $tax_name = substr($tax_arr[$z],0,$pos);
                                                $operation_tax = 'add';
                                                $tax_fname = DB::table('tax')
                                                    ->where('abbreviation',$tax_name)
                                                    ->value('name');
                                                $account_tax = $tax_fname;
                                                $category_tax = DB::table('account')
                                                    ->where('account_name',$account_tax)
                                                    ->value('account_type');

                                                $vendor_name = DB::table('vendor_customer')
                                                    ->where('id',$vendor)
                                                    ->value('name');


                                                $db_tax = DB::table('transactions')->insert(
                                                    ['date' => $from, 'operation' => $operation_tax, 'amount' => $sum_tax, 'account' => $account_tax, 'notes' => $notes,'transaction_type'=> 1,'description'=> $vendor_name."- Bill ".$num."-".$products[$i],'category'=> $category_tax,'status'=> 0,'invoice_num' => $num]);*/
                                            }
                                        }
                                    } else {

                                        $var1 = "(";
                                        $var2 = ")";
                                        $pool = $tax[$z];
                                        if (!empty($pool)) {

                                            $temp1 = strpos($pool, $var1) + strlen($var1);
                                            $result = substr($pool, $temp1, strlen($pool));
                                            $dd = strpos($result, $var2);
                                            if ($dd == 0) {
                                                $dd = strlen($result);
                                            }

                                            $percent = substr($result, 0, $dd);
                                            $sum_tax = ($percent / 100) * ($quantity[$i] * $price[$i]);

                                            $sum = $sum + $sum_tax;

                                        }
                                    }

                                }

                            }

                            if ($db_bill_item) {

                                $operation_expense = 'add';
                                $account_expense = DB::table('account')
                                    ->where('id', $accounts[$i])
                                    ->value('account_name');
                                $category_expense = DB::table('account')
                                    ->where('id', $accounts[$i])
                                    ->value('account_type');

                                $vendor_name = DB::table('vendor_customer')
                                    ->where('id', $vendor)
                                    ->value('name');

                                $sum_expenses = ($quantity[$i] * $price[$i]) + $sum_tax;
                                $db_expenses = DB::table('transactions')->insert(
                                    ['date' => $from, 'operation' => $operation_expense, 'amount' => $sum_expenses, 'account' => $account_expense, 'notes' => $notes, 'transaction_type' => 4, 'description' => $vendor_name . "- Bill " . $num . "-" . $products[$i], 'category' => $category_expense, 'status' => 0, 'invoice_num' => $num]);

                            } else {
                                $db_expenses = false;
                            }

                            if ($i >= count($quantity) - 1 && $db_bill_item) {
                                $check = 'success';
                            } else if ($db_expenses) {
                                continue;
                            } else {
                                $check = 'failed';
                                break;
                            }

                        } catch (Exception $e) {
                            $check = 'failed';
                        }
                    }
                } else {
                    $check = 'failed';
                }

                if ($check == 'success') {
                    $operation = 'add';
                    $amount = $sum;
                    $account = 'Accounts Payable';
                    $vendor_name = DB::table('vendor_customer')
                        ->where('id', $vendor)
                        ->value('name');

                    $db = DB::table('transactions')->insert(
                        ['date' => $from, 'operation' => $operation, 'amount' => $amount, 'account' => $account, 'notes' => $notes, 'transaction_type' => 1, 'description' => $vendor_name . "-Bill " . $num, 'category' => $account, 'status' => 0, 'invoice_num' => $num]);
                    if (!$db) {
                        $check = 'failed';
                    }
                }
            }

            if ($check != 'success') {
                DB::table('bill_item')->where('bill_no', '=', $num)->delete();
                DB::table('bill')->where('bill_no', '=', $num)->delete();
                DB::table('transactions')->where('invoice_num', '=', $num)->delete();
            }

        }

        echo $check;

    }

    public function editBillOne(Request $request)
    {

        $vendor = $request->get('vendor');
        $currency = $request->get('currency');
        $from = $request->get('from');
        $to = $request->get('to');
        $po = $request->get('po');
        $num = $request->get('num');
        $notes = $request->get('notes');

        $products = explode(',', $request->get('products'));
        $accounts = explode(',', $request->get('accounts'));
        $desc = explode(',', $request->get('desc'));
        $quantity = explode(',', $request->get('quantity'));
        $price = explode(',', $request->get('prices'));
        $tax = explode(',', $request->get('taxs'));

        $validator = Validator::make($request->all(), [
            'vendor' => 'required',
            'currency' => 'required',
            'from' => 'required',
            'to' => 'required',
            'po' => 'required',
            'num' => 'required'
        ]);
        $check = 'failed';
        $db = false;
        if ($validator->fails()) {
            $check = 'failed_';
        } else {

            $db = DB::table('bill')
                ->where('bill_no', '=', $num)
                ->update(['vendor' => $vendor, 'currency' => $currency, 'date' => $from, 'due_date' => $to, 'po_os' => $po, 'notes' => $notes]);

            for ($i = 0; $i < count($quantity); $i++) {
                try {
                    $da = DB::table('bill_item')
                        ->where([
                            ['bill_no', '=', $num],
                            ['item_name', '=', $products[$i]]
                        ])
                        ->get();


                    $da_ = DB::table('bill_item')
                        ->where([
                            ['bill_no', '=', $num],
                        ])
                        ->get();


                    foreach ($da_ as $item) {
                        if (!in_array($item->item_name, $products)) {
                            $delete = DB::table('bill_item')
                                ->where([
                                    ['bill_no', '=', $num],
                                    ['item_name', '=', $item->item_name]
                                ])
                                ->delete();
                            if ($delete) $check = 'success';

                        }
                    }

                    if (count($da) < 1) {


                        $db_ = DB::table('bill_item')->insert(['item_name' => $products[$i], 'account' => $accounts[$i], 'description' => $desc[$i], 'quantity' => $quantity[$i], 'price' => $price[$i], 'tax' => str_replace("$", ",", $tax[$i]), 'bill_no' => $num]);

                        if ($db_) {
                            $operation_expense = 'add';
                            $account_expense = DB::table('account')
                                ->where('id', $accounts[$i])
                                ->value('account_name');
                            $category_expense = DB::table('account')
                                ->where('id', $accounts[$i])
                                ->value('account_type');

                            $vendor_name = DB::table('vendor_customer')
                                ->where('id', $vendor)
                                ->value('name');

                            $sum_expenses = ($quantity[$i] * $price[$i]);
                            $db_expenses = DB::table('transactions')->insert(
                                ['date' => $from, 'operation' => $operation_expense, 'amount' => $sum_expenses, 'account' => $account_expense, 'notes' => $notes, 'transaction_type' => 4, 'description' => $vendor_name . "- Bill " . $num . "-" . $products[$i], 'category' => $category_expense, 'status' => 0, 'invoice_num' => $num]);


                            if ($db_expenses)
                                $check = 'success';
                        }

                    } else {

                        $db_1 = DB::table('bill_item')
                            ->where([
                                ['bill_no', '=', $num],
                                ['item_name', '=', $products[$i]]
                            ])
                            ->update(['account' => $accounts[$i], 'description' => $desc[$i], 'quantity' => $quantity[$i], 'price' => $price[$i], 'tax' => str_replace("$", ",", $tax[$i])]);
                        if ($db_1) {
                            $operation_expense = 'add';
                            $account_expense = DB::table('account')
                                ->where('id', $accounts[$i])
                                ->value('account_name');
                            $category_expense = DB::table('account')
                                ->where('id', $accounts[$i])
                                ->value('account_type');

                            $vendor_name = DB::table('vendor_customer')
                                ->where('id', $vendor)
                                ->value('name');

                            $sum_expenses = ($quantity[$i] * $price[$i]);
                            $description = $vendor_name . "- Bill " . $num . "-" . $products[$i];
                            $db_expenses = DB::table('transactions')
                                ->where([
                                    ['invoice_num', '=', $num],
                                    ['description', '=', $description]
                                ])
                                ->update(
                                    ['operation' => $operation_expense, 'amount' => $sum_expenses, 'account' => $account_expense, 'notes' => $notes, 'transaction_type' => 4, 'description' => $description, 'category' => $category_expense, 'status' => 0, 'invoice_num' => $num]);


                            if ($db_expenses)
                                $check = 'success';

                        }


                    }

                } catch (Exception $e) {
                    $check = 'failed';
                }
            }
        }

        if ($db) {
            $check = 'success';
        }

        $sum = 0;
        for ($i = 0; $i < count($quantity); $i++) {
            try {
                $sum = $sum + ($quantity[$i] * $price[$i]);
                if (count($tax) > 0) {
                    for ($z = 0; $z < count($tax); $z++) {
                        if (strpos($tax[$z], "$") > 0) {
                            $tax_arr = explode("$", $tax[$z]);
                            for ($z = 0; $z < count($tax); $z++) {
                                $str = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                                if (strcmp($products[$i], $str) == 0) {
                                    $var1 = "(";
                                    $var2 = ")";
                                    $pool = $tax_arr[$z];
                                    $temp1 = strpos($pool, $var1) + strlen($var1);
                                    $result = substr($pool, $temp1, strlen($pool));
                                    $dd = strpos($result, $var2);
                                    if ($dd == 0) {
                                        $dd = strlen($result);
                                    }

                                    $percent = substr($result, 0, $dd);
                                    $sum = $sum + (($percent / 100) * ($quantity[$i] * $price[$i]));
                                }
                            }
                        } else {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum = $sum + (($percent / 100) * ($quantity[$i] * $price[$i]));
                        }

                    }

                }
            } catch (Exception $exceptione) {
            }
        }


        $operation = 'add';
        $amount = $sum;
        $account = 'Accounts Payable';
        $cust = DB::table('vendor_customer')
            ->where('id', $vendor)
            ->value('name');

        $tr = DB::table('transactions')
            ->where([
                ['invoice_num', '=', $num],
            ])
            ->get();

        if (count($tr) > 0) {
            $db_ = DB::table('transactions')
                ->where([
                    ['invoice_num', '=', $num],
                    ['account', '=', $account]
                ])
                ->update(['date' => $from, 'operation' => $operation, 'amount' => $amount, 'account' => $account, 'notes' => $notes, 'transaction_type' => 1, 'description' => $cust . "-" . $num, 'category' => $account, 'status' => 0]);

            if (!$db_) {
                $check = 'failed';
            }
        }

        echo $check;

    }


    public static function sumItemVendor($id)
    {
        $return_array = array();
        $data = DB::table('bill_item')->where('bill_no', intval($id))->get(['price', 'quantity', 'tax']);

        $total = 0;
        foreach ($data as $dat) {
            $total = $total + ($dat->price * $dat->quantity);
            if (!empty($dat->tax)) {
                if (strpos($dat->tax, ",") > 0) {
                    $taxes = explode(",", $dat->tax);
                    foreach ($taxes as $item) {
                        $pool = substr($item, strpos($item, "("), strpos($item, ")"));
                        $percent = ProductsAndServicesController::GetBetween("(", ")", $pool);
                        $total = $total + (($percent / 100) * ($dat->price * $dat->quantity));
                    }
                } else {
                    $taxes = $dat->tax;
                    $pool = substr($taxes, strpos($taxes, "("), strpos($taxes, ")"));
                    $percent = ProductsAndServicesController::GetBetween("(", ")", $pool);
                    $total = $total + (($percent / 100) * ($dat->price * $dat->quantity));
                }

            }
        }

        $data_paid = DB::table('payment')->select('amount')->where('invoice_num', intval($id))->get();

        $sum_payed = 0;
        foreach ($data_paid as $item) {
            $sum_payed = $sum_payed + $item->amount;
        }

        $return_array[0] = $data;
        $return_array[1] = $total;
        $return_array[2] = $sum_payed;
        return $return_array;
    }

    public function editBillItem(Request $request)
    {
        $id = $request->get('id');
        $vendors = DB::table('vendor_customer')->get();
        $products = DB::table('products_services')
            ->where('sales_purchases', '=', 1)
            ->orWhere('sales_purchases', '=', 11)
            ->get();
        $taxes = DB::table('tax')->get();
        $bills = DB::table('bill')
            ->where('bill_no', $id)
            ->get();

        $expenses = DB::table('account')
            ->where('account_chart', '=', 4)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        $bill_currency = array();
        $payments = DB::table('payment')
            ->where('invoice_num', $id)
            ->get();

        foreach ($bills as $bill) {
            $vendor_ = $bill->vendor;
            $date = $bill->date;
            $due = $bill->due_date;
            $po = $bill->po_os;
            $bill_no = $bill->bill_no;
            $notes = $bill->notes;
            $currency = $bill->currency;
        }

        $bill_items = DB::table('bill_item')
            ->where('bill_no', $id)
            ->get();
        echo '         
                <div class="row" style="margin-left: 4%;margin-top: 3%;">
                    <div class="col-sm-9">
                        <h3>Edit Bill</h3>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-primary" id="back" onclick="LoadContent(\'bills\')" style="margin-left: 55%;">Back</button>
                    </div>
                </div>
                
                <div class="row"style="padding-left: 5%; margin-top: 2% ">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12" id="vendor_data_currency">
                            <div class="form-group row">
                                <label class="control-label col-sm-4" for="vendor">Vendor*:</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-example-basic-multiple" name="currency" style="width:65%" id="vendor" onchange="getCurrencyData()">';
        foreach ($vendors as $vendor) {
            if ($vendor_ == $vendor->id) {
                echo '<option value="' . $vendor->id . '" selected="selected">' . $vendor->name . '</option>';
            } else {
                echo '<option value="' . $vendor->id . '">' . $vendor->name . '</option>';
            }

        }
        echo '</select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-4" for="currency">Currency:</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-example-basic-multiple" name="currency" style="width:65%" id="currency">';
        foreach ($vendors as $vendor) {
            if (strcmp($currency, $vendor->currency) == 0) {
                if (!in_array($vendor->currency, $bill_currency)) {
                    echo '<option value="' . $vendor->currency . '" selected="selected">' . $vendor->currency . '</option>';
                    array_push($bill_currency, $vendor->currency);
                }

            } else {
                if (!in_array($vendor->currency, $bill_currency)) {
                    echo '<option value="' . $vendor->currency . '">' . $vendor->currency . '</option>';
                    array_push($bill_currency, $vendor->currency);
                }

            }
        }
        echo '</select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="control-label col-sm-4" for="from">Date:</label>
            
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" style="width: 50%;margin-left:5%;font-size: 14px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" datepicker-here id="from" data-position="right top" value="' . $date . '" data-language="en" data-date-format="yyyy-mm-dd"/>
                                        <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </div>
                                    </div>
            
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-4" for="to">Due Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" datepicker-here id="to" data-position="right top" data-language="en" style="width: 50%;margin-left:5%;font-size: 14px;background-color: white;border: solid 1px #C0C0C0;border-right-style: none;padding-left: 5%" value="' . $due . '" data-date-format="yyyy-mm-dd"/>
                                        <div class="input-group-addon" style="background-color: white;border: solid 1px #C0C0C0; border-left-style: none">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </div>
                                    </div>
            
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-4" for="po">P.O./S.O.:</label>
                                <div class="col-sm-8">
                                    <input type="name" value="' . $po . '" class="form-control" style="width:66%;margin-left:5%;font-size:14px" id="po">
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
                                    <label class="control-label col-sm-2" for="bill_num">Bill #</label>
                                    <div class="col-sm-10">
                                        <input type="name" class="form-control" id="bill_num" placeholder="####" style="width:65%;font-size:14px" value="' . $bill_no . '">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2" for="notes">Notes:</label>
                                    <div class="col-sm-10">
                                        <textarea type="name" class="form-control" style="width:65%;font-size:14px" id="notes">' . $notes . '</textarea>
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
                    <tr>
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
                    <tbody id="dataTable">';
        $i = 1;
        $z = (18 - count($bill_items)) + 3;
        $u = count($bill_items) + 1;
        foreach ($bill_items as $bill_item) {
            echo '
                                 <tr id="tr' . $i . '">
                                <td width="15%">
                                    <select class="form-control js-example-basic-multiple" name="product[]" id="product" onchange="getProductData(this.value,' . $i . ',1)" style="font-size:14px">
                                        <option value=""></option>';
            foreach ($products as $product) {

                if (strcmp($bill_item->item_name, $product->name) == 0) {
                    echo '<option value="' . $product->name . '" selected="selected">' . $product->name . '</option>';
                } else {
                    echo '<option value="' . $product->name . '">' . $product->name . '</option>';
                }
            }

            echo '</select>
                                </td>
                                <td width="20%">
                                   <select class="form-control js-example-basic-multiple" name="account[]" id="account' . $i . '" style="font-size:14px">
                                        <option value=""></option>
                                        <optgroup label="EXPENSES" style="font-size: 14px;">';

            foreach ($expenses as $expense) {
                if ($expense->id == $bill_item->account) {
                    echo '<option value="' . $expense->id . '" selected>' . $expense->account_name . '</option>';
                } else {
                    echo '<option value="' . $expense->id . '" >' . $expense->account_name . '</option>';
                }

            }

            echo '</optgroup>
                                    </select>
                                </td>
                                <td width="15%"> <textarea type="text" class="form-control" name="desc[]" id="desc' . $i . '" placeholder="Enter description" style="font-size:14px">' . $bill_item->description . '</textarea></td>
                                <td width="10%"> <input type="number" class="form-control" name="quantity[]" id="quantity' . $i . '" min="1" value="' . $bill_item->quantity . '" onchange=getProductData("' . $bill_item->item_name . '",' . $i . ',1) style="font-size:14px"></td>
                                <td width="10%"> <input type="number" class="form-control" name="price[]" id="price' . $i . '" min="0" value="' . $bill_item->price . '" onchange=getProductData("' . $bill_item->item_name . '",' . $i . ',1) style="font-size:14px"></td>
                                <td width="10%">
                                  <select class="form-control js-example-basic-multiple" id="tax' . $i . '" name="tax' . $i . '[]" multiple="multiple" aria-describedby="basic-addon1" style="font-size:14px" onchange="getTaxUpdate(' . count($bill_items) . ')">';

            echo '<option value=""></option>';

            /* onchange=getProductData("' . $bill_item->item_name . '",' . $i . ',1)*/
            foreach ($taxes as $taxs) {
                if (strpos($bill_item->tax, $taxs->abbreviation) !== false) {
                    echo '<option value="' . $taxs->abbreviation . '(' . $taxs->tax_rate . ')" selected="selected">' . $taxs->abbreviation . '</option>';

                } else {
                    echo '<option value="' . $taxs->abbreviation . '(' . $taxs->tax_rate . ')">' . $taxs->abbreviation . '</option>';
                }
            }
            echo '</select>  
                                </td>
                                <td width="10%">
                                    <span style="float: right;" id="t' . $i . '">' . ($bill_item->price * $bill_item->quantity) . '</span>
                                    <input type="hidden" id="init_total' . $i . '" value="' . ($bill_item->price * $bill_item->quantity) . '">
                                </td>';
            echo '<td>
                            <button type="button" class="btn btn-default" style="background-color: transparent" onclick=deleteRowBill("tr' . $i . '")>
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>';
            echo '</tr>';
            $i++;
        }
        echo '</tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td colspan="2">
                                <span>Subtotal: </span><span style="float: right;" id="subtotal">0</span><br />
                                <div id="tax_space"></div>
                                <span>Total: </span><span  style="float: right;" id="total">0</span>
                                <input type="hidden" id="cur" value="' . $currency . '"/>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row" style="padding-left: 5%;margin-bottom: 5%;">
            <div class="col-sm-12">
                <button  type="button" id="addrowbtn" onclick="addRowBill()" class="btn btn-primary">ADD Row</button> <button  type="button" id="editbillbtn" class="btn btn-primary" onclick="EditBill();">EDIT BILL</button>
            </div>
        </div>';

        if (count($payments) > 0) {
            echo '
        <div class="row" style="margin-top: 2.5%">
            <div class="col-sm-12" style="padding-left: 5%; padding-right: 5%" id="payment_table">
        <table class="table table-striped borderless" style="border: solid 1px #CCCCCC;font-size: 14px;">
            <thead>
                    <tr>
                        <th scope="col">Payment Date</th>
                        <th scope="col">Payment method</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="dataTable2">';
            foreach ($payments as $payment) {
                echo '<td>' . $payment->date . '</td>';
                echo '<td>' . $payment->payment_method . '</td>';
                echo '<td>' . $payment->amount . '</td>';
                echo '<td>
                                    <a href="#" onclick=deleteDataPayment("' . $payment->id . '","' . $id . '")>
                            <i class="fa fa-fw fa-trash"></i>
                        </a>
                                </td>';
            }
            echo '</tbody>
                    </table>
            </div>
        </div>';
        }
    }

    public function saveinvoice(Request $request)
    {

        $title = $request->get('title');
        $summary = $request->get('summary');
        $customer_id = $request->get('customer_id');
        $invoice_num = $request->get('invoice_num');
        $po = $request->get('po');
        $invoice_date = $request->get('invoice_date');
        $due_date = $request->get('due_date');
        $notes = $request->get('notes');
        $status = 0;
        $products = $request->get('products');
        $desc = $request->get('desc');
        $quantity = $request->get('quantity');
        $prices = $request->get('prices');
        $taxes = $request->get('taxes');
        $customer = DB::table('vendor_customer')
            ->where('id', $customer_id)
            ->value('name');

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'customer_id' => 'required',
            'invoice_num' => 'required',
            'invoice_date' => 'required',
            'due_date' => 'required',
            'products' => 'required',
            'quantity' => 'required',
            'prices' => 'required'
        ]);

        if ($validator->fails()) {

        } else {

            $row = DB::table('invoice')
                ->where('invoice_num', '=', $invoice_num)
                ->get();
            $check = 0;
            $db_sales = false;

            if (count($row) > 0) {
                $check = 2;
            } else {
                try {
                    $res = DB::table('invoice')->insert(
                        ['title' => $title, 'subtitle' => $summary, 'po_os' => $po, 'invoice_date' => $invoice_date, 'payment_due' => $due_date, 'customer_id' => $customer_id, 'notes' => $notes, 'status' => $status, 'invoice_num' => $invoice_num]
                    );

                    $array_products = explode(",", $products);
                    $array_desc = explode(",", $desc);
                    $array_quantity = explode(",", $quantity);
                    $array_prices = explode(",", $prices);
                    $array_taxes = explode(",", $taxes);

                    $operation = 'add';
                    $account_receivable = 'Accounts Receivable';
                    $account_sales = 'Sales';


                    if ($res) {

                        $sum = 0;
                        for ($i = 0; $i < count($array_products); $i++) {
                            $res2 = DB::table('invoice_item')->insert(
                                ['item_name' => $array_products[$i], 'item_description' => $array_desc[$i], 'item_quantity' => $array_quantity[$i], 'item_price' => $array_prices[$i], 'invoice_num' => $invoice_num, 'tax' => $taxes]);


                            if ($res2) {
                                $sum = $sum + ($array_prices[$i] * $array_quantity[$i]);
                                $sales_amount = ($array_prices[$i] * $array_quantity[$i]);

                                $db_sales = DB::table('transactions')->insert(
                                    ['date' => $invoice_date, 'operation' => $operation, 'amount' => $sales_amount, 'account' => $account_sales, 'notes' => $notes, 'transaction_type' => 3, 'description' => $customer . "-" . $invoice_num . "-" . $array_products[$i], 'category' => $account_sales, 'status' => 0, 'invoice_num' => $invoice_num]);

                                if (!empty($taxes)) {
                                    if (strpos($taxes, ",") !== false) {
                                            for ($z = 0; $z < count($array_taxes); $z++) {
                                                $abbr = substr($array_taxes[$z], 0, strpos($array_taxes[$z], "("));
                                                $product = substr($array_taxes[$z], strpos($array_taxes[$z], "_") + 1);

                                                if (strpos(trim($array_products[$i]), trim($product)) !== false) {

                                                    $price = $array_prices[$i];
                                                    $percent = self::GetBetween("(", ")", $array_taxes[$z]);
                                                    $amount = ($percent / 100) * ($price * $array_quantity[$i]);

                                                    $sum_tax = $amount;
                                                    $sum = $sum + $sum_tax;
                                                    $operation_tax = 'add';
                                                    $tax_name = DB::table('tax')
                                                        ->where('abbreviation', $abbr)
                                                        ->value('name');
                                                    $account_tax = $tax_name;
                                                    $category_tax = DB::table('account')
                                                        ->where('account_name', $account_tax)
                                                        ->value('account_type');

                                                    $db_tax = DB::table('transactions')->insert(
                                                        ['date' => $invoice_date, 'operation' => $operation_tax, 'amount' => $sum_tax, 'account' => $account_tax, 'notes' => $notes, 'transaction_type' => 1, 'description' => "Sales tax for " . $customer . "-" . $invoice_num . "-" . $product, 'category' => $category_tax, 'status' => 0, 'invoice_num' => $invoice_num]);

                                                    if (!$db_tax) $check = 0;
                                                    else $check = 1;
                                                }
                                            }
                                    } else {
                                        $abbr = substr($taxes, 0, strpos($taxes, "("));
                                        $product = substr($taxes, strpos($taxes, "_") + 1);

                                        if (strpos($array_products[$i], $product) !== false) {
                                            $price = $array_prices[$i];

                                            $percent = self::GetBetween("(", ")", $taxes);
                                            $amount = ($percent / 100) * ($price * $array_quantity[$i]);
                                            $sum_tax = $amount;
                                            $sum = $sum + $sum_tax;
                                            $operation_tax = 'add';
                                            $tax_name = DB::table('tax')
                                                ->where('abbreviation', $abbr)
                                                ->value('name');
                                            $account_tax = $tax_name;
                                            $category_tax = DB::table('account')
                                                ->where('account_name', $account_tax)
                                                ->value('account_type');

                                            $db_tax = DB::table('transactions')->insert(
                                                ['date' => $invoice_date, 'operation' => $operation_tax, 'amount' => $sum_tax, 'account' => $account_tax, 'notes' => $notes, 'transaction_type' => 1, 'description' => "Sales tax for " . $customer . "-" . $invoice_num . "-" . $product, 'category' => $category_tax, 'status' => 0, 'invoice_num' => $invoice_num]);
                                            if (!$db_tax) $check = 0;
                                        }

                                    }
                                }
                            } else {
                                DB::table('invoice')->where('invoice', $invoice_num)->delete();
                                DB::table('invoice')->where('invoice_num', $invoice_num)->delete();
                            }
                        }


                        $amount = $sum;
                        $db_account_receivable = DB::table('transactions')->insert(
                            ['date' => $invoice_date, 'operation' => $operation, 'amount' => $amount, 'account' => $account_receivable, 'notes' => $notes, 'transaction_type' => 0, 'description' => $customer . "-" . $invoice_num, 'category' => $account_receivable, 'status' => 0, 'invoice_num' => $invoice_num]);

                        if ($db_account_receivable && $db_sales) {
                            $check = 1;
                        } else {
                            $check = 0;
                        }

                    } else {
                        DB::table('invoice')->where('invoice', $invoice_num)->delete();
                        $check = 0;
                    }
                } catch (Exception $e) {
                    $check = 0;
                }
            }

            echo $check;

        }

    }

    public function updateinvoice(Request $request)
    {

        $title = $request->get('title');
        $summary = $request->get('summary');
        $customer_id = $request->get('customer_id');
        $invoice_num = $request->get('invoice_num');
        $po = $request->get('po');
        $invoice_date = $request->get('invoice_date');
        $due_date = $request->get('due_date');
        $notes = $request->get('notes');
        $status = 0;
        $products = $request->get('products');
        $desc = $request->get('desc');
        $quantity = $request->get('quantity');
        $prices = $request->get('prices');
        $taxes = $request->get('taxes');
        $customer = DB::table('vendor_customer')
            ->where('id', $customer_id)
            ->value('name');

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'customer_id' => 'required',
            'invoice_num' => 'required',
            'invoice_date' => 'required',
            'due_date' => 'required',
            'products' => 'required',
            'quantity' => 'required',
            'prices' => 'required'
        ]);

        $db_sales = false;
        $operation = 'add';
        $account_receivable = 'Accounts Receivable';
        $account_sales = 'Sales';
        $sum = 0;
        $db_run_tax = false;

        if ($validator->fails()) {
            $check = 2;
        } else {

            try {

                DB::table('invoice')
                    ->where('invoice_num', $invoice_num)
                    ->update(
                        ['title' => $title, 'subtitle' => $summary, 'po_os' => $po, 'invoice_date' => $invoice_date, 'payment_due' => $due_date, 'customer_id' => $customer_id, 'notes' => $notes, 'status' => $status, 'invoice_num' => $invoice_num]
                    );

                $array_products = explode(",", $products);
                $array_desc = explode(",", $desc);
                $array_quantity = explode(",", $quantity);
                $array_prices = explode(",", $prices);
                $array_taxes = explode(",", $taxes);

                for ($i = 0; $i < count($array_products); $i++) {
                    try {

                        $da = DB::table('invoice_item')
                            ->where([
                                ['invoice_num', '=', $invoice_num],
                                ['item_name', '=', $array_products[$i]]
                            ])
                            ->get();
                        $da_ = DB::table('invoice_item')
                            ->where([
                                ['invoice_num', '=', $invoice_num],
                            ])
                            ->get();

                        foreach ($da_ as $item) {

                            if (!in_array($item->item_name, $array_products)) {
                                $del_db = DB::table('invoice_item')
                                    ->where('item_name', $item->item_name)
                                    ->where('invoice_num', $invoice_num)
                                    ->delete();
                            }
                        }
                        if (count($da) < 1) {
                            $db = DB::table('invoice_item')
                                ->insert(['item_name' => $array_products[$i], 'item_description' => $array_desc[$i], 'item_quantity' => $array_quantity[$i], 'item_price' => $array_prices[$i], 'tax' => $taxes, 'invoice_num' => $invoice_num]);
                            if ($db) {
                                $sum_sales = ($array_prices[$i] * $array_quantity[$i]);

                                $db_sales = DB::table('transactions')->insert(
                                    ['date' => $invoice_date, 'operation' => $operation, 'amount' => $sum_sales, 'account' => $account_sales, 'notes' => $notes, 'transaction_type' => 3, 'description' => $customer . "-" . $invoice_num . "-" . $array_products[$i], 'category' => $account_sales, 'status' => 0, 'invoice_num' => $invoice_num]);

                                if ($db_sales) {
                                    $sum = $sum + $sum_sales;
                                }
                            }

                        } elseif (count($da) > 1) {
                            $desc_transaction = $customer . "-" . $invoice_num . "-" . $array_products[$i];
                            $del_db_duplicate = DB::table('invoice_item')
                                ->where('item_name', $array_products[$i])
                                ->where('invoice_num', $invoice_num)
                                ->delete();

                            $del_db_duplicate_tr = DB::table('transactions')
                                ->where('description', $desc_transaction)
                                ->where('invoice_num', $invoice_num)
                                ->delete();

                            if ($del_db_duplicate && $del_db_duplicate_tr) {
                                $db = DB::table('invoice_item')
                                    ->insert(['item_name' => $array_products[$i], 'item_description' => $array_desc[$i], 'item_quantity' => $array_quantity[$i], 'item_price' => $array_prices[$i], 'tax' => $taxes, 'invoice_num' => $invoice_num]);
                                if ($db) {
                                    $sum_sales = ($array_prices[$i] * $array_quantity[$i]);
                                    $db_sales = DB::table('transactions')->insert(
                                        ['date' => $invoice_date, 'operation' => $operation, 'amount' => $sum_sales, 'account' => $account_sales, 'notes' => $notes, 'transaction_type' => 3, 'description' => $desc_transaction, 'category' => $account_sales, 'status' => 0, 'invoice_num' => $invoice_num]);
                                    if ($db_sales) {
                                        $sum = $sum + $sum_sales;
                                    }
                                }
                            }

                        } else {
                            $db = DB::table('invoice_item')
                                ->where([
                                    ['invoice_num', '=', $invoice_num],
                                    ['item_name', '=', $array_products[$i]]
                                ])
                                ->update(['item_name' => $array_products[$i], 'item_description' => $array_desc[$i], 'item_quantity' => $array_quantity[$i], 'item_price' => $array_prices[$i], 'tax' => $taxes]);

                            if ($db) {
                                $da_trans = DB::table('transactions')
                                    ->where([
                                        ['invoice_num', '=', $invoice_num],
                                        ['account', '=', $account_sales],
                                        ['description', '=', $customer . "-" . $invoice_num . "-" . $array_products[$i]]])
                                    ->get();

                                if (count($da_trans) > 0) {
                                    $sum_sales = ($array_prices[$i] * $array_quantity[$i]);
                                    $db_sales = DB::table('transactions')
                                        ->where([
                                            ['invoice_num', '=', $invoice_num],
                                            ['account', '=', $account_sales],
                                            ['description', '=', $customer . "-" . $invoice_num . "-" . $array_products[$i]]
                                        ])
                                        ->update(
                                            ['date' => $invoice_date, 'operation' => $operation, 'amount' => $sum_sales, 'account' => $account_sales, 'notes' => $notes, 'transaction_type' => 3, 'description' => $customer . "-" . $invoice_num . "-" . $array_products[$i], 'category' => $account_sales]);
                                    if ($db_sales) {
                                        $check = 1;
                                        $sum = $sum + $sum_sales;
                                    } else $check = 0;
                                } else {
                                    $sum_sales = $array_quantity[$i] * $array_price[$i];
                                    $db_sales = DB::table('transactions')->insert(
                                        ['date' => $invoice_date, 'operation' => $operation, 'amount' => $sum_sales, 'account' => $account_sales, 'notes' => $notes, 'transaction_type' => 1, 'description' => $customer . "-" . $invoice_num . "-" . $array_products[$i], 'category' => $account_sales, 'status' => 0, 'invoice_num' => $invoice_num]);
                                    if ($db_sales) {
                                        $check = 1;
                                        $sum = $sum + $sum_sales;
                                    } else $check = 0;
                                }
                            }
                        }
                    } catch (Exception $e) {
                        $check = 0;
                    }
                }

                if (!empty($taxes)) {
                    if (strpos($taxes, ",") > -1) {
                        if (count($array_taxes) > 0) {
                            for ($i = 0; $i < count($array_taxes); $i++) {
                                $abbr = substr($array_taxes[$i], 0, strpos($array_taxes[$i], "("));
                                $product = substr($array_taxes[$i], strpos($array_taxes[$i], "_") + 1);

                                $price = DB::table('invoice_item')
                                    ->where([
                                        ['invoice_num', '=', $invoice_num],
                                        ['item_name', '=', $product],
                                    ])
                                    ->value('item_price');

                                $quantity = DB::table('invoice_item')
                                    ->where([
                                        ['invoice_num', '=', $invoice_num],
                                        ['item_name', '=', $product],
                                    ])
                                    ->value('item_quantity');

                                $account = DB::table('tax')
                                    ->where('abbreviation', $abbr)
                                    ->value('name');

                                $category_tax = "Sales Tax";


                                $percent = self::GetBetween("(", ")", $array_taxes[$i]);
                                $amount = ($percent / 100) * ($price * $quantity);

                                $sum_tax = $amount;

                                $da_tax = DB::table('transactions')
                                    ->where([
                                        ['invoice_num', '=', $invoice_num],
                                        ['account', '=', $account],
                                        ['description', '=', $customer . "-" . $invoice_num . "-" . $product]])
                                    ->get();


                                if (count($da_tax) > 0) {
                                    $db_run_tax = DB::table('transactions')
                                        ->where([
                                            ['invoice_num', '=', $invoice_num],
                                            ['account', '=', $account],
                                            ['description', '=', $customer . "-" . $invoice_num . "-" . $product]
                                        ])
                                        ->update(
                                            ['date' => $invoice_date, 'operation' => $operation, 'amount' => $sum_tax, 'account' => $account, 'notes' => $notes, 'transaction_type' => 1, 'description' => $customer . "-" . $invoice_num . "-" . $product, 'category' => $category_tax]);
                                    $sum = $sum + $sum_tax;
                                } else {
                                    $db_run_tax = DB::table('transactions')->insert(
                                        ['date' => $invoice_date, 'operation' => $operation, 'amount' => $sum_tax, 'account' => $account, 'notes' => $notes, 'transaction_type' => 1, 'description' => $customer . "-" . $invoice_num . "-" . $product, 'category' => $category_tax, 'status' => 0, 'invoice_num' => $invoice_num]);
                                    $sum = $sum + $sum_tax;
                                }

                            }
                        }
                    } else {
                        $abbr = substr($taxes, 0, strpos($taxes, "("));
                        $product = substr($taxes, strpos($taxes, "_") + 1);
                        $sum_tax = 0;

                        $price = DB::table('invoice_item')
                            ->where([
                                ['invoice_num', '=', $invoice_num],
                                ['item_name', '=', $product],
                            ])
                            ->value('item_price');

                        $quantity = DB::table('invoice_item')
                            ->where([
                                ['invoice_num', '=', $invoice_num],
                                ['item_name', '=', $product],
                            ])
                            ->value('item_quantity');

                        $account = DB::table('tax')
                            ->where('abbreviation', $abbr)
                            ->value('name');

                        $percent = self::GetBetween("(", ")", $taxes);
                        $amount = ($percent / 100) * ($price * $quantity);
                        $sum_tax = $amount;

                        $operation_tax = 'add';
                        $tax_name = DB::table('tax')
                            ->where('abbreviation', $abbr)
                            ->value('name');
                        $account_tax = $tax_name;
                        $category_tax = DB::table('account')
                            ->where('account_name', $account_tax)
                            ->value('account_type');

                        $da_tax = DB::table('transactions')
                            ->where([
                                ['invoice_num', '=', $invoice_num],
                                ['account', '=', $account_tax],
                                ['description', '=', $customer . "-" . $invoice_num . "-" . $product]])
                            ->get();

                        if (count($da_tax) > 0) {
                            $db_run_tax = DB::table('transactions')
                                ->where([
                                    ['invoice_num', '=', $invoice_num],
                                    ['account', '=', $account],
                                    ['description', '=', $customer . "-" . $invoice_num . "-" . $product]
                                ])
                                ->update(
                                    ['date' => $invoice_date, 'operation' => $operation_tax, 'amount' => $sum_tax, 'account' => $account, 'notes' => $notes, 'transaction_type' => 1, 'description' => $customer . "-" . $invoice_num . "-" . $product, 'category' => $category_tax]);
                            $sum = $sum + $sum_tax;
                        } else {
                            $db_run_tax = DB::table('transactions')->insert(
                                ['date' => $invoice_date, 'operation' => $operation_tax, 'amount' => $sum_tax, 'account' => $account_tax, 'notes' => $notes, 'transaction_type' => 1, 'description' => $customer . "-" . $invoice_num . "-" . $product, 'category' => $category_tax, 'status' => 0, 'invoice_num' => $invoice_num]);
                            $sum = $sum + $sum_tax;
                        }

                    }
                }

                $amount = $sum;
                $desc = $customer . "-" . $invoice_num;
                $db_account_receivable = DB::table('transactions')
                    ->where([
                        ['invoice_num', '=', $invoice_num],
                        ['description', '=', $desc],
                    ])
                    ->update(['date' => $invoice_date, 'operation' => $operation, 'amount' => $amount, 'account' => $account_receivable, 'notes' => $notes, 'transaction_type' => 0, 'description' => $customer . "-" . $invoice_num, 'category' => $account_receivable, 'status' => 0, 'invoice_num' => $invoice_num]);

                $response = array();
                $response['db_account_receivable'] = $db_account_receivable;
                $response['db_sales'] = $db_sales;
                $response['db_run_tax'] = $db_run_tax;


                if ($db_account_receivable && $db_sales && $db_run_tax) {
                    $check = 1;
                    $response['check'] = $check;
                } else {
                    $check = 0;
                    $response['check'] = $check;
                }

            } catch (Exception $exception) {
                echo $exception;
                $check = 0;
            }

        }

        echo json_encode($response);

    }

    public static function customerName($id)
    {
        $name = DB::table('vendor_customer')->select('name')->where('id', $id)->value('name');
        return $name;
    }

    public static function customerEmail($id)
    {
        $email = DB::table('vendor_customer')->select('email')->where('id', $id)->value('email');
        return $email;
    }

    public static function customerFullName($id)
    {
        $fname = DB::table('vendor_customer')->select('last_name')->where('id', $id)->value('last_name') . " " . DB::table('vendor_customer')->select('first_name')->where('id', $id)->value('first_name');
        return $fname;
    }

    public static function DueAmount($invoice)
    {
        $data = DB::table('invoice_item')->select('item_quantity', 'item_price', 'tax', 'item_name')->where('invoice_num', $invoice)->get();

        $data_paid = DB::table('payment')->select('amount')->where('invoice_num', $invoice)->get();

        $sum_invoice = 0;
        foreach ($data_paid as $item) {
            $sum_invoice = $sum_invoice + $item->amount;
        }

        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);

            if (!empty($item->tax)) {
                if (strpos($item->tax, ",") > 0) {
                    $tax_arr = explode(",", $item->tax);
                    for ($z = 0; $z < count($tax_arr); $z++) {
                        $str = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                        if (strcmp($item->item_name, $str) == 0) {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax_arr[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum = $sum + (($percent / 100) * ($item->item_quantity * $item->item_price));
                        }
                    }
                } else {
                    $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                    if (strcmp($item->item_name, $str_) == 0) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum = $sum + (($percent / 100) * ($item->item_quantity * $item->item_price));
                    }
                }

            }
        }
        $remain = $sum - $sum_invoice;
        if ($remain < 1)
            return 0;
        else return $remain;
    }

    public static function getIncomeWithTax($data)
    {
        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);

            if (!empty($item->tax)) {
                if (strpos($item->tax, ",") > 0) {
                    $tax_arr = explode(",", $item->tax);
                    for ($z = 0; $z < count($tax_arr); $z++) {
                        $str = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                        if (strcmp($item->item_name, $str) == 0) {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax_arr[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum = $sum + (($percent / 100) * ($item->item_quantity * $item->item_price));
                        }
                    }
                } else {
                    $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                    if (strcmp($item->item_name, $str_) == 0) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum = $sum + (($percent / 100) * ($item->item_quantity * $item->item_price));
                    }
                }

            }
        }

        return $sum;
    }

    public static function InvoiceAmount($invoice)
    {
        $data = DB::table('invoice_item')->select('item_quantity', 'item_price', 'tax', 'item_name')->where('invoice_num', $invoice)->get();

        $sum = 0;
        $sum_tax = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);
            if (!empty($item->tax)) {
                if (strpos($item->tax, ",") > -1) {
                    $tax_arr = explode(",", $item->tax);
                    for ($z = 0; $z < count($tax_arr); $z++) {
                        $str_ = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                        if (strcmp($item->item_name, $str_) == 0) {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax_arr[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                        }
                    }
                } else {
                    $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                    if (strcmp($item->item_name, $str_) == 0) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                    }
                }
            }

            /*$data_paid = DB::table('payment')->select('amount')->where('invoice_num', $invoice)->get();

            $sum_invoice = 0;
            foreach ($data_paid as $item_){
                $sum_invoice =  $sum_invoice + $item_->amount;
            }

            $sum = $sum - $sum_invoice;*/
        }
        return ($sum + $sum_tax);
    }

    public static function DueAmountExcludeTax($bill)
    {
        $data = DB::table('invoice_item')->select('item_quantity', 'item_price', 'tax', 'item_name')->where('invoice_num', $bill)->get();
        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);
        }

        return $sum;
    }

    public static function ProductTaxList($name)
    {
        $data = DB::table('products_services')->select('tax')->where('name', $name)->value('tax');
        return $data;
    }

    public static function GetBetween($var1 = "", $var2 = "", $pool)
    {
        $temp1 = strpos($pool, $var1) + strlen($var1);
        $result = substr($pool, $temp1, strlen($pool));
        $dd = strpos($result, $var2);
        if ($dd == 0) {
            $dd = strlen($result);
        }

        return substr($result, 0, $dd);
    }


    public function approveinvoice($invoice_no)
    {
        DB::table('invoice')
            ->where('invoice_num', $invoice_no)
            ->update(['status' => 1, 'approved_date' => Carbon::now()]);


        $num = $invoice_no;
        $invoices = DB::table('invoice')
            ->where('invoice_num', $invoice_no)
            ->get();
        $invoice_items = DB::table('invoice_item')
            ->where('invoice_num', $invoice_no)
            ->get();
        return View::make('action_invoice_list', compact('invoices', 'invoice_items', 'num'))->render();

    }

    public function printInvoice(Request $request)
    {
        /* $num = $request->get('num');
        $invoices = DB::table('invoice')
            ->where('invoice_num', $num)
            ->get();
        $invoice_items = DB::table('invoice_item')
            ->where('invoice_num', $num)
            ->get();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();/
        /* $pdf = PDF::loadView('pdf.document', compact('invoice_items','invoices','num'));
         return $pdf->download('document.pdf');*/

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }

    public static function printInvoice_($num)
    {
        $invoices = DB::table('invoice')
            ->where('invoice_num', $num)
            ->get();
        $invoice_items = DB::table('invoice_item')
            ->where('invoice_num', $num)
            ->get();
        $view = View::make('invoice_print_preview', compact('invoices', 'invoice_items', 'num'))->render();
        $pdf = PDF::loadView('invoice_print_preview', compact('invoices', 'invoice_items', 'num'));
        $pdf->download('invoice.pdf');
        //return $view;
    }

    public function downloadInvoice($num)
    {
        $invoices = DB::table('invoice')
            ->where('invoice_num', $num)
            ->get();
        $invoice_items = DB::table('invoice_item')
            ->where('invoice_num', $num)
            ->get();
        $pdf = PDF::loadView('pdf.invoice_pdf', compact('invoices', 'invoice_items', 'num'));
        return $pdf->download('invoice.pdf');
    }

    public function pdfview($num)
    {
        $invoices = DB::table('invoice')
            ->where('invoice_num', $num)
            ->get();
        $invoice_items = DB::table('invoice_item')
            ->where('invoice_num', $num)
            ->get();
        return view('invoice_print_preview', compact('num', 'invoice_items', 'invoices'));
    }

    public function uploadReceipt(Request $request)
    {

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

            $path = "upload/"; //set your folder path
            //set the valid file extensions
            $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "GIF", "JPG", "PNG", "doc", "txt", "docx", "pdf", "xls", "xlsx", "mp4"); //add the formats you want to upload

            $name = $_FILES['receipt_upload']['name']; //get the name of the file
            $size = $_FILES['receipt_upload']['size']; //get the size of the file
            if (strlen($name)) { //check if the file is selected or cancelled after pressing the browse button.
                list($txt, $ext) = explode(".", $name); //extract the name and extension of the file
                if (in_array($ext, $valid_formats)) { //if the file is valid go on.
                    if ($size < 2098888) { // check if the file size is more than 2 mb
                        $tmp = $_FILES['receipt_upload']['tmp_name'];
                        $path_full = $path . $name;
                        if (move_uploaded_file($tmp, $path_full)) {
                            $date = date('Y-m-d H:i');
                            $db = DB::table('receipts')
                                ->insert(['path' => $path_full, 'date' => $date]);
                            if ($db) {
                                echo 1;
                            } else {
                                echo 0;
                            }

                        } else {
                            echo 0;
                        }
                    } else {
                        echo 0;
                    }
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
            exit;

        }

    }

    public function deletereceipt($id)
    {

        $db = DB::table('receipts')->where('id', $id)->delete();

        if ($db) {
            $receipts = DB::table('receipts')
                ->get();
            $view = View::make('receipts', compact('receipts'))->render();
            echo $view;
        } else {
            echo 0;
        }
    }

    public function updatereceipt(Request $request)
    {
        $merchant = $request->get('merchant');
        $category = $request->get('category');
        $date = $request->get('date');
        $account = $request->get('account');
        $subtotal = $request->get('subtotal');
        $currency = $request->get('currency');
        $total = $request->get('total');
        $amount = $request->get('amount');
        $notes = $request->get('notes');
        $taxes = $request->get('taxes');
        $id = $request->get('id');

        $tax = $taxes . "&" . $amount;
        try {
            $db = DB::table('receipts')
                ->where('id', '=', $id)
                ->update(['merchant' => $merchant, 'category' => $category, 'account' => $account, 'total' => $total, 'subtotal' => $subtotal, 'date' => $date, 'notes' => $notes, 'currency' => $currency, 'tax' => $tax]);

            if ($db) {
                echo 1;
            } else echo 0;
        } catch (Exception $exception) {
            echo 0;
        }


    }

    public function statementpreview(Request $request)
    {

        $customer_id = $request->get('customer');
        $from = $request->get('from');
        $to = $request->get('to');
        $unpaid = $request->get('paid');
        $invoices_all = DB::table('invoice')
            ->whereBetween('invoice_date', [$from, $to])
            ->where('customer_id', '=', $customer_id)
            ->orderBy('invoice_date', 'ASC')
            ->get();
        $payments = DB::table('payment')
            ->join('invoice', 'invoice.invoice_num', 'payment.invoice_num')
            ->where('invoice.customer_id', $customer_id)
            ->whereBetween('payment.date', [$from, $to])
            ->get();
        if ($unpaid == 1) {
            $invoices = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('customer_id', '=', $customer_id)
                ->where('status', '=', 1)
                ->orWhere('status', '=', 2)
                ->orderBy('invoice_date', 'DESC')
                ->get();
        } elseif ($unpaid == 0) {
            $invoices = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('customer_id', '=', $customer_id)
                ->where('status', '=', 4)
                ->orWhere('status', '=', 5)
                ->orderBy('invoice_date', 'DESC')
                ->distinct()
                ->get();
        } else {
            $invoices = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('customer_id', '=', $customer_id)
                ->orderBy('invoice_date', 'DESC')
                ->distinct()
                ->get();
        }

        $customer = DB::table('vendor_customer')
            ->where('id', '=', $customer_id)
            ->first();
        return View::make('statement_preview', compact('invoices', 'customer', 'from', 'to', 'unpaid', 'invoices_all', 'customer_id', 'payments'))->render();

    }

    public static function DueAmountStatement($bill, $unpaid, $customer)
    {

        if ($unpaid == 1) {
            $data = DB::table('invoice_item')
                ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
                ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name')
                ->where('invoice.customer_id', '=', $customer)
                ->where('invoice.status', '=', 1)
                ->orWhere('invoice.status', '=', 2)
                ->orderBy('invoice.invoice_date', 'DESC')
                ->get();

        } elseif ($unpaid == 0) {
            $data = DB::table('invoice_item')
                ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
                ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name')
                ->where('invoice.customer_id', '=', $customer)
                ->where('invoice.status', '=', 4)
                ->orWhere('invoice.status', '=', 5)
                ->orderBy('invoice.invoice_date', 'DESC')
                ->get();
        } else {
            $data = DB::table('invoice_item')
                ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
                ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name')
                ->where('invoice.customer_id', '=', $customer)
                ->where('invoice.status', '=', 1)
                ->orWhere('invoice.status', '=', 2)
                ->where('invoice.invoice_num', $bill)
                ->orderBy('invoice.invoice_date', 'DESC')
                ->get();
        }

        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);
            if (!empty($item->tax)) {
                $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                if (strcmp($item->item_name, $str_) == 0) {
                    $array_taxes = explode(",", $item->tax);
                    for ($i = 0; $i < count($array_taxes); $i++) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum = $sum + (($percent / 100) * ($item->item_quantity * $item->item_price));
                    }
                }
            }
        }

        return $sum;
    }


    public static function DueAmountFromTo($from, $to, $customer, $unpaid)
    {

        if ($unpaid == 1) {
            $data = DB::table('invoice_item')
                ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
                ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice.invoice_date', 'invoice.invoice_num')
                ->whereBetween('invoice.payment_due', [$to, $from])
                ->where('invoice.customer_id', '=', $customer)
                ->where('invoice.status', '=', 1)
                ->orWhere('invoice.status', '=', 2)
                ->orderBy('invoice.payment_due', 'DESC')
                ->get();

        } elseif ($unpaid == 0) {
            $data = DB::table('invoice_item')
                ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
                ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice.invoice_date', 'invoice.invoice_num')
                ->whereBetween('invoice.payment_due', [$to, $from])
                ->where('customer_id', '=', $customer)
                ->where('invoice.status', '=', 4)
                ->orWhere('invoice.status', '=', 5)
                ->orderBy('invoice.payment_due', 'DESC')
                ->get();
        } else {
            $data = DB::table('invoice_item')
                ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
                ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice.invoice_date', 'invoice.invoice_num')
                ->whereBetween('invoice.payment_due', [$to, $from])
                ->where('customer_id', '=', $customer)
                ->where('invoice.status', '=', 1)
                ->orWhere('invoice.status', '=', 2)
                ->orderBy('invoice.payment_due', 'DESC')
                ->get();
        }

        $sum = 0;
        $sum_tax = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);

            if (!empty($item->tax)) {
                if (strpos($item->tax, ",") > -1) {
                    $tax_arr = explode(",", $item->tax);
                    for ($z = 0; $z < count($tax_arr); $z++) {
                        $str_ = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                        if (strcmp($item->item_name, $str_) == 0) {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax_arr[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                        }
                    }
                } else {
                    $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                    if (strcmp($item->item_name, $str_) == 0) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                    }
                }
            }

            $data_paid = DB::table('payment')->select('amount')->where('invoice_num', $item->invoice_num)->get();

            $sum_invoice = 0;
            foreach ($data_paid as $item) {
                $sum_invoice = $sum_invoice + $item->amount;
            }

            $sum = $sum - $sum_invoice;
        }

        return ($sum + $sum_tax);
    }

    public static function InvoicedAmountFromTo($from, $to, $customer)
    {

        $data = DB::table('invoice_item')
            ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
            ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice.invoice_date', 'invoice.invoice_num')
            ->whereBetween('invoice.invoice_date', [$from, $to])
            ->where('invoice.customer_id', $customer)
            ->get();


        $sum = 0;
        $sum_tax = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);

            if (!empty($item->tax)) {
                if (strpos($item->tax, ",") > -1) {
                    $tax_arr = explode(",", $item->tax);
                    for ($z = 0; $z < count($tax_arr); $z++) {
                        $str_ = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                        if (strcmp($item->item_name, $str_) == 0) {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax_arr[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                        }
                    }
                } else {
                    $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                    if (strcmp($item->item_name, $str_) == 0) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                    }
                }
            }

            /* $data_paid = DB::table('payment')->select('amount')->where('invoice_num', $item->invoice_num)->get();

            $sum_invoice = 0;
            foreach ($data_paid as $item){
                $sum_invoice =  $sum_invoice + $item->amount;
            }

            $sum = $sum - $sum_invoice;*/
        }

        return ($sum + $sum_tax);
    }

    function addPayment(Request $request)
    {

        $date = $request->get('date');
        $amount = $request->get('amount');
        $method = $request->get('method');
        $account = $request->get('account');
        $notes = $request->get('notes');
        $type = $request->get('type');
        $user_id = $request->get('user');
        $bid = $request->get('bid');

        $operation_cash = "add";
        $operation_account_receivable = "less";


        $customer = DB::table('invoice')
            ->join('vendor_customer', 'invoice.customer_id', '=', 'vendor_customer.id')
            ->where('invoice_num', $bid)
            ->value('name');

        $category = "payment from " . $customer . "|invoice #" . $bid;
        $desc = "Invoice Payment";

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required',
            'notes' => 'required',
            'bid' => 'required',
        ]);

        if ($validator->fails()) {
            echo 2;
        } else {

            DB::table('payment')->insert(['date' => $date, 'amount' => $amount, 'payment_method' => $method, 'payment_account' => $account, 'notes' => $notes, 'invoice_num' => $bid]);

            $id = DB::table('payment')
                ->orderBy('id', 'DESC')
                ->value('id');

            $db = DB::table('transactions')->insert(
                ['date' => $date, 'operation' => $operation_cash, 'amount' => $amount, 'account' => $account, 'notes' => $notes, 'transaction_type' => $type, 'description' => $desc, 'category' => $category, 'status' => 0, 'payment_id' => $user_id, 'invoice_num' => $bid]);

            $account_receivable = "Accounts Receivable";
            $db_ = DB::table('transactions')->insert(
                ['date' => $date, 'operation' => $operation_account_receivable, 'amount' => $amount, 'account' => $account_receivable, 'notes' => $notes, 'transaction_type' => $type, 'description' => $desc, 'category' => $category, 'status' => 0, 'payment_id' => $user_id, 'invoice_num' => $bid]);


            if ($db && $db_) {
                $data = DB::table('payment')->select('amount')->where('invoice_num', $bid)->get();
                $sum = 0;
                foreach ($data as $item) {
                    $sum = $sum + $item->amount;
                }

                $amount_need = self::DueAmount($bid);
                if ($amount_need <= $sum) {
                    $status = DB::table('invoice')
                        ->where('invoice_num', '=', $bid)
                        ->value('status');

                    $new_status = $status + 3;
                    DB::table('invoice')
                        ->where('invoice_num', '=', $bid)
                        ->update(['status' => $new_status]);
                }
                $num = $bid;
                $cash_bank = DB::table('account')
                    ->where('account_type', 'Cash and Bank')
                    ->where('account_chart', 0)
                    ->get();
                $view = View::make('addPayment', compact('num', 'cash_bank', 'user_id'))->render();
                echo "1&" . $view;
            } else {
                echo 0;
            }
        }

    }

    public static function totalBill($num)
    {
        $data = DB::table('bill_item')->select('quantity', 'price')->where('bill_no', $num)->get();
        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->quantity * $item->price);
        }

        return $sum;
    }

    function addPaymentBill(Request $request)
    {
        $date = $request->get('date');
        $amount = $request->get('amount');
        $method = $request->get('method');
        $account = $request->get('account');
        $notes = $request->get('notes');
        $bid = $request->get('bid');
        $user_id = $request->get('user');
        $type = $request->get('type');
        $operation_cash = "less";
        $operation_account_payable = "less";

        $vendor = DB::table('bill')
            ->join('vendor_customer', 'bill.vendor', '=', 'vendor_customer.id')
            ->where('bill_no', $bid)
            ->value('name');

        $category = "payment to " . $vendor;
        $desc = "Bill Payment";

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required',
            'notes' => 'required',
            'bid' => 'required',
        ]);

        if ($validator->fails()) {
            echo 2;
        } else {

            $db_1 = DB::table('payment')->insert(['date' => $date, 'amount' => $amount, 'payment_method' => $method, 'payment_account' => $account, 'notes' => $notes, 'invoice_num' => $bid]);

            $id = DB::table('payment')
                ->orderBy('id', 'DESC')
                ->value('id');

            $db_2 = DB::table('transactions')->insert(
                ['date' => $date, 'operation' => $operation_cash, 'amount' => $amount, 'account' => $account, 'notes' => $notes, 'transaction_type' => 0, 'description' => $desc, 'category' => $category, 'status' => 0, 'payment_id' => $user_id, 'invoice_num' => $bid]);

            $account_payable = "Accounts payable";
            $db_3 = DB::table('transactions')->insert(
                ['date' => $date, 'operation' => $operation_account_payable, 'amount' => $amount, 'account' => $account_payable, 'notes' => $notes, 'transaction_type' => 1, 'description' => $desc, 'category' => $category, 'status' => 0, 'payment_id' => $user_id, 'invoice_num' => $bid]);

            /* $bill_item = DB::table('bill_item')
                ->where('bill_no','=',$bid)
                ->get();
            $item_num = count($bill_item);
            $amount_need = self::totalBill($bid);
            $z =0;
            if ($amount < $amount_need){
                foreach ($bill_item as $item){
                    $amount_paid = $amount/($item_num - $z);
                    $desc = "Bill Payment -".$bid." -".$item->item_name;
                    $amount_req = $item->quantity * $item->price;
                    $account_name = DB::table('account')
                        ->where('id','=',$item->account)
                        ->value('account_name');

                    if ($amount_req < $amount_paid){
                        DB::table('transactions')->insert(
                            ['date' => $date, 'operation' => "add" , 'amount' => $amount_req, 'account' => $account_name, 'notes' => $notes,'transaction_type'=> 4,'description'=> $desc,'category'=> $account,'status'=> 0,'payment_id'=> $user_id,'invoice_num'=>$bid]);
                        $amount = $amount + ($amount_paid - $amount_req);
                    }
                else{
                        DB::table('transactions')->insert(
                            ['date' => $date, 'operation' => "add" , 'amount' => $amount_paid, 'account' => $account_name, 'notes' => $notes,'transaction_type'=> 4,'description'=> $desc,'category'=> $account,'status'=> 0,'payment_id'=> $user_id,'invoice_num'=>$bid]);
                        $amount = $amount - $amount_paid;
                    }
                    $z++;
                }
            }*/


            if ($db_1 && $db_2 && $db_3) {

                $data = DB::table('payment')->select('amount')->where('invoice_num', $bid)->get();
                $sum = 0;
                foreach ($data as $item) {
                    $sum = $sum + $item->amount;
                }

                $amount_need = self::totalBill($bid);
                if ($amount_need <= $sum) {
                    DB::table('bill')
                        ->where('bill_no', '=', $bid)
                        ->update(['status' => 1]);
                }
                $num = $bid;
                $cash_bank = DB::table('account')
                    ->where('account_type', 'Cash and Bank')
                    ->where('account_chart', 0)
                    ->get();
                $view = View::make('addPaymentBill', compact('num', 'cash_bank', 'user_id'))->render();
                echo "1&" . $view;
            } else {
                echo 0;
            }
        }

    }

    public static function GetPayment($num)
    {
        $data = DB::table('payment')
            ->select('amount')
            ->where('invoice_num', $num)->get();
        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + $item->amount;
        }

        return $sum;
    }

    public static function money($num)
    {
        setlocale(LC_MONETARY, 'en_US');
        return money_format('%i', $num);
    }

    public function dashboard_filter($customer, $from, $to, $status)
    {

        if ($customer != -1 && $from != 0 && $to != 0 && $status != 0) {

            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', [$from, $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));
        } elseif ($customer != -1 && $from != 0 && $to != 0 && $status == 0) {

            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereBetween('invoice_date', [$from, $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('customer_id', '=', $customer)
                ->where('status', 0)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('customer_id', '=', $customer)
                ->whereNotIn('status', [4, 5])
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));
        } elseif ($customer != -1 && $from != 0 && $to == 0 && $status == 0) {

            $to = date('m/d/Y', time());
            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereBetween('invoice_date', [$from, $to])
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereBetween('invoice_date', [$from, $to])
                ->where('status', 0)
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereBetween('invoice_date', [$from, $to])
                ->whereNotIn('status', [4, 5])
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer != -1 && $from != 0 && $to == 0 && $status != 0) {

            $to = date('m/d/Y', time());
            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', [$from, $to])
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', [$from, $to])
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', [$from, $to])
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer != -1 && $from == 0 && $to == 0 && $status == 0) {

            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', 0)
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereNotIn('status', [4, 5])
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer != -1 && $from == 0 && $to == 0 && $status != 0) {

            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer == -1 && $from != 0 && $to != 0 && $status == 0) {

            $invoice_all = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->get();
            $invoice_draft = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('status', 0)
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->whereNotIn('status', [4, 5])
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer == -1 && $from != 0 && $to != 0 && $status != 0) {

            $invoice_all = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('status', '=', $status)
                ->get();
            $invoice_draft = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('status', '=', $status)
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->whereBetween('invoice_date', [$from, $to])
                ->where('status', '=', $status)
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer == -1 && $from == 0 && $to != 0 && $status == 0) {
            $invoice_all = DB::table('invoice')
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->where('status', 0)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->whereNotIn('status', [4, 5])
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer == -1 && $from == 0 && $to != 0 && $status != 0) {
            $invoice_all = DB::table('invoice')
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer == -1 && $from == 0 && $to == 0 && $status != 0) {
            $invoice_all = DB::table('invoice')
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('status', '=', $status)
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer != -1 && $from == 0 && $to != 0 && $status != 0) {
            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer != -1 && $from == 0 && $to != 0 && $status == 0) {
            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->where('status', 0)
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->whereNotIn('status', [4, 5])
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer == -1 && $from != 0 && $to == 0 && $status != 0) {
            $to = date('m/d/Y', time());
            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', [$from, $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', [$from, $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', [$from, $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } elseif ($customer != -1 && $from == 0 && $to != 0 && $status != 0) {
            $to = date('m/d/Y', time());
            $invoice_all = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->where('customer_id', '=', $customer)
                ->where('status', '=', $status)
                ->whereBetween('invoice_date', ['12/15/1970', $to])
                ->orderBy('invoice_date', 'DESC')
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));

        } else {
            $invoice_all = DB::table('invoice')
                ->get();
            $invoice_draft = DB::table('invoice')
                ->where('status', 0)
                ->get();
            $invoice_unpaid = DB::table('invoice')
                ->whereNotIn('status', [4, 5])
                ->get();

            $customers = DB::table('vendor_customer')
                ->where('role', 1)
                ->get();
            return view('invoice_dashboard_filter', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers'));
        }

    }

    function uploadCSVFile()
    {
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

            $valid_formats = array("csv");

            $name = $_FILES['csv_upload']['name'];
            $size = $_FILES['csv_upload']['size'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats)) {
                    if ($size < 2098888) {
                        $tmp = $_FILES['csv_upload']['tmp_name'];
                        $data = $this->csvToArray($tmp, ',');
                        $db = false;
                        $task = 0;
                        for ($i = 0; $i < count($data); $i++) {

                            $flag = array();
                            $z = 0;
                            foreach ($data[$i] as $item) {
                                $flag[$z] = $item;
                                $z++;
                            }

                            $db = DB::table('vendor_customer')->insert(
                                ['name' => $flag[0], 'email' => $flag[1], 'phone' => $flag[2], 'first_name' => $flag[3], 'last_name' => $flag[4], 'currency' => $flag[5], 'role' => $flag[6]]
                            );
                            $task = $flag[6];
                        }
                        if ($db) {
                            if ($task == 1) {
                                $this->showCustomer();
                            } else {
                                $this->showVendor();
                            }
                        } else {
                            echo 0;
                        }
                    } else {
                        echo 0;
                    }
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
            exit;

        }
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public static function DueAmountInvoices()
    {
        $data = DB::table('invoice_item')
            ->join('invoice', 'invoice.invoice_num', 'invoice_item.invoice_num')
            ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice_item.invoice_num')
            ->where('invoice.status', 1)
            ->orWhere('invoice.status', 2)
            ->distinct()
            ->get();
        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);
            if (!empty($item->tax)) {
                $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                if (strcmp($item->item_name, $str_) == 0) {
                    $array_taxes = explode(",", $item->tax);
                    for ($i = 0; $i < count($array_taxes); $i++) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum = $sum + (($percent / 100) * ($item->item_quantity * $item->item_price));
                    }
                }
            }

            $data_paid = DB::table('payment')->select('amount')->where('invoice_num', $item->invoice_num)->get();

            $sum_invoice = 0;
            foreach ($data_paid as $item) {
                $sum_invoice = $sum_invoice + $item->amount;
            }

            $sum = $sum - $sum_invoice;
        }

        return $sum;
    }

    public static function OverDueAmountInvoices()
    {
        $data = DB::table('invoice_item')
            ->join('invoice', 'invoice.invoice_num', 'invoice_item.invoice_num')
            ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice_item.invoice_num')
            ->where('invoice.payment_due', '<', date("Y-m-d", time()))
            ->where('invoice.status', 1)
            ->orWhere('invoice.status', 2)
            ->distinct()
            ->get();
        $sum = 0;
        $sum_tax = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);
            if (!empty($item->tax)) {
                if (strpos($item->tax, ",") > -1) {
                    $tax_arr = explode(",", $item->tax);
                    for ($z = 0; $z < count($tax_arr); $z++) {
                        $str_ = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                        if (strcmp($item->item_name, $str_) == 0) {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax_arr[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                        }
                    }
                } else {
                    $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                    if (strcmp($item->item_name, $str_) == 0) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                    }
                }
            }


            $data_paid = DB::table('payment')->select('amount')->where('invoice_num', $item->invoice_num)->get();

            $sum_invoice = 0;
            foreach ($data_paid as $item) {
                $sum_invoice = $sum_invoice + $item->amount;
            }

            $sum = $sum - $sum_invoice;
        }
        return ($sum + $sum_tax);
    }

    public static function AvarageDueDateInvoices($from, $to)
    {
        $data = DB::table('invoice')
            ->select('payment_due')
            ->whereBetween('payment_due', [$from, $to])
            ->where('status', 1)
            ->orWhere('status', 2)
            ->get();
        $sum = 0;
        $n = 0;
        foreach ($data as $item) {
            $now = time();
            $your_date = strtotime($item->payment_due);
            $datediff = $your_date - $now;
            $days = floor($datediff / (60 * 60 * 24));
            if ($days > 0) {
                $sum = $sum + $days;
                $n++;
            }
        }
        if ($n != 0)
            return ceil($sum / $n);
        else return 0;
    }

    public static function DueAmountCommingFromTo($from, $to)
    {
        $data = DB::table('invoice_item')
            ->join('invoice', 'invoice.invoice_num', 'invoice_item.invoice_num')
            ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice.payment_due', 'invoice.status', 'invoice_item.tax', 'invoice.invoice_num')
            ->whereBetween('invoice.payment_due', [$from, $to])
            ->where('invoice.status', 1)
            ->orWhere('invoice.status', 2)
            ->distinct()
            ->get();

        $sum = 0;
        $sum_tax = 0;
        foreach ($data as $item) {
            $sum = $sum + ($item->item_quantity * $item->item_price);
            if (!empty($item->tax)) {
                if (strpos($item->tax, ",") > -1) {
                    $tax_arr = explode(",", $item->tax);
                    for ($z = 0; $z < count($tax_arr); $z++) {
                        $str_ = substr($tax_arr[$z], stripos($tax_arr[$z], "_") + 1);
                        if (strcmp($item->item_name, $str_) == 0) {
                            $var1 = "(";
                            $var2 = ")";
                            $pool = $tax_arr[$z];
                            $temp1 = strpos($pool, $var1) + strlen($var1);
                            $result = substr($pool, $temp1, strlen($pool));
                            $dd = strpos($result, $var2);
                            if ($dd == 0) {
                                $dd = strlen($result);
                            }

                            $percent = substr($result, 0, $dd);
                            $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                        }
                    }
                } else {
                    $str_ = substr($item->tax, stripos($item->tax, "_") + 1);
                    if (strcmp($item->item_name, $str_) == 0) {
                        $var1 = "(";
                        $var2 = ")";
                        $pool = $item->tax;
                        $temp1 = strpos($pool, $var1) + strlen($var1);
                        $result = substr($pool, $temp1, strlen($pool));
                        $dd = strpos($result, $var2);
                        if ($dd == 0) {
                            $dd = strlen($result);
                        }

                        $percent = substr($result, 0, $dd);
                        $sum_tax = $sum_tax + (($percent / 100) * ($item->item_quantity * $item->item_price));

                    }
                }
            }


            $data_paid = DB::table('payment')->select('amount')->where('invoice_num', $item->invoice_num)->get();

            $sum_invoice = 0;
            foreach ($data_paid as $item) {
                $sum_invoice = $sum_invoice + $item->amount;
            }

            $sum = $sum - $sum_invoice;
        }

        return ($sum + $sum_tax);
    }

    public function sendmail()
    {
        Mail::send('emails.welcome', ['key' => 'value'], function ($message) {
            $message->to('foo@example.com', 'John Smith')->subject('Welcome!');
        });
        dd('Mail send successfully');
    }

    public function addAccount(Request $request)
    {
        $type = $request->get('type');
        $name = $request->get('name');
        $currency = $request->get('currency');
        $desc = $request->get('desc');
        $id = $request->get('id');
        $account = $request->get('account');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            echo 0;

        } else {
            $db = DB::table('account')->insert(
                ['account_name' => $name, 'description' => $desc, 'account_type' => $type, 'currency' => $currency, 'account_id' => $id, 'account_chart' => $account]
            );

            if ($db) {
                echo 1;
            } else echo 2;
        }
    }

    public function editAccount(Request $request)
    {
        $type = $request->get('type');
        $name = $request->get('name');
        $currency = $request->get('currency');
        $desc = $request->get('desc');
        $id = $request->get('id');
        $account = $request->get('account');
        $id_ = $request->get('id_');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'id_' => 'required'
        ]);

        if ($validator->fails()) {
            echo 0;
        } else {
            $db = DB::table('account')
                ->where('id', '=', $id_)
                ->update(['account_name' => $name, 'description' => $desc, 'account_type' => $type, 'currency' => $currency, 'account_id' => $id, 'account_chart' => $account]);

            if ($db) {
                echo 1;
            } else echo 2;

        }
    }

    public function deleteAccount(Request $request)
    {
        $id = $request->get('id');
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        $check = 0;

        if ($validator->fails()) {
            $check = 0;
        } else {
            $db = DB::table('account')
                ->where('id', '=', $id)
                ->delete();
            if ($db) {
                $check = 1;
            }
        }
        echo $check;
    }

    public function addTransaction(Request $request)
    {

        $type = $request->get('type');
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            echo 0;
        } else {

            $date = date("Y-m-d", time());
            $account_cash = "Cash on Hand";
            $transaction_type_cash = 0;
            $operation_cash = "add";
            $operation_exp_inc = "add";
            $account_sales_purchases = "";
            $transaction_type = 0;

            if (strpos($type, "3") !== false || strpos($type, "4") !== false) {

                switch ($type) {
                    case 3:
                        $operation_cash = "add";
                        $operation_exp_inc = "add";
                        $account_sales_purchases = "Uncategorized Income";
                        $transaction_type = 3;
                        $type = 3;
                        break;
                    case 4:
                        $operation_cash = "less";
                        $operation_exp_inc = "add";
                        $account_sales_purchases = "Uncategorized Expense";
                        $transaction_type = 4;
                        $type = 4;
                        break;
                }

                $db = DB::table('transactions')->insert(
                    ['date' => $date, 'operation' => $operation_cash, 'amount' => 0, 'account' => $account_cash, 'notes' => "", 'transaction_type' => $transaction_type_cash, 'description' => "", 'category' => $account_sales_purchases, 'status' => 0]);

                $db1 = DB::table('transactions')->insert(
                    ['date' => $date, 'operation' => $operation_exp_inc, 'amount' => 0, 'account' => $account_sales_purchases, 'notes' => "", 'transaction_type' => $transaction_type, 'description' => "", 'category' => $account_cash, 'status' => 0]);


                if ($db && $db1) {
                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->orWhere('transactions.category', '=', 'journal statement')
                        ->where('transactions.category', '!=', 'Accounts Receivable')
                        ->select(['transactions.*'])
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();


                    $cash_bank = DB::table('account')
                        ->where('account_type', 'Cash and Bank')
                        ->where('account_chart', 0)
                        ->get();
                    $assets = DB::table('account')
                        ->where('account_chart', '=', 0)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $liabilities = DB::table('account')
                        ->where('account_chart', '=', 1)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $equities = DB::table('account')
                        ->where('account_chart', '=', 2)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $incomes = DB::table('account')
                        ->where('account_chart', '=', 3)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $expenses = DB::table('account')
                        ->where('account_chart', '=', 4)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    return view('transaction_list', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type'))->render();
                } else echo 2;
            } elseif (strpos($type, "5") !== false) {
                $category = "journal statement";
                $operation_income = "add";
                $account_income = "Uncategorized Income";
                $transaction_type = 3;

                $id = $this->createID(8);

                $db_query = DB::table('transactions')
                    ->where('invoice_num', $id)
                    ->get();
                $num_rows = count($db_query);

                while ($num_rows > 0) {
                    $id = $this->createID(8);
                    $db_query = DB::table('transactions')
                        ->where('invoice_num', $id)
                        ->get();
                    $num_rows = count($db_query);
                }

                $db = DB::table('transactions')->insert(
                    ['date' => $date, 'operation' => $operation_income, 'amount' => 0, 'account' => $account_income, 'notes' => "", 'transaction_type' => $transaction_type, 'description' => "", 'category' => $category, 'status' => 0, 'invoice_num' => $id]);

                $db1 = DB::table('journal_details')
                    ->insert(['detail' => "", 'category' => $account_income, 'debit' => 0, 'credit' => 0, 'journal_id' => $id]);

                $operation_exp_inc = "less";
                $account_expenses = "Uncategorized Expense";
                $transaction_type = 4;

                $db2 = DB::table('transactions')->insert(
                    ['date' => $date, 'operation' => $operation_exp_inc, 'amount' => 0, 'account' => $account_expenses, 'notes' => "", 'transaction_type' => $transaction_type, 'description' => "", 'category' => $category, 'status' => 0, 'invoice_num' => $id]);


                $db3 = DB::table('journal_details')
                    ->insert(['detail' => "", 'category' => $account_expenses, 'debit' => 0, 'credit' => 0, 'journal_id' => $id]);

                if ($db && $db1 && $db2 & $db3) {
                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->orWhere('transactions.category', '=', 'journal statement')
                        ->where('transactions.category', '!=', 'Accounts Receivable')
                        ->select(['transactions.*'])
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();


                    $cash_bank = DB::table('account')
                        ->where('account_type', 'Cash and Bank')
                        ->where('account_chart', 0)
                        ->get();
                    $assets = DB::table('account')
                        ->where('account_chart', '=', 0)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $liabilities = DB::table('account')
                        ->where('account_chart', '=', 1)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $equities = DB::table('account')
                        ->where('account_chart', '=', 2)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $incomes = DB::table('account')
                        ->where('account_chart', '=', 3)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $expenses = DB::table('account')
                        ->where('account_chart', '=', 4)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    return view('transaction_list', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type'))->render();
                } else echo 2;

            }

        }
    }

    // function to generate random ID number
    function createID($length)
    {
        $numbers = range(0, 9);
        shuffle($numbers);
        $digits = "";
        for ($i = 0; $i < $length; $i++)
            $digits .= $numbers[$i];
        return $digits;
    }

    public function updateTransaction(Request $request)
    {
        $desc = $request->get('desc');
        $account = $request->get('account');
        $date = $request->get('date');
        $dw = $request->get('dw');
        $total = $request->get('total');
        $category = $request->get('category');
        $notes = $request->get('notes');
        $id = $request->get('id');
        $type = $request->get('type');


        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            echo 0;
        } else {

            $operation_1 = "";
            $operation_exp_inc = "add";
            $account_cash = 0;
            $check = 0;

            $db_ = false;

            $account_chart_exception = DB::table('account')
                ->where('account.account_name', '=', $category)
                ->value('account.account_type');

            if (!isset($type)) {
                $type = DB::table('account')
                    ->where('account.account_name', '=', $category)
                    ->value('account.account_chart');
            }
            /* $response = array();
            $response['type'] = $type;
            echo json_encode($response);*/

            if (strpos($type, "0") !== false || strpos($type, "1") !== false || strpos($type, "2") !== false || strpos($type, "3") !== false || strpos($type, "4") !== false) {
                $operation_1 = "";
                $operation_2 = "";
                $account_cash = "Cash on Hand";

                if (strpos($dw, "withdrawal") !== false) {

                    switch ($type) {
                        case 0:
                            $operation_1 = "less";
                            $operation_2 = "add";
                            break;
                        case 1:
                            $operation_1 = "less";
                            $operation_2 = "less";
                            break;
                        case 2:
                            $operation_1 = "less";
                            $operation_2 = "less";
                            break;
                        case 4:
                            $operation_1 = "less";
                            $operation_2 = "less";
                            break;
                        default:
                            $operation_1 = "";
                            $operation_2 = "";
                            break;
                    }
                } elseif (strpos($dw, "Deposit") !== false) {

                    switch ($type) {
                        case 0:
                            $operation_1 = "add";
                            $operation_2 = "less";
                            break;
                        case 1:
                            $operation_1 = "add";
                            $operation_2 = "add";
                            break;
                        case 2:
                            $operation_1 = "add";
                            $operation_2 = "add";
                            break;
                        case 3:
                            $operation_1 = "add";
                            $operation_2 = "add";
                            break;
                        default:
                            $operation_1 = "";
                            $operation_2 = "";
                            break;
                    }
                }

                $db = DB::table('transactions')
                    ->where('id', $id)
                    ->update(['date' => $date, 'operation' => $operation_1, 'amount' => $total, 'account' => $account_cash, 'notes' => $notes, 'description' => $desc, 'category' => $category, 'status' => 0, 'transaction_type' => 0]);

                $str = (int)$id + 1;
                $db1 = DB::table('transactions')
                    ->where('id', "=", $str)
                    ->update(['date' => $date, 'operation' => $operation_2, 'amount' => $total, 'account' => $category, 'notes' => $notes, 'description' => $desc, 'category' => $account, 'status' => 0, 'transaction_type' => $type]);

                if (($db && $db1) || $db_) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                    $cash_bank = DB::table('account')
                        ->where('account_type', 'Cash and Bank')
                        ->where('account_chart', 0)
                        ->get();
                    $assets = DB::table('account')
                        ->where('account_chart', '=', 0)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $liabilities = DB::table('account')
                        ->where('account_chart', '=', 1)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $incomes = DB::table('account')
                        ->where('account_chart', '=', 2)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $expenses = DB::table('account')
                        ->where('account_chart', '=', 3)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $equities = DB::table('account')
                        ->where('account_chart', '=', 4)
                        ->orderBy('id', 'DESC')
                        ->orderBy('account_type', 'ASC')
                        ->get();

                    $check = view('transaction_list', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type', 'id'))->render();
                } else $check = 2;

                echo $check;

            } elseif (strpos($type, "5") !== false) {
                $account = "journal statement";

                $inv = DB::table('transactions')
                    ->where('id', $id)
                    ->value('invoice_num');

                $j_desc_ = $request->get('j_desc');
                $j_category = $request->get('j_category');
                $debit = $request->get('debit');
                $credit = $request->get('credit');

                $array_desc = explode(",", $j_desc_);
                $array_category = explode(",", $j_category);
                $array_debit = explode(",", $debit);
                $array_credit = explode(",", $credit);

                for ($i = 0; $i < count($array_category); $i++) {

                    $da = DB::table('journal_details')
                        ->where([
                            ['journal_id', '=', $inv],
                            ['category', '=', $array_category[$i]]
                        ])
                        ->get();
                    $da_ = DB::table('journal_details')
                        ->where([
                            ['journal_id', '=', $inv],
                        ])
                        ->get();
                    foreach ($da_ as $item) {
                        if (!in_array($item->category, $array_category)) {
                            DB::table('journal_details')
                                ->where('category', $item->category)
                                ->delete();
                        }
                    }

                    if (count($da) < 1) {

                        $type = DB::table('account')
                            ->where('account_name', '=', $array_category[$i])
                            ->value('account_chart');
                        $operation_1 = "";
                        $operation_2 = "";

                        switch ($type) {
                            case 0:
                                $operation_1 = "less";
                                $operation_2 = "add";
                                break;
                            case 1:
                                $operation_1 = "less";
                                $operation_2 = "less";
                                break;
                            case 2:
                                $operation_1 = "less";
                                $operation_2 = "less";
                                break;
                            case 3:
                                $operation_1 = "less";
                                $operation_2 = "add";
                                break;
                            case 4:
                                $operation_1 = "less";
                                $operation_2 = "add";
                                break;
                            default:

                                break;
                        }
                        $db = DB::table('transactions')
                            ->where('id', $id)
                            ->update(['date' => $date, 'operation' => $operation_1, 'amount' => $total, 'account' => $array_category[$i], 'notes' => $notes, 'description' => $desc, 'status' => 0, 'transaction_type' => 0]);

                        if ($db) {
                            $db_ = DB::table('journal_details')
                                ->insert(['detail' => $array_desc[$i], 'category' => $array_category[$i], 'debit' => $array_debit[$i], 'credit' => $array_credit[$i], 'journal_id' => $inv]);
                            if ($db_) {
                                $transactions = DB::table('transactions')
                                    ->join('account', 'transactions.account', 'account.account_name')
                                    ->select('transactions.*')
                                    ->where('account.account_type', '=', 'Cash and Bank')
                                    ->orderBy('transactions.id', 'DESC')
                                    ->orderBy('transactions.date', 'DESC')
                                    ->get();

                                $cash_bank = DB::table('account')
                                    ->where('account_type', 'Cash and Bank')
                                    ->where('account_chart', 0)
                                    ->get();
                                $assets = DB::table('account')
                                    ->where('account_chart', '=', 0)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $liabilities = DB::table('account')
                                    ->where('account_chart', '=', 1)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $incomes = DB::table('account')
                                    ->where('account_chart', '=', 2)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $expenses = DB::table('account')
                                    ->where('account_chart', '=', 3)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $equities = DB::table('account')
                                    ->where('account_chart', '=', 4)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $check = view('transaction_list', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type', 'id'))->render();
                            }
                        } else echo 2;
                    } else {
                        $type = DB::table('account')
                            ->where('account_name', '=', $array_category[$i])
                            ->value('account_chart');
                        $operation_1 = "";
                        $operation_2 = "";

                        switch ($type) {
                            case 0:
                                $operation_1 = "less";
                                $operation_2 = "add";
                                break;
                            case 1:
                                $operation_1 = "less";
                                $operation_2 = "less";
                                break;
                            case 2:
                                $operation_1 = "less";
                                $operation_2 = "less";
                                break;
                            case 3:
                                $operation_1 = "less";
                                $operation_2 = "add";
                                break;
                            case 4:
                                $operation_1 = "less";
                                $operation_2 = "add";
                                break;
                            default:

                                break;
                        }

                        $db = DB::table('transactions')
                            ->where('id', $id)
                            ->update(['date' => $date, 'operation' => $operation_1, 'amount' => $total, 'account' => $array_category[$i], 'notes' => $notes, 'description' => $desc, 'status' => 0, 'transaction_type' => 0]);

                        if ($db) {
                            $db_ = DB::table('journal_details')
                                ->where([
                                    ['journal_id', '=', $inv],
                                    ['category', '=', $array_category[$i]]
                                ])
                                ->update(['detail' => $array_desc[$i], 'category' => $array_category[$i], 'debit' => $array_debit[$i], 'credit' => $array_credit[$i]]);
                            if ($db_) {
                                $transactions = DB::table('transactions')
                                    ->join('account', 'transactions.account', 'account.account_name')
                                    ->select('transactions.*')
                                    ->where('account.account_type', '=', 'Cash and Bank')
                                    ->orderBy('transactions.id', 'DESC')
                                    ->orderBy('transactions.date', 'DESC')
                                    ->get();

                                $cash_bank = DB::table('account')
                                    ->where('account_type', 'Cash and Bank')
                                    ->where('account_chart', 0)
                                    ->get();
                                $assets = DB::table('account')
                                    ->where('account_chart', '=', 0)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $liabilities = DB::table('account')
                                    ->where('account_chart', '=', 1)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $incomes = DB::table('account')
                                    ->where('account_chart', '=', 2)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $expenses = DB::table('account')
                                    ->where('account_chart', '=', 3)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $equities = DB::table('account')
                                    ->where('account_chart', '=', 4)
                                    ->orderBy('id', 'DESC')
                                    ->orderBy('account_type', 'ASC')
                                    ->get();

                                $check = view('transaction_list', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type', 'id'))->render();
                            }
                        } else $check = 2;
                    }
                    $id = (int)$id - 1;
                }

                echo $check;

            }

            /*$db = DB::table('transactions')
                ->where('id',$id)
                ->update(['date' => $date, 'operation' => $operation_cash, 'amount' => $total, 'account' => $account, 'notes' => $notes,'description'=> $desc,'category'=> $category,'status'=> 0,'transaction_type'=> $account_cash]);

            $str = (int)$id + 1;
            $db1 = DB::table('transactions')
                ->where('id',"=",$str)
                ->update(['date' => $date, 'operation' => $operation_exp_inc, 'amount' => $total, 'account' => $category, 'notes' => $notes,'description'=> $desc,'category'=> $account,'status'=> 0,'transaction_type'=> $account_chart]);

             if ($dw == 2 && $db){
               $j_desc_ = $request->get('j_desc');
               $j_category = $request->get('j_category');
               $debit = $request->get('debit');
               $credit = $request->get('credit');

               $array_desc = explode(",", $j_desc_);
               $array_category = explode(",", $j_category);
               $array_debit = explode(",", $debit);
               $array_credit = explode(",", $credit);

               for ($i=0;$i< count($array_category);$i++){

                   $da = DB::table('journal_details')
                       ->where([
                           ['journal_id', '=', $id],
                           ['category', '=', $array_category[$i]]
                       ])
                       ->get();
                   $da_ = DB::table('journal_details')
                       ->where([
                           ['journal_id', '=', $id],
                       ])
                       ->get();
                   foreach ($da_ as $item) {
                       if (!in_array($item->category, $array_category)) {
                           DB::table('journal_details')
                               ->where('category', $item->category)
                               ->delete();
                       }
                   }

                   if (count($da) < 1) {

                       $db_ = DB::table('journal_details')
                           ->insert(['detail' => $array_desc[$i], 'category' => $array_category[$i], 'debit' => $array_debit[$i], 'credit' => $array_credit[$i],'journal_id' => $id]);
                       if(!db_){
                           DB::table('journal_details')
                               ->where('journal_id',$id)
                               ->delete();

                           echo 0;
                       }

                   } else {
                       $db_ = DB::table('journal_details')
                           ->where([
                               ['journal_id', '=', $id],
                               ['category', '=', $array_category[$i]]
                           ])
                           ->update(['detail' => $array_desc[$i], 'category' => $array_category[$i], 'debit' => $array_debit[$i], 'credit' => $array_credit[$i],'journal_id' => $id]);
                   }

               }
           }
             if(($db && $db1) || $db_){

                 $transactions = DB::table('transactions')
                     ->join('account','transactions.account','account.account_name')
                     ->select('transactions.*')
                     ->where('account.account_type','=','Cash and Bank')
                     ->orderBy('transactions.id','DESC')
                     ->orderBy('transactions.date','DESC')
                     ->get();

                 $cash_bank = DB::table('account')
                     ->where('account_type','Cash and Bank')
                     ->where('account_chart',0)
                     ->get();
                 $assets = DB::table('account')
                     ->where('account_chart', '=', 0)
                     ->orderBy('id','DESC')
                     ->orderBy('account_type','ASC')
                     ->get();

                 $liabilities = DB::table('account')
                     ->where('account_chart', '=', 1)
                     ->orderBy('id','DESC')
                     ->orderBy('account_type','ASC')
                     ->get();

                 $incomes = DB::table('account')
                     ->where('account_chart', '=', 2)
                     ->orderBy('id','DESC')
                     ->orderBy('account_type','ASC')
                     ->get();

                 $expenses = DB::table('account')
                     ->where('account_chart', '=', 3)
                     ->orderBy('id','DESC')
                     ->orderBy('account_type','ASC')
                     ->get();

                 $equities = DB::table('account')
                     ->where('account_chart', '=', 4)
                     ->orderBy('id','DESC')
                     ->orderBy('account_type','ASC')
                     ->get();

                  echo view('transaction_list',compact('transactions','cash_bank','incomes','expenses','equities','assets','liabilities','type','id'))->render();
             }
             else echo 2;*/

        }
    }


    public function updateMarkTransaction(Request $request)
    {
        $id = $request->get('id');
        $status_ = $request->get('status');
        $selector = $request->get('selector');

        $type = $request->get('type');
        $status = $request->get('reviewed');
        $from = $request->get('from');
        $to = $request->get('to');

        $db = DB::table('transactions')
            ->where('id', $id)
            ->update(['status' => (1 - $status_)]);

        if ($db) {

            if (empty($type) && empty($status)) {
                $type = $type - 1;
                $status = $status - 1;
                if (empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } else if (!empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } else if (empty($from) && !empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } else {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                }
            } else if (!empty($type) && empty($status)) {
                $type = $type - 1;
                $status = $status - 1;
                if (empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('account', $selector)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (!empty($from) && empty($to)) {

                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('transaction_type', $type)
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (empty($from) && !empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('transaction_type', $type)
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } else {

                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('transaction_type', $type)
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                }
            } elseif (empty($type) && !empty($status)) {
                $type = $type - 1;
                $status = $status - 1;
                if (empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (!empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (empty($from) && !empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } else {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                }
            } elseif (!empty($type) && !empty($status)) {
                $type = $type - 1;
                $status = $status - 1;
                if (empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (!empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (empty($from) && !empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } else {

                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('account', $selector)
                            ->where('status', $status)
                            ->where('transaction_type', $type)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                }
            } else {
                $type = $type - 1;
                $status = $status - 1;
                if (empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (!empty($from) && empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '>=', $from)
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } elseif (empty($from) && !empty($to)) {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->where('date', '<=', $to)
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                } else {
                    if (strpos($selector, 'All Account') !== false) {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    } else {
                        $transactions = DB::table('transactions')
                            ->where('category', '!=', 'Accounts Receivable')
                            ->whereBetween('date', [$from, $to])
                            ->where('account', $selector)
                            ->orderBy('id', 'DESC')
                            ->orderBy('date', 'DESC')
                            ->get();
                    }
                }
            }

            $cash_bank = DB::table('account')
                ->where('account_type', 'Cash and Bank')
                ->where('account_chart', 0)
                ->get();
            $assets = DB::table('account')
                ->where('account_chart', '=', 0)
                ->orderBy('id', 'DESC')
                ->orderBy('account_type', 'ASC')
                ->get();

            $liabilities = DB::table('account')
                ->where('account_chart', '=', 1)
                ->orderBy('id', 'DESC')
                ->orderBy('account_type', 'ASC')
                ->get();

            $incomes = DB::table('account')
                ->where('account_chart', '=', 2)
                ->orderBy('id', 'DESC')
                ->orderBy('account_type', 'ASC')
                ->get();

            $expenses = DB::table('account')
                ->where('account_chart', '=', 3)
                ->orderBy('id', 'DESC')
                ->orderBy('account_type', 'ASC')
                ->get();

            $equities = DB::table('account')
                ->where('account_chart', '=', 4)
                ->orderBy('id', 'DESC')
                ->orderBy('account_type', 'ASC')
                ->get();

            $type = 0;

            echo view('transaction_list', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type'))->render();
        } else echo 2;
    }

    public static function TransactionAmount($tr)
    {
        if (strpos($tr, 'All Account') !== false) {
            $data = DB::table('transactions')
                ->select('amount')
                ->get();
        } else {
            $data = DB::table('transactions')
                ->select('amount')
                ->where('account', $tr)
                ->get();
        }

        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + $item->amount;
        }
        return $sum;
    }

    public function selectTransaction(Request $request)
    {
        $selector = $request->get('selector');
        $type = $request->get('type');
        $status = $request->get('reviewed');
        $from = $request->get('from');
        $to = $request->get('to');


        if (empty($type) && empty($status)) {
            $type = $type - 1;
            $status = $status - 1;
            if (empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {
                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {
                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } else if (!empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } else if (empty($from) && !empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                }
            } else {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                }
            }
        } else if (!empty($type) && empty($status)) {
            $type = $type - 1;
            $status = $status - 1;
            if (empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.account', $selector)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } elseif (!empty($from) && empty($to)) {

                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.transaction_type', $type)
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                }
            } elseif (empty($from) && !empty($to)) {
                if (strpos($selector, 'All Account') !== false) {
                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                } else {


                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.transaction_type', $type)
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();

                }
            } else {

                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.transaction_type', $type)
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            }
        } elseif (empty($type) && !empty($status)) {
            $type = $type - 1;
            $status = $status - 1;
            if (empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {
                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } elseif (!empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } elseif (empty($from) && !empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } else {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            }
        } elseif (!empty($type) && !empty($status)) {
            $type = $type - 1;
            $status = $status - 1;
            if (empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } elseif (!empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } elseif (empty($from) && !empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } else {

                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.account', $selector)
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            }
        } else {
            $type = $type - 1;
            $status = $status - 1;
            if (empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.status', $status)
                        ->where('transactions.transaction_type', $type)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } elseif (!empty($from) && empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '>=', $from)
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } elseif (empty($from) && !empty($to)) {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->where('transactions.date', '<=', $to)
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            } else {
                if (strpos($selector, 'All Account') !== false) {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                } else {

                    $transactions = DB::table('transactions')
                        ->join('account', 'transactions.account', 'account.account_name')
                        ->select('transactions.*')
                        ->where('account.account_type', '=', 'Cash and Bank')
                        ->whereBetween('transactions.date', [$from, $to])
                        ->where('transactions.account', $selector)
                        ->orderBy('transactions.id', 'DESC')
                        ->orderBy('transactions.date', 'DESC')
                        ->get();
                }
            }
        }


        $cash_bank = DB::table('account')
            ->where('account_type', 'Cash and Bank')
            ->where('account_chart', 0)
            ->get();
        $assets = DB::table('account')
            ->where('account_chart', '=', 0)
            ->orderBy('id', 'DESC')
            ->orderBy('account_type', 'ASC')
            ->get();

        $liabilities = DB::table('account')
            ->where('account_chart', '=', 1)
            ->orderBy('id', 'DESC')
            ->orderBy('account_type', 'ASC')
            ->get();

        $incomes = DB::table('account')
            ->where('account_chart', '=', 2)
            ->orderBy('id', 'DESC')
            ->orderBy('account_type', 'ASC')
            ->get();

        $expenses = DB::table('account')
            ->where('account_chart', '=', 3)
            ->orderBy('id', 'DESC')
            ->orderBy('account_type', 'ASC')
            ->get();

        $equities = DB::table('account')
            ->where('account_chart', '=', 4)
            ->orderBy('id', 'DESC')
            ->orderBy('account_type', 'ASC')
            ->get();

        $type = 0;

        echo view('transaction_list', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type'))->render();
    }

    public static function BalanceAmount($from_, $to_, $account)
    {
        $from = $from_;
        $to = $to_;
        if ($from_ > $to_) {
            $from = $to_;
            $to = $from_;
        }
        $data = DB::table('transactions')
            ->where('account', $account)
            ->select('amount', 'operation')
            ->whereBetween('date', [$from, $to])
            ->get();

        $sum = 0;
        foreach ($data as $item) {
            if (strpos($item->operation, "payment_in") !== false || strpos($item->operation, "Deposit") !== false) {
                $sum = $sum + $item->amount;
            } else {
                $sum = $sum - $item->amount;
            }
        }

        return $sum;
    }

    public function addreconcile(Request $request)
    {
        $amount = $request->get('amount');
        $date = $request->get('date');
        $account = $request->get('account');

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'date' => 'required',
            'account' => 'required',
        ]);

        if ($validator->fails()) {
            echo 2;
        } else {
            $db = DB::table('reconcile')
                ->insert(['ending_balance_date' => $date, 'ending_balance_amount' => $amount, 'account' => $account, 'status' => 0]);

            if ($db) echo 1;
            else echo 0;
        }
    }

    public function updatereconcile(Request $request)
    {

    }

    public function deleteTransaction(Request $request)
    {
        $id = $request->get('id');

        $db = DB::table('transactions')
            ->where('id', $id)
            ->delete();

        DB::table('journal_details')
            ->where('journal_id', $id)
            ->delete();

        if ($db) echo 1;
        else echo 0;
    }

    public static function getAccountChart($name)
    {

        $val = DB::table('account')
            ->where('account_name', $name)
            ->value('account_chart');

        $acc = "";
        switch ($val) {
            case 0:
                $acc = "Asset";
                break;
            case 1:
                $acc = "Liability";
                break;
            case 2:
                $acc = "Income";
                break;
            case 3:
                break;
                $acc = "Expenses";
            case 4:
                $acc = "Equity";
                break;
            default:
                break;
        }

        return $acc;
    }

    public function reconcileTransactionSearch(Request $request)
    {
        $account = $request->get('account');
        $from = $request->get('from');
        $to = $request->get('to');
        $report = $request->get('report');
        $contact = $request->get('contact');

        $transactions = array();
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
            'report' => 'required'
        ]);

        if ($validator->fails()) {
            echo 0;
        } else {

            if (strcmp("all contacts", $contact) == 0 && strcmp("all account", $account) == 0) {
                if (strcmp("Cash Only", $report) == 0) {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->where('account', 'Cash on Hand')
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                } else {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                }

            } elseif (strcmp("all account", $account) != 0 && strcmp("all contacts", $contact) == 0) {
                if (strcmp("Cash Only", $report) == 0) {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->where('account', $account)
                        ->where('account', 'Cash on Hand')
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                } else {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->where('account', $account)
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                }

            } elseif (strcmp("all account", $account) == 0 && strcmp("all contacts", $contact) != 0) {


                if (strcmp("Cash Only", $report) == 0) {
                    $transactions = DB::table('transactions')
                        ->where('account', 'Cash on Hand')
                        ->whereBetween('date', [$from, $to])
                        ->where('payment_id', $contact)
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                } else {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->where('payment_id', $contact)
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                }

            } elseif (strcmp("all account", $account) != 0 && strcmp("all contacts", $contact) != 0) {

                if (strcmp("Cash Only", $report) == 0) {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->where('account', $account)
                        ->where('account', 'Cash on Hand')
                        ->where('payment_id', $contact)
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                } else {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->where('account', $account)
                        ->where('payment_id', $contact)
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                }

            } else {
                if (strcmp("Cash Only", $report) == 0) {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->where('account', 'Cash on Hand')
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                } else {
                    $transactions = DB::table('transactions')
                        ->whereBetween('date', [$from, $to])
                        ->orderBy('date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();
                }

            }


        }
        if (count($transactions) < 1) {
            echo 0;
        } else {
            $items = DB::table('invoice')
                ->join('invoice_item', 'invoice.invoice_num', 'invoice_item.invoice_num')
                ->join('products_services', 'products_services.name', 'invoice_item.item_name')
                ->select(['products_services.income_account'])
                ->whereNotNull('products_services.income_account')
                ->get();

            $date = DB::table('transactions')
                ->orderBy('id', 'ASC')
                ->value('date');

            echo view('account_reconciliation_table', compact('transactions', 'account', 'from', 'to', 'date', 'items'))->render();
        }

    }

    static function getAccountChartIndex($account)
    {

        $account_chart = DB::table('account')
            ->select('account_chart')
            ->where('account_name', $account)
            ->value('account_chart');

        return $account_chart;
    }

    static function getStartingBalance($from, $to, $account)
    {

        $data = DB::table('transactions')
            ->where('category', $account)
            ->orWhere('account', $account)
            ->select('amount', 'operation')
            ->whereBetween('date', [$from, $to])
            ->get();

        $sum = 0;
        foreach ($data as $item) {
            if (strpos($item->operation, "payment_in") !== false || strpos($item->operation, "Deposit") !== false) {
                $sum = $sum + $item->amount;
            } else {
                $sum = $sum - $item->amount;
            }
        }

        return $sum;
    }

    static function getStartingAccrualBalance($from, $to, $account, $side)
    {

        $data = DB::table('invoice')
            ->join('invoice_item', 'invoice.invoice_num', 'invoice_item.invoice_num')
            ->join('products_services', 'products_services.name', 'invoice_item.item_name')
            ->select(['invoice_item.item_quantity as quantity', 'invoice_item.item_price as price'])
            ->whereBetween('invoice.invoice_date', [$from, $to])
            ->whereNotNull('products_services.income_account')
            ->where('products_services.income_account', $account)
            ->get();

        $sum = 0;
        foreach ($data as $item) {
            if (strpos($side, "db") !== false) {
                $sum = $sum + $item->amount;
            } else {
                $sum = $sum - $item->amount;
            }
        }

        return $sum;
    }


    static function getBegginingBalance($from, $to)
    {

        $data = DB::table('payment')
            ->select('amount')
            ->whereBetween('date', [$from, $to])
            ->get();

        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + $item->amount;
        }

        return $sum;
    }

    static function getDueRange($from, $to)
    {
        $data = DB::table('invoice_item')
            ->join('invoice', 'invoice_item.invoice_num', '=', 'invoice.invoice_num')
            ->select('invoice_item.item_quantity', 'invoice_item.item_price', 'invoice_item.tax', 'invoice_item.item_name', 'invoice.invoice_date', 'invoice.invoice_num')
            ->where('invoice.status', '=', 1)
            ->orWhere('invoice.status', '=', 2)
            ->whereBetween('invoice_date', [$to, $from])
            ->orderBy('invoice_date', 'DESC')
            ->get();

    }

    public function deletepayment($pid, $id)
    {

        $db = DB::table('payment')->where('id', $pid)->delete();

        if ($db) {
            $payments = DB::table('payment')
                ->where('invoice_num', $id)
                ->get();

            if (count($payments) > 0) {
                echo '
        <table class="table table-striped borderless" style="border: solid 1px #CCCCCC;font-size: 14px;">
            <thead>
                    <tr>
                        <th scope="col">Payment Date</th>
                        <th scope="col">Payment method</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="dataTable2">';
                foreach ($payments as $payment) {
                    echo '<td>' . $payment->date . '</td>';
                    echo '<td>' . $payment->payment_method . '</td>';
                    echo '<td>' . $payment->amount . '</td>';
                    echo '<td>
                                    <a href="#" onclick=deleteDataPayment("' . $payment->id . '","' . $id . '")>
                            <i class="fa fa-fw fa-trash"></i>
                        </a>
                                </td>';
                }
                echo '</tbody>
                    </table>';
            }
        } else {
            echo 0;
        }
    }

    static function PaymentAmountFromTo($from, $to, $customer_id)
    {
        $data_paid = DB::table('payment')
            ->join('invoice', 'invoice.invoice_num', 'payment.invoice_num')
            ->select('payment.amount', 'payment.date', 'payment.invoice_num')
            ->whereBetween('payment.date', [$from, $to])
            ->where('invoice.customer_id', $customer_id)
            ->get();

        $invoice_all = DB::table('invoice')
            ->where('customer_id', '=', $customer_id)
            ->whereBetween('invoice_date', [$from, $to])
            ->orderBy('invoice_date', 'DESC')
            ->get();

        $sum = 0;
        foreach ($invoice_all as $invoice) {
            foreach ($data_paid as $item) {
                if ($item->date >= $invoice->invoice_date && strcmp($item->invoice_num, $invoice->invoice_num) == 0) {
                    $sum = $sum + $item->amount;
                }
            }
        }

        return $sum;
    }

    public static function getIncomeInvoices($from, $to)
    {
        $invoice_income = DB::table('invoice')
            ->join('invoice_item', 'invoice.invoice_num', 'invoice_item.invoice_num')
            ->join('products_services', 'products_services.name', 'invoice_item.item_name')
            ->select(['invoice_item.item_price', 'invoice_item.item_quantity', 'invoice.invoice_date', 'invoice_item.item_name', 'products_services.income_account', 'invoice.invoice_num'])
            ->whereBetween('invoice.invoice_date', [$from, $to])
            ->whereNotNull('products_services.income_account')
            ->get();

        return $invoice_income;
    }

    public static function getAmountTransactedBasedOnType($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->where('transactions.transaction_type', $type)
            ->whereBetween('transactions.date', [$from, $to])
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeCashAndEquivalent($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.category', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '=', 'Cash and Bank')
            ->select(['transactions.*', 'account.account_type'])
            ->get();

        return $transaction_money;

    }

    /*    public static function getAmountTransactedBasedOnTypeCashInflow($from,$to,$type){

        $transaction_money = DB::table('transactions')
            ->join('account','transactions.category','account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type',3)
            ->where('account.account_type','=','Cash and Bank')
            ->orWhere('transactions.transaction_type',0)
            ->whereBetween('transactions.date', [$from, $to])
            ->where('account.account_type','Expected Payment from customers')
            ->orWhere('account.account_type','Vendor Prepayment and Vendor Credits')
            ->orWhere('account.account_type','Other Short-Term Asset')
            ->select(['transactions.*','account.account_type'])
            ->get();

        return $transaction_money;

    }*/

    public static function getAmountTransactedBasedOnTypeCashInflow($from, $to, $type)
    {


        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where([['account.account_type', '=', 'Cash and Bank'], ['transactions.transaction_type', '=', $type]])
            ->orWhere('transactions.transaction_type', 0)
            ->where('transactions.category', 'LIKE', 'payment from%')
            ->where('transactions.account', '=', 'Cash on Hand')
            ->orWhere([['account.account_type', '=', 'Vendor Prepayment and Vendor Credits'], ['account.account_type', '=', 'Other Short-Term Asset']])
            ->select(['transactions.*', 'account.account_type'])
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypePaidOperatingExpenses($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypePurchases($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('account.account_type', '!=', 'Due For Payroll')
            ->where('account.account_type', '!=', 'Loan and Line of Credit')
            ->where('account.account_type', '!=', 'Due to You and Other Business Owners')
            ->where('transactions.transaction_type', 1)
            ->where('transactions.category', "Cash on Hand")
            ->orWhere('transactions.transaction_type', 4)
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.category', "Cash on Hand")
            ->orWhere('transactions.transaction_type', 0)
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.category', "Cash on Hand")
            ->where('account.account_type', '=', 'Vendor Prepayment and Vendor Credits')
            ->orderBy('transactions.id', 'ASC')
            ->get();

        return $transaction_money;

    }


    public static function getAmountTransactedBasedOnTypePPE($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.category', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Property, Plant and Equipment')
            ->orWhere('account.account_type', 'Depreciation and Amortization')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeLongAssetOther($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.category', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Other Long-Term Asset')
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeInvestingAssetOther($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.category', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Other Long-Term Asset')
            ->orWhere('account.account_type', 'Other short-Term Asset')
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeCashFlowOther($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.category', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Depreciation and Amortization')
            ->orWhere('account.account_type', 'Vendor Prepayment and Vendor Credits')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->orWhere('account.account_type', 'Other Short-Term Asset')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeLoansLinesofCredit($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Loan and Line of Credit')
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeEquity($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Retain Earning - Profit and Business Owner Drawing')
            ->orWhere('account.account_type', 'Business Owner Contribution')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeEquityCashFlow($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Retain Earning - Profit and Business Owner Drawing')
            ->orWhere('account.account_type', 'Business Owner Contribution')
            ->orWhere('account.account_type', 'Due to You and Other Business Owners')
            ->where('transactions.transaction_type', 1)
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeEquityOther($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '!=', 'Retain Earning - Profit and Business Owner Drawing')
            ->where('account.account_type', '!=', 'Business Owner Contribution')
            ->get();

        return $transaction_money;

    }


    public static function getAmountPaid($id)
    {

        $payments = DB::table('payment')
            ->where('invoice_num', $id)
            ->get();

        $sum = 0;
        foreach ($payments as $item) {
            $sum = $sum + $item->amount;
        }

        return $sum;

    }

    public static function getBillItemNo($id)
    {

        $bill = DB::table('bill_item')
            ->where('bill_no', $id)
            ->get();

        return count($bill);

    }

    public static function getSumMoney($data)
    {
        $sum = 0;
        foreach ($data as $item) {
            $sum = $sum + $item->amount;
        }

        return $sum;

    }

    public static function getAmountTransactedBasedOnTypeInventory($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.category', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '=', 'Inventory')
            ->select(['transactions.*', 'account.account_type'])
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypePayroll($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '=', 'Due For Payroll')
            ->select(['transactions.*', 'account.account_type'])
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeSalesTax($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '=', 'Sales Taxes')
            ->select(['transactions.*', 'account.account_type'])
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeOther($from, $to, $type)
    {

        $transaction_money = DB::table('transactions')
            ->join('account', 'transactions.account', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '=', 'Other')
            ->select(['transactions.*', 'account.account_type'])
            ->get();

        return $transaction_money;

    }

    public static function getAmountTransactedBasedOnTypeCashAndBank($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_cash_and_bank = DB::table('transactions')
            ->join('account', 'account.account_name', '=', 'transactions.account')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '=', 'Cash and Bank')
            ->get();

        return $transaction_cash_and_bank;

    }


    public static function getAmountTransactedBasedOnTypeSales($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_cash_and_bank = DB::table('transactions')
            ->join('account', 'account.account_name', '=', 'transactions.account')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', '=', 'Income')
            ->get();

        return $transaction_cash_and_bank;

    }

    public static function getAmountTransactedBasedOnTypeCashSales($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_cash_and_bank = DB::table('transactions')
            ->join('account', 'account.account_name', '=', 'transactions.account')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('transactions.category','=','Cash on Hand')
            ->orWhere('transactions.category','LIKE','payment from%')
            ->where('account.account_type','=','Expected Payment from customers')
            ->get();

        return $transaction_cash_and_bank;

    }

    public static function getSumCashAndBank($from, $to, $account, $type)
    {
        $data = DB::table('transactions')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('transactions.account', '=', $account)
            ->get();
        $sum_cash_and_bank = 0;
        foreach ($data as $item) {
            if (strpos("add", $item->operation) > -1) {
                $sum_cash_and_bank = $sum_cash_and_bank + $item->amount;;
            } else if (strpos("less", $item->operation) > -1) {
                $sum_cash_and_bank = $sum_cash_and_bank - $item->amount;
            }
        }
        return $sum_cash_and_bank;
    }

    public static function getSumArray($data, $account)
    {

        $sum_cash_and_bank = 0;
        foreach ($data as $item) {
            if (strpos(strtolower($account), strtolower($item->account)) !== false) {
                if (strpos("add", $item->operation) !== false) {
                    $sum_cash_and_bank = $sum_cash_and_bank + $item->amount;;
                } else if (strpos("less", $item->operation) !== false) {
                    $sum_cash_and_bank = $sum_cash_and_bank - $item->amount;
                }
            }
        }
        return $sum_cash_and_bank;

    }

    public static function getSumArrayNum($data, $account,$num)
    {

        $sum= 0;
        foreach ($data as $item) {
            if (strpos(strtolower($account), strtolower($item->account)) !== false && $num == $item->invoice_num) {
                if (strpos("add", $item->operation) !== false) {
                    $sum = $sum + $item->amount;;
                } else if (strpos("less", $item->operation) !== false) {
                    $sum = $sum - $item->amount;
                }
            }
        }
        return $sum;

    }

    public static function getSumArrayCategory($data, $category)
    {
        $sum_cash_and_bank = 0;
        foreach ($data as $item) {
            if (strpos($category, $item->category) > -1) {
                if (strpos("add", $item->operation) > -1) {
                    $sum_cash_and_bank = $sum_cash_and_bank + $item->amount;;
                } else if (strpos("less", $item->operation) > -1) {
                    $sum_cash_and_bank = $sum_cash_and_bank - $item->amount;
                }
            }
        }
        return $sum_cash_and_bank;
    }

    public static function getTotalAccountReceivable($invoice_num)
    {
        $data = DB::table('transactions')
            ->where('invoice_num','=',$invoice_num)
            ->where('category','=','Accounts Receivable')
            ->get();

        $sum = 0;
        foreach ($data as $item) {
            if (strpos("add", $item->operation) > -1) {
                $sum = $sum + $item->amount;
            } else if (strpos("less", $item->operation) > -1) {
                $sum = $sum - $item->amount;
            }
        }
        return $sum;
    }

    public static function getInOutFlow($data,$invoice_num)
    {
        $arr = array();
        $sum_account_income = 0;
        $cash_inflow = 0;
        $cash_outflow = 0;
        foreach ($data as $item) {
            if ($invoice_num == $item->invoice_num) {
                if (strpos($item->operation,"add") !== false){
                    $sum_account_income = $sum_account_income + $item->amount;
                    $cash_inflow = $cash_inflow + $item->amount;
                }
                else{
                    $sum_account_income = $sum_account_income - $item->amount;
                    $cash_outflow = $cash_outflow + $item->amount;
                }
            }
        }
        $arr['in'] = $cash_inflow;
        $arr['out'] = $cash_outflow;
        $arr['sum'] = $sum_account_income;
        return $arr;
    }

    public static function getSumArrayCategoryTax($data, $category,$account)
    {
        $sum_cash_and_bank = 0;
        foreach ($data as $item) {
            if (strpos($category, $item->category) > -1 && strpos(strtolower($account), strtolower($item->account)) !== false) {
                if (strpos("add", $item->operation) > -1) {
                    $sum_cash_and_bank = $sum_cash_and_bank + $item->amount;;
                } else if (strpos("less", $item->operation) > -1) {
                    $sum_cash_and_bank = $sum_cash_and_bank - $item->amount;
                }
            }
        }
        return $sum_cash_and_bank;
    }

    public static function getAmountTransactedBasedOnTypeCurrentAssets($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_current_assets = DB::table('transactions')
            ->join('account', 'transactions.account', '=', 'account.account_name')
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Inventory')
            ->orWhere('account.account_type', 'Expected Payment from customers')
            ->orWhere('account.account_type', 'Vendor Prepayment and Vendor Credits')
            ->orWhere('account.account_type', 'Other Short-Term Asset')
            ->select(['transactions.*'])
            ->get();

        return $transaction_current_assets;

    }

    public static function getAmountTransactedBasedOnTypeLongAssets($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_long_assets = DB::table('transactions')
            ->join('account', 'transactions.account', '=', 'account.account_name')
            ->select(['transactions.*'])
            ->whereBetween('transactions.date', [$from, $to])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Property, Plant and Equipment')
            ->orWhere('account.account_type', 'Depreciation and Amortization')
            ->orWhere('account.account_type', 'Other Long-Term Asset')
            ->get();

        return $transaction_long_assets;

    }

    public static function getAmountTransactedBasedOnTypeCurrentLiabilities($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_current_liabilities = DB::table('transactions')
            ->join('account', 'transactions.account', '=', 'account.account_name')
            ->select(['transactions.*'])
            ->where('transactions.transaction_type', $type)
            ->whereBetween('transactions.date', [$from, $to])
            ->where('account.account_type', 'Expected Payments to Vendors')
            ->orWhere('account.account_type', 'Sales Taxes')
            ->orWhere('account.account_type', 'Due For Payroll')
            ->orWhere('account.account_type', 'Due to You and Other Business Owners')
            ->orWhere('account.account_type', 'Customer Prepayments and Customer Credits')
            ->orWhere('account.account_type', 'Due to You and Other Business Owners')
            ->orWhere('account.account_type', 'Credit Card')
            ->orWhere('account.account_type', 'Other Short-Term Liability')
            ->get();

        return $transaction_current_liabilities;

    }

    public static function getAmountTransactedBasedOnTypeLongLiabilities($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_long_liabilities = DB::table('transactions')
            ->join('account', 'transactions.account', '=', 'account.account_name')
            ->select(['transactions.*'])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Loan and Line of Credit')
            ->where('account.account_type', 'Other Long-Term Liability')
            ->whereBetween('transactions.date', [$from, $to])
            ->get();

        return $transaction_long_liabilities;

    }

    public static function getAmountTransactedBasedOnTypeOtherEquity($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_other_equity = DB::table('transactions')
            ->join('account', 'transactions.account', '=', 'account.account_name')
            ->select(['transactions.*'])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Business Owner Contribution')
            ->whereBetween('transactions.date', [$from, $to])
            ->get();

        return $transaction_other_equity;

    }

    public static function getAmountTransactedBasedOnTypeRetainedEquity($from, $to, $type)
    {
        $arr = explode("-", $to);
        $from = $arr[0] . "-01-01";
        $transaction_other_equity = DB::table('transactions')
            ->join('account', 'transactions.account', '=', 'account.account_name')
            ->select(['transactions.*'])
            ->where('transactions.transaction_type', $type)
            ->where('account.account_type', 'Retain Earning - Profit and Business Owner Drawing')
            ->whereBetween('transactions.date', [$from, $to])
            ->get();

        return $transaction_other_equity;

    }


    public static function sumInvoiceItem($data, $account)
    {
        $sum = 0;
        foreach ($data as $item) {
            if (strpos($item->account, $account) > -1) {
                $sum = $sum + $item->amount;
            }
        }

        return $sum;
    }

    /*public static function sumBillItem($data,$account){
        $sum = 0;
        foreach ($data as $item){
            if (strpos($item->income_account,$account) > -1){
                $sum = $sum + ($item->item_price*$item->item_quantity);
            }
        }

        return $sum;
    }*/

    public static function COGS($from, $to)
    {
        $sum = 0;
        foreach ($data as $item) {
            if (strpos($item->income_account, $account) > -1) {
                $sum = $sum + ($item->item_price * $item->item_quantity);
            }
        }

        return $sum;
    }

    public static function getBillInvoices($from, $to)
    {
        $invoice_bill = DB::table('bill')
            ->join('bill_item', 'bill_item.bill_no', 'bill.bill_no')
            ->join('products_services', 'products_services.name', 'bill_item.item_name')
            ->select(['bill_item.price', 'bill_item.quantity', 'bill.date', 'bill_item.item_name', 'products_services.income_account'])
            ->whereBetween('bill.date', [$from, $to])
            ->whereNotNull('products_services.income_account')
            ->get();

        return $invoice_bill;
    }


    public static function sumBillItem($data, $account)
    {
        $sum = 0;
        foreach ($data as $item) {
            if (strpos($item->account, $account) > -1) {
                $sum = $sum + $item->amount;
            }
        }

        return $sum;
    }

    public static function reconcileTransactionSearchOne($from, $to, $account)
    {

        if (strpos($account, 'Accounts Receivable') > -1) {

            $transactions = DB::table('transactions')
                ->where('category', $account)
                ->orWhere('category', 'like', 'payment from%')
                ->whereBetween('date', [$from, $to])
                ->orderBy('date', 'DESC')
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $transactions = DB::table('transactions')
                ->whereBetween('date', [$from, $to])
                ->where('category', $account)
                ->orderBy('date', 'DESC')
                ->orderBy('id', 'DESC')
                ->get();
        }

        return $transactions;

    }

    public static function reconcileTransactionSearchTwo($from, $to, $account)
    {

        $transactions = DB::table('transactions')
            ->whereBetween('date', [$from, $to])
            ->where('account', $account)
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        return $transactions;

    }

    public function balancereport(Request $request)
    {
        $end_ = $request->get('start');
        $arr = explode("-", $end_);
        $start_ = $arr[0] . "-01-01";
        $active = $request->get('active');
        return view('balancesheetFetch', compact('start_', 'end_', 'active'));
    }


    public function loadincomestatement(Request $request)
    {
        $start_ = $request->get('from');
        $end_ = $request->get('to');
        $active = $request->get('active');
        return view('incomestatement', compact('start_', 'end_', 'active'));
    }

    public function cashflowreport(Request $request)
    {
        $start_ = $request->get('from');
        $end_ = $request->get('to');
        $active = $request->get('active');
        return view('cash_flow_search', compact('start_', 'end_', 'active'));
    }


    public function getcategory(Request $request)
    {
        $dw = $request->get('operation');
        $id = $request->get('id');
        $type = 0;
        if (strpos($dw, 'add') !== false) {
            $type = 3;
        } else if (strpos($dw, 'less') !== false) {
            $type = 4;
        } else if (strpos($dw, 'journal') !== false) {
            $type = 2;
        } else if (strpos($dw, 'payment_in') !== false) {
            $type = 3;
        } else if (strpos($dw, 'payment_out') !== false) {
            $type = 4;
        }

        $category = DB::table('transactions')
            ->where('id', $id)
            ->value('category');
        $transactions = DB::table('transactions')
            ->where('category', '!=', 'Accounts Receivable')
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
        $cash_bank = DB::table('account')
            ->where('account_type', 'Cash and Bank')
            ->where('account_chart', 0)
            ->get();
        $assets = DB::table('account')
            ->where('account_chart', '=', 0)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        $liabilities = DB::table('account')
            ->where('account_chart', '=', 1)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        $incomes = DB::table('account')
            ->where('account_chart', '=', 3)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        $expenses = DB::table('account')
            ->where('account_chart', '=', 4)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        $equities = DB::table('account')
            ->where('account_chart', '=', 2)
            ->orderBy('account_type', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        if (strpos($type, '0') !== false) {

            $transactions = DB::table('transactions')
                ->join('account', 'transactions.account', 'account.account_name')
                ->select('transactions.*')
                ->where('account.account_type', '=', 'Cash and Bank')
                ->orderBy('transactions.id', 'DESC')
                ->orderBy('transactions.date', 'DESC')
                ->get();

            return view('transaction_bill', compact('transactions', 'category'))->render();
        }

        return view('transaction_category', compact('transactions', 'cash_bank', 'incomes', 'expenses', 'equities', 'assets', 'liabilities', 'type', 'category'))->render();
    }

    public static function contribution_sales($num)
    {

        $db = DB::table('invoice_item')
            ->join('transactions', 'invoice_item.invoice_num', 'transactions.invoice_num')
            ->where('invoice_item.invoice_num', $num)
            ->where('transactions.category', 'Sales')
            ->orWhere('transactions.category', 'Sales Taxes')
            ->orWhere('transactions.category', 'Accounts Receivable')
            ->get();
        //$db = DB::query("SELECT * FROM invoice_item");
        //dd($db);
        $amount = array();
        if (count($db) > 0) {
            $i = 0;
            foreach ($db as $item) {
                $amount[$i] = $item->amount;
                $i++;
            }
        }
        return $amount;
    }

    public static function sales_tax($num)
    {

        $db = DB::table('transactions')
            ->where('transactions.invoice_num', $num)
            ->orWhere('transactions.category', 'Sales Taxes')
            ->get();

        return count($db);
    }

}