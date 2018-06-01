<optgroup label="ASSETS" style="font-size: 14px;">
    @foreach($assets as $asset)
        <option value="{{$asset->account_name}}" style="font-size: 14px;">{{$asset->account_name}}</option>
    @endforeach
</optgroup>
<optgroup label="LIABILITY" style="font-size: 14px;">
    @foreach($liabilities as $liability)
        <option value="{{$liability->account_name}}" style="font-size: 14px;">{{$liability->account_name}}</option>
    @endforeach
</optgroup>
<optgroup label="INCOME" id="income_" style="font-size: 14px;">
        @foreach($incomes as $income)
            <option value="{{$income->account_name}}" style="font-size: 14px;">{{$income->account_name}}</option>
        @endforeach
            <option value="Uncategorized Income" selected style="font-size: 14px;">Uncategorized Income</option>
    </optgroup>
    <optgroup label="EXPENSES" style="font-size: 14px;">
        @foreach($expenses as $expnse)
            <option value="{{$expnse->account_name}}" style="font-size: 14px;">{{$expnse->account_name}}</option>
        @endforeach
            <option value="Uncategorized Expense" selected style="font-size: 14px;">Uncategorized Expense</option>
    </optgroup>
<optgroup label="EQUITY" style="font-size: 14px;">
    @foreach($equities as $equity)
        <option value="{{$equity->account_name}}" style="font-size: 14px;">{{$equity->account_name}}</option>
    @endforeach
</optgroup>