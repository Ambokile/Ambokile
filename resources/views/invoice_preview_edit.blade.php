<div class="container" id="preview" style="width: 70%;border: solid 1px lightblue; box-shadow: 5px 10px 8px #888888;margin-bottom: 2%">
    <div class="row" style="border-bottom: solid 1px #cccccc;">
        <div class="col-sm-6">
            <img src="{{asset('img/1.png')}}" id="invoice_logo" class="thumbnail" style="margin-top: 5%;padding-bottom: 2%" width="144px" height="144px" id="preview"/>
        </div>
        <div class="col-sm-6" style="text-align: right;font-family: 'Times New Roman'">
            <div style="margin-top: 5%;font-size: 18px;font-weight: bold" id="title"></div>
            <div style="font-size: 14px;" id="summary"></div>
            <div style="font-size: 14px; font-weight: bold" id="company_prev"></div>
            <div style="font-size: 14px;" ><small id="location_prev"></small></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div style="margin-top: 10%;font-size: 14px;font-weight: bold">BILL TO</div>
            <div style="font-size: 14px; font-weight: bold" id="name_in_prev"></div>
            <div style="font-size: 14px" id="full_name"></div>
            <div style="margin-top: 5%;font-size: 14px" id="email_in_prev"></div>
        </div>
        <div class="col-sm-6">
            <div class="row" style="margin-top: 10%;font-size: 14px;">
                <label class="control-label col-sm-9" for="invoice_num" style="font-weight: bold;text-align: right">Invoice Number:</label>
                <div class="col-sm-3" id="invoice_num" style=";text-align: right"></div>
            </div>
            <div class="row" style="font-size: 14px;;text-align: right">
                <label class="control-label col-sm-9" for="invoice_date" style="font-weight: bold">Invoice Date:</label>
                <div class="col-sm-3" id="invoiceDate"></div>
            </div>
            <div class="row" style="font-size: 14px;;text-align: right">
                <label class="control-label col-sm-9" for="payment_due" style="font-weight: bold">Payment Due:</label>
                <div class="col-sm-3" id="payment_due"></div>
            </div>
            <div class="row" style="font-size: 14px;;text-align: right">
                <label class="control-label col-sm-9" for="amount_due" style="font-weight: bold">Amount Due (TZS):</label>
                <div class="col-sm-3" id="amount_due"></div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 3%;padding: 0px">
        <div class="col-sm-12" style="padding: 0px">
            <div class="table-responsive">
                <table class="table borderless" style="font-size: 14px" id="myTable_prev">
                    <thead class="thead-dark">
                    <tr style="">
                        <th scope="col">Products</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col" style="text-align: center">Amount</th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12" style="padding: 2%" id="notes_prev_pare">
            <div style="font-size: 15px;font-weight: bold">Notes:</div>
            <div id="notes_prev"></div>
        </div>
    </div>

    <dir class="row">
        <div class="col-sm-8">

        </div>
        <div class="col-sm-4">
            <button class="btn btn-info" id="btn_preview_">Edit</button>
            <button type="button" class="btn btn-secondary" onclick="editInvoice(1)">Saves and Continue</button>
        </div>
    </dir>
</div>