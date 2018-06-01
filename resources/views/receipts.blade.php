<?php use App\Http\Controllers\ProductsAndServicesController; ?>
<div class="row" style="margin-top: 3%;">
    <div class="col-sm-10 offset-1">

        <div class="row" style="padding-left: 7%;padding-right: 7%">
            <div class="col-sm-8">
                <h2>Receipts</h2>
            </div>
            <div class="col-sm-4">
                <form action="ru.php" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="btn btn-info btn-file" style="float: right">Upload a Receipt<input type="file" id="receipt_upload" onchange="uploadFile()"></span>
                        </div>
                        <div class="col-sm-12">
                            <div id="dvProgress" style="width: 100%; min-width: 2em;"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row" style="margin-top: 3%;margin-left: 6%">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="input-group" style="width: 90%;font-size: 14px">
                                      <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>
                </span>
                            <input type="name" class="form-control" id="search_name" placeholder="search for name" style="font-size: 14px" onkeyup="mySearchFunction()">
                        </div>

                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
            </div>
        </div>


        <div class="row" style="margin-top: 3%;padding: 0px">
            <div class="col-sm-10 offset-1" style="padding: 0px">
                <div class="table-responsive">
                    <table class="table borderless" style="font-size: 14px;border: solid 1px #C0C0C0" id="myTable_prev">
                        <thead class="borderless" style="border-bottom: solid 1px black;">
                        <tr style="border: solid 1px #C0C0C0">
                            <th scope="col">Date</th>
                            <th scope="col">Merchant</th>
                            <th scope="col">Category</th>
                            <th scope="col">Account</th>
                            <th scope="col">Total</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                         @foreach($receipts as $receipt)
                             <tr style="border-top: solid 1px #C0C0C0;font-size: 14px;">
                                 <td>
                                     @if(strcmp($receipt->date,"0") == 0)
                                         add date
                                     @else {{$receipt->date}}
                                     @endif
                                 </td>
                                 <td>
                                     @if(strcmp($receipt->merchant,"0") == 0)
                                         add merchant
                                     @else {{$receipt->merchant}}
                                     @endif
                                 </td>
                                 <td>
                                     <?php $i = 0; ?>
                                     @foreach($expenses as $expense)
                                         @if(strcmp($expense->account_name,$receipt->category) == 0)
                                             {{$expense->account_name}}
                                             @break;
                                         @else
                                                 @if(count($expenses) - 1 <= $i)
                                                     <span style="font-size: 12px">add account</span>
                                                 @endif
                                         @endif
                                         <?php $i++; ?>
                                     @endforeach
                                 </td>
                                 <td>
                                     <?php $i = 0; ?>
                                     @foreach($cash_bank as $item)
                                         @if(strcmp($item->account_name,$receipt->account) == 0)
                                             {{$item->account_name}}
                                             @break;
                                         @else
                                             @if(count($cash_bank) - 1 <= $i)
                                                     <span style="font-size: 12px">add category</span>
                                             @endif
                                         @endif
                                         <?php $i++; ?>
                                     @endforeach
                                 </td>
                                 <td>
                                     @if(!empty($receipt->total))<?php
                                            echo $receipt->currency." ".ProductsAndServicesController::money($receipt->total);
                                     ?>
                                     @else {{$receipt->currency." 0.00"}}
                                     @endif
                                 </td>
                                 <td>
                                     <div class="btn-group">
                                         <button type="button" style="background-color: transparent"
                                                 class="btn btn-default"></button>
                                         <button type="button" style="background-color: transparent"
                                                 class="btn btn-default dropdown-toggle dropdown-toggle-split"
                                                 data-toggle="dropdown"
                                                 aria-haspopup="true" aria-expanded="false">
                                             <span class="sr-only">Toggle Dropdown</span>
                                         </button>
                                         <div class="dropdown-menu" style="color: blue">
                                             <a class="dropdown-item" href="#"></a>
                                             <a class="dropdown-item" style="color: dodgerblue;font-size: 14px" href="#" onclick=LoadContent("receiptsDetails/{{$receipt->id}}")>View/ Edit Details</a>
                                             <div class="dropdown-divider"></div>
                                             <a class="dropdown-item" href="#" onclick="DeleteReceipt({{$receipt->id}})" style="color: dodgerblue;font-size: 14px">Delete</a>
                                         </div>
                                     </div>
                                 </td>
                             </tr>
                         @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>