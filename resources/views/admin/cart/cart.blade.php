@extends('layouts.admin')
@section('header' , 'Cart')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 120px;
    }

    .tampil-terbilang {
        padding: 10px;
    }

    #cart_list tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endsection

@section('content')
<div class="row" id="controller">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="product_id" class="col-lg-2">Product ID</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="code" id="code" value="{{ $code }}">
                                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                                <input type="text" class="form-control" name="product_id" id="product_id">
                                <span class="input-group-btn">
                                    <button @click="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-hover table-bordered" id="cart_list">
                    <thead>
                        <th width="5%">No</th>
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th width="5%">Qty</th>
                        <th>Subtotal</th>
                        <th width="5%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-dark"></div>
                        <div class="tampil-terbilang bg-secondary"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('invoices.store') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="code" value="{{ $code }}">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="total_transaction" id="total_transaction">
                            <input type="hidden" name="member_id" id="member_id">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            
                            <div class="form-group row">
                                <label for="id_member" class="col-lg-2 control-label">Member</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="id_member">
                                        <span class="input-group-btn">
                                            <button @click="tampilMember()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="payment" class="col-lg-2 control-label">Diterima</label>
                                <div class="col-lg-8">
                                    <input type="number" id="payment" class="form-control" name="payment" value="{{ $invoice->payment ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan col-md-1 offset-md-11"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>
@includeIf('admin.cart.product')
@includeIf('admin.cart.member')
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

    <script type="text/javascript">
        var vm = new Vue({
            el: '#controller',
            data: {
                datas: [],
                data: {},
            },
            mounted: function() {
                this.datatable();
            },
            methods: {
                datatable(){
                    const _this = this;
                    _this.table = $('#cart_list').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        autoWidth: false,
                        ajax: {
                            url: '{{ route('carts.api') }}',
                        },
                        columns: [
                            {data: 'DT_RowIndex', class: 'text-center', sortable: false},
                            {data: 'product_id', class: 'text-center', orderable: false},
                            {data: 'product_name', class: 'text-center', orderable: false},
                            {data: 'product_price', class: 'text-center', orderable: false},
                            {data: 'qty', class: 'text-center', orderable: false},
                            {data: 'subtotal', class: 'text-center', orderable: false},
                            {data: 'aksi', class: 'text-center', sortable: false},
                        ],
                        dom: 'Brt',
                        bSort: false,
                        paginate: false,
                    })
                    .on('draw.dt', function () {
                        _this.loadForm();
                        setTimeout(() => {
                            $('#payment').trigger('input');
                        }, 300);
                    });
                },

                tampilProduk() {
                    $('#modal-produk').modal('show');
                },

                tampilMember() {
                    $('#modal-member').modal('show');
                },
                
                pilihProduk(id) {
                    $('#product_id').val(id);
                    this.hideProduk();
                    this.tambahProduk();
                },

                pilihMember(id) {
                    $('#member_id').val(id);
                    $('#id_member').val(id);
                    $('#payment').val(0).focus().select();
                    this.loadForm();
                    this.hideMember();
                },
                
                hideProduk() {
                    $('#modal-produk').modal('hide');
                },

                hideMember() {
                    $('#modal-member').modal('hide');
                },

                tambahProduk() {
                    $.post('{{ route('carts.store') }}', $('.form-produk').serialize())
                        .done(response => {
                            $('#product_id').focus();
                            this.table.ajax.reload();
                        })
                        .fail(errors => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
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
                },

                loadForm(payment = 0) {
                    $('#total_transaction').val($('.total_transaction').text());
                    $('#total_item').val($('.total_item').text());

                    $.get(`{{ url('/carts/loadform') }}/${$('.total_transaction').text()}/${payment}`)
                        .done(response => {
                            $('#totalrp').val('Rp. '+ response.totalrp);
                            $('.tampil-bayar').text('Total: Rp. '+ response.totalrp);
                            $('.tampil-terbilang').text(response.terbilang);

                            $('#kembali').val('Rp.'+ response.kembalirp);
                            if ($('#payment').val() != 0) {
                                $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                                $('.tampil-terbilang').text(response.kembali_terbilang);
                            }
                        })
                        .fail(errors => {
                            alert('Tidak dapat menampilkan data');
                            return;
                        })
                }
            }
        });

        table2 = $('.table-produk').DataTable();
        table3 = $('.table-member').DataTable();

        $('#payment').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            vm.loadForm($(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-simpan').on('click', function () {
            $('.form-penjualan').submit();
        });

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/carts') }}/${id}`, {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'put',
                    'qty': jumlah
                })
                .done(response => {
                    $('#cart_list').DataTable().ajax.reload();
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });

        
        
        
    </script>
@endsection