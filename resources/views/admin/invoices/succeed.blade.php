@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <div class="alert alert-success alert-dismissible">
                    <i class="fa fa-check icon"></i>
                    Transaction has succeed.
                </div>
            </div>
            <div class="box-footer">
                <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-flat">New Transaction</a>
            </div>
        </div>
    </div>
</div>
@endsection