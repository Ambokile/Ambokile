<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row">
    <div class="col-sm-10 offset-1">

        <div class="row" style="">
            <div class="col-sm-8">
                <h2>Reports</h2>
            </div>
            <div class="col-sm-4">
            </div>
        </div>

        <div class="row" style="width: 100%;margin-left: 0%;font-size: 14px;color: #3C4858;margin-top: 2%">
            <fieldset class="col-sm-12" style="padding: 20px;background-color: white">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Get the big picture</h3>
                        <div>How much profit are you making? Are your assets growing faster than your liabilities? Is cash flowing, or getting stuck?</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="list-group" style="border-style: none">

                            <a href="#" onclick="LoadContent('profitlossreport')" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Profit & Loss (Income Statement)</h5>
                                    <small><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">Summary of your revenue and expenses that determine the profit you made.</p>
                            </a>

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none" onclick="LoadContent('balancereport')">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Balance Sheet</h5>
                                    <small class="text-muted"><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Snapshot of what your business owns or is due to receive from others (assets), what it owes to others (liabilities), and what you've invested or retained in your company (equity).</p>
                            </a>
                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none" onclick="LoadContent('cashflowreport')">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Cash Flow</h5>
                                    <small class="text-muted"><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Cash coming in and going out of your business. Includes items not included in Profit & Loss such as repayment of loan principal and owner drawings (paying yourself).</p>
                            </a>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="row" style="width: 100%;margin-left: 0%;font-size: 14px;color: #3C4858;margin-top: 2%">
            <fieldset class="col-sm-12" style="padding: 20px;background-color: white">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Stay on top of taxes</h3>
                        <div>Find out how much tax you’ve collected and how much tax you owe back to tax agencies.</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="list-group" style="border-style: none">

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Sales Tax</h5>
                                    <small><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Taxes collected from sales and paid on purchases to help you file sales tax returns.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>


        <div class="row" style="width: 100%;margin-left: 0%;font-size: 14px;color: #3C4858;margin-top: 2%">
            <fieldset class="col-sm-12" style="padding: 20px;background-color: white">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Focus on customers</h3>
                        <div>See which customers contribute most of your revenue, and keep on top of overdue balances.</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="list-group" style="border-style: none">

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Income by Customer</h5>
                                    <small><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">Paid and Unpaid income broken down by customer.</p>
                            </a>

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Aged Receivables</h5>
                                    <small class="text-muted"><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Unpaid and overdue invoices for the last 30, 60, and 90+ days.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>


        <div class="row" style="width: 100%;margin-left: 0%;font-size: 14px;color: #3C4858;margin-top: 2%">
            <fieldset class="col-sm-12" style="padding: 20px;background-color: white">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Focus on vendors</h3>
                        <div>Understand business spending, where you spend, and how much you owe to your vendors.</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="list-group" style="border-style: none">

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        Purchases by Vendor
                                        </h5>
                                    <small><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Business purchases, broken down by who you bought from.</p>
                            </a>

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        Aged Payables
                                    </h5>
                                    <small class="text-muted"><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Unpaid and overdue bills for the last 30, 60, and 90+ days.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>



        <div class="row" style="width: 100%;margin-left: 0%;font-size: 14px;color: #3C4858;margin-top: 2%;margin-bottom: 4%;">
            <fieldset class="col-sm-12" style="padding: 20px;background-color: white">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Dig deeper</h3>
                        <div>Drill into the detail of financial transactions over the life of your company.</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="list-group" style="border-style: none">

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        Account Balances
                                    </h5>
                                    <small><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Summary view of balances and activity for all accounts.
                                </p>
                            </a>

                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        Trial Balance
                                    </h5>
                                    <small class="text-muted"><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Balance of all your accounts on a specified date.</p>
                            </a>
                            <a href="#" class="list-group-item flex-column align-items-start" style="border-style: none">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        Account Transactions (General Ledger)</h5>
                                    <small class="text-muted"><i class="fa fa-chevron-right"></i></small>
                                </div>
                                <p class="mb-1">
                                    Detailed list of all transactions and their total by account—everything in your Chart of Accounts.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

    </div>
</div>