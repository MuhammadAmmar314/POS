@extends('layouts.admin')

@section('header' , 'Sales List')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row" id="controller">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered" id="table2">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Transaction Date</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Total Item</th>
                            <th>Total Price</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('admin.invoices.detail')
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var vm = new Vue({
            el: '#controller',
            data: {
                datas: [],
                data: {}
            },

            mounted: function() {
                this.datatable();
            },

            methods: {
                datatable(){
                    const _this = this;
                    _this.table = $('#table2').DataTable({
                        ajax: {
                            url:'{{ url('api/invoices') }}',
                        },
                        columns: [
                            {data: 'DT_RowIndex', class: 'text-center', orderable: false},
                            {data: 'transaction_date', class: 'text-center', orderable: false},
                            {data: 'member_id', class: 'text-center', orderable: false},
                            {data: 'member_name', class: 'text-center', orderable: false},
                            {data: 'total_item', class: 'text-center', orderable: false},
                            {data: 'total_transaction', class: 'text-center', orderable: false},
                            {data: 'action', class: 'text-center', sortable: false},
                        ]
                    });
                },

                showDetail(url) {
                    $('#modal-detail').modal('show');

                    table1.ajax.url(url);
                    table1.ajax.reload();
                },

                deleteData(url) {
                    if (confirm('Yakin ingin menghapus data terpilih?')) {
                        $.post(url, {
                                '_token': '{{ csrf_token() }}',
                                '_method': 'delete'
                            })
                            .done((response) => {
                                this.table.ajax.reload();
                            })
                            .fail((errors) => {
                                alert('Tidak dapat menghapus data');
                                return;
                            });
                    }
                }
            }
        });

        let table1;
        
        table1 = $('#table-detail').DataTable({
                        processing: true,
                        bSort: false,
                        columns: [
                            {data: 'DT_RowIndex', class: 'text-center', orderable: false},
                            {data: 'product_id', class: 'text-center', orderable: false},
                            {data: 'product_name', class: 'text-center', orderable: false},
                            {data: 'product_price', class: 'text-center', orderable: false},
                            {data: 'qty', class: 'text-center', orderable: false},
                            {data: 'subtotal', class: 'text-center', orderable: false},
                        ]
                    });
    </script>
@endsection