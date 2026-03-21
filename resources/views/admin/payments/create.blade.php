@extends('layouts.admin')

@section('title', 'Add Payment')
@section('page-title', 'Add Payment')

@section('content')
@include('admin.payments.form', ['payment' => null])
@endsection
