@if(!is_null($value))
    {{ date("F d, Y", strtotime($value)) }}
@endif