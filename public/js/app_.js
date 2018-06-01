function format1(n, currency) {
    return currency + " " + n.toFixed(2).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    });
}

$('.co').click( function(e) {
    $('.collapse').collapse('hide');
});

function uploadFile() {
    $('#dvProgress').show();
    var uploadProgress = $('#dvProgress').progressbarManager({
        totalValue: 100,
        initValue: '0',
        animate: true,
        stripe: true,
        style: 'primary'
    });
    var fd = new FormData();
    fd.append('receipt_upload', $('#receipt_upload')[0].files[0]);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'uploadReceipt', true);

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            var percentComplete = (e.loaded / e.total) * 100;
            uploadProgress.setValue(percentComplete);
            if (percentComplete >= 100){
                uploadProgress.style('success');
            }
        }
    };
    xhr.onload = function() {
        if (this.status == 200 && this.readyState == 4) {

            if(this.responseText == 1){
                $('#dvProgress').hide();
                setTimeout(function () {
                    LoadContent("receipts");
                },5000);

                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; font-size: 14px; text-align: center;width: 100%'>successfull added, wait updating...</span>";
            }
            else{
                $('#dvProgress').html("Opps failed!");
                document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;text-align: center;width: 100%'>failed to be added</span>";
            }

            $("#msg").show();
            setTimeout(function () {
                $("#msg").hide();
            },5000);
        };
    };
    xhr.send(fd);

}

function uploadCSVFile() {
    $('#dvProgress').show();
    var uploadProgress = $('#dvProgress').progressbarManager({
        totalValue: 100,
        initValue: '0',
        animate: true,
        stripe: true,
        style: 'primary'
    });
    var fd = new FormData();
    fd.append('csv_upload', $('#receipt_upload')[0].files[0]);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'uploadCSVFile', true);

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            var percentComplete = (e.loaded / e.total) * 100;
            uploadProgress.setValue(percentComplete);
            if (percentComplete >= 100){
                uploadProgress.style('success');
            }
        }
    };
    xhr.onload = function() {
        if (this.status == 200 && this.readyState == 4) {

            if(this.responseText == 0){
                $('#dvProgress').html("Opps failed!");
                document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;text-align: center;width: 100%'>failed to be added</span>";
            }
            else{
                $('#dvProgress').hide();
                setTimeout(function () {
                    document.getElementById("content").innerHTML = xhr.responseText;
                },5000);

                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; font-size: 14px; text-align: center;width: 100%'>successfull added, please wait updating...</span>";
            }

            $("#dropdownMenuButton").click();
            $("#msg").show();
            setTimeout(function () {
                $("#msg").hide();
            },5000);
        };
    };
    xhr.send(fd);
}

var id = 0;
var sal_purh = 00;
var role_out = 0;
$('#reconcileModal').on('show.bs.modal', function (e) {
    var data_str = $(e.relatedTarget).data('str');
    // alert(data_str);
    var array = data_str.split(",");
    var date = array[0];
    var account = array[1];
    var amount = array[2];
    var id = array[3];
    var day = array[4];
    var month = array[5];
    var year = array[6];

    $("#date_head").html(month+" "+day+", "+year);
    $("#ending_date").html(month.substr(0,3)+" "+year);
    $("#account").html(account);
    $("#reconcile_account").val(account);

    // Initialization
    $('#balance_date').datepicker({
        onSelect: function onSelect(fd, date) {
        }
    });
    // Access instance of plugin
    $('#balance_date').data('datepicker');
    $("#balance_date").val(date);

    if(amount !== "0")
        $("#balance_amount").val(amount);

    if(id !== "0")
        $("#reconcile_id").val(id);

});

$('#addTaxModal').on('show.bs.modal', function (e) {
    var data_str = $(e.relatedTarget).data('role');
    role_out = data_str;
});

//triggered when modal is about to be shown
$('#updateModal').on('show.bs.modal', function(e) {
    //get data-id attribute of the clicked element
    $(".progress").hide();
    var data_str = $(e.relatedTarget).data('str');
    var array = data_str.split(",");
    var name = document.getElementById("name");
    name.value = array[array.length - 11];
    var desc = document.getElementById("desc");
    desc.value = array[array.length - 9];
    var price = document.getElementById("price");
    price.value = array[array.length - 10];

    var buy = document.getElementById("buy");
    var sales = document.getElementById("sales");
    var tax_all = array[array.length - 7];

    var tax_str =  tax_all.split("&");
    $("#taxe").empty();
    for(var i=0;i<tax_str.length - 1;i++){
        $('#taxe').append('<option value="'+tax_str[i]+'">'+tax_str[i]+'</option>');
    }

    var x = array[array.length - 8];

    sal_purh = x;
    if(x.length < 2){
        sales.checked = false;
        $("#select_income").hide();
        if (x.charAt(0) == 1){
            buy.checked = true;
            $("#select_expense").show();
        }else {
            buy.checked = false;
            $("#select_expense").hide();
        }
    }
    else{
        if (x.charAt(0) == 1){
            sales.checked = true;
            $("#select_income").show();
        }else {
            sales.checked = false;
            $("#select_income").hide();
        }

        if (x.charAt(1) == 1){
            buy.checked = true;
            $("#select_expense").show();
        }else {
            buy.checked = false;
            $("#select_expense").hide();
        }
    }

    var expense_all = array[array.length - 2];
    if (expense_all.indexOf("&") > -1){
        var expense_str =  expense_all.split("&");
        $("#expense").empty();
        for(var i=0; i < expense_str.length - 1;i++){
            $('#expense').append('<option value="'+expense_str[i]+'">'+expense_str[i]+'</option>');
        }
    }
    else{
        $("#expense").empty();
        $('#expense').append('<option value="'+expense_all+'">'+expense_all+'</option>');
    }

    var income_all = array[array.length - 3];


    if (income_all.indexOf("&") > -1){
        var income_str =  income_all.split("&");
        $("#income").empty();
        for(var i=0;i < income_str.length;i++){
            $('#income').append('<option value="'+income_str[i]+'">'+income_str[i]+'</option>');
        }
    }
    else{
        $("#income").empty();
        $('#income').append('<option value="'+income_all+'">'+income_all+'</option>');
    }

    var income = array[array.length - 5];
    $("#income").val([""]).change();
    $("#income").val(income).change();

    var expenses = array[array.length - 4];
    $("#expense").val([""]).change();
    $("#expense").val(expenses).change();

    id = array[array.length - 1];
    var tax = array[array.length - 6];
    var tax_str =  tax.split("&");
    $("#taxe").val([""]).change();
    $("#taxe").val(tax_str).change();
});

//triggered when vendor modal is about to be shown
$('#updateModalVendor').on('show.bs.modal', function(e) {
    //get data-id attribute of the clicked element
    var data_str = $(e.relatedTarget).data('str');
    var array = data_str.split(",");
    var name1 = document.getElementById("vname");
    name1.value = array[array.length - 8];
    var email = document.getElementById("email");
    email.value = array[array.length - 7];
    var phone = document.getElementById("phone");
    phone.value = array[array.length - 6];
    var fname = document.getElementById("fname");
    fname.value = array[array.length - 5];
    var lname = document.getElementById("lname");
    lname.value = array[array.length - 4];
    $('#currency option[value="' +array[array.length - 3] +'"]').prop('selected', true).change();

    id = array[array.length - 2];
    role_out = array[array.length - 1];
    if (role_out == 1)
        $('#addVendorModal').html("UPDATE CUSTOMER");
    else $('#addVendorModal').html("UPDATE VENDOR");
});

function updateVendor() {
    var x = 1;
    $("#update_vendor_progress").show();
    $("#updateVendor").prop("disabled",true);
    var name = $('#vname').val();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var fname = $('#fname').val();
    var lname = $('#lname').val();
    var currency = $('#currency').val();
    var role= role_out;

    var http = new XMLHttpRequest();
    var url = "vendor/update";
    var params = "name="+name+"&email="+email+"&phone="+phone+"&fname="+fname+"&lname="+lname+"&currency="+currency+"&role="+role+"&id="+id;
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == "failed"){

                $("#errormsg_vendor_update").show();
                setTimeout(function () {
                    $("#errormsg_vendor_update").hide();
                },5000);
                $("#update_vendor_progress").hide();
                document.getElementById("errormsg_vendor_update").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
                $("#updateVendor").prop("disabled",false);
            }
            else if(http.responseText == "failed_"){
                setTimeout(function () {
                    $("#errormsg_vendor_update").hide();
                },5000);
                $("#errormsg_vendor_update").show();
                $("#update_vendor_progress").hide();
                document.getElementById("errormsg_").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
                $("#updateVendor").prop("disabled",false);
            }
            else{

                setTimeout(function () {
                    $("#errormsg_vendor_update").hide();
                    $("#updateVendor").prop("disabled",false);
                    $("#updateModalVendor .close").click();
                    $('#updateModalVendor').on('hidden.bs.modal', function () {
                        if (x == 1){
                            document.getElementById("content").innerHTML = http.responseText;
                            x = 0;
                        }
                    });
                },5000);

                $("#errormsg_vendor_update").show();
                $("#update_vendor_progress").hide();
                document.getElementById("errormsg_vendor_update").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";

            }
        }
    }
    http.send(params);
}


function updateProduct() {
    var a = 0;
    $(".progress").show();
    $("#updateProduct").prop("disabled",true);
    var name = $('#name').val();
    var desc = $('#desc').val();
    var price = $('#price').val();

    var taxes = [];
    $("[name='taxes[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            taxes.push($(this).val());
        }
    });

    var buy = 0;
    var sales = 0;
    var income = "";
    var expenses = "";

    if($('#buy').is(':checked')){
        buy = 1;
        expenses = $('#expense').val();
    }

    if($('#sales').is(':checked')){
        sales = 1;
        income = $('#income').val();
    }

    /*var taxes = [];
              $("[name='tax[]']").each(function() {
                  var str = $(this).val();
                  if (str.length > 0){
                      taxes.push($(this).val());
                  }
              });*/

    var role = $('#role').val();

    var http = new XMLHttpRequest();
    var url = "updateProducts";
    var params = "name="+name+"&desc="+desc+"&price="+price+"&taxes="+taxes+"&buy="+buy+"&sales="+sales+"&id="+id+"&role="+role+"&expense="+expenses+"&income="+income;
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $(".progress").hide();
                $("#errormsg").show();
                document.getElementById("errormsg").innerHTML = "<span style='color: red;font-size: 14px;text-align: center;width: 100%'>Opps!! failed to be updated</span>";
                $("#updateProduct").prop("disabled",false);
                setTimeout(function () {
                    $("#errormsg").hide();
                },3000);
            }
            else{
                $("#errormsg").show();
                document.getElementById("errormsg").innerHTML = "<span style='color: blueviolet; font-size: 14px; text-align: center;width: 100%'>successfull added, wait updating...</span>";
                $(".progress").hide();
                setTimeout(function () {
                    $("#updateProduct").prop("disabled",false);
                    $("#updateModal .close").click();
                    $("#errormsg").hide();
                    LoadContent('productsandservicespurchases/'+role);
                    /* a = 1;
                              $('#updateModal').on('hidden.bs.modal', function () {
                                  if (a == 1){
                                      document.getElementById("content").innerHTML = http.responseText;
                                      LoadContent('productsandservicespurchases/'+role);
                                      a = 0;
                                  }
                              });*/
                },3000);
            }
        }
    }
    http.send(params);
}

var tab_invoice = 0;
function LoadContent(str) {

    $("#pre_load").show();
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            index = 1;
            counter = 0;
            account_chart = 0;
            current_transaction = 0;
            tab_invoice = 0;
            var htmlContent = this.responseText;
            $("#content").html(htmlContent);
            $('#transaction_filter').hide();
            setTimeout(function () {
                $("#pre_load").hide();
                if (str =="addbills"){
                    addRowBill();
                }
                $("#msg").hide();
                $("#progress").hide();
                $('.js-example-basic-multiple').select2({
                    width: 'resolve',
                    selectOnClose: true,
                    placeholder: function(){
                        $(this).data('placeholder');
                    }
                });
                $(".js-example-basic-multiple").select2({ dropdownCssClass: "myFont" });
                // $('.js-example-basic-multiple').select2().data('select2').$dropdown.addClass('my-container');

                // Initialization
                $('#from').datepicker({
                    onSelect: function onSelect(fd, date) {
                        FilterBillByCurrency('1');
                    }
                });
                // Access instance of plugin
                $('#from').data('datepicker');
                // Initialization
                $('#end_report').datepicker({
                    onSelect: function onSelect(fd, date) {
                       $('#selectRange').val("Custom");
                       var to = $('#end_report').val();
                        LoadBalanceSheetReport(to);
                    }
                });
                // Access instance of plugin
                $('#end_report').data('datepicker');

                $('#to').datepicker({
                    onSelect: function onSelect(fd, date) {
                        FilterBillByCurrency('1');
                    }
                });
                // Access instance of plugin
                $('#to').data('datepicker');

                // Initialization
                $('#from_invoice').datepicker({
                    onSelect: function onSelect(fd, date) {
                        InvoiceDashBoard();
                    }
                });
                // Access instance of plugin
                $('#from_invoice').data('datepicker');
                // Initialization
                $('#to_invoice').datepicker({
                    onSelect: function onSelect(fd, date) {
                        InvoiceDashBoard();
                    }
                });
                $('#to_invoice').data('datepicker');

                // Initialization
                $('#from_reconcile').datepicker({
                    onSelect: function onSelect(fd, date) {
                        dateRange("","","Custom");
                    }
                });
                // Access instance of plugin
                $('#from_reconcile').data('datepicker');
                // Initialization
                $('#to_reconcile').datepicker({
                    onSelect: function onSelect(fd, date) {
                        dateRange("","","Custom");
                    }
                });
                $('#to_reconcile').data('datepicker');

                // Initialization
                $('#from_transaction').datepicker({
                    onSelect: function onSelect(fd, date) {
                        dateRange2("","","Custom");
                    }
                });
                // Access instance of plugin
                $('#from_transaction').data('datepicker');
                // Initialization
                $('#to_transaction').datepicker({
                    onSelect: function onSelect(fd, date) {
                        dateRange2("","","Custom");
                    }
                });
                $('#to_transaction').data('datepicker');

                // Initialization
                $('#from_report_profit_loss').datepicker({
                    onSelect: function onSelect(fd, date) {
                        LoadIncomeStatement();
                    }
                });
                // Access instance of plugin
                $('#from_report_profit_loss').data('datepicker');

                // Initialization
                $('#to_report_profit_loss').datepicker({
                    onSelect: function onSelect(fd, date) {

                    }
                });
                // Access instance of plugin
                $('#to_report_profit_loss').data('datepicker');


                // Initialization
                $('#from_report_cash_flow').datepicker({
                    onSelect: function onSelect(fd, date) {
                        Loadcashflowreport();
                    }
                });
                // Access instance of plugin
                $('#from_report_cash_flow').data('datepicker');

                // Initialization
                $('#to_report_cash_flow').datepicker({
                    onSelect: function onSelect(fd, date) {
                        Loadcashflowreport();
                    }
                });
                // Access instance of plugin
                $('#to_report_cash_flow').data('datepicker');


                $('#due_date').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                $('#due_date').data('datepicker');
                $('#invoice_date').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                $('#invoice_date').data('datepicker');

                // Initialization
                $('#pdate').datepicker();
                // Access instance of plugin
                $('#pdate').data('datepicker');

                // To style only <select>s with the selectpicker class
                $('.selectpicker').selectpicker();

                // Get the element with id="defaultOpen" and click on it
                document.getElementById("openDefault").click();
            },1000);
        }
    };
    xmlhttp.open("GET", str, true);
    xmlhttp.send();
}

function LoadContentLedger(str) {
    var from = $("#from_report_profit_loss").val();
    var to = $("#to_report_profit_loss").val();
    var str = str+"/"+from+"/"+to;
    $("#pre_load").show();
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var htmlContent = this.responseText;
            $("#content").html(htmlContent);
            $('#transaction_filter').hide();
            setTimeout(function () {
                $("#pre_load").hide();

                $('.js-example-basic-multiple').select2({
                    width: 'resolve',
                    selectOnClose: true,
                    placeholder: function(){
                        $(this).data('placeholder');
                    }
                });
                $('.js-example-basic-multiple').select2().data('select2').$dropdown.addClass('my-container');

                // Initialization
                $('#from_ledger').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                // Access instance of plugin
                $('#from_ledger').data('datepicker');

                // Initialization
                $('#to_ledger').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                // Access instance of plugin
                $('#to_ledger').data('datepicker');

            },1000);
        }
    };
    xmlhttp.open("GET", str, true);
    xmlhttp.send();
}

