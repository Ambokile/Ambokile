<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'addproductsandservicespurchases',
        'updateProducts',
        'addVendor',
        'vendor/update',
        'addTax',
        'getProductData',
        'getcurrency',
        'editbillitem',
        'addbillitem',
        'deletebill',
        'filterbill',
        'invoice_preview',
        'saveinvoice',
        'action_invoice_list',
        'approveinvoice',
        'sendinvoice',
        'printinvoice',
        'getProduct',
        'editbillitems',
        'deleteinvoice',
        'receipts',
        'uploadReceipt',
        'deletereceipts',
        'receiptsDetails',
        'updatereceipt',
        'payrolldashboard',
        'getstatementpreview',
        'addPayment',
        'addPaymentBill',
        'uploadCSVFile',
        'account_chart_dashboard',
        'addAccount',
        'editAccount',
        'addTransaction',
        'getcategory',
        'updateTransaction',
        'updateMarkTransaction',
        'selectTransaction',
        'getAccount',
        'getjournalItem',
        'reconciling_search',
        'reconciling',
        'reconciling_account',
        'addreconcile',
        'updatereconcile',
        'deleteTransaction',
        'reconcileTransactionSearch',
        'updateinvoice',
        'deletepayment',
        'getTaxList',
        'ledger',
        'balancereport',
        'loadincomestatement',
        'cashflowreport',
        'getcategory',
        'cashflowreport',
        'deleteAccount',
    ];
}
