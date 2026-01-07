@extends('layouts.customer.app')

@section('content')
<div>
    @livewire('customer.customer-home')
    @livewire('customer.area-selector')

</div>
@endsection