function InvoiceDashBoard() {

    var customer = $('#customer').val();
    var from = $('#from_invoice').val();
    var to = $('#to_invoice').val();
    var status = $('#status').val();

    if(customer.length < 1){
        customer = -1;
    }
    if(from.length < 1) {
        from = 0;
    }

    if(to.length < 1){
        to = 0;
    }

    if(status.length < 1) {
        status = 0;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $("#tab").html(this.responseText);
            $('.js-example-basic-multiple').select2({
                width: 'resolve'
            });

            // Initialization
            $('#from1').datepicker({
                onSelect: function onSelect(fd, date) {
                    InvoiceDashBoard();
                }
            });
            // Access instance of plugin
            $('#from1').data('datepicker');
            // Initialization
            $('#to1').datepicker({
                onSelect: function onSelect(fd, date) {
                    InvoiceDashBoard();
                }
            });
            // Access instance of plugin
            $('#to1').data('datepicker');

            // Get the element with id="defaultOpen" and click on it
            switch(tab_invoice){
                case 0:
                    document.getElementById("openDefault").click();
                    break;
                case 1:
                    document.getElementById("unpaid_btn").click();
                    break;
                case 2:
                    document.getElementById("draft_btn").click();
                    break;
                default:
                    document.getElementById("openDefault").click();
                    break;
            }

        }
    };
    xmlhttp.open("GET", "invoice_dashboard/"+customer+"/"+from+"/"+to+"/"+status, true);
    xmlhttp.send();
}

function FilterBillByCurrency(id) {
    var str = $('#currency').val();
    var from = $('#from').val();
    var to = $('#to').val();
    if (str.length < 1){
        str = 0;
    }

    if (from.length < 1){
        from = 0;
    }
    else{from = from.replace(/\//g,"-");}

    if (to.length < 1){
        to = 0;
    }
    else{to = to.replace(/\//g,"-");}

    var url = "filterbill/"+str+"/"+from+"/"+to+"/"+id;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("myTable").innerHTML = this.responseText;
            $('.js-example-basic-multiple').select2({
                width: 'resolve'
            });

            // Initialization
            $('#from').datepicker();
            // Access instance of plugin
            $('#from').data('datepicker');
            // Initialization
            $('#to').datepicker();
            // Access instance of plugin
            $('#to').data('datepicker');
            index = 1;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function insertVendor(role) {
    $(".progress").show();
    var name = $('#name').val();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var lname = $('#lname').val();
    var fname = $('#fname').val();
    var currency = $('#currency').val();

    var http = new XMLHttpRequest();
    var url = "addVendor";
    var params = "name="+name+"&email="+email+"&phone="+phone+"&first_name="+fname+"&last_name="+lname+"&currency="+currency+"&role="+role;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            $(".progress").hide();
            $("#msg").show();
            setTimeout(function () {
                $("#msg").hide();
            },5000);

            if(http.responseText == 0){
                document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;text-align: center;width: 100%'>failed to be added</span>";
            }
            else{
                if ((http.responseText).indexOf("&$") > 0){
                    var ar =  (http.responseText).split("&$");
                    document.getElementById("content").innerHTML = ar[1];
                    if(ar[0] == 0){
                        document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;text-align: center;width: 100%'>failed to be added</span>";
                    }
                    else{
                        document.getElementById("msg").innerHTML = "<span style='color: blueviolet; font-size: 14px; text-align: center;width: 100%'>successfull added</span>";
                    }
                }
                else{
                    document.getElementById("content").innerHTML = http.responseText;
                    document.getElementById("msg").innerHTML = "<span style='color: blueviolet; font-size: 14px; text-align: center;width: 100%'>successfull added</span>";
                }
            }
            $("#top").click();
            $('.js-example-basic-multiple').select2({
                width: 'resolve'
            });
        }
    }
    http.send(params);
}

function insertProduct() {

    $(".progress").show();
    $("#product_send").prop("disabled",true);
    var name = $('#name').val();
    var desc = $('#desc').val();
    var price = $('#price').val();

    var tax = $('#htax').val();
    var buy = 0;
    var sales = 0;
    var income = "";
    var expenses = "";

    if($('#buy').is(':checked')){
        buy = 1;
        expenses = $('#expense').val();
    }

    if($('#sales').is(':checked')){
        sales = 1;
        income = $('#income').val();
    }

    var taxes = [];
    $("[name='tax[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            taxes.push($(this).val());
        }
    });


    var http = new XMLHttpRequest();
    var url = "addproductsandservicespurchases";
    var params = "name="+name+"&desc="+desc+"&price="+price+"&taxes="+taxes+"&buy="+buy+"&sales="+sales+"&tax="+tax+"&expense="+expenses+"&income="+income;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $(".progress").hide();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
                $("#msg").show();
                document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>failed to be added, all field with * required</span>";
                $("#product_send").prop("disabled",false);
            }
            else{
                if (http.responseText.indexOf("&$") > 0){
                    $(".progress").hide();
                    var ar = http.responseText.split("&$");
                    document.getElementById("content").innerHTML = ar[1];
                    document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>failed to be added, all field with * required</span>";
                    $("#msg").show();
                    setTimeout(function () {
                        $("#msg").hide();
                    },5000);
                    $("#product_send").prop("disabled",false);
                }
                else{
                    $(".progress").hide();
                    document.getElementById("content").innerHTML = http.responseText;
                    document.getElementById("msg").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center;font-size: 14px'>successfull added, wait update...</span>";
                    $("#msg").show();
                    setTimeout(function () {
                        $("#msg").hide();
                        var link = $('#back');
                        link.click();
                    },5000);
                    $("#product_send").prop("disabled",false);
                }

            }

            $('.js-example-basic-multiple').select2({
                width: 'resolve'
            });
        }
    }
    http.send(params);

}

/*function insertProduct() {

            $(".progress").show();
            $("#product_send").prop("disabled",true);
            var name = $('#name').val();
            var desc = $('#desc').val();
            var price = $('#price').val();

            var taxes = $('#tax').val();
            var buy = 0;
            var sales = 0;

            if($('#buy').is(':checked')){
                buy = 1;
            }

            if($('#sales').is(':checked')){
                sales = 1;
            }

            var data = {'name' : name, 'desc' : desc, 'price' : price, 'taxes[]' : taxes, 'buy' : buy, 'sales' : sales};
            var url = "addproductsandservicespurchases";

             $.ajax({
                 type: "POST",
                 url: url,
                 data: data,
                 success: function (data) {
                     $(".progress").hide();
                     if (data.toLocaleString("0")){
                         $(".progress").hide();
                         setTimeout(function () {
                             $("#msg").hide();
                         },5000);
                         $("#msg").show();
                         document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>failed to be added, all field with * required</span>";
                         $("#product_send").prop("disabled",false);
                     }
                     else{
                         document.getElementById("content").innerHTML = data;
                         document.getElementById("msg").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center;font-size: 14px'>successfull added</span>";
                         $("#msg").show();
                         setTimeout(function () {
                             $("#msg").hide();
                         },5000);
                         $("#product_send").prop("disabled",false);
                     }
                 },
                 error: function (data) {
                     $(".progress").hide();
                     setTimeout(function () {
                         $("#msg").hide();
                     },5000);
                     $("#msg").show();
                     document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>failed to be added, all field with * required</span>";
                     $("#product_send").prop("disabled",false);
                 }
             });
        }*/

function DeleteInvoice(bill_no) {
    $(".progress").show();
    var url = "deleteinvoice/"+bill_no;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText == 0){
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; width: 100%;text-align: center;font-size: 14px'>failed to be deleted</span>";
            }
            else{
                document.getElementById("content").innerHTML = this.responseText;
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; width: 100%;text-align: center;font-size: 14px'>successfull delete</span>";
                document.getElementById("openDefault").click();
            }
            $(".progress").hide();
            $("#msg").show();
            setTimeout(function () {
                $("#msg").hide();
            },5000);
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function deleteData(id,role){
    var url = "delete/"+id+"/"+role;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function deleteVendor(id,task){
    $("#progress").show();
    var url = "vendor/delete/"+id+"/"+task;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $("#progress").hide();
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function addInput(divName) {
    var choices = ["one", "two"];
    var newDiv = document.createElement('div');
    newDiv.className ="col-sm-12";
    newDiv.style.marginTop = 10 +"px";
    var selectHTML = "";
    selectHTML="<select class='form-control'>";
    for(i = 0; i < choices.length; i = i + 1) {
        selectHTML += "<option value='" + choices[i] + "'>" + choices[i] + "</option>";
    }
    selectHTML += "</select>";
    newDiv.innerHTML = selectHTML;
    document.getElementById(divName).appendChild(newDiv);
}

function isVisible(elem) {
    return elem.offsetWidth > 0 || elem.offsetHeight > 0;
}


var index = 1;
function addRow() {
    index++;
    var tr = document.getElementById("tr"+index);
    tr.style.visibility = "visible";
}

function addRowBill() {

    var url = "getProduct";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var id = "tr"+index;
            var tr = '<tr id="'+id+'">';
            var newRow = $(tr);
            var cols = "";

            var arr = (this.responseText).split("&");

            var JSONObject = JSON.parse(arr[0]);
            var selectHtml="<option value=''></option>";
            for (var key in JSONObject) {
                if (JSONObject.hasOwnProperty(key)) {
                    selectHtml += ("<option value='"+JSONObject[key]['name']+"'>" + JSONObject[key]['name'] + "</option>");
                }
            }

            var JSONObject = JSON.parse(arr[1]);
            var selectHtml_="<option value=''></option>";
            for (var key in JSONObject) {
                if (JSONObject.hasOwnProperty(key)) {
                    selectHtml_ += ("<option value='"+JSONObject[key]['id']+"'>" + JSONObject[key]['account_name'] + "</option>");
                }
            }

            cols += ' <td width="15%"><select class="form-control js-example-basic-multiple" data-placeholder="product" name="product[]" id="product" onchange="getProductData(this.value,'+index+',0)">'+selectHtml+'</select></td>';


            cols += '<td width="20%"><select class="form-control js-example-basic-multiple" name="account[]" id="account" data-placeholder="expenses"><option value="" ></option><optgroup label="EXPENSES">'+selectHtml_+'</optgroup></select></td>';

            cols += '<td width="15%"><textarea type="text" class="form-control" name="desc[]" id="desc'+index+'" placeholder="Enter description" style="font-size: 14px"></textarea></td></td>';

            cols += '<td width="10%"> <input type="number" class="form-control"  name="quantity[]" id="quantity'+index+'" min="1" value="1"></td>';

            cols += '<td width="10%"> <input type="number" class="form-control" name="price[]" id="price'+index+'" min="0" value="0"></td>';

            cols += ' <td width="10%"><select class="form-control js-example-basic-multiple" id="tax'+index+'" name="tax[]" multiple="multiple" value=""></select></td>';

            cols += '<td width="10%"><span style="float: right;" id="t'+index+'">0</span><input type="hidden" id="init_total'+index+'" value="0"></td>';

            cols += '<td><button type="button" class="btn btn-default" style="background-color: transparent" onclick=deleteRowBill("'+id+'")><i class="fa fa-trash" aria-hidden="true"></i></button></td>';

            newRow.append(cols);
            $("#dataTable").append(newRow);
            $('.js-example-basic-multiple').select2({
                width: 'resolve',
                selectOnClose: true,
                placeholder: function(){
                    $(this).data('placeholder');
                }
            });
            $(".js-example-basic-multiple").select2({ dropdownCssClass: "myFont" });
            index++;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function deleteRowBill(id) {
    $('#'+id).remove();
    var total_s = 0;
    var json_s = [];
    var ind =  index;
    index--;
    for(var z=1;z <= ind;z++){
        if ($("#tr"+z).length > 0){
            var money = $("#init_total"+z).val();
            if (money === undefined || money === null) {
                continue;
            }
            total_s = parseInt(total_s) + parseInt(money);
            var taxs = $("#tax"+z).val();
            for (var u=0;u< taxs.length;u++){
                var percent=taxs[u].substring(taxs[u].indexOf("(")+1,taxs[u].lastIndexOf(")"));
                var p = (percent/100) * money;
                json_s.push({
                    "name": taxs[u],
                    "percent": p,
                    "money" : money
                });

            }
        }
    }

    $("#tax_space").empty();
    var tax_total_s = 0;
    for (var key in json_s) {
        if (json_s.hasOwnProperty(key)) {
            $("#tax_space").append('<span>'+json_s[key].name+':</span><span style="float: right;">'+json_s[key].percent+'('+json_s[key].money+')</span><br>');
        }
        tax_total_s = tax_total_s + json_s[key].percent;
    }
    $("#subtotal").html(total_s);
    $("#total").html(total_s +tax_total_s);
}

function getProductData(val,s,l) {
    var p = $('#price'+s).val();
    var q = $('#quantity'+s).val();
    var desc = $('#desc'+s).val();
    var tax = $('#tax'+s).val();
    var account = $('#account'+s).val();

    var http = new XMLHttpRequest();
    var url = "getProductData";
    var params = "id="+val+"&q="+q+"&p="+p+"&s="+s+"&desc="+desc+"&tax="+tax+"&account="+account;

    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {

            var tr_ = document.getElementById("tr"+s);
            if(isVisible(tr_))
            {
                tr_.innerHTML = this.responseText;
                var total = 0;
                var json = [];

                for(var i=1;i < index;i++){

                    if ($("#tr"+i).is(':visible')){

                        var money = document.getElementById("init_total"+i).value;
                        if (money === undefined || money === null) {
                            continue;
                        }

                        total = parseInt(total) + parseInt(money);
                        var taxs = $("#tax"+i).val();

                        for (var z=0;z< taxs.length;z++){
                            var percent=taxs[z].substring(taxs[z].indexOf("(")+1,taxs[z].lastIndexOf(")"));
                            var p = (percent/100) * money;
                            json.push({
                                "name": taxs[z],
                                "percent": p,
                                "money" : money
                            });

                        }
                    }

                }

                $("#tax_space").empty();
                var tax_total = 0;
                for (var key in json) {
                    if (json.hasOwnProperty(key)) {
                        $("#tax_space").append('<span>'+json[key].name+':</span><span style="float: right;">'+format1(json[key].percent, "Tsh")+'('+json[key].money+')</span><br>');
                    }
                    tax_total = tax_total + json[key].percent;
                }
                document.getElementById("subtotal").innerHTML = format1(total,"Tsh ");
                document.getElementById("total").innerHTML = format1(total+tax_total, "Tsh");
            }
            $('.js-example-basic-multiple').select2({
                width: 'resolve',
                selectOnClose: true,
                placeholder: function(){
                    $(this).data('placeholder');
                }
            });
            $(".js-example-basic-multiple").select2({ dropdownCssClass: "myFont" });
        }
    }
    http.send(params);
}

function getTaxUpdate(count) {
    var total = 0;
    var json = [];

    for(var i=1;i <= count;i++){

        if ($("#tr"+i).is(':visible')){

            var money = document.getElementById("init_total"+i).value;
            if (money === undefined || money === null) {
                continue;
            }

            total = parseInt(total) + parseInt(money);
            var taxs = $("#tax"+i).val();

            for (var z=0;z< taxs.length;z++){
                var percent=taxs[z].substring(taxs[z].indexOf("(")+1,taxs[z].lastIndexOf(")"));
                var p = (percent/100) * money;
                json.push({
                    "name": taxs[z],
                    "percent": p,
                    "money" : money
                });

            }
        }

    }

    $("#tax_space").empty();
    var tax_total = 0;
    for (var key in json) {
        if (json.hasOwnProperty(key)) {
            $("#tax_space").append('<span>'+json[key].name+':</span><span style="float: right;">'+format1(json[key].percent, "Tsh")+'('+json[key].money+')</span><br>');
        }
        tax_total = tax_total + json[key].percent;
    }
    document.getElementById("subtotal").innerHTML = total;
    document.getElementById("total").innerHTML = format1(total+tax_total, "Tsh");
}

function deleteRow(s) {
    index--;
    var tr = document.getElementById("tr"+s);
    tr.style.visibility = "collapse";
    document.getElementById("desc"+s).value = "";
    document.getElementById("quantity"+s).value = "";
    document.getElementById("price"+s).value = "";
    document.getElementById("t"+s).innerHTML = 0;

    var total_s = 0;
    var json_s = [];
    for(var z=1;z <= index;z++){
        var tr = document.getElementById("tr"+z);
        if (isVisible(tr)){
            var money = document.getElementById("init_total"+z).value;
            if (money === undefined || money === null) {
                continue;
            }
            total_s = parseInt(total_s) + parseInt(money);
            var taxs = $("#tax"+z).val();
            for (var u=0;u< taxs.length;u++){
                var percent=taxs[u].substring(taxs[u].indexOf("(")+1,taxs[u].lastIndexOf(")"));
                var p = (percent/100) * money;
                json_s.push({
                    "name": taxs[u],
                    "percent": p,
                    "money" : money
                });

            }
        }
    }

    $("#tax_space").empty();
    var tax_total_s = 0;
    for (var key in json_s) {
        if (json_s.hasOwnProperty(key)) {
            $("#tax_space").append('<span>'+json_s[key].name+':</span><span style="float: right;">'+format1(json_s[key].percent, "Tsh")+'('+json_s[key].money+')</span><br>');
        }
        tax_total_s = tax_total_s + json_s[key].percent;
    }
    document.getElementById("subtotal").innerHTML = total_s;
    document.getElementById("total").innerHTML = format1(total_s +tax_total_s, "Tsh");

}


function addTax() {
    $("#tax_progress").show();
    $("#tax_errormsg").hide();
    var name = $('#tname').val();
    var desc = $('#tdesc').val();
    var abbr = $('#abbr').val();
    var rate = $('#rate').val();
    var num = $('#num').val();
    var role = role_out;

    var rec = 0;
    var compound = 0;
    var invoice = 0;

    if($('#invoice').is(':checked')){
        invoice = 1;
    }

    if($('#compound').is(':checked')){
        compound = 1;
    }

    if($('#recov').is(':checked')){
        rec = 1;
    }

    var http = new XMLHttpRequest();
    var url = "addTax";
    var params = "name="+name+"&abbr="+abbr+"&desc="+desc+"&rate="+rate+"&num="+num+"&invoice="+invoice+"&comp="+compound+"&recov="+rec;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            $("#containerDiv").animate({ scrollTop: 0 }, "fast");
            if (http.responseText == 1){

                setTimeout(function () {
                    $("#tax_errormsg").hide();
                    $("#addTaxModal .close").click();
                    LoadContent('addproductsandservicespurchases/'+role);
                },5000);

                $('#tname').val('');
                $('#tdesc').val('');
                $('#abbr').val('');
                $('#rate').val('');
                $('#num').val('');
                $("#tax_errormsg").show();
                $("#tax_progress").hide();
                document.getElementById("tax_errormsg").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";
            }
            else {
                $("#tax_errormsg").show();
                setTimeout(function () {
                    $("#tax_errormsg").hide();
                },5000);
                $("#tax_progress").hide();
                document.getElementById("tax_errormsg").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
                $("#updateVendor").prop("disabled",false);
            }
        }
    }
    http.send(params);

}

