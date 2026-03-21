@extends('layouts.admin')

@section('title', 'Edit Payment')
@section('page-title', 'Edit Payment')

@section('content')
@include('admin.payments.form', ['payment' => $payment])
@endsection
