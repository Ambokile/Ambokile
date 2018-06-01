<?php $i = 0; ?>
@foreach($journal as $item)
    <?php  $id = "tr".$i; ?>
    <tr id="{{$id}}">
        <td width="20%;">
            <select class="form-control js-example-basic-multiple" id="category_edit" style="width: 100%;background-color: #FFFFFF;border-style: none;z-index: 5" name="category[]">
                <optgroup label="ASSETS" style="font-size: 14px;">
                    @foreach($assets as $asset)
                        <option value="{{$asset->account_name}}" style="font-size: 14px;" @if(strpos($asset->account_name,$item->category) !== false) selected="selected" @endif>{{$asset->account_name}}</option>
                    @endforeach
                </optgroup>
                <optgroup label="LIABILITY" style="font-size: 14px;">
                    @foreach($liabilities as $liability)
                        <option value="{{$liability->account_name}}" style="font-size: 14px;" @if(strpos($liability->account_name,$item->category) !== false) selected="selected" @endif>{{$liability->account_name}}</option>
                    @endforeach
                </optgroup>
                <optgroup label="INCOME" id="income_" style="font-size: 14px;">
                    @foreach($incomes as $income)
                        <option value="{{$income->account_name}}" style="font-size: 14px;" @if(strpos($income->account_name,$item->category) !== false) selected="selected" @endif>{{$income->account_name}}</option>
                    @endforeach
                    <option value="" style="font-size: 14px;">Uncategorized Income</option>
                </optgroup>
                <optgroup label="EXPENSES" style="font-size: 14px;">
                    @foreach($expenses as $expense)
                        <option value="{{$expense->account_name}}" style="font-size: 14px;" @if(strpos($expense->account_name,$item->category) !== false) selected="selected" @endif>{{$expense->account_name}}</option>
                    @endforeach
                    <option value="" style="font-size: 14px;">Uncategorized Expense</option>
                </optgroup>

                <optgroup label="EQUITY" style="font-size: 14px;">
                    @foreach($equities as $equity)
                        <option value="{{$equity->account_name}}" style="font-size: 14px;" @if(strpos($equity->account_name,$item->category) !== false) selected="selected" @endif>{{$equity->account_name}}</option>
                    @endforeach
                </optgroup>
            </select>
        </td>
        <td>
            <textarea class="form-control" rows="5" id="desc_journal" style="width: 78%;background-color: #FFFFFF;" name="desc_journal[]">{{$item->detail}}</textarea>
        </td>
        <td style="vertical-align: middle"><input type="text" id="debit" name="debit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="{{$item->debit}}"></td>
        <td style="vertical-align: middle"><input type="text" id="credit" name="credit[]" style="width: 85%;background-color: #ffffff;border: solid 1px #C0C0C0;padding-left: 3%" onkeyup="DebitCreditTotal()" value="{{$item->credit}}"></td>
        <td><?php
            if ($i < 1){
                echo '&nbsp;';
            }
            else{
                echo '<button type="button" class="btn btn-default" style="background-color: transparent" onclick="deleteRowAccount('.$id.')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
            }
            ?></td>
    </tr>
    <?php $i++; ?>
@endforeach