function addBill() {

    $(".progress").show();
    $("#msg").hide();
    $("#addrowbtn").prop("disabled",true);
    $("#addbillbtn").prop("disabled",true);
    var vendor = $('#vendor').val();
    var currency = $('#currency').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var po = $('#po').val();
    var num = $('#bill_num').val();
    var notes = $('#notes').val();

    var products = [];
    $("[name='product[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            products.push(str);
        }
    });

    var quantitys = [];
    $("[name='quantity[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            quantitys.push(str);
        }
    });

    var accounts = [];
    $("[name='account[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            accounts.push(str);
        }
    });

    var descs = [];
    var in_dec = 0;
    $("[name='desc[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            descs.push(str);
        }
        else{
            if (in_dec < quantitys.length){
                descs.push("");
            }
        }
        in_dec++;
    });

    var prices = [];
    $("[name='price[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            prices.push(str);
        }
    });

    var taxs = [];
    var in_taxs = 0;
    var count = index;
    for (var i=1;i <= count;i++){
        if ($("[name='tax"+i+"[]']").length > 0){
            var str =  $("[name='tax"+i+"[]']").val();
            if (str.length > 0){
                if (str.length > 1){
                    var str_="";
                    for(var z=0;z<str.length;z++){
                        if(z == 0) {str_ = str[z];}
                        else { str_ = str_+"$"+str[z];}
                    }
                    taxs.push(str_);
                }
                else{
                    taxs.push(str);
                }

            }
            else {
                if (i < count){
                    taxs.push("");
                }
            }
        }
    }

    var http = new XMLHttpRequest();
    var url = "addbillitem";
    var params = "vendor="+vendor+"&currency="+currency+"&from="+from+"&to="+to+"&po="+po+"&num="+num+"&notes="+notes+"&products="+products+"&accounts="+accounts+"&desc="+descs+"&quantity="+quantitys+"&prices="+prices+"&taxs="+taxs;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            $("#content").animate({ scrollTop: 0 }, "fast");
            if (http.responseText =="success"){
                $(".progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center;font-size: 14px'>successfull added, please wait</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                    var link = $('#back');
                    link.click();
                },5000);
            }
            else if(http.responseText == "failed_"){
                $(".progress").hide();
                $("#addrowbtn").prop("disabled",false);
                $("#addbillbtn").prop("disabled",false);
                document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>failed to be added, all field with * required</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
            else if(http.responseText == "_failed_"){
                $(".progress").hide();
                $("#addrowbtn").prop("disabled",false);
                $("#addbillbtn").prop("disabled",false);
                document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>add at least one product in your bill</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
            else{
                $(".progress").hide();
                $("#addrowbtn").prop("disabled",false);
                $("#addbillbtn").prop("disabled",false);
                document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>failed to be added</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
        }
    }
    http.send(params);

}
function EditBill() {

    $(".progress").show();
    $("#msg").hide();
    $("#addrowbtn").prop("disabled",true);
    $("#editbillbtn").prop("disabled",true);
    var vendor = $('#vendor').val();
    var currency = $('#currency').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var po = $('#po').val();
    var num = $('#bill_num').val();
    var notes = $('#notes').val();



    var products = [];
    $("[name='product[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            products.push($(this).val());
        }
    });

    var quantitys = [];
    $("[name='quantity[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            quantitys.push($(this).val());
        }
    });

    var accounts = [];
    $("[name='account[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            accounts.push($(this).val());
        }
    });

    var descs = [];
    var in_dec = 0;
    $("[name='desc[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            descs.push($(this).val());
        }
        else{
            if (in_dec < quantitys.length){
                descs.push("");
            }
        }
        in_dec++;
    });

    var prices = [];
    $("[name='price[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            prices.push($(this).val());
        }
    });

    var taxs = [];
    var in_taxs = 0;
    var count = index;
    for (var i=1;i <= count;i++){
        if ($("[name='tax"+i+"[]']").length > 0){
            var str =  $("[name='tax"+i+"[]']").val();
            if (str.length > 0){
                if (str.length > 1){
                    var str_="";
                    for(var z=0;z<str.length;z++){
                        if(z == 0) {str_ = str[z];}
                        else { str_ = str_+"$"+str[z];}
                    }
                    taxs.push(str_);
                }
                else{
                    taxs.push(str);
                }

            }
            else {
                if (i < count){
                    taxs.push("");
                }
            }
        }
    }


    /*$("[name='tax[]']").each(function() {
                  var str = $(this).val();
                  if (str.length > 0){
                      taxs.push($(this).val());
                  }
                  else {
                      if (in_taxs < quantitys.length){
                          taxs.push("");
                      }
                  }
                  in_taxs++;
              });*/

    var http = new XMLHttpRequest();
    var url = "editbillitems";
    var params = "vendor="+vendor+"&currency="+currency+"&from="+from+"&to="+to+"&po="+po+"&num="+num+"&notes="+notes+"&products="+products+"&accounts="+accounts+"&desc="+descs+"&quantity="+quantitys+"&prices="+prices+"&taxs="+taxs;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {
        $("#content").animate({ scrollTop: 0 }, "fast");
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == "success"){
                $(".progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center;font-size: 14px'>successfull added, please wait</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                    var link = $('#back');
                    link.click();
                },5000);
            }
            else{
                $(".progress").hide();
                $("#addrowbtn").prop("disabled",false);
                $("#editbillbtn").prop("disabled",false);
                document.getElementById("msg").innerHTML = "<span style='color: red;width: 100%;text-align: center;font-size: 14px'>failed to be added</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
        }
    }
    http.send(params);

}
function editbills(id,l,id_) {
    var http = new XMLHttpRequest();
    var url = "editbillitem";
    var params = "id="+id;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    index = l;
    http.onreadystatechange = function() {
        $("#content").animate({ scrollTop: 0 }, "fast");
        if(http.readyState == 4 && http.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
            $('.js-example-basic-multiple').select2({
                width: 'resolve'
            });

            // Initialization
            $('#from').datepicker();
            // Access instance of plugin
            $('#from').data('datepicker');
            // Initialization
            $('#to').datepicker();
            // Access instance of plugin
            $('#to').data('datepicker');

            var total_s = 0;
            var json_s = [];
            index = l + 1;
            for(var z=1;z <= l;z++){

                var tr = document.getElementById("tr"+z);
                if (isVisible(tr)){
                    var money = document.getElementById("init_total"+z).value;
                    if (money === undefined || money === null) {
                        continue;
                    }
                    total_s = parseInt(total_s) + parseInt(money);
                    var taxs = $("#tax"+z).val();
                    for (var u=0;u< taxs.length;u++){
                        var percent=taxs[u].substring(taxs[u].indexOf("(")+1,taxs[u].lastIndexOf(")"));
                        var p = (percent/100) * money;
                        json_s.push({
                            "name": taxs[u],
                            "percent": p,
                            "money" : money
                        });

                    }
                }
            }

            $("#tax_space").empty();
            var tax_total_s = 0;
            for (var key in json_s) {
                if (json_s.hasOwnProperty(key)) {
                    $("#tax_space").append('<span>'+json_s[key].name+':</span><span style="float: right;">'+json_s[key].percent+'('+json_s[key].money+')</span><br>');
                }
                tax_total_s = tax_total_s + json_s[key].percent;
            }
            document.getElementById("subtotal").innerHTML = total_s;
            document.getElementById("total").innerHTML = format1(total_s +tax_total_s, $("#cur").val());
        }
    }
    http.send(params);

}
function deletebill(id) {
    var url = "deletebill/"+id;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}
function getCurrencyData() {
    var id = $('#vendor').val();

    var http = new XMLHttpRequest();
    var url = "getcurrency";
    var params = "id="+id;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            document.getElementById("vendor_data_currency").innerHTML = this.responseText;
            $('.js-example-basic-multiple').select2({
                width: 'resolve'
            });
        }
    }
    http.send(params);
}
function unique(obj){
    var uniques=[];
    var stringify={};
    for(var i=0;i<obj.length;i++){
        var keys=Object.keys(obj[i]);
        keys.sort(function(a,b) {return a-b});
        var str='';
        for(var j=0;j<keys.length;j++){
            str+= JSON.stringify(keys[j]);
            str+= JSON.stringify(obj[i][keys[j]]);
        }
        if(!stringify.hasOwnProperty(str)){
            uniques.push(obj[i]);
            stringify[str]=true;
        }
    }
    return uniques;
}
function addCustomerSelect() {
    $('#mybtn').hide();
    $('#selectCustomerBillTo').show();
    $('#bill_to').hide();
    document.getElementById("billToSelectCustomer").onchange = function () {
        $('#mybtn').hide();
        $('#selectCustomerBillTo').hide();
        $('#bill_to').show();
        addCustomerBIllTo(this.value);
    };
}

