<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Session::has('username')){
        return redirect('dashboard');
    }
    else{
        return view('login');
    }
});

Route::post('login','ProductsAndServicesController@login')->name('login');

Route::get('dashboard', function () {
    if (Session::has('username')){
        return view('dashboard');
    }
    else{
        return redirect('/');
    }
});

Route::get('logout', function () {
    Auth::logout();
    Session::forget('username');
    if (Session::has('username')){
        return view('dashboard');
    }
    else{
        return redirect('/');
    }
});

Route::get('vendor', 'ProductsAndServicesController@showVendor')->name('showVendor');
Route::get('addvendor', function () {
    return view('addvendor');
});
Route::post('addVendor', 'ProductsAndServicesController@storeVendor')->name('addVendor');
Route::get('vendor/delete/{id}/{task}', 'ProductsAndServicesController@deleteVendor')->name('deleteVendor');
Route::post('vendor/update', 'ProductsAndServicesController@updateVendor')->name('updateVendor');

Route::post('addTax', 'ProductsAndServicesController@addTax')->name('addTax');


Route::get('productsandservicespurchases/{role}', 'ProductsAndServicesController@index')->name('productsandservices');
Route::post('getProductData', 'ProductsAndServicesController@getProductData')->name('getProductData');

Route::get('addproductsandservicespurchases/{role}', function () {
    $role = Route::input('role');
    $taxes = DB::table('tax')->get();
    $tax = Route::input("role");
    $incomes = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();
    $expenses = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();
    return view('addproductsandservicespurchases', compact('taxes', 'tax','role','incomes','expenses'));
})->name('product');

Route::get('delete/{id}/{role}', 'ProductsAndServicesController@delete')->name('delete');


Route::post('updateProducts', 'ProductsAndServicesController@updateProduct')->name('update');

Route::post('addproductsandservicespurchases', 'ProductsAndServicesController@store')->name('addproductandservices');
Route::post('getcurrency', 'ProductsAndServicesController@getCurrency')->name('getcurrency');
Route::get('bills', function () {
    $bills = DB::table('bill')
        ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
        ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
        ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
        ->get();
    $expenses = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();
    return view('bills', compact('bills','expenses'));
});
Route::get('filterbill/{currency}/{from}/{to}/{id}', function () {
    $currency = Route::input("currency");
    $from = Route::input("from");
    $to = Route::input("to");
    $id = Route::input("id");

    $from = str_replace("-", "/", $from);
    $to = str_replace("-", "/", $to);

    if ($currency != "0" && $from != "0" && $to != "0") {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->where([['bill.currency', '=', $currency]])
            ->whereBetween('date', [$from, $to])
            ->get();
    } elseif ($currency != "0" && $from != "0" && $to == "0") {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->where([['bill.currency', '=', $currency], ['bill.date', '>=', $from]])
            ->get();
    } elseif ($currency != "0" && $from == "0" && $to != "0") {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->where([['bill.currency', '=', $currency], ['bill.date', '<=', $to]])
            ->get();
    } elseif ($currency != "0" && $from == "0" && $to == "0") {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->where('bill.currency', $currency)
            ->get();
    } elseif ($currency == "0" && $from != 0 && $to != "0") {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->whereBetween('date', [$from, $to])
            ->get();
    } elseif ($currency == "0" && $from != "0" && $to == "0") {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->where('bill.date', '>=', $from)
            ->get();
    } elseif ($currency == "0" && $from == "0" && $to != "0") {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->where('bill.date', '<=', $to)
            ->get();
    } else {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'bill.currency as cur', 'vendor_customer.*', 'bill_item.*')
            ->get();
    }

    return view('bill_filter', compact('bills'));
});

Route::post('addbillitem', 'ProductsAndServicesController@addbillitem')->name('addbillitem');
Route::post('editbillitems', 'ProductsAndServicesController@editBillOne')->name('editbillitems');


Route::get('addbills', function () {
    $vendors = DB::table('vendor_customer')
        ->where('role', 0)
        ->get();
    $products = DB::table('products_services')->get();
    $taxes = DB::table('tax')->get();
    $expenses = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();
    return view('addbills', compact('vendors', 'products', 'taxes','expenses'));
});

Route::get('customers', 'ProductsAndServicesController@showCustomer')->name('showCustomer');

