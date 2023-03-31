@extends('layouts.admin')
@section('header' , 'Products')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div id="controller" class="card">
        <div clas="row">
            <div class="col-md-5 offset-md-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Search from product" v-model="search">
                </div>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary" @click="addData()">Create New Product</button>   
            </div>
        </div>

        <div class="row bg-light">
            <div class="col-md-3 col-sm-6 col-xs-12 my-2" v-for="product in filteredList">
                <div class="info-box btn" v-on:click="editData(product)">
                    <div class="info-box-content">
                        <span class="info-box-text h3">@{{ product.product_name }}</span>
                        <span class="info-box-text h5">( @{{ product.product_quantity }} )</span>
                        <span class="info-box-number">Rp. @{{ numberWithSpaces(product.product_price) }},-<small></small></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" :action="actionUrl" autocomplete="off" @submit="submitForm($event, product.id)">
                        <div class="modal-header">
                            <h4 class="modal-title">@{{ editStatus==false ? "Create Product" : "Edit Product" }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>   
                        </div>
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="_method" value="PUT" v-if="editStatus">

                            <div class="form-group">
                                <label>Product Category</label>
                                <select name="category_id" class="form-control">
                                    @foreach($product_categories as $product_category)
                                        <option :selected="product.category_id == {{ $product_category->id }}" value="{{ $product_category->id }}">{{ $product_category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Product Unit</label>
                                <select name="unit_id" class="form-control">
                                    @foreach($product_units as $product_unit)
                                        <option :selected="product.unit_id == {{ $product_unit->id }}" value="{{ $product_unit->id }}">{{ $product_unit->unit_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Product</label>
                                <input type="text" class="form-control" name="product_name" :value="product.product_name" required="">
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="text" class="form-control" name="product_quantity" :value="product.product_quantity" required="">
                            </div>
                            <div class="form-group">
                                <label>Product Cost</label>
                                <input type="text" class="form-control" name="product_cost" :value="product.product_cost" required="">
                            </div>
                            <div class="form-group">
                                <label>Product Price</label>
                                <input type="text" class="form-control" name="product_price" :value="product.product_price" required="">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default bg-danger" v-if="editStatus" v-on:click="deleteData(product.id)">Delete</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
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

    <script type="text/javascript">
        var actionUrl = '{{ url('products') }}';
        var apiUrl = '{{ url('/api/products') }}';

        var vm = new Vue({
            el: '#controller',
            data: {
                products: [],
                product: {},
                search: '',
                actionUrl,
                apiUrl,
                editStatus: false,
            },
            mounted: function() {
                this.get_products();
            },
            methods: {
                get_products() {
                    const _this = this;
                    _this.list = $.ajax({
                        url: _this.apiUrl,
                        method: 'GET',
                        success: function(data){
                            _this.products = JSON.parse(data);
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                },
                addData() {
                    this.products = {};
                    this.editStatus = false;
                    $("#modal-default").modal();
                },
                editData(product) {
                    this.product = product;
                    this.editStatus = true;
                    $("#modal-default").modal();
                },
                deleteData(id) {
                    if (confirm("Are you sure?")){
                        $(event.target).parents('tr').remove();
                        axios.post(this.actionUrl+'/'+id, {_method: 'DELETE'}).then(response => {
                            $('#modal-default').modal('hide');
                            alert('Data has been removed');
                            location.reload();
                        });
                    };
                },
                submitForm(event, id){
                    event.preventDefault();
                    const _this = this;
                    var actionUrl = ! _this.editStatus ? _this.actionUrl : _this.actionUrl+'/'+id;
                    axios.post(actionUrl, new FormData($(event.target)[0])).then(response => {
                        $('#modal-default').modal('hide');
                        location.reload();
                    });
                },
                numberWithSpaces(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");  
                }
            },
            computed: {
                filteredList() {
                    return this.products.filter(product => {
                        return product.product_name.toLowerCase().includes(this.search.toLowerCase())
                    })
                }   
            }
        });
    </script>
@endsection