function addCustomerBIllTo(str) {
    var url = "addCustomerbillTo/"+str;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("bill_to").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

var item_deleted = 0;
$(document).ready(function(){

    $("body").on("click","#btn",function(){

        $("#invoiceModal").modal("show");

        $('.modal-backdrop').appendTo('#myTable');

        $('body').removeClass("modal-open");

        $('body').css("padding-right","");
    });

    $("body").on("click","#btn_preview_",function(){
        $('#preview').remove();
        $('#invoicefill').show();

    });

});

function invoicePreview(str){

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $('#content').prepend(this.responseText);

            var title = $('#invoice_head').val();
            $('#title').html(title);

            var company = $('#company').html();
            $('#company_prev').html(company);

            var location = $('#location').html();
            $('#location_prev').html(location);

            var $i = $( '#invoice_upload_logo' ),
                input = $i[0];
            if ( input.files && input.files[0] ) {
                var file = input.files[0];
                var fr = new FileReader();
                fr.onload = function () {
                    $('#invoice_logo').attr('src', fr.result);
                };
                fr.readAsDataURL( file );
            }
            var summary = $('#summ').val();
            if (summary.length < 1){ $('#summary').hide();}
            else{$('#summary').show();$('#summary').html(summary);}
            var num = $('#invoiceNumber').val();
            $('#invoice_num').html(num);


            var nd = $('#invoice_date').val();
            $('#invoiceDate').html(nd);

            var pd = $('#due_date').val();
            $('#payment_due').html(pd);

            var due_amount = $('#second_total').html();
            $('#amount_due').html(due_amount);

            var name = $('#bill_to_bus_name').html();
            $('#name_in_prev').html(name);

            var email = $('#bill_to_email').html();
            $('#email_in_prev').html(email);

            var fname = $('#bill_to_name').html();
            $('#full_name').html(fname);

            var notes = $('#notes').val();
            if (notes.length < 1) $('#notes_prev_pare').hide();
            else{$('#notes_prev_pare').show();$('#notes_prev').html("<small>"+notes+"</small>");}

            var rowCount = $("[name='product[]']").length + item_deleted;
            //$('#myTable_in >tbody >tr').length;// produce x
            var i = 0;
            var subtotal_t = 0;
            var tax_t = 0;


            while(i <= rowCount){
                var product = $('#product'+i).html();
                var desc = $('#desc'+i).val();
                var quantity = $('#quantity'+i).val();
                var price = $('#price'+i).val();
                var subtotal = $('#subtotal'+i).val();

                if (product != undefined && desc != undefined && quantity != undefined && price != undefined && subtotal != undefined){

                    var myNumeral_subtotal_t = numeral(subtotal_t);
                    var myNumeral_subtotal = numeral(subtotal);

                    var subtotal_t = myNumeral_subtotal.value() + myNumeral_subtotal_t.value();
                    var tr = '<tr id="tr_prev'+counter+'">';
                    var newRow = $(tr);
                    var cols = "";

                    cols += '<td>'+product+'</td>';
                    cols += '<td>'+desc+'</td>';
                    cols += '<td>'+quantity+'</td>';
                    cols += '<td>'+format1(Number(price),"")+'</td>';
                    cols += '<td style="text-align: right;">'+format1(subtotal_t,"")+'</td>';

                    newRow.append(cols);
                    $("#myTable_prev").append(newRow);
                    var tax = $('#tax_'+i).val();
                    if(tax.indexOf("_") > -1){
                        tax = tax.replace(/&/g,",");
                    }
                    if (tax.length > 0){
                        if (tax.indexOf("&") > -1 ){
                            var taxes = tax.split("&");
                            var x = 0;

                            while (x < taxes.length){
                                if ($('#trs_'+i+"_"+ x).length > 0){
                                    var tr_ = '<tr id="tr_r'+(x+1)+'">';
                                    var newRow_ = $(tr_);
                                    var cols_ = "";
                                    var percent=taxes[x].substring(taxes[x].indexOf("(")+1,taxes[x].lastIndexOf(")"));
                                    var p = (percent/100) * (price* quantity);
                                    tax_t = parseInt(tax_t) + parseInt(p);
                                    cols_ += '<td colspan="3">&nbsp;</td>';
                                    if(tax.indexOf("_") > 1)
                                        cols_ += '<td colspan="1" style="background-color: lightblue;text-align: left">'+taxes[x].substring(0,taxes[x].indexOf("_"))+'</td>';
                                    else
                                        cols_ += '<td colspan="1" style="background-color: lightblue;text-align: left">'+taxes[x]+'</td>';
                                    cols_ += '<td style="text-align: right;background-color: lightblue;">'+format1(p,"")+'</td>';
                                    if(p > 0){
                                        newRow_.append(cols_);
                                        $("#myTable_prev").append(newRow_);
                                    }

                                }
                                else{
                                    var tr_ = '<tr id="tr_r'+(x)+'">';
                                    var newRow_ = $(tr_);
                                    var cols_ = "";
                                    var percent=taxes[x].substring(taxes[x].indexOf("(")+1,taxes[x].lastIndexOf(")"));
                                    var p = (percent/100) * (price* quantity);
                                    tax_t = parseInt(tax_t) + parseInt(p);
                                    cols_ += '<td colspan="3">&nbsp;</td>';
                                    if(tax.indexOf("_") > 1)
                                        cols_ += '<td colspan="1" style="background-color: lightblue;text-align: left">'+taxes[x].substring(0,taxes[x].indexOf("_"))+'</td>';
                                    else
                                        cols_ += '<td colspan="1" style="background-color: lightblue;text-align: left">'+taxes[x]+'</td>';
                                    cols_ += '<td style="text-align: right;background-color: lightblue;">'+format1(p,"")+'</td>';
                                    if(p > 0){
                                        newRow_.append(cols_);
                                        $("#myTable_prev").append(newRow_);
                                    }

                                }
                                x++;
                            }
                        }
                        else if(tax.indexOf(",") > -1){
                            var taxes = tax.split(",");
                            var x = 0;
                            while (x < taxes.length){
                                if ($('#trs_'+i+"_"+ x).length > 0 && taxes[x].indexOf(product) > -1){
                                    var tr_ = '<tr id="tr_r'+(x+1)+'">';
                                    var newRow_ = $(tr_);
                                    var cols_ = "";
                                    var percent=taxes[x].substring(taxes[x].indexOf("(")+1,taxes[x].lastIndexOf(")"));
                                    var p = (percent/100) * (price* quantity);
                                    var item_product = taxes[x].substring(taxes[x].indexOf("_")+1)
                                    if(item_product.indexOf(product) > -1){
                                        tax_t = parseInt(tax_t) + parseInt(p);
                                        cols_ += '<td colspan="3">&nbsp;</td>';
                                        cols_ += '<td colspan="1" style="background-color: lightblue;text-align: left">'+taxes[x].substring(0,taxes[x].indexOf("_"))+'</td>';
                                        cols_ += '<td style="text-align: right;background-color: lightblue;">'+format1(p,"")+'</td>';
                                        newRow_.append(cols_);
                                        $("#myTable_prev").append(newRow_);
                                    }
                                }
                                else{
                                    var tr_ = '<tr id="tr_r'+x+'">';
                                    var newRow_ = $(tr_);
                                    var cols_ = "";
                                    var percent=taxes[x].substring(taxes[x].indexOf("(")+1,taxes[x].lastIndexOf(")"));
                                    var p = (percent/100) * (price* quantity);
                                    var item_product = taxes[x].substring(taxes[x].indexOf("_")+1)
                                    if(item_product.indexOf(product) > -1){
                                        tax_t = parseInt(tax_t) + parseInt(p);
                                        cols_ += '<td colspan="3">&nbsp;</td>';
                                        cols_ += '<td colspan="1" style="background-color: lightblue;text-align: left">'+taxes[x].substring(0,taxes[x].indexOf("_"))+'</td>';
                                        cols_ += '<td style="text-align: right;background-color: lightblue;">'+format1(p,"")+'</td>';
                                        newRow_.append(cols_);
                                        $("#myTable_prev").append(newRow_);
                                    }
                                }
                                x++;
                            }
                        }
                        else{
                            var tr_ = '<tr id="tr'+(i+1)+'">';
                            var newRow_ = $(tr_);
                            var cols_ = "";
                            var percent=tax.substring(tax.indexOf("(")+1,tax.lastIndexOf(")"));
                            var p = (percent/100) * (price* quantity);
                            tax_t = parseInt(tax_t) + parseInt(p);
                            cols_ += '<td colspan="3">&nbsp;</td>';
                            if(tax.indexOf("_") > 1)
                                cols_ += '<td colspan="1" style="background-color: lightblue;text-align:left">'+tax.substring(0,tax.indexOf("_"))+'</td>';
                            else
                                cols_ += '<td colspan="1" style="background-color: lightblue;text-align:left">'+tax+'</td>';
                            cols_ += '<td style="text-align: right;background-color: lightblue;text-align:right">'+format1(p,"")+'</td>';
                            newRow_.append(cols_);
                            $("#myTable_prev").append(newRow_);
                        }
                    }
                    else{

                    }
                }
                i++;
            }

            if (tax_t != 0){

                var tr_ = '<tr style="border-top: solid 1px #C0C0C0;">';
                var newRow_ = $(tr_);
                var cols_ = "";
                cols_ += '<td colspan="3">&nbsp;</td>';
                cols_ += '<td colspan="1">Subtotal: </td>';
                cols_ += '<td style="text-align: right">'+format1(subtotal_t,"")+'</td>';
                newRow_.append(cols_);
                $("#myTable_prev").append(newRow_);


                var tr_ = '<tr>';
                var newRow_ = $(tr_);
                var cols_ = "";
                cols_ += '<td colspan="3">&nbsp;</td>';
                cols_ += '<td colspan="1">Tax Total: </td>';
                cols_ += '<td style="text-align: right">'+format1(tax_t,"")+'</td>';
                newRow_.append(cols_);
                $("#myTable_prev").append(newRow_);
            }

            var tr_ = '<tr>';
            var newRow_ = $(tr_);
            var cols_ = "";
            cols_ += '<td colspan="3">&nbsp;</td>';
            cols_ += '<td colspan="1" style="border-top: solid 1px #C0C0C0;">Total: </td>';
            var total = tax_t + subtotal_t;
            cols_ += '<td style="text-align: right;border-top: solid 1px #C0C0C0;">'+format1(total,"Tsh. ")+'</td>';
            newRow_.append(cols_);
            $("#myTable_prev").append(newRow_);
        }

        $('#invoicefill').hide();
    };
    xmlhttp.open("GET", str, true);
    xmlhttp.send();

}
var counter = 0;
var array_num_row = [];
function productInvoceFetch(str) {
    var ar = str.split(",");
    addRowInvoice(ar[0],ar[1],ar[2],ar[3],ar[4]);
}

function addNextRowInvoice(id,product,desc,tax,price) {
    addRowInvoice(id,product,desc,tax,price)
}

var current_product = "";

function addRowInvoice(id,product,desc,tax,price) {
    num = 0;
    $("#invoiceModal").modal("hide");
    var c =  $("[name='product[]']");
    if(c.length > 0)
        counter = c.length;

    if ($('#tr'+counter).length < 1){
        current_product = product;
        var tr = '<tr id="tr_'+counter+'" style="font-size: 14px;">';
        var newRow = $(tr);
        var cols = "";
        cols += '<td id="product' + counter + '" name ="product[]">'+product+'</td>';
        cols += '<td><textarea class="form-control" rows="5" name="desc[]" id="desc' + counter + '" style="font-size: 14px">'+desc+'</textarea></td>';
        cols += '<td><input type="text" class="form-control" name="quantity[]" id="quantity' + counter + '" value="1" onkeyup="calculateRowTotal('+counter+','+num+')" style="font-size: 14px" /></td>';
        cols += '<td><input type="text" onkeyup="calculateRowTotal('+counter+','+num+')" class="form-control" name="price[]" id="price' + counter + '" value="'+format1(Number(price),"")+'" style="font-size: 14px" /></td>';
        cols += '<td style="text-align: right" ><input type="text" id="subtotal_viewed' + counter + '" value="'+format1(Number(price),"")+'" readonly style="text-align: right;background-color: transparent;border-style: none;font-size: 14px"><input type="hidden" name="subtotal[]" id="subtotal' + counter + '" value="'+price+'" ></td>';
        cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-default" onclick=deleteRowInvoice("tr_'+counter+'","") style="background-color: transparent"><i class="fa fa-trash" aria-hidden="true"></i></button></td><input type="hidden" value="'+tax+'" id="tax_'+counter+'"/>';
        newRow.append(cols);
        $("table.order-list").append(newRow);

        if (tax.length > 0){
            if (tax.indexOf("&") > -1){
                var taxes = tax.split("&");
                var x = 0;
                while (x < taxes.length){
                    var id = counter+"_"+x;
                    var tr = '<tr id="trs_'+id+'" style="font-size: 14px">';
                    var newRow = $(tr);
                    var cols = "";
                    var percent=taxes[x].substring(taxes[x].indexOf("(")+1,taxes[x].lastIndexOf(")"));
                    var p = (percent/100) * price;
                    cols += '<td colspan="2">&nbsp;</td>';
                    cols += '<td colspan="1">&nbsp;</td>';
                    cols += '<td colspan="1"><input type="text" class="form-control" id="tax_'+counter +"_"+ x + '" value="'+taxes[x]+'" readonly style="background-color:white;border-style:solid;font-size: 14px" /><input type="hidden" value="'+taxes[x]+'_'+product+'" name="tax[]"/></td>';
                    cols += '<td style="text-align: right"><input type="text" name="subtotaltax[]" value="'+format1(p,"")+'" id="subtotaltax_'+counter+"_"+x+'" readonly style="background-color:transparent;border-style:none;font-size: 14px" /></td>';
                    var tax_ = taxes[x].replace(/\s/g,"&");
                    cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-default " style="background-color: transparent" onclick=deleteRowInvoice("trs_'+id+'","'+tax_+'")><i class="fa fa-trash" aria-hidden="true"></i></button></td>';

                    newRow.append(cols);
                    $("table.order-list").append(newRow);
                    x++;
                }
            }
            else{
                var tr = '<tr id="trs_'+counter+'" style="font-size: 14px">';
                var newRow = $(tr);
                var cols = "";
                var percent=tax.substring(tax.indexOf("(")+1,tax.lastIndexOf(")"));
                var p = (percent/100) * price;

                cols += '<td colspan="2">&nbsp;</td>';
                cols += '<td colspan="1">&nbsp;</td>';
                cols += '<td colspan="1"><input type="text" class="form-control" id="tax_' + counter + '_0" value="'+tax+'" readonly style="background-color:white;border-style:solid;font-size: 14px" /><input type="hidden" value="'+tax+'_'+product+'" name="tax[]"/></td>';
                cols += '<td style="text-align: right"><input type="text" id="subtotaltax_'+counter+"_"+0+'" name="subtotaltax[]" value="'+format1(p,"")+'" readonly style="background-color: transparent;border-style: none;font-size: 14px"/></td>';
                var tax_1 = tax.replace(/\s/g,"&");
                cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-default " style="background-color: transparent" onclick=deleteRowInvoice("trs_'+counter+'","'+tax_1+'")><i class="fa fa-trash" aria-hidden="true"></i></button></td>';

                newRow.append(cols);
                $("table.order-list").append(newRow);
                alert(newRow.html());
            }
        }

        var url = "getTaxList";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var id = 'add'+counter;
                var tr = '<tr id="'+id+'">';
                var newRow = $(tr);
                var cols = "";
                var product_ = product.replace(/\s/g,"&");

//,this.value,'+price+','+id+','+product_+'
                cols += '<td colspan="2">&nbsp;</td>';
                cols += '<td colspan="1">&nbsp;</td>';
                cols += '<td colspan="1"><select class="js-example-basic-multiple" name="" id="select'+counter+'" onchange=addTaxRow('+counter+',this.value,"'+id+'","'+product_+'","'+price+'") style="width: 100%;" data-placeholder="tax"><option value=""></option>'+this.responseText+'</select></td>';
                cols += '<td></td>';
                cols += '<td></td>';

                newRow.append(cols);
                $("table.order-list").append(newRow);
                  $('.js-example-basic-multiple').select2({
                      width: 'resolve',
                      selectOnClose: true,
                      placeholder: function(){
                          $(this).data('placeholder');
                      }
                  });
                  $(".js-example-basic-multiple").select2({ dropdownCssClass: "myFont" });
                counter++;

            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    else{
        counter++;
        addNextRowInvoice(id,product,desc,tax,price)
    }
    calculateGrandTotal();
}
var num = 0;
function addTaxRow(counter,tax,id_add,product_,price){
    if (product_.indexOf("&") > -1)
        product = product_.replace(/&/g," ");
    else{
        if(product_.length > 0)
            product = product_;
    }

    var avail = $("#tax_"+counter).val();
    if(avail == "" || avail ==  null){
        $("#tax_"+ counter).val(tax);
    }
    else{
        $("#tax_"+ counter).val(avail+"&"+tax);
    }

    var quantity = $('#quantity'+counter).val();
    var price = $('#price'+counter).val();
    var product = $('#product'+counter).html();
    var id = "trs_"+counter+"_"+num;
    var tr = '<tr id="'+id+'" style="font-size: 14px">';
    var newRow = $(tr);
    var cols = "";
    var percent=tax.substring(tax.indexOf("(")+1,tax.lastIndexOf(")"));
    var p = (percent/100) * (price*quantity);


    cols += '<td colspan="2">&nbsp;</td>';
    cols += '<td colspan="1">&nbsp;</td>';
    cols += '<td colspan="1"><input type="text" class="form-control" id="tax_' + counter +"_"+num+'" value="'+tax+'" readonly style="background-color:white;border-style:solid;font-size: 14px" /><input type="hidden" value="'+tax+'_'+product+'" name="tax[]"/></td>';

    cols += '<td style="text-align: right"><input type="text" id="subtotaltax_'+counter+"_"+num+'" name="subtotaltax[]" value="'+format1(p,"")+'" readonly style="background-color: transparent;border-style: none;font-size: 14px;text-align: right"/></td>';

    var tax_ = tax.replace(/\s/g,"&");
    cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-default " style="background-color: transparent" onclick=deleteRowInvoice("'+id+'","'+tax_+'") ><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
    newRow.append(cols);
    $( "#"+id_add ).before( newRow );
    num++;
    array_num_row[counter] = num;
    calculateGrandTotal();
}

var num_1 = 0;
var num_r = 0;
function addTaxRowEdit(counter,tax,price,id_add,product_,quantity,num_row){
    if (num_r > num_row){
        num_1++;
    }
    else{
        if (num_row != 0){num_1++;}
        num_r++;
    }
    if (product_.indexOf("&") > -1){
        product = product_.replace(/&/g," ");
    }
    else{
        if(product_.length > 0)
            product = product_;
    }

    var avail = $("#tax_"+counter).val();
    if(avail == "" || avail ==  null){
        $("#tax_"+ counter).val(tax);
    }
    else{
        var taxes = avail.split("&");
        for (var z=0;z < taxes.length;z++){

        }

        $("#tax_"+ counter).val(avail+"&"+tax+"_"+product);
    }

    var product = $('#product'+counter).html();

    if ($("#quantity"+counter).length){
        quantity = $("#quantity"+counter).val();
    }

    if($("#price"+counter).length){
        price = $("#price"+counter).val();
    }

    var id = "trs_"+counter+"_"+num_1;
    var tr = '<tr id="'+id+'" style="font-size: 14px">';
    var newRow = $(tr);
    var cols = "";
    var percent=tax.substring(tax.indexOf("(")+1,tax.lastIndexOf(")"));
    var p = (percent/100) * (price*quantity);

    cols += '<td colspan="2">&nbsp;</td>';
    cols += '<td colspan="1">&nbsp;</td>';
    cols += '<td colspan="1"><input type="text" class="form-control" id="tax_' + counter + '_'+num_1+'" value="'+tax+'" readonly style="background-color:white;border-style:solid;font-size: 14px" /><input type="hidden" value="'+tax+'_'+product+'" name="tax[]"/></td>';
    cols += '<td style="text-align: right"><input type="text" id="subtotaltax_'+counter+"_"+num_1+'" name="subtotaltax[]" value="'+format1(p,"")+'"  style="background-color:transparent;border-style:none;text-align: right;font-size: 14px" readonly /></td>';
    var tax_ = tax.replace(/\s/g,"&");
    cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-default " style="background-color: transparent" onclick=deleteRowInvoice("'+id+'","'+tax_+'") ><i class="fa fa-trash" aria-hidden="true"></i></button></td>';

    newRow.append(cols);
    $( "#"+id_add ).before( newRow );
    array_num_row[""+counter] = num_1;
    calculateGrandTotal();
}

function deleteRowInvoice(id,tax_) {
    var tax_1 ="";
    if (tax_.indexOf("&") > -1){
        tax_1 = tax_.replace(/&/g," ");
    }
    else{
        tax_1 = tax_;
    }


    if (!(counter <= 0) && (id.indexOf("trs") < 0)){
        counter -= 1;
    }
    if(id.indexOf("tr_") > -1){
        var i = id.substr(id.indexOf("tr_")+3);
        var tax = $('#tax_'+i).val();

        if(tax.length < 1){
            $('#'+id).remove();
            $("#add"+i).remove();
        }
        else{
            if (tax.indexOf("&") > -1){
                var taxes = tax.split("&");
                for (var z=0;z < taxes.length;z++){
                    if ($('#trs_'+i+"_"+z).length > 0){
                        $('#trs_'+i+"_"+z).remove();
                    }
                }
                if ($('#trs_'+i+'_0').length > 0){
                    $('#trs_'+i+'_0').remove();
                }
                $('#'+id).remove();
            }
            else if (tax.indexOf(",") > -1){
                var taxes = tax.split(",");
                for (var z=0;z < taxes.length;z++){
                    if ($('#trs_'+i+"_"+z).length > 0){
                        $('#trs_'+i+"_"+z).remove();
                    }
                }
                if ($('#trs_'+i+'_0').length > 0){
                    $('#trs_'+i+'_0').remove();
                }
                $('#'+id).remove();
            }
            else{
                if ($('#trs_'+i+'_0').length > 0){
                    $('#trs_'+i+'_0').remove();
                }

                $('#'+id).remove();
            }
        }
        $("#add"+i).remove();
        item_deleted++;
    }
    else{
        var i = id.substr(id.indexOf("_")+1,1);
        var ts = id.split("_");
        var tax ="";
        var taxes;
        if(ts.length <= 2) {
            tax = $('#tax_' + i).val();
            if (tax.indexOf("&") > -1){
                taxes = tax.split("&");
                for (var z=0;z < taxes.length;z++){
                    if ($('#trs_'+i+"_"+z).length > 0){
                        var t = taxes.indexOf(tax_1);
                        if (t > -1) {
                            taxes.splice(t, 1);
                        }
                        $('#tax_'+i).val(taxes.join("&"));
                        $('#'+id).remove();
                    }
                    else if($('#trs_'+i).length > 0){
                        var u = taxes.indexOf(tax_1);
                        if (u > -1) {
                            taxes.splice(u, 1);
                        }
                        $('#tax_'+i).val(taxes.join("&"));
                        $('#'+id).remove();
                    }
                }
            }
            else if (tax.indexOf(",") > -1){
                var taxes = tax.split(",");

                for (var z=0;z < taxes.length;z++){
                    if ($('#trs_'+i+"_"+z).length > 0){
                        var t = taxes.indexOf(tax_1);
                        if (t > -1) {
                            taxes.splice(t, 1);
                        }
                        $('#tax_'+i).val(taxes.join(","));
                        $('#'+id).remove();
                    }
                    else if($('#trs_'+i).length > 0){
                        var u = taxes.indexOf(tax_1);
                        if (u > -1) {
                            taxes.splice(u, 1);
                        }
                        $('#tax_'+i).val(taxes.join(","));
                        $('#'+id).remove();
                    }
                }

            }
            else{
                $('#'+id).remove();
            }
        }
        else{
            tax = $('#tax_' + i ).val();
            if (tax.indexOf("&") > -1){
                taxes = tax.split("&");
                for (var z=0;z < taxes.length;z++){
                    if ($('#trs_'+i +"_"+z).length > 0){
                        var t = taxes.indexOf(tax_1);
                        if (t > -1) {
                            taxes.splice(t, 1);
                        }
                        $('#tax_'+i).val(taxes.join("&"));
                        $('#'+id).remove();
                    }
                    else if($('#trs_'+i).length > 0){
                        var y = taxes.indexOf(tax_1)
                        if (y > -1) {
                            taxes.splice(y, 1);
                        }
                        $('#tax_'+i).val(taxes.join("&"));
                        $('#'+id).remove();
                    }
                }

            }
            else if (tax.indexOf(",") > -1){
                var taxes = tax.split(",");
                for (var z=0;z < taxes.length;z++){
                    if ($('#trs_'+i +"_"+z).length > 0){
                        var t = taxes.indexOf(tax_1);
                        if (t > -1) {
                            taxes.splice(t, 1);
                        }
                        $('#tax_'+i).val(taxes.join("&"));
                        $('#'+id).remove();
                    }
                    else if($('#trs_'+i).length > 0){
                        var y = taxes.indexOf(tax_1)
                        if (y > -1) {
                            taxes.splice(y, 1);
                        }
                        $('#tax_'+i).val(taxes.join("&"));
                        $('#'+id).remove();
                    }
                }
            }
            else{
                $('#'+id).remove();
            }
        }
        num_1--;
        num_r--;
       // array_num_row[""+counter] = num_1;
    }

    calculateGrandTotal();
}

function calculateRowTotal(row,num_row) {
    var quantity = $('#quantity'+row).val();
    var price = $('#price'+row).val();
    var t = Number(quantity) * Number(price);
    if( typeof array_num_row[row] === 'undefined' || array_num_row[row] === null ){
        if (num_1 > num_row){
            array_num_row[row] = num_1;
        }
        else{
            array_num_row[row] = num_row;
        }
    }
    else{
        num_1 = array_num_row[row];
    }
    var tax_input = "";
    for (var i=0;i <= num_1;i++){
        if ($("#tax"+i).length){
            var tax = $("#tax"+i).val();
            var percent = tax.split('(').pop().split(')').shift();
            var p = (percent/100)*t;

            if ($("#"+i+"subtotaltax").length){
                tax_input = $("#"+i+"subtotaltax");
            }
            else{
                if ($("#subtotaltax_"+counter+"_"+i).length){
                    tax_input = $("#subtotaltax_"+row+"_"+i);
                }
            }
        }
        else{
            if ($("#tax_"+row+"_"+i).length){
                var tax = $("#tax_"+row+"_"+i).val();
                var percent = tax.split('(').pop().split(')').shift();
                var p = (percent/100)*t;

                if ($("#subtotaltax_"+row+"_"+i).length){
                    tax_input = $("#subtotaltax_"+row+"_"+i);
                }
                else{
                    if ($("#subtotaltax_"+counter+"_"+i).length){
                        tax_input = $("#subtotaltax_"+row+"_"+i);
                    }
                }
            }
        }
        if (tax_input.length)
            tax_input.val(format1(Number(p),""));
    }
    t = format1(t,"");
    $('#subtotal_viewed'+row).val(t);
    $('#subtotal_viewed'+row).attr("style", "text-align: right;background-color: transparent;border-style: none;font-size: 14px");
    $('#subtotal'+row).val(t);
    $('#subtotal'+row).attr("style", "text-align: right;background-color: transparent;border-style: none;font-size: 14px");

    calculateGrandTotal();
}

function calculateGrandTotal() {
    var total = 0;
    var total_tax = 0;

    $("[name='subtotal[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            var myNumeral2 = numeral(str);
            var num = myNumeral2.value();
            total = total + num;
        }
    });
    $("[name='subtotaltax[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            var myNumeral2 = numeral(str);
            var num = myNumeral2.value();
            total_tax = total_tax + num;
        }
    });

    if (total > 0){
        $('#t1').css("visibility","visible");
    }
    else{
        $('#t1').css("visibility","collapse");
    }

    if (total_tax > 0){
        $('#t2').css("visibility","visible");
    }
    else{
        $('#t2').css("visibility","collapse");
    }

    var all = total_tax+total;
    if (all > 0){
        $('#t3').css("visibility","visible");
    }
    else{
        $('#t3').css("visibility","collapse");
    }

    $('#second_subtotal').html(format1(total,""));
    $('#second_subtotal').attr("style", "font-size:14px;text-align:right");
    $('#second_subtotaltax').html(format1(total_tax,""));
    $('#second_subtotaltax').attr("style", "font-size:14px;text-align:right");
    $('#second_total').html(format1(all,""));
    $('#second_total').attr("style", "font-size:14px;text-align:right");
}

