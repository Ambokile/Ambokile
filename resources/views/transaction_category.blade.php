
<option value="" disabled hidden></option>
@if($type == 3)
    @if(count($incomes) > 0)
    <optgroup label="INCOME ACCOUNTS" id="income_" style="visibility: hidden">
        @foreach($incomes as $income)
            <option value="{{$income->account_name}}" @if(empty($category)) @elseif(strpos($income->account_name,$category) !== false)selected @endif>{{$income->account_name}}</option>
        @endforeach
        <option value="Uncategorized Income" @if(strpos($income->account_name,"Uncategorized Income") !== false)selected="selected" @elseif(strpos($category,"Uncategorized Income") !== false) selected="selected" @endif>Uncategorized Income</option>
    </optgroup>
        @endif
@elseif($type == 4)
    @if(count($expenses) > 0)
    <optgroup label="EXPENSE ACCOUNTS">
        @foreach($expenses as $expense)
            <option value="{{$expense->account_name}}" @if(empty($category)) @elseif(strpos($expense->account_name,$category) !== false)selected @endif>{{$expense->account_name}}</option>
        @endforeach
            <option value="Uncategorized Expense" @if(strpos($expense->account_name,"Uncategorized Expense") !== false)selected="selected"  @elseif(strpos($category,"Uncategorized Expense") !== false) selected="selected" @endif>Uncategorized Expense</option>
    </optgroup>
        @endif
@endif
@if(count($assets) > 0)
<optgroup label="ASSETS ACCOUNTS">
    @foreach($assets as $asset)
        <option value="{{$asset->account_name}}" @if(empty($category)) @elseif(strpos($asset->account_name,$category) !== false)selected @endif>{{$asset->account_name}}</option>
    @endforeach
</optgroup>
@endif

@if(count($equities) > 0)
<optgroup label="EQUITY ACCOUNTS">
    @foreach($equities as $equity)
        <option value="{{$equity->account_name}}" @if(empty($category)) @elseif(strpos($equity->account_name,$category) !== false)selected @endif>{{$equity->account_name}}</option>
    @endforeach
</optgroup>
@endif

@if(count($liabilities) > 0)
<optgroup label="LIABILITY ACCOUNTS">
    @foreach($liabilities as $liability)
        <option value="{{$liability->account_name}}" @if(empty($category)) @elseif(strpos($liability->account_name,$category) !== false)selected @endif>{{$liability->account_name}}</option>
    @endforeach
</optgroup>
@endif