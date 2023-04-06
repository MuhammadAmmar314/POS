@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <div class="alert alert-danger alert-dismissible">
                    Current POS is not available. Please create new POS.
                </div>
            </div>
            <div class="box-footer">
                <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-flat">New POS</a>
            </div>
        </div>
    </div>
</div>
@endsection