function imagesPreview(input) {
    if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                $('#preview').attr('src', event.target.result);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }
}

function saveInvoice(str) {
    $("#progress").show();
    var title = $('#invoice_head').val();
    var summary = $('#summ').val();

    var invoice_num = $('#invoiceNumber').val();
    var po = $('#pos-number').val();
    var invoiceDate = $('#invoice_date').val();
    var payment_due = $('#due_date').val();
    var customer_id = $('#customer_id').val();
    var notes = $('#notes').val();

    var products =[];
    $("[name='product[]']").each(function() {
        var str = $(this).html();
        if (str.length > 0){
            products.push(str);
        }
    });

    var descs = [];
    $("[name='desc[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            descs.push(str);
        }
    });

    var quanties = [];
    $("[name='quantity[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            quanties.push(str);
        }
    });

    var prices = [];
    $("[name='price[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            prices.push(str);
        }
    });

    var taxes = [];
    $("[name='tax[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            taxes.push(str);
        }
    });


    var http = new XMLHttpRequest();
    var url = "saveinvoice";
    var params = "title="+title+"&customer_id="+customer_id+"&summary="+summary+"&invoice_num="+invoice_num+"&po="+po+"&invoice_date="+invoiceDate+"&due_date="+payment_due+"&products="+products+"&desc="+descs+"&quantity="+quanties+"&prices="+prices+"&notes="+notes+"&taxes="+taxes;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (this.responseText == 1){
                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                $("#progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: blue;font-size: 14px;width:100%; text-align: center'>successfull saved</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
                if (str == 1){

                    loadsavedInvoice(invoice_num);
                }
            }
            else{
                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                $("#progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;width:100%; text-align: center'>failed to be saved</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
        }
    }
    http.send(params);
}

function editInvoice(str) {
    $("#progress").show();
    var title = $('#invoice_head').val();
    var summary = $('#summ').val();

    var invoice_num = $('#invoiceNumber').val();
    var po = $('#pos-number').val();
    var invoiceDate = $('#invoice_date').val();
    var payment_due = $('#due_date').val();
    var customer_id = $('#customer_id').val();
    var notes = $('#notes').val();

    var products =[];
    $("[name='product[]']").each(function() {
        var str = $(this).html();
        if (str.length > 0){
            products.push(str);
        }
    });

    var descs = [];
    $("[name='desc[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            descs.push(str);
        }
    });

    var quanties = [];
    $("[name='quantity[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            quanties.push(str);
        }
    });

    var prices = [];
    $("[name='price[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            prices.push(str);
        }
    });

    var taxes = [];
    $("[name='tax[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0){
            taxes.push(str);
        }
    });

    var http = new XMLHttpRequest();
    var url = "updateinvoice";
    var params = "title="+title+"&customer_id="+customer_id+"&summary="+summary+"&invoice_num="+invoice_num+"&po="+po+"&invoice_date="+invoiceDate+"&due_date="+payment_due+"&products="+products+"&desc="+descs+"&quantity="+quanties+"&prices="+prices+"&notes="+notes+"&taxes="+taxes;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (this.responseText == 1){
                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                $("#progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: blue;font-size: 14px;width:100%; text-align: center'>successfull updated</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
                if (str == 1){

                    loadsavedInvoice(invoice_num);
                }
            }
            else{
                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                $("#progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;width:100%; text-align: center'>failed to be updated</span>";
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
        }
    }
    http.send(params);
}

function openTabInvoice(evt, tabName) {

    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    switch(tabName){
        case 'allinvoice':
            tab_invoice = 0;
            break;
        case 'unpaid':
            tab_invoice = 1;
            break;
        case 'draft':
            tab_invoice = 2;
            break;
        default:
            tab_invoice = 0;
            break;
    }
}

function loadsavedInvoice(str){
    $("#pre_load").show();
    var url = "action_invoice_list/"+str;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
            var due = $("#due_total").val();
            //$("#amount_due").html(due);
            setTimeout(function () {
                $("#pre_load").hide();
            },1500);
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

}

function ApproveInvoice(bill_num) {

    var url = "approveinvoice/"+bill_num;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function sendMail() {
    var http = new XMLHttpRequest();
    var url = "sendinvoice";
    var params = "id="+"msg";
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            alert(http.responseText);
        }
    }
    http.send(params);
}

function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
}

function loadurl(url) {
    window.location.href = url;
}

function printPDF(str) {
    alert(str);
    var http = new XMLHttpRequest();
    var url = "printinvoice";
    var params = "num="+str;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            //document.getElementById("content").innerHTML = this.responseText;

            /*var data = this.responseText;
                      myWindow = window.open("data:text/html," + encodeURIComponent(data),
                          "_blank", "width=200,height=100");
                      myWindow.focus();*/
        }
    }
    http.send(params);
}

function DeleteReceipt(str) {
    $(".progress").show();
    var url = "deletereceipts/"+str;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $(".progress").hide();
            $("#msg").show();
            setTimeout(function () {
                $("#msg").hide();
            },5000);
            if(this.responseText == 0){
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; width: 100%;text-align: center;font-size: 14px'>failed to be deleted</span>";
            }
            else{
                document.getElementById("content").innerHTML = this.responseText;
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; width: 100%;text-align: center;font-size: 14px'>successfull delete</span>";
                document.getElementById("openDefault").click();
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

}

function updateReceipt() {
    $("#progress").show();
    var merchant = $('#merchant').val();
    var date = $('#date').val();
    var me = $('#me').val();

    var notes = $('#notes').val();
    var category = $('#category').val();

    var account = $('#account').val();
    var subtotal = $('#subtotal').val();
    var currency = $('#currency').val();
    var total = $('#total').val();

    var tax =[];
    $("[name='tax[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0 && $(this).is(':checked')){
            tax.push(str);
        }
    });

    var amount = [];
    var x = 0;
    $("[name='taxamount[]']").each(function() {
        var str = $(this).val();
        if (str.length > 0 && document.getElementById("taxamount"+x).disabled == false){
            amount.push(str);
        }
        x++;
    });

    var http = new XMLHttpRequest();
    var url = "updatereceipt";
    var params = "merchant="+merchant+"&category="+category+"&date="+date+"&account="+account+"&subtotal="+subtotal+"&currency="+currency+"&total="+total+"&amount="+amount+"&notes="+notes+"&taxes="+tax+"&id="+me;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (this.responseText == 1){
                $("#progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: blue;font-size: 14px;width:100%; text-align: center'>successfull verified</span>";
                $("#top").click();
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
            else{
                $("#progress").hide();
                document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;width:100%; text-align: center'>failed to be saved</span>";
                $("#top").click();
                $("#msg").show();
                setTimeout(function () {
                    $("#msg").hide();
                },5000);
            }
        }
    }
    http.send(params);
}

function EnableInput(str) {
    if(document.getElementById("taxamount"+str).disabled == true){
        $("#taxamount"+str).prop( "disabled", false );
    }
    else{
        $("#taxamount"+str).prop( "disabled", true );
    }
}

function GenerateStatement() {

    var from = $("#from").val();
    var to = $("#to").val();
    // Initialization
    $('#from').datepicker({
        onSelect: function onSelect(fd, date) {
            GenerateStatement();
        }
    });
    // Access instance of plugin
    $('#from').data('datepicker');
    $('#from').val(from);
    // Initialization
    $('#to').datepicker({
        onSelect: function onSelect(fd, date) {
            GenerateStatement();
        }
    });

    // Access instance of plugin
    $('#to').data('datepicker');
    $('#to').val(to);
    var paid = 0;
    if($('#unpaid').is(':checked')){
        paid = 1;
    }

    var str = $("#customer").val();
    var preview = $("#preview");
    var action = $("#action");
    if(str.length < 1){
        $("#preview").hide();
        $("#action").hide();
        $("#date_g").hide();
        $("#statement_g").hide();
    }
    else{
        $("#statement_g").show();
        $("#preview").hide();
        $("#action").hide();
        $("#date_g").hide();
        $("#statement_g").html("<span style='color: #C0C0C0;font-size: 16px;text-align: center'>LOADING YOUR CUSTOMER STATEMENT<br /> Please wait...</span>");
        setTimeout(function () {
            $("#statement_g").html("STATEMENT OF ACCOUNT");
            $("#date_g").show();
            $("#preview").show();
            $("#action").show();

            var http = new XMLHttpRequest();
            var url = "getstatementpreview";
            var params = "customer="+str+"&from="+from+"&to="+to+"&paid="+paid;
            http.open("POST", url, true);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.onreadystatechange = function() {//Call a function when the state changes.
                if(http.readyState == 4 && http.status == 200) {
                    $("#preview").html(http.responseText);
                    var amount = $("#total_in").val();
                    var money = format1(Number(amount),"Sh");
                    $("#amount_due").html(money);
                }
            }
            http.send(params);

        },2000);

    }
}