Route::post('editbillitem', 'ProductsAndServicesController@editBillItem')->name('editBill');

Route::get('deletebill/{num}', function () {
    $num = Route::input("num");
    $res = DB::table('bill_item')->where('bill_no', '=', $num)->delete();
    $res2 = DB::table('bill')->where('bill_no', '=', $num)->delete();
    $res3 = DB::table('transactions')->where('invoice_num', '=', $num)->delete();
    if ($res && $res2 && $res3) {
        $bills = DB::table('bill')
            ->join('vendor_customer', 'vendor_customer.id', '=', 'bill.vendor')
            ->join('bill_item', 'bill_item.bill_no', '=', 'bill.bill_no')
            ->select('bill.*', 'bill.bill_no', 'vendor_customer.*', 'bill_item.*')
            ->get();

        return view('bills', compact('bills'));
    }
    return false;
});

/*Route::get('customers', function () {
    return view('customers');
});*/
Route::get('addCustomers', function () {
    return view('addCustomers');
});
Route::get('customersStatement', function () {
    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();
    return view('customerstatement', compact('customers'));
});
Route::get('customersPreviewStatement/{customer}/{from}/{to}/{unpaid}', function () {
    $customer_id = Route::input('customer');
    $from = Route::input('from');
    $to = Route::input('to');
    $unpaid = Route::input('unpaid');
    $invoices_all = DB::table('invoice')
        ->whereBetween('invoice_date', [$from, $to])
        ->where('customer_id','=',$customer_id)
        ->orderBy('invoice_date','ASC')
        ->get();
    $payments = DB::table('payment')
        ->join('invoice','invoice.invoice_num','payment.invoice_num')
        ->where('invoice.customer_id',$customer_id)
        ->whereBetween('payment.date',[$from,$to])
        ->get();

    if ($unpaid == 1){
        $invoices = DB::table('invoice')
            ->whereBetween('invoice_date', [$from, $to])
            ->where('customer_id','=',$customer_id)
            ->where('status','=',1)
            ->orWhere('status','=',2)
            ->orderBy('invoice_date','DESC')
            ->get();
    }
    elseif ($unpaid == 0){
        $invoices = DB::table('invoice')
            ->whereBetween('invoice_date', [$from, $to])
            ->where('customer_id','=',$customer_id)
            ->where('status','=',4)
            ->orWhere('status','=',5)
            ->orderBy('invoice_date','DESC')
            ->get();
    }
    else{
        $invoices = DB::table('invoice')
            ->whereBetween('invoice_date', [$from, $to])
            ->where('customer_id','=',$customer_id)
            ->where('status','=',1)
            ->orWhere('status','=',2)
            ->orderBy('invoice_date','DESC')
            ->get();
    }

    $customer = DB::table('vendor_customer')
        ->where('id', '=',$customer_id)
        ->first();
    return View::make('statement_customer_preview', compact('invoices','customer','from','to','unpaid','customer_id','invoices_all','payments'))->render();
});
Route::get('invoices', function () {
    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();
    $products = DB::table('products_services')->where('sales_purchases', '10')->orWhere('sales_purchases', '11')->get();
    $taxes = DB::table('tax')->get();
    return view('invoices', compact('customers', 'products', 'taxes'));
});
Route::get('edit_invoice/{id}/{customer_id}', function () {
    $id = Route::input('id');
    $customer_id = Route::input('customer_id');
    $invoices = DB::table('invoice')
        ->where('invoice_num', '=', $id)
        ->get();
    $invoice_items = DB::table('invoice_item')
        ->where('invoice_num', '=', $id)
        ->get();
    $customer_all = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();
    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->where('id',$customer_id)
        ->get();
    $products = DB::table('products_services')
        ->where('sales_purchases', '=', 11)
        ->orWhere('sales_purchases', 10)
        ->get();
    $taxes = DB::table('tax')
        ->get();
    return view('edit_invoice', compact('invoices','invoice_items','customers','customer_all','products','taxes'));
});

