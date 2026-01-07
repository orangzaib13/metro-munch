@extends('././layouts/app')
@section('content')
@livewire('admin.show-order', ['orderId' => $orderId])
@endsection