function addPayment(str,type,user) {
    $("#progress_").show();
    var date = $('#pdate').val();
    var amount = $('#amount').val();
    var payment_method = $('#payment_method').val();
    var payment_account = $('#payment_account').val();
    var notes = $('#notes').val();
    var bid = $('#bid').val();

    var http = new XMLHttpRequest();
    var url = str;
    var params = "date="+date+"&amount="+amount+"&method="+payment_method+"&account="+payment_account+"&notes="+notes+"&bid="+bid+"&type="+type+"&user="+user;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            var data = (http.responseText).split('&');
            if(data[0] == 1){
                document.getElementById("content").innerHTML = data[1];
                // Initialization
                $('#pdate').datepicker();
                // Access instance of plugin
                $('#pdate').data('datepicker');
                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet;font-size: 14px;text-align: center;width: 100%'>successfull added</span>";
            }
            else{
                if (http.responseText == 2){
                    document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;text-align: center;width: 100%'>All field Required, please fill and try again</span>";
                }
                else{
                    document.getElementById("msg").innerHTML = "<span style='color: red;font-size: 14px;text-align: center;width: 100%'>Opps!! failed to be added added</span>";
                }

            }
            $("#progress_").hide();
            $("#msg").show();
            setTimeout(function () {
                $("#msg").hide();
            },8000);
        }
    }
    http.send(params);
}

function mySearchFunction() {
    // Declare variables
    var input, filter, table, tr, td,td1,td2,td3, i;
    input = document.getElementById("search_name");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable_prev");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        td1 = tr[i].getElementsByTagName("td")[1];
        td2 = tr[i].getElementsByTagName("td")[2];
        td3 = tr[i].getElementsByTagName("td")[3];
        if (td || td1 || td2 || td3) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1 || td1.innerHTML.toUpperCase().indexOf(filter) > -1 || td2.innerHTML.toUpperCase().indexOf(filter) > -1 || td3.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function mySearchFunctionSingle() {
    // Declare variables
    var input, filter, table, tr, td,td1,td2,td3, i;
    input = document.getElementById("search_name");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable_prev");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];

        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function mySearchFunctionInvoice() {

    // Declare variables
    var input, filter,current_table, table,table_1,table_2, tr, td,td_1,td_2, i;
    input = document.getElementById("search_name");
    filter = input.value.toUpperCase();
    table = document.getElementById("allinvoice_table");
    table_1 = document.getElementById("unpaid_table");
    table_2 = document.getElementById("draft_table");
    if (tab_invoice == 0){
        current_table = table;
    }
    else if (tab_invoice == 1){
        current_table = table_1;
    }
    else if (tab_invoice == 2){
        current_table = table_2;
    }
    else {
        current_table = table;
    }


    tr = current_table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[3];

        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            }
            else {
                tr[i].style.display = "none";
            }
        }
    }
}

function mySearchFunctionVendorCustomer() {
    // Declare variables
    var input, filter, table, tr, td,td_1,td_2, i;
    input = document.getElementById("search_name");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable_prev");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        td_1 = tr[i].getElementsByTagName("td")[1];
        td_2= tr[i].getElementsByTagName("td")[2];
        if (td || td_1  || td_2) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            }
            else if (td_1.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            }
            else if (td_2.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            }
            else {
                tr[i].style.display = "none";
            }
        }
    }
}

var current_transaction = 0;
function income_expense_account_open(d) {

    income_expense_account_close(d);
    var http = new XMLHttpRequest();
    var url = "addTransaction";
    var params = "type="+d;
    http.open("POST", url, true);

    $("#progress_transaction").show();
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $("#errormsg_transaction").show();
                setTimeout(function () {
                    $("#errormsg_transaction").hide();
                },5000);
                $("#progress_transaction").hide();
                document.getElementById("errormsg_transaction").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
            }
            else if(http.responseText == 2){
                $("#errormsg_transaction").show();
                setTimeout(function () {
                    $("#errormsg_transaction").hide();
                },5000);
                $("#progress_transaction").hide();
                document.getElementById("errormsg_transaction").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be addedd</span>";
            }
            else{
                $("#errormsg_transaction").show();
                $("#progress_transaction").hide();
                setTimeout(function () {
                    $("#errormsg_transaction").hide();
                    if (d == 5){
                        $("#journal_account").show();
                    }
                    else{
                        $("#income_expense_account").show();
                        if(d == 3){
                            $('#D_W option[value="' +'Deposit' +'"]').prop('selected', true).change();
                        }
                        else if(d == 4){
                            $('#D_W option[value="' + 'withdrawal' +'"]').prop('selected', true).change();
                        }
                    }

                },5000);
                document.getElementById("transaction_list").innerHTML = http.responseText;
                var id = $("#transaction_id").val();
                current_transaction = id;

                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                }); $('.js-example-basic-multiple').select2({
                    width: 'resolve',
                    selectOnClose: true,
                    placeholder: function(){
                        $(this).data('placeholder');
                    }
                });
                $(".js-example-basic-multiple").select2({ dropdownCssClass: "myFont" });

                // Initialization
                $('#to').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                // Access instance of plugin
                $('#to').data('datepicker')

                document.getElementById("errormsg_transaction").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";
            }
        }
    }
    http.send(params);
}

function updateTransaction() {
    var desc = $("#desc_transaction").val();
    var account = $("#account_transaction").val();
    var date = $("#to").val();
    var D_W = $("#D_W").val();
    var total= $("#account_transaction_total").val();
    var category = $("#category").val();
    var notes = $("#notes").val();
    var id = current_transaction;


    var http = new XMLHttpRequest();
    var url = "updateTransaction";
    var params = "desc="+desc+"&account="+account+"&date="+date+"&dw="+D_W+"&total="+total+"&category="+category+"&notes="+notes+"&id="+id+"&type="+0;
    http.open("POST", url, true);

    $("#progress_transaction_").show();
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $("#errormsg_transaction_").show();
                setTimeout(function () {
                    $("#errormsg_transaction_").hide();
                },5000);
                $("#progress_transaction_").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                document.getElementById("errormsg_transaction_").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
            }
            else if(http.responseText == 2){
                $("#errormsg_transaction_").show();
                setTimeout(function () {
                    $("#errormsg_transaction_").hide();
                },5000);
                $("#progress_transaction_").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                document.getElementById("errormsg_transaction_").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be addedd</span>";
            }
            else{

                $("#errormsg_transaction_").show();
                $("#progress_transaction_").hide();
                setTimeout(function () {
                    $("#errormsg_transaction_").hide();
                    $("#income_expense_account_").show();
                    if(d == 0){
                        $('#D_W option[value="' + 'Deposit' +'"]').prop('selected', true).change();
                    }
                    else if(d == 1){
                        $('#D_W option[value="' + 'withdrawal' +'"]').prop('selected', true).change();

                    }

                },5000);

                document.getElementById("transaction_list").innerHTML = http.responseText;
                var id = $("#transaction_id").val();
                current_transaction = id;

                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                // Initialization
                $('#to').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                // Access instance of plugin
                $('#to').data('datepicker');
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                document.getElementById("errormsg_transaction_").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";
            }
        }
    }
    http.send(params);

}

function updateTransactionJournal() {

    var desc = $("#desc_transaction_journal").val();
    var date = $("#to").val();
    var total= $("#account_transaction_total_journal").val();
    var notes = $("#notes_journal").val();

    var category = [];
    $("[name='category[]']").each(function () {
        var str = $(this).val();
        if(str.length > 0){
            category.push($(this).val());
        }
        else{
            category.push("");
        }

    });

    var desc_ = [];
    $("[name='desc_journal[]']").each(function () {
        var str = $(this).val();
        if(str.length > 0){
            desc_.push($(this).val());
        }
        else{
            desc_.push("");
        }

    });

    var debit = [];
    $("[name='debit[]']").each(function () {
        var str = $(this).val();
        if(str.length > 0){
            debit.push($(this).val());
        }
        else{
            debit.push("");
        }

    });

    var credit = [];
    $("[name='credit[]']").each(function () {
        var str = $(this).val();
        if(str.length > 0){
            credit.push($(this).val());
        }
        else{
            credit.push("");
        }
    });

    var id = current_transaction;

    var amount = $("#account_transaction_total_journal").val();
    if(amount == "" || amount == null || amount == "!!Unbalanced"){
        $("#errormsg_transaction_journal").show();
        setTimeout(function () {
            $("#errormsg_transaction_journal").hide();
        },5000);
        $("#progress_transaction_journal").hide();
        $(".panel-body").animate({ scrollTop: 0 }, "fast");
        document.getElementById("errormsg_transaction_journal").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! transaction is unbalanced, check carefull</span>";
        return;
    }

    var http = new XMLHttpRequest();
    var url = "updateTransaction";
    var params = "desc="+desc+"&account="+"journal statement"+"&date="+date+"&dw="+"journal"+"&total="+total+"&category="+""+"&notes="+notes+"&id="+id+"&j_category="+category+"&j_desc="+desc_+"&debit="+debit+"&credit="+credit+"&type="+2;
    http.open("POST", url, true);

    $("#progress_transaction_journal").show();
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $("#errormsg_transaction_journal").show();
                setTimeout(function () {
                    $("#errormsg_transaction_journal").hide();
                },5000);
                $("#progress_transaction_journal").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                document.getElementById("errormsg_transaction_journal").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
            }
            else if(http.responseText == 2){
                $("#errormsg_transaction_journal").show();
                setTimeout(function () {
                    $("#errormsg_transaction_journal").hide();
                },5000);
                $("#progress_transaction_journal").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                document.getElementById("errormsg_transaction_journal").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be addedd</span>";
            }
            else{
                $("#errormsg_transaction_journal").show();
                $("#progress_transaction_journal").hide();
                setTimeout(function () {
                    $("#errormsg_transaction_journal").hide();
                    $("#income_expense_account_journal").show();
                    if(d == 0){
                        $('#D_W option[value="' + 'Deposit' +'"]').prop('selected', true).change();
                    }
                    else if(d == 1){
                        $('#D_W option[value="' + 'withdrawal' +'"]').prop('selected', true).change();

                    }

                },5000);

                document.getElementById("transaction_list").innerHTML = http.responseText;
                var id = $("#transaction_id").val();
                current_transaction = id;

                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                // Initialization
                $('#to').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                // Access instance of plugin
                $('#to').data('datepicker');
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                document.getElementById("errormsg_transaction_journal").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";
            }
        }
    }
    http.send(params);

}

function ChangeDW(str) {

    var url = "getcategory/"+str+"/"+current_transaction;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText == 0){
            }
            else{
                document.getElementById("category").innerHTML = this.responseText;
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

}

function income_expense_account_close(id) {
    $("#"+id).hide();
    $('input:radio[name=edit]').each(function () { $(this).prop('checked', false); });
}

function add_account_form_open() {
    $(".js-example-basic-multiple").select2();
    $("#account_type_add").select2({ dropdownCssClass: "myFont" });
    $("#add_account_form").show();
    $('#account_type_add').on('change', function() {
        var label = $(this.options[this.selectedIndex]).closest('optgroup').prop('label');
        switch (label){
            case "ASSETS":
                account_chart = 0;
                break;

            case "LIABILITIES & CREDIT CARD":
                account_chart = 1;
                break;

            case "EQUITY":
                account_chart = 2;
                break;

            case "INCOME":
                account_chart = 3;
                break;

            case "EXPENSES":
                account_chart = 4;
                break;

            default:
                account_chart = 0;
                break;
        }
    })
}

function add_account_form_close() {
    $("#account_name_add").val("");
    $("#account_id_add").val("");
    $("#account_desc_add").val("");
    $("#add_account_form").hide();
}

function editAccount(str) {

    var type = $("#account_type_edit"+str).val();
    var name = $("#account_name_edit"+str).val();
    var currency = $("#account_currency_edit"+str).val();
    var id = $("#account_id_edit"+str).val();
    var desc = $("#account_desc_edit"+str).val();

    $("#progress_account"+str).show();
    $("#cancel_account"+str).prop("disabled",true);
    $("#save_account"+str).prop("disabled",true);
    var http = new XMLHttpRequest();
    var url = "editAccount";
    var params = "name="+name+"&type="+type+"&currency="+currency+"&id="+id+"&desc="+desc+"&account="+account_chart+"&id_="+str;
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){

                $("#errormsg_account"+str).show();
                setTimeout(function () {
                    $("#errormsg_account"+str).hide();
                },5000);
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! field with * all required</span>";
                $("#cancel_account"+str).prop("disabled",false);
                $("#save_account"+str).prop("disabled",false);
            }
            else if(http.responseText == 2){
                setTimeout(function () {
                    $("#errormsg_account"+str).hide();
                },5000);
                $("#errormsg_account"+str).show();
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
                $("#cancel_account"+str).prop("disabled",false);
                $("#save_account"+str).prop("disabled",false);
            }
            else if (http.responseText == 1){

                var x = account_chart;
                setTimeout(function () {
                    LoadContent("account_chart_dashboard");
                    switch (x){
                        case 0:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault").click();
                            },1000);
                            break;

                        case 1:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_liabilities").click();
                            },1000);
                            break;

                        case 2:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_equity").click();
                            },1000);
                            break;

                        case 3:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_income").click();
                            },1000);
                            break;

                        case 4:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_expenses").click();
                            },1000);

                            break;
                        default:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault").click();
                            },1000);
                            break;
                    }

                },4000);

                $("#errormsg_account"+str).show();
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";

            }
            else{
                setTimeout(function () {
                    $("#errormsg_account"+str).hide();
                },5000);
                $("#errormsg_account"+str).show();
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
                $("#cancel_account"+str).prop("disabled",false);
                $("#save_account"+str).prop("disabled",false);
            }
        }
    }
    http.send(params);
}

function deleteAccount(str) {

    var id = $("#account_id_edit"+str).val();

    $("#progress_account"+str).show();
    $("#cancel_account"+str).prop("disabled",true);
    $("#save_account"+str).prop("disabled",true);
    var http = new XMLHttpRequest();
    var url = "deleteAccount";
    var params = "id="+str;
    http.open("POST", url, true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){

                $("#errormsg_account"+str).show();
                setTimeout(function () {
                    $("#errormsg_account"+str).hide();
                },5000);
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be deleted</span>";
                $("#cancel_account"+str).prop("disabled",false);
                $("#save_account"+str).prop("disabled",false);
            }
            else if(http.responseText == 2){
                setTimeout(function () {
                    $("#errormsg_account"+str).hide();
                },5000);
                $("#errormsg_account"+str).show();
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be deleted</span>";
                $("#cancel_account"+str).prop("disabled",false);
                $("#save_account"+str).prop("disabled",false);
            }
            else if (http.responseText == 1){

                var x = account_chart;
                setTimeout(function () {
                    LoadContent("account_chart_dashboard");
                    switch (x){
                        case 0:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault").click();
                            },1000);
                            break;

                        case 1:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_liabilities").click();
                            },1000);
                            break;

                        case 2:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_equity").click();
                            },1000);
                            break;

                        case 3:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_income").click();
                            },1000);
                            break;

                        case 4:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_expenses").click();
                            },1000);

                            break;
                        default:
                            setTimeout(function () {
                                $("#errormsg_account"+str).hide();
                                $("#cancel_account"+str).prop("disabled",false);
                                $("#save_account"+str).prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault").click();
                            },1000);
                            break;
                    }

                },4000);

                $("#errormsg_account"+str).show();
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull deleted, please wait update...</span>";

            }
            else{
                setTimeout(function () {
                    $("#errormsg_account"+str).hide();
                },5000);
                $("#errormsg_account"+str).show();
                $("#progress_account"+str).hide();
                document.getElementById("errormsg_account"+str).innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be deleted</span>";
                $("#cancel_account"+str).prop("disabled",false);
                $("#save_account"+str).prop("disabled",false);
            }
        }
    }
    http.send(params);
}

var account_chart = 0;
function openChartAccount(evt, tab) {

    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tab).style.display = "block";
    evt.currentTarget.className += " active";
    switch (tab){
        case "assets":
            account_chart = 0;
            break;

        case "liabilities":
            account_chart = 1;
            break;

        case "equity":
            account_chart = 2;
            break;

        case "income":
            account_chart = 3;
            break;

        case "expenses":
            account_chart = 4;
            break;

        default:
            account_chart = 0;
            break;
    }

}

