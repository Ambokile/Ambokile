<?php $array = array(); ?>
@foreach($transactions as $transaction)
    @if(!in_array($transaction->category,$array))
        <option value="{{$transaction->category}}" @if(strpos($select,$transaction->category) !== false) selected @endif>{{$transaction->category}}</option>
        <?php array_push($array,$transaction->category); ?>
    @endif
@endforeach