Route::get('edit_invoice/{id}/{customer_id}/{quantity}', function () {
    $id = Route::input('id');
    $customer_id = Route::input('customer_id');
    $quantity = Route::input('quantity');
    $invoices = DB::table('invoice')
        ->where('invoice_num', '=', $id)
        ->get();
    $invoice_items = DB::table('invoice_item')
        ->where('invoice_num', '=', $id)
        ->get();
    $customer_all = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();
    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->where('id',$customer_id)
        ->get();
    $products = DB::table('products_services')
        ->where('sales_purchases', '=', 11)
        ->orWhere('sales_purchases', 10)
        ->get();
    $taxes = DB::table('tax')
        ->get();
    return view('edit_invoice', compact('invoices','invoice_items','customers','customer_all','customer_id','products','taxes','quantity'));
});

Route::get('addCustomerbillTo/{id}', function () {
    $id = Route::input("id");
    $customers = DB::table('vendor_customer')
        ->where([['role', '=', 1], ['id', '=', $id]])
        ->get();
    return view('customerbillto', compact('customers'));
});
Route::get('invoice_dashboard', function () {
    $invoice_all = DB::table('invoice')
        ->get();
    $payment_due = DB::table('invoice')
        ->value('payment_due');
    $invoice_draft = DB::table('invoice')
        ->where('status', '=', 0)
        ->get();
    $invoice_unpaid = DB::table('invoice')
        ->where('status', '=', 0)
        ->orWhere('status', '=', 1)
        ->orWhere('status', '=', 2)
        ->get();

    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();
    return view('invoice_dashboard', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers','payment_due'));
});

Route::get('invoice_dashboard', function () {
    $invoice_all = DB::table('invoice')
        ->get();
    $payment_due = DB::table('invoice')
        ->value('payment_due');
    $invoice_draft = DB::table('invoice')
        ->where('status', '=', 0)
        ->get();
    $invoice_unpaid = DB::table('invoice')
        ->where('status', '=', 0)
        ->orWhere('status', '=', 1)
        ->orWhere('status', '=', 2)
        ->get();

    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();
    return view('invoice_dashboard', compact('invoice_all', 'invoice_draft', 'invoice_unpaid', 'customers','payment_due'));
});

Route::get('invoice_dashboard/{customer}/{from}/{to}/{status}', 'ProductsAndServicesController@dashboard_filter')->name('invoice_dashboard');


Route::get('invoice_preview', function () {
    return view('invoice_preview');
});

Route::get('invoice_preview_edit', function () {
    return view('invoice_preview_edit');
});

Route::post('saveinvoice', 'ProductsAndServicesController@saveinvoice')->name('saveinvoice');
Route::post('updateinvoice', 'ProductsAndServicesController@updateinvoice')->name('updateinvoice');
Route::get('action_invoice_list/{num}', function () {
    $num = Route::input("num");
    $invoices = DB::table('invoice')
        ->where('invoice_num', $num)
        ->get();
    $invoice_items = DB::table('invoice_item')
        ->where('invoice_num', $num)
        ->get();
    return view('action_invoice_list', compact('invoices', 'invoice_items', 'num'));
});
Route::get('approveinvoice/{bill_no}', 'ProductsAndServicesController@approveinvoice')->name('approveinvoice');
//Route::get('sendinvoice', 'ProductsAndServicesController@sendEmail');
Route::post('sendinvoice', function () {
    $data = array('name' => "Virat Gandhi");

    Mail::send(['text' => 'mail'], $data, function ($message) {
        $message->to('ambokilekifukwe@gmail.com', 'Tutorials Point')->subject
        ('Laravel Basic Testing Mail');
        $message->from('ambokilekifukwe@gmail.com', 'Virat Gandhi');
    });
    return response()->json(['data' => 'mail sent']);
});
Route::get('printinvoice/{num}', 'ProductsAndServicesController@printInvoice_')->name('printinvoice');
//Route::post('pdfview', 'ProductsAndServicesController@pdfview')->name('pdfview');
Route::get('pdfview/{num}',array('as'=>'pdfview','uses'=>'ProductsAndServicesController@pdfview'));

Route::get('invoiceDownload/{num}','ProductsAndServicesController@downloadInvoice')->name('download');

Route::get('printinfo/{num}',
    ['as'=> 'print', 'uses'=>'ProductsAndServicesController@printInvoice_']
);

Route::get('getProduct', function () {
    $products = DB::table('products_services')
        ->where('sales_purchases', '=', 11)
        ->orWhere('sales_purchases', 1)
        ->get();

    $expenses = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();

    return $products."&".$expenses;
});
Route::get('getAccount', function () {

    $transactions = DB::table('transactions')
        ->orderBy('id','DESC')
        ->orderBy('date','DESC')
        ->get();
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    $assets = DB::table('account')
        ->where('account_chart', '=', 0)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $liabilities = DB::table('account')
        ->where('account_chart', '=', 1)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();

    $incomes = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();

    $expenses = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();

    $equities = DB::table('account')
        ->where('account_chart', '=', 2)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();
    return view('account_options',compact('transactions','cash_bank','incomes','expenses','equities','assets','liabilities'));
});

Route::get('deleteinvoice/{bill_no}', 'ProductsAndServicesController@deleteinvoice')->name('deleteinvoice');

Route::get('receipts', function () {

    $receipts = DB::table('receipts')->get();
    $expenses = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    return view('receipts', compact('receipts','expenses','cash_bank'));
});

Route::post('uploadReceipt', 'ProductsAndServicesController@uploadReceipt')->name('uploadReceipt');

Route::get('deletereceipts/{id}', 'ProductsAndServicesController@deletereceipt')->name('deletereceipts');

Route::get('receiptsDetails/{id}', function () {
    $id = Route::input("id");
    $receipts = DB::table('receipts')
        ->where('id', '=', $id)
        ->first();
    $taxes = DB::table('tax')->get();
    $expenses = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('id','DESC')
        ->orderBy('account_type','ASC')
        ->get();
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    return view('receipt_details', compact('receipts', 'taxes','expenses','cash_bank'));
});

Route::post('updatereceipt', 'ProductsAndServicesController@updatereceipt')->name('updatereceipt');
Route::get('payrolldashboard', function () {
    return view('payroll_dashboard');
});

Route::post('getstatementpreview', 'ProductsAndServicesController@statementpreview')->name('getstatementpreview');

Route::get('addPayment/{invoice_num}/{path}', function () {
    $num = Route::input("invoice_num");
    $path = Route::input("path");
    $user_id = DB::table('invoice')
        ->where('invoice_num',$num)
        ->value('customer_id');
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    return view('addPayment', compact('num','cash_bank','user_id','path'));
})->name('addPayment');

Route::post('addPayment', 'ProductsAndServicesController@addPayment')->name('addPayment');

Route::get('addPaymentBill/{bill_num}', function () {
    $num = Route::input("bill_num");
    $user_id = DB::table('bill')
        ->where('bill_no',$num)
        ->value('vendor');
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    return view('addPaymentBill', compact('num','cash_bank','user_id'));
})->name('addPaymentBill');
Route::post('addPaymentBill', 'ProductsAndServicesController@addPaymentBill')->name('addPaymentBill');
Route::post('uploadCSVFile', 'ProductsAndServicesController@uploadCSVFile')->name('uploadCSVFile');
Route::get('sendmail', 'ProductsAndServicesController@sendmail')->name('sendmail');
Route::get('transaction_dashboard/{type}', function () {
    $type = Route::input('type');
    $transactions = DB::table('transactions')
        ->join('account','transactions.account','account.account_name')
        ->select('transactions.*')
        ->where('account.account_type','=','Cash and Bank')
      ->orWhere('transactions.category','=','journal statement')
        ->where('transactions.category','!=','Accounts Receivable')
        ->orderBy('transactions.id','DESC')
        ->orderBy('transactions.date','DESC')
        ->get();
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    $assets = DB::table('account')
        ->where('account_chart', '=', 0)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
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
    return view('transaction_dashboard',compact('transactions','cash_bank','incomes','expenses','equities','assets','liabilities','type'));
})->name('transaction_dashboard');

Route::get('account_chart_dashboard', function () {
    $invoice_all = DB::table('invoice')
        ->get();
    $account_category = DB::table('account_category')
        ->get();
    $account_assets = DB::table('account')
        ->where('account_chart', '=', 0)
        ->orderBy('account_name','ASC')
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $account_liabilities = DB::table('account')
        ->where('account_chart', '=', 1)
        ->orderBy('account_name','ASC')
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $account_equities = DB::table('account')
        ->where('account_chart', '=', 2)
        ->orderBy('account_name','ASC')
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $account_incomes = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('account_name','ASC')
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $account_expenses = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('account_name','ASC')
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    return view('account_chart_dashboard',compact('account_assets','account_liabilities','account_incomes','account_expenses','account_equities','account_category'));
})->name('account_chart_dashboard');
Route::post('addAccount', 'ProductsAndServicesController@addAccount')->name('addAccount');
Route::post('editAccount', 'ProductsAndServicesController@editAccount')->name('editAccount');
Route::post('deleteAccount', 'ProductsAndServicesController@deleteAccount')->name('deleteAccount');
Route::post('addTransaction', 'ProductsAndServicesController@addTransaction')->name('addTransaction');
Route::post('updateTransaction', 'ProductsAndServicesController@updateTransaction')->name('updateTransaction');
Route::post('getcategory', 'ProductsAndServicesController@getcategory')->name('getcategory');
Route::get('getcategory/{dw}/{id}', function () {
    $dw = Route::input("dw");
    $id = Route::input("id");
    $type = 0;
    if (strpos($dw, 'add') !== false) {
        $type = 3;
    }
    else if (strpos($dw, 'less') !== false) {
        $type = 4;
    }
    else if (strpos($dw, 'journal') !== false){
            $type = 2;
    }
    else if (strpos($dw, 'payment_in') !== false){
        $type = 3;
    }
    else if (strpos($dw, 'payment_out') !== false){
        $type = 4;
    }

    $category = DB::table('transactions')
        ->where('id',$id)
        ->value('category');
    $transactions = DB::table('transactions')
        ->where('category','!=','Accounts Receivable')
        ->orderBy('date','DESC')
        ->orderBy('id','DESC')
        ->get();
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    $assets = DB::table('account')
        ->where('account_chart', '=', 0)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $liabilities = DB::table('account')
        ->where('account_chart', '=', 1)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $incomes = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $expenses = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $equities = DB::table('account')
        ->where('account_chart', '=', 2)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    if (strpos($type, '0') !== false){

        $transactions = DB::table('transactions')
            ->join('account','transactions.account','account.account_name')
            ->select('transactions.*')
            ->where('account.account_type','=','Cash and Bank')
            ->orderBy('transactions.id','DESC')
            ->orderBy('transactions.date','DESC')
            ->get();

        return view('transaction_bill',compact('transactions','category'));
    }

    return view('transaction_category',compact('transactions','cash_bank','incomes','expenses','equities','assets','liabilities','type','category'));
})->name('getcategory');
Route::get('getcategory/{dw}/{id}/{select}', function () {
    $dw = Route::input("dw");//0
    $id = Route::input("id");//310
    $select = Route::input("select");//sales

    if (strpos($select, 'payment to') !== false) {
        $transactions = DB::table('transactions')
            ->join('account','transactions.account','account.account_name')
            ->select('transactions.*')
            ->where('account.account_type','=','Cash and Bank')
            ->where('transactions.category','like','payment to%')
            ->orderBy('transactions.id','DESC')
            ->orderBy('transactions.date','DESC')
            ->get();

    }

    else if (strpos($select, 'payment from') !== false) {
        $transactions = DB::table('transactions')
            ->join('account','transactions.account','account.account_name')
            ->select('transactions.*')
            ->where('account.account_type','=','Cash and Bank')
            ->where('transactions.category','like','payment from%')
            ->orderBy('transactions.id','DESC')
            ->orderBy('transactions.date','DESC')
            ->get();
    }
    else{
        $transactions = array();
    }

    return view('transaction_bill',compact('transactions','select','type'));
})->name('getcategory');
Route::post('updateMarkTransaction', 'ProductsAndServicesController@updateMarkTransaction')->name('updateMarkTransaction');
Route::post('selectTransaction', 'ProductsAndServicesController@selectTransaction')->name('selectTransaction');
Route::get('getjournalItem/{id}', function () {
    $id = Route::input("id");

    $inv = DB::table('transactions')
        ->where('id',$id)
        ->value('invoice_num');

    $journal = DB::table('journal_details')
        ->where('journal_id',$inv)
        ->get();

    $transactions = DB::table('transactions')
        ->where('category','!=','Accounts Receivable')
        ->orderBy('date','DESC')
        ->orderBy('id','DESC')
        ->get();
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    $assets = DB::table('account')
        ->where('account_chart', '=', 0)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $liabilities = DB::table('account')
        ->where('account_chart', '=', 1)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $incomes = DB::table('account')
        ->where('account_chart', '=', 2)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $expenses = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $equities = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    return view('journal_item',compact('transactions','cash_bank','incomes','expenses','equities','assets','liabilities','journal'));
})->name('getjournalItem');

Route::get('reconciling', function () {
    $account = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->value('account_name');
    $account_chart = DB::table('account')
        ->select('account_chart')
        ->where('account_name',$account)
        ->value('account_chart');
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    $date = DB::table('transactions')
        ->where('account',$account)
        ->orderBy('id','ASC')
        ->value('date');
    $amount = DB::table('transactions')
        ->where('account',$account)
        ->orderBy('id','ASC')
        ->value('amount');

    $reconciles = DB::table('reconcile')
        ->where('account',$account)
        ->orderBy('id','DESC')
        ->get();
    $reconcile_last_date = DB::table('reconcile')
        ->where('account',$account)
        ->orderBy('id','DESC')
        ->value('ending_balance_date');
    return view('account_reconciliation_dashboard', compact('cash_bank','date','amount','reconciles','reconcile_last_date','account','account_chart'));
});

Route::get('reconciling_search/{account}', function () {
    $account = Route::input('account');
    $account_chart = DB::table('account')
        ->select('account_chart')
        ->where('account_name',$account)
        ->value('account_chart');
    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();

    $date = DB::table('transactions')
        ->where('account',$account)
        ->orderBy('id','ASC')
        ->value('date');

    $amount = DB::table('transactions')
        ->where('account',$account)
        ->orderBy('id','ASC')
        ->value('amount');

    $reconciles = DB::table('reconcile')
        ->where('account',$account)
        ->orderBy('id','DESC')
        ->get();

    $reconcile_last_date = DB::table('reconcile')
        ->where('account',$account)
        ->orderBy('id','DESC')
        ->value('ending_balance_date');

    return view('account_reconciliation_dashboard', compact('cash_bank','date','amount','reconciles','reconcile_last_date','account','account_chart'));
});

Route::get('reconciling_account/{id}/{from}/{to}/{account}', function () {
    $id = Route::input('id');
    $from = Route::input('from');
    $to = Route::input('to');
    $account = Route::input('account');
    $account_chart = DB::table('account')
        ->select('account_chart')
        ->where('account_name',$account)
        ->value('account_chart');

    if ($from < $to ){
        $arr = explode("-",$to);
        $m = $arr[1];
        $d = $arr[2];
        $y = $arr[0];

        $from = $y."-".$m."-01";
        $transactions = DB::table('transactions')
            ->whereBetween('date',[$from,$to])
            ->where('account',$account)
            ->orderBy('date','DESC')
            ->orderBy('id','DESC')
            ->get();
    }
    else{
        $arr = explode("-",$from);
        $m = $arr[1];
        $d = $arr[2];
        $y = $arr[0];

        $to = $from;
        $from = $y."-".$m."-01";


        $transactions = DB::table('transactions')
            ->whereBetween('date',[$from,$to])
            ->where('account',$account)
            ->orderBy('date','DESC')
            ->orderBy('id','DESC')
            ->get();
    }

    $items = DB::table('invoice')
        ->join('invoice_item','invoice.invoice_num','invoice_item.invoice_num')
        ->join('products_services','products_services.name','invoice_item.item_name')
        ->select(['products_services.income_account'])
        ->whereNotNull('products_services.income_account')
        ->get();


    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    $date = DB::table('transactions')
        ->orderBy('id','ASC')
        ->value('date');
    $amount = DB::table('transactions')
        ->orderBy('id','DESC')
        ->value('amount');
    $assets = DB::table('account')
        ->where('account_chart', '=', 0)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $liabilities = DB::table('account')
        ->where('account_chart', '=', 1)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $incomes = DB::table('account')
        ->where('account_chart', '=', 2)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $expenses = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $equities = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $vendors = DB::table('vendor_customer')
        ->where('role', 0)
        ->get();

    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();

    return view('account_reconciliation', compact('cash_bank','date','amount','assets','liabilities','incomes','expenses','equities','vendors','customers','transactions','from','to','account','items','account_chart'));
});


Route::post('addreconcile', 'ProductsAndServicesController@addreconcile')->name('addreconcile');
Route::post('updatereconcile', 'ProductsAndServicesController@updatereconcile')->name('updatereconcile');
Route::post('deleteTransaction', 'ProductsAndServicesController@deleteTransaction')->name('deleteTransaction');
Route::post('reconcileTransactionSearch', 'ProductsAndServicesController@reconcileTransactionSearch')->name('reconcileTransactionSearch');
Route::get('deletepayment/{pid}/{id}', 'ProductsAndServicesController@deletepayment')->name('deletepayment');
Route::get('getTaxList', function () {
    $taxes = DB::table('tax')->get();
    return view('tax_select', compact('taxes'));
});

Route::get('reports', function () {
    return view('reports');
});
Route::post('loadincomestatement', 'ProductsAndServicesController@loadincomestatement')->name('loadincomestatement');

Route::get('balancereport', function () {

    $start_ = date("Y",time())."-01-01";
    $end_ = date("Y-m-d",time());
    return view('reportbalancesheet',compact('start_','end_'));
});
Route::post('balancereport', 'ProductsAndServicesController@balancereport')->name('balancereport');
Route::get('profitlossreport', function () {
    return view('reportprofitloss');
});
Route::get('cashflowreport', function () {
    $active = 1;
    $start_ = date("Y",time())."-01-01";
    $end_ = date("Y-m-d",time());
    return view('cash_flow',compact('start_','end_','active'));
});
Route::post('cashflowreport', 'ProductsAndServicesController@cashflowreport')->name('cashflowreport');
Route::get('ledger/{num}/{account}/{from}/{to}', function () {
    $id = Route::input('num');
    $account = Route::input('account');
    $from = Route::input('from');
    $to = Route::input('to');

    if ($from < $to ){
        $arr = explode("-",$to);
        $m = $arr[1];
        $d = $arr[2];
        $y = $arr[0];

        $from = $y."-".$m."-01";
        $items = DB::table('invoice')
            ->join('invoice_item','invoice.invoice_num','invoice_item.invoice_num')
            ->join('products_services','products_services.name','invoice_item.item_name')
            ->select(['invoice.invoice_date as date','invoice_item.item_description as description','invoice_item.item_quantity as quantity','invoice_item.item_price as price','products_services.income_account'])
            ->whereBetween('invoice.invoice_date', [$from, $to])
            ->whereNotNull('products_services.income_account')
            ->where('products_services.income_account',$account)
            ->get();
    }
    else{
        $arr = explode("-",$from);
        $m = $arr[1];
        $d = $arr[2];
        $y = $arr[0];

        $to = $from;
        $from = $y."-".$m."-01";


        $items = DB::table('invoice')
            ->join('invoice_item','invoice.invoice_num','invoice_item.invoice_num')
            ->join('products_services','products_services.name','invoice_item.item_name')
            ->select(['invoice.invoice_date as date','invoice_item.item_description as description','products_services.income_account'])
            ->whereBetween('invoice.invoice_date', [$from, $to])
            ->whereNotNull('products_services.income_account')
            ->where('products_services.income_account',$account)
            ->get();
    }
    $operation = "cr";

    $cash_bank = DB::table('account')
        ->where('account_type','Cash and Bank')
        ->where('account_chart',0)
        ->get();
    $date = DB::table('transactions')
        ->orderBy('id','ASC')
        ->value('date');
    $amount = DB::table('transactions')
        ->orderBy('id','DESC')
        ->value('amount');
    $assets = DB::table('account')
        ->where('account_chart', '=', 0)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $liabilities = DB::table('account')
        ->where('account_chart', '=', 1)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $incomes = DB::table('account')
        ->where('account_chart', '=', 2)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $expenses = DB::table('account')
        ->where('account_chart', '=', 3)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $equities = DB::table('account')
        ->where('account_chart', '=', 4)
        ->orderBy('account_type','ASC')
        ->orderBy('id','DESC')
        ->get();

    $vendors = DB::table('vendor_customer')
        ->where('role', 0)
        ->get();

    $customers = DB::table('vendor_customer')
        ->where('role', 1)
        ->get();

    return view('ledger', compact('cash_bank','date','amount','assets','liabilities','incomes','expenses','equities','vendors','customers','items','from','to','account','operation'));
});