function expandAccountList(str , i) {
    if(i == 0){
        $("#first_part").prop("class","col-7");
        $("#second_part").prop("class","col-5");
        $("#second_part").show();
        $("#list-home-list"+str).prop("href","#"+str);
    }
    else if (i == 1){
        $("#third_part").prop("class","col-7");
        $("#fourth_part").prop("class","col-5");
        $("#fourth_part").show();
        $("#list-home-list"+str).prop("href","#"+str);
    }

    else if (i == 2){
        $("#fifth_part").prop("class","col-7");
        $("#sixth_part").prop("class","col-5");
        $("#sixth_part").show();
        $("#list-home-list"+str).prop("href","#"+str);
    }

    else if (i == 3){
        $("#seventh_part").prop("class","col-7");
        $("#eighth_part").prop("class","col-5");
        $("#eighth_part").show();
        $("#list-home-list"+str).prop("href","#"+str);
    }

    else if (i == 4){
        $("#nineth_part").prop("class","col-7");
        $("#tenth_part").prop("class","col-5");
        $("#tenth_part").show();
        $("#list-home-list"+str).prop("href","#"+str);
    }
}

function edit_account_form_close(str, i) {
    if(i == 0){
        $("#first_part").prop("class","col-12");
        $("#second_part").hide();
    }
    else if (i == 1){
        $("#third_part").prop("class","col-12");
        $("#fourth_part").hide();
    }
    else if (i == 2){
        $("#fifth_part").prop("class","col-12");
        $("#sixth_part").hide();
    }
    else if (i == 3){
        $("#seventh_part").prop("class","col-12");
        $("#eighth_part").hide();
    }

    else if (i == 4){
        $("#nineth_part").prop("class","col-12");
        $("#tenth_part").hide();
    }

}

function addAccount() {
    var type = $("#account_type_add").val();
    var name = $("#account_name_add").val();
    var currency = $("#account_currency_add").val();
    var id = $("#account_id_add").val();
    var desc = $("#account_desc_add").val();


    $("#progress_account").show();
    $("#cancel_account").prop("disabled",true);
    $("#save_account").prop("disabled",true);
    var http = new XMLHttpRequest();
    var url = "addAccount";
    var params = "name="+name+"&type="+type+"&currency="+currency+"&id="+id+"&desc="+desc+"&account="+account_chart;
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){

                $("#errormsg_account").show();
                setTimeout(function () {
                    $("#errormsg_account").hide();
                },5000);
                $("#progress_account").hide();
                document.getElementById("errormsg_account").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! field with * all required</span>";
                $("#cancel_account").prop("disabled",false);
                $("#save_account").prop("disabled",false);
            }
            else if(http.responseText == 2){
                setTimeout(function () {
                    $("#errormsg_account").hide();
                },5000);
                $("#errormsg_account").show();
                $("#progress_account").hide();
                document.getElementById("errormsg_account").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
                $("#cancel_account").prop("disabled",false);
                $("#save_account").prop("disabled",false);
            }
            else if (http.responseText == 1){

                var x = account_chart;
                setTimeout(function () {
                    LoadContent("account_chart_dashboard");
                    switch (x){
                        case 0:
                            setTimeout(function () {
                                $("#errormsg_account").hide();
                                $("#cancel_account").prop("disabled",false);
                                $("#save_account").prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault").click();
                            },1000);
                            break;

                        case 1:
                            setTimeout(function () {
                                $("#errormsg_account").hide();
                                $("#cancel_account").prop("disabled",false);
                                $("#save_account").prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_liabilities").click();
                            },1000);
                            break;

                        case 2:
                            setTimeout(function () {
                                $("#errormsg_account").hide();
                                $("#cancel_account").prop("disabled",false);
                                $("#save_account").prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_equity").click();
                            },1000);
                            break;

                        case 3:
                            setTimeout(function () {
                                $("#errormsg_account").hide();
                                $("#cancel_account").prop("disabled",false);
                                $("#save_account").prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_income").click();
                            },1000);
                            break;

                        case 4:
                            setTimeout(function () {
                                $("#errormsg_account").hide();
                                $("#cancel_account").prop("disabled",false);
                                $("#save_account").prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault_expenses").click();
                            },1000);

                            break;
                        default:
                            setTimeout(function () {
                                $("#errormsg_account").hide();
                                $("#cancel_account").prop("disabled",false);
                                $("#save_account").prop("disabled",false);
                                add_account_form_close();
                                document.getElementById("openDefault").click();
                            },1000);
                            break;
                    }

                },4000);

                $("#errormsg_account").show();
                $("#progress_account").hide();
                document.getElementById("errormsg_account").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull added, please wait update...</span>";

            }
            else{
                setTimeout(function () {
                    $("#errormsg_account").hide();
                },5000);
                $("#errormsg_account").show();
                $("#progress_account").hide();
                document.getElementById("errormsg_account").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
                $("#cancel_account").prop("disabled",false);
                $("#save_account").prop("disabled",false);
            }
        }
    }
    http.send(params);
}

function updateAgainTransaction(array) {
    alert(array.toString());
    //Invoice Payment,2017-11-29,Cash on Hand,payment from ABC Company|invoice #1120,3430,213,invoice payments,add,0

    //my sales,2018-04-25,Cash on Hand,Sales,40,310,,add,0
    //Bill Payment,2018-04-17,Cash on Hand,payment to Amazon Tz,10,246,second payment,less,0
    //,2018-04-25,Sales,Cash on Hand,0,312,,journal,journal
    //alert(array.toString());
   /* if(array[8] == "Deposit") {
        $('#to_edit').datepicker({
            onSelect: function onSelect(fd, date) {
            }
        });
        // Access instance of plugin
        $('#to_edit').data('datepicker');

        if (array[0] !== undefined){
            if(array[0] != null || array[0] != ""){
                $("#desc_transaction_edit").val(array[0]);
            }
            else{$("#desc_transaction_edit").val("Write a description");}
        }

        if (array[1] !== undefined)
            $("#to_edit").val(array[1]);
        if (array[4] !== undefined)
            $("#account_transaction_total_edit").val(array[4]);
        if (array[6] !== undefined)
            $("#notes_edit").val(array[6]);

        current_transaction = array[5];
        var dw = array[7];
        var account = array[2];

        $('#D_W_edit option[value="' + dw +'"]').prop('selected', true).change();

        $('#account_transaction_edit option[value="' + account +'"]').prop('selected', true).change();

        $('#category_edit option[value="' + array[3] +'"]').prop('selected', true).change();

        var url = "getcategory/"+dw+"/"+current_transaction;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("category_edit").innerHTML = this.responseText;
                $("#edit_transaction").show();
                $("#edit_transaction_payment").hide();
                $("#journal_account").hide();
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    else if(array[8] == "withdrawal") {
        $('#to_edit').datepicker({
            onSelect: function onSelect(fd, date) {
            }
        });
        // Access instance of plugin
        $('#to_edit').data('datepicker');

        if (array[0] !== undefined){
            if(array[0] != null || array[0] != ""){
                $("#desc_transaction_edit").val(array[0]);
            }
            else{$("#desc_transaction_edit").val("Write a description");}
        }
        if (array[1] !== undefined)
            $("#to_edit").val(array[1]);
        if (array[4] !== undefined)
            $("#account_transaction_total_edit").val(array[4]);
        if (array[6] !== undefined)
            $("#notes_edit").val(array[6]);

        current_transaction = array[5];
        var dw = array[7];
        var account = array[2];

        $('#D_W_edit option[value="' + dw +'"]').prop('selected', true).change();

        $('#account_transaction_edit option[value="' + account +'"]').prop('selected', true).change();

        var url = "getcategory/"+dw+"/"+current_transaction;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("category_edit").innerHTML = this.responseText;
                $('#category_edit option[value="' + array[3] +'"]').prop('selected', true).change();

                $("#edit_transaction").show();
                $("#edit_transaction_payment").hide();
                $("#journal_account").hide();
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();

    }
    else if(array[8] == "journal"){
        if (array[0] !== undefined){
            if(array[0] != null || array[0] != ""){
                $("#desc_transaction_journal").val(array[0]);
            }
            else{$("#desc_transaction_journal").val("Write a description");}
        }

        if (array[1] !== undefined)
            $("#to").val(array[1]);
        if (array[4] !== undefined)
            $("#account_transaction_total_journal").val(array[4]);
        if (array[6] !== undefined)
            $("#notes__journal").val(array[6]);

        current_transaction = array[5];
        var dw = array[7];
        var account = array[2];

        var url = "getjournalItem/"+current_transaction;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dataTable").innerHTML = this.responseText;
                $('#category_edit_journal option[value="' + array[3] +'"]').prop('selected', true).change();
                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });
                $("#journal_account").show();
                $("#edit_transaction_payment").hide();
                $("#edit_transaction").hide();
                DebitCreditTotal();
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }*/
    if (array[3].indexOf("payment from") >= 0 || array[3].indexOf("payment to") >= 0 ){
        $('#to_edit_payment').datepicker({
            onSelect: function onSelect(fd, date) {
            }
        });
        // Access instance of plugin
        $('#to_edit_payment').data('datepicker');
        if (array[0] !== undefined){
            if(array[0] != null || array[0] != ""){
                $("#desc_transaction_edit_payment").val(array[0]);
            }
            else{$("#desc_transaction_edit_payment").val("Write a description");}
        }

        if (array[1] !== undefined)
            $("#to_edit_payment").val(array[1]);
        if (array[4] !== undefined)
            $("#account_transaction_total_edit_payment").val(array[4]);
        if (array[6] !== undefined)
            $("#notes_edit_payment").val(array[6]);
        $("#payment_id").val(array[8]);

        current_transaction = array[5];
        var dw = array[8];
        var account = array[2];
        var category =  array[3];

        var url = "getcategory/"+dw+"/"+current_transaction+"/"+array[3];
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("category_edit_payment").innerHTML = this.responseText;
                $('#category_edit_payment option[value="' + category + '"]').prop('selected', true).change();
                $("#edit_transaction").hide();
                $("#edit_transaction_payment").show();
                $("#journal_account").hide();

                if (array[0] == "Bill Payment"){
                    $('#D_W_edit_payment option[value="withdrawal"]').prop('selected', true).change();
                }
                else if(array[0] == "Invoice Payment"){
                    $('#D_W_edit_payment option[value="Deposit"]').prop('selected', true).change();
                }

                $('#account_transaction_edit_payment option[value="' + account +'"]').prop('selected', true).change();

            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();

    }
    else if (array[3].indexOf("journal statement") >= 0 ){
        if (array[0] !== undefined){
            if(array[0] != null || array[0] != ""){
                $("#desc_transaction_journal").val(array[0]);
            }
            else{$("#desc_transaction_journal").val("Write a description");}
        }

        if (array[1] !== undefined)
            $("#to").val(array[1]);
        if (array[4] !== undefined)
            $("#account_transaction_total_journal").val(array[4]);
        if (array[6] !== undefined)
            $("#notes__journal").val(array[6]);

        current_transaction = array[5];
        var dw = array[7];
        var account = array[2];

        var url = "getjournalItem/"+current_transaction;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dataTable").innerHTML = this.responseText;
                $('#category_edit_journal option[value="' + array[3] +'"]').prop('selected', true).change();
                $('.js-example-basic-multiple').select2({
                    width: 'resolve',
                    selectOnClose: true,
                    placeholder: function(){
                        $(this).data('placeholder');
                    }
                });
                $(".js-example-basic-multiple").select2({ dropdownCssClass: "myFont" });
                $("#journal_account").show();
                $("#edit_transaction_payment").hide();
                $("#edit_transaction").hide();
                DebitCreditTotal();
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    else{
        $('#to_edit').datepicker({
            onSelect: function onSelect(fd, date) {
            }
        });
        // Access instance of plugin
        $('#to_edit').data('datepicker');

        if (array[0] !== undefined){
            if(array[0] != null || array[0] != ""){
                $("#desc_transaction_edit").val(array[0]);
            }
            else{$("#desc_transaction_edit").val("Write a description");}
        }

        if (array[1] !== undefined)
            $("#to_edit").val(array[1]);
        if (array[4] !== undefined)
            $("#account_transaction_total_edit").val(array[4]);
        if (array[6] !== undefined)
            $("#notes_edit").val(array[6]);

        current_transaction = array[5];
        var dw = array[7];
        var account = array[2];

        var url = "getcategory/"+dw+"/"+current_transaction;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("category_edit").innerHTML = this.responseText;
                $("#edit_transaction").show();
                $("#edit_transaction_payment").hide();
                $("#journal_account").hide();
                $('#account_transaction_edit option[value="' + account +'"]').prop('selected', true).change();
                $('#category_edit option[value="' + array[3] +'"]').prop('selected', true).change();

                if (array[7] == "add"){
                    $('#D_W_edit option[value="Deposit"]').prop('selected', true).change();
                }
                else{
                    $('#D_W_edit option[value="withdrawal"]').prop('selected', true).change();
                }


            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();

    }
    /*  else if (array[8] == 1){
          $('#to_edit').datepicker({
              onSelect: function onSelect(fd, date) {
              }
          });
          // Access instance of plugin
          $('#to_edit').data('datepicker');
          if (array[0] !== undefined){
              if(array[0] != null || array[0] != ""){
                  $("#desc_transaction_edit_payment").val(array[0]);
              }
              else{$("#desc_transaction_edit_payment").val("Write a description");}
          }

          if (array[1] !== undefined)
              $("#to_edit_payment").val(array[1]);
          if (array[4] !== undefined)
              $("#account_transaction_total_edit_payment").val(array[4]);
          if (array[6] !== undefined)
              $("#notes_edit_payment").val(array[6]);
          $("#payment_id").val("payment_out");

          current_transaction = array[5];
          var dw = "withdrawal";
          var account = array[2];


          $('#D_W_edit_payment option[value="' + dw +'"]').prop('selected', true).change();

          $('#account_transaction_edit_payment option[value="' + account +'"]').prop('selected', true).change();

          var dw = "payment_out";
          var url = "getcategory/"+dw+"/"+current_transaction+"/"+array[3];
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("category_edit_payment").innerHTML = this.responseText;
                  $('#category_edit_payment option[value="' + array[3] +'"]').prop('selected', true).change();
                  $("#edit_transaction").hide();
                  $("#edit_transaction_payment").show();
                  $("#journal_account").hide();
              }
          };
          xmlhttp.open("GET", url, true);
          xmlhttp.send();

      }*/
   /* else{
        $('#to_edit').datepicker({
            onSelect: function onSelect(fd, date) {
            }
        });
        // Access instance of plugin
        $('#to_edit').data('datepicker');
        if (array[0] !== undefined){
            if(array[0] != null || array[0] != ""){
                $("#desc_transaction_edit").val(array[0]);
            }
            else{$("#desc_transaction_edit").val("Write a description");}
        }

        if (array[1] !== undefined)
            $("#to_edit").val(array[1]);
        if (array[4] !== undefined)
            $("#account_transaction_total_edit").val(array[4]);
        if (array[6] !== undefined)
            $("#notes_edit").val(array[6]);

        current_transaction = array[5];
        var dw = array[7];
        var account = array[2];

        $('#D_W_edit option[value="' + dw +'"]').prop('selected', true).change();

        $('#account_transaction_edit option[value="' + account +'"]').prop('selected', true).change();


        var url = "getcategory/"+dw+"/"+current_transaction;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("category_edit").innerHTML = this.responseText;
                $('#category_edit option[value="' + array[3] +'"]').prop('selected', true).change();
                $("#edit_transaction").show();
                $("#edit_transaction_payment").hide();
                $("#journal_account").hide();
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();

    }*/

}

function ChangeDWEdit(str) {
    if (str == "Deposit"){
        str = "add";
    }
    else{
       str = "less";
    }
    var http = new XMLHttpRequest();
    var url = "getcategory";
    var params = "operation="+str+"&id="+current_transaction;
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            if(http.responseText == 0){
            }
            else{
                document.getElementById("category_edit").innerHTML = http.responseText;
                var selectedItem = $('#category_edit option:selected').val();
                $('#category_edit option[value="' + selectedItem +'"]').prop('selected', true).change();

            }
        }
    };
    http.send(params);
}

function updateEdtTransaction() {

    var account = $("#account_transaction_edit").val();
    var date = $("#to_edit").val();
    var D_W = $("#D_W_edit").val();
    var total = $("#account_transaction_total_edit").val();
    var category = $("#category_edit").val();
    var notes = $("#notes_edit").val();
    var desc= $("#desc_transaction_edit").val();

    var id = current_transaction;

    var http = new XMLHttpRequest();
    var url = "updateTransaction";
    var params = "desc="+desc+"&account="+account+"&date="+date+"&dw="+D_W+"&total="+total+"&category="+category+"&notes="+notes+"&id="+id;
    http.open("POST", url, true);

    $("#progress_transaction_edit").show();
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $("#errormsg_transaction_edit").show();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit").hide();
                },5000);
                $("#progress_transaction_edit").hide();
                document.getElementById("errormsg_transaction_edit").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
            }
            else if(http.responseText == 2){
                $("#errormsg_transaction_edit").show();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit").hide();
                },5000);
                $("#progress_transaction_edit").hide();
                document.getElementById("errormsg_transaction_edit").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be addedd</span>";
            }
            else{
                $("#errormsg_transaction_edit").show();
                $("#progress_transaction_edit").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit").hide();
                    $("#income_expense_account_edit").show();
                    if(d == 0){
                        $('#D_W_edit option').removeAttr('selected').filter('[value=' + 'Deposit' + ']').attr('selected', true).change();
                    }
                    else if(d == 1){
                        $('#D_W_edit option').removeAttr('selected').filter('[value=' + 'withdrawal' + ']').attr('selected', true).change();
                    }

                },5000);

                document.getElementById("transaction_list").innerHTML = http.responseText;
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                var id = $("#transaction_id").val();

                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                // Initialization
                $('#to_edit').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                // Access instance of plugin
                $('#to_edit').data('datepicker');

                document.getElementById("errormsg_transaction_edit").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";
            }
        }
    }
    http.send(params);

}

function updateEdtTransactionPayment() {

    var account = $("#account_transaction_edit_payment").val();
    var date = $("#to_edit_payment").val();
    var D_W = $("#D_W_edit_payment").val();
    var total = $("#account_transaction_total_edit_payment").val();
    var category = $("#category_edit_payment").val();
    var notes = $("#notes_edit_payment").val();
    var desc= $("#desc_transaction_edit_payment").val();
    var str = $("#payment_id").val();
    var operation="";
    if($('#D_W_edit_payment').val() == "Deposit"){
        operation = "add";
    }
    else{
        operation = "less";
    }

    var id = current_transaction;

    var http = new XMLHttpRequest();
    var url = "updateTransaction";
    var params = "desc="+desc+"&account="+account+"&date="+date+"&dw="+str+"&total="+total+"&category="+category+"&notes="+notes+"&id="+id+"&operation="+operation;
    http.open("POST", url, true);

    $("#progress_transaction_edit_payment").show();
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $("#progress_transaction_edit_payment").hide();
                document.getElementById("errormsg_transaction_edit_payment").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be updated</span>";
                $("#errormsg_transaction_edit_payment").show();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit_payment").hide();
                },3000);
            }
            else if(http.responseText == 2){
                document.getElementById("errormsg_transaction_edit_payment").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be updated</span>";
                $("#errormsg_transaction_edit_payment").show();
                $("#progress_transaction_edit_payment").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit_payment").hide();
                },3000);

            }
            else{
                document.getElementById("errormsg_transaction_edit_payment").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";
                $("#errormsg_transaction_edit_payment").show();
                $("#progress_transaction_edit_payment").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                $("#transaction_list").html(http.responseText);
                var id = $("#transaction_id").val();
                current_transaction = id;

                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                // Initialization
                $('#to_edit_payment').datepicker();
                // Access instance of plugin
                $('#to_edit_payment').data('datepicker');


                setTimeout(function () {
                    $("#errormsg_transaction_edit_payment").hide();
                    $("#edit_transaction_payment").show();
                    if(d == 0){
                        $('#D_W_edit_payment option').removeAttr('selected').filter('[value=' + 'Deposit' + ']').attr('selected', true).change();
                    }
                    else if(d == 1){
                        $('#D_W_edit_payment option').removeAttr('selected').filter('[value=' + 'withdrawal' + ']').attr('selected', true).change();
                    }

                },3000);
            }
        }
    }
    http.send(params);

}


var ind = 0;
function addRowAccount() {

    var url = "getAccount";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            var id = "tr"+ind;
            var tr = '<tr id="'+id+'">';
            var newRow = $(tr);
            var cols = "";

            cols += '<td width="20%;"><select class="form-control js-example-basic-multiple" id="category_edit" name="category[]" style="width: 98%;background-color: #FFFFFF;border-style: none;z-index: 5">'+xmlhttp.responseText+'</td>';

            cols += '<td><textarea class="form-control" rows="5" id="notes_journal" name="desc_journal[]" style="width: 78%;background-color: #ffffff"></textarea></td>';

            cols += '<td style="vertical-align: middle"><input type="text" id="debit" name="debit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="0" /></td>';

            cols += '<td style="vertical-align: middle"><input type="text" id="credit" name="credit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="0" /></td>';

            cols += ' <td><button type="button" class="btn btn-default" style="background-color: transparent" onclick="deleteRowAccount('+id+')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';

            newRow.append(cols);
            $("#dataTable").append(newRow);
            $('.js-example-basic-multiple').select2({
                width: 'resolve',
                selectOnClose: true,
                placeholder: function(){
                    $(this).data('placeholder');
                }
            });
            $(".js-example-basic-multiple").select2({ dropdownCssClass: "myFont" });
            ind++;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

}
function deleteRowAccount(id) {
    $(id).remove();
    DebitCreditTotal();
    ind--;
}

function markReviewed(id,status) {

    var str = $("#default_all_transaction").html();

    var from = $("#from_transaction").val();
    var to = $("#to_transaction").val();
    var reviewed = $("#reviewed_transaction").val();
    var type = $("#type_transaction").val();



    var http = new XMLHttpRequest();
    var url = "updateMarkTransaction";
    var params = "selector="+str+"&from="+from+"&to="+to+"&reviewed="+reviewed+"&type="+type+"&id="+id+"&status="+status;
    http.open("POST", url, true);

    $("#progress_transaction_edit").show();
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $("#errormsg_transaction_edit").show();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit").hide();
                },5000);
                $("#progress_transaction_edit").hide();
                document.getElementById("errormsg_transaction_edit").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";
            }
            else if(http.responseText == 2){
                $("#errormsg_transaction_edit").show();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit").hide();
                },5000);
                $("#progress_transaction_edit").hide();
                document.getElementById("errormsg_transaction_edit").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be addedd</span>";
            }
            else{
                $("#errormsg_transaction_edit").show();
                $("#progress_transaction_edit").hide();
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                setTimeout(function () {
                    $("#errormsg_transaction_edit").hide();
                    $("#income_expense_account_edit").show();
                    if(d == 0){
                        $('#D_W_edit option').removeAttr('selected').filter('[value=' + 'Deposit' + ']').attr('selected', true).change();
                    }
                    else if(d == 1){
                        $('#D_W_edit option').removeAttr('selected').filter('[value=' + 'withdrawal' + ']').attr('selected', true).change();
                    }

                },5000);

                document.getElementById("transaction_list").innerHTML = http.responseText;
                $(".panel-body").animate({ scrollTop: 0 }, "fast");
                var id = $("#transaction_id").val();
                current_transaction = id;

                $('.js-example-basic-multiple').select2({
                    width: 'resolve'
                });

                // Initialization
                $('#to_edit').datepicker({
                    onSelect: function onSelect(fd, date) {
                    }
                });
                // Access instance of plugin
                $('#to_edit').data('datepicker');

                document.getElementById("errormsg_transaction_edit").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";
            }
        }
    }
    http.send(params);
}

function SelectTransactionAccording() {

    var str = $("#default_all_transaction").html();
    var amount = $("#sum_transaction_input").val();

    var from = $("#from_transaction").val();
    var to = $("#to_transaction").val();
    var reviewed = $("#reviewed_transaction").val();
    var type = $("#type_transaction").val();

    $("#default_all_transaction").html(str);
    $("#sum_transaction").html(format1(Number(amount),"Sh "));
    var http = new XMLHttpRequest();
    var url = "selectTransaction";
    var params = "selector="+str+"&from="+from+"&to="+to+"&reviewed="+reviewed+"&type="+type;
    http.open("POST", url, true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
            }

            else{
                document.getElementById("transaction_list").innerHTML = http.responseText;
                var amount_ = $("#sum_transaction_input").val();
                $("#sum_transaction").html(format1(Number(amount_),"Sh "));
            }
        }
    }
    http.send(params);
}

function DebitCreditTotal() {

    var total_debit = 0;
    var total_credit = 0;
    $("[name='debit[]']").each(function () {
        var str = $(this).val();
        if (str.length > 0) {
            total_debit = parseInt(total_debit) + parseInt(str);
        }
    });

    $("[name='credit[]']").each(function () {
        var str = $(this).val();
        if (str.length > 0) {
            total_credit = parseInt(total_credit) + parseInt(str);
        }
    });

    $("#debit_side").html(total_debit);
    $("#credit_side").html(total_credit);


    if(total_credit !== total_debit){
        $("#account_transaction_total_journal").val("!!Unbalanced");
    }
    else{
        $("#account_transaction_total_journal").val(total_debit);
    }

}

function addReconcile() {

    $("#load_reconcile").show();
    var amount = $("#balance_amount").val();
    var date = $("#balance_date").val();
    var id = $("#reconcile_id").val();
    var account =  $("#reconcile_account").val();

    var http = new XMLHttpRequest();
    if(id == "" || id == 0){
        var url = "addreconcile";
        var params = "amount="+amount+"&date="+date+"&account="+account;
    }
    else{
        var url = "updatereconcile";
        var params = "amount="+amount+"&date="+date+"&id="+id+"&account="+account;
    }


    http.open("POST", url, true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {

            if (http.responseText == 1){

                setTimeout(function () {
                    $("#errormsg_reconcile").hide();
                    $("#reconcileModal .close").click();
                    LoadContent('reconciling_search/'+account);
                },5000);

                $("#errormsg_reconcile").show();
                $("#load_reconcile").hide();
                document.getElementById("errormsg_reconcile").innerHTML = "<span style='color: blueviolet;width: 100%;text-align: center'>successfull updated, please wait update...</span>";

            }
            else if (http.responseText == 2){

                $("#errormsg_reconcile").show();
                setTimeout(function () {
                    $("#errormsg_reconcile").hide();
                },5000);
                $("#load_reconcile").hide();
                document.getElementById("errormsg_reconcile").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! all field with * required</span>";

            }
            else{

                $("#errormsg_reconcile").show();
                setTimeout(function () {
                    $("#errormsg_reconcile").hide();
                },5000);
                $("#load_reconcile").hide();
                document.getElementById("errormsg_reconcile").innerHTML = "<span style='color: red;width: 100%;text-align: center'>Opps!! failed to be added</span>";

            }
        }
    }
    http.send(params);
}
function deleteTransaction() {
    $("#pre_load").show();
    var id = current_transaction;
    var http = new XMLHttpRequest();
    var url = "deleteTransaction";
    var params = "id="+id;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {

            if (http.responseText == 1){

                LoadContent("transaction_dashboard/0");
            }
            else{
                $("#pre_load").hide();
            }
        }
    };
    http.send(params);
}

function dateRange(from, to,val){
    $("#default_all_transaction_reconcile").html(val);
    if (val !== "Custom"){
        $("#from_reconcile").val(from);
        $("#to_reconcile").val(to);
    }
    LoadReconcileTransaction();

}

function dateRange2(from, to,val){
    $("#default_all_transaction_transaction").html(val);
    if (val !== "Custom"){
        $("#from_transaction").val(from);
        $("#to_transaction").val(to);
    }
    var str = $("#default_all_transaction").html();
    var amount = $("#sum_transaction_input").val();
    SelectTransactionAccording();
}

function dateRange3(from, to,val){
    $("#default_all_report").html(val);
    if (val !== "Custom"){
        $("#end_report").val(to);
    }

    LoadBalanceSheetReport(to);
}

function dateRange4(from, to,val){
    $("#default_all_report_profit_loss").html(val);
    if (val !== "Custom"){
        $("#from_report_profit_loss").val(from);
        $("#to_report_profit_loss").val(to);
    }
    LoadIncomeStatement();
}

function dateRange5(from, to,val){
    $("#default_all_report_cash_flow").html(val);
    if (val !== "Custom"){
        $("#from_report_cash_flow").val(from);
        $("#to_report_cash_flow").val(to);
    }
    Loadcashflowreport();
}

function SetNameTransaction(name){
    $("#default_all_transaction").html(name);
    SelectTransactionAccording()
}

function ReportType(val){
    $("#report_type_reconcile").html(val);
    LoadReconcileTransaction();
}

function LoadIncomeStatement() {
    var from = $("#from_report_profit_loss").val();
    var to = $("#to_report_profit_loss").val();

    var active = 0;
    if($('.active.tab-pane').hasClass('one')){
        active = 1;
    } else if($('.active.tab-pane').hasClass('two')){
        active = 2;
    }


    var http = new XMLHttpRequest();
    var url = "loadincomestatement";
    var params = "from="+from+"&to="+to+"&active="+active;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            $('#pills-tabContent').html(http.responseText);
        }
    };
    http.send(params);
}

function Loadcashflowreport() {
    var from = $("#from_report_cash_flow").val();
    var to = $("#to_report_cash_flow").val();

    var active = 0;
    if($('.active.tab-pane').hasClass('one')){
        active = 1;
    } else if($('.active.tab-pane').hasClass('two')){
        active = 2;
    }

    var http = new XMLHttpRequest();
    var url = "cashflowreport";
    var params = "from="+from+"&to="+to+"&active="+active;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            $('#pills-tabContent').html(http.responseText);
        }
    };
    http.send(params);
}

function LoadReconcileTransaction() {
    var account = $("#acccount_reconcile_transaction").val();
    var from = $("#from_reconcile").val();
    var to = $("#to_reconcile").val();
    var report = $("#report_type_reconcile").html();
    var contact = $("#contact_reconcile_transaction").val();

    var http = new XMLHttpRequest();
    var url = "reconcileTransactionSearch";
    var params = "account="+account+"&from="+from+"&to="+to+"&report="+report+"&contact="+contact;
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            if (http.responseText == 0){
                $("#account_transaction_list_div").html("<div style='width:100%;text-align: center;margin-top:5%;'><i class='fa fa-thumbs-o-up' style='font-size: 120px;color: #718FA2'></i><br /><span style='font-size:18px;color: #718FA2;font-weight: bold;'>No results were found.</span><br /><span style='font-size:14px;'>Try choosing a different date range or account.</span></div>");
            }
            else{
                $("#account_transaction_list_div").html(http.responseText);
            }
        }
    };
    http.send(params);
}

function customerStatementPreview() {
    $("#pre_load").show();
    var customer = $("#customer").val();
    var from = $("#from").val();
    var to = $("#to").val();

    var unpaid = 0;
    if($('#unpaid').is(':checked')){
        unpaid = 1;
    }

    var url = "customersPreviewStatement/"+customer+"/"+from+"/"+to+"/"+unpaid;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            setTimeout(function () {
                $("#pre_load").hide();
                $("#content").html(xmlhttp.responseText);
            },2000);

        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function deleteDataPayment(pid,id) {

    $("#pre_load").show();
    var url = "deletepayment/"+pid+"/"+id;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText == 0){
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; width: 100%;text-align: center;font-size: 14px'>failed to be deleted</span>";
            }
            else{
                document.getElementById("msg").innerHTML = "<span style='color: blueviolet; width: 100%;text-align: center;font-size: 14px'>successfull delete</span>";
            }
            $("#pre_load").hide();
            $("#msg").show();
            setTimeout(function () {
                $("#msg").hide();
                $("#payment_table").html(xmlhttp.responseText);
            },2000);
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

}
var open_tr = 1;
function openTransactionFilter(){
    var filter  = $('#transaction_filter');
    if(open_tr == 1){
        filter.show();
    }
    else if(open_tr == 0){
        filter.hide();
    }
    open_tr = 1 - open_tr;
}

function openSelectExpensesIncomeAccount() {
    if($('#buy').is(':checked')){
        $("#select_expense").show();
    }
    else{
        $("#select_expense").hide();
    }

    if($('#sales').is(':checked')){
        $("#select_income").show();
    }
    else{
        $("#select_income").hide();
    }
}

function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("myTable2");
    switching = true;
    // Set the sorting direction to ascending:
    dir = "asc";
    /* Make a loop that will continue until
              no switching has been done: */
    while (switching) {
        // Start by saying: no switching is done:
        switching = false;
        rows = table.getElementsByTagName("TR");
        /* Loop through all table rows (except the
                  first, which contains table headers): */
        for (i = 1; i < (rows.length - 1); i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
                      one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /* Check if the two rows should switch place,
                      based on the direction, asc or desc: */
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch= true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch= true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /* If a switch has been marked, make the switch
                      and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            // Each time a switch is done, increase this count by 1:
            switchcount ++;
        } else {
            /* If no switching has been done AND the direction is "asc",
                      set the direction to "desc" and run the while loop again. */
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
