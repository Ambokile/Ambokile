    @foreach($taxes as $tax)
            <option value="{{$tax->abbreviation}}({{$tax->tax_rate}})">{{$tax->abbreviation}}</option>
    @endforeach