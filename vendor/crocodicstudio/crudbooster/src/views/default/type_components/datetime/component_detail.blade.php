@if(!is_null($value))
    {{ date("F d, Y H:i", strtotime($value)) }}
@endif