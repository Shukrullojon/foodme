@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fproduct Management</h3>

                        <a href="{{ route('fproduct.create') }}" class="btn btn-success btn-sm float-right">
                            <span class="fas fa-plus-circle"></span>
                            Create
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <!-- Data table -->
                        <table id="dataTable" class="table table-bordered table-striped dataTable dtr-inline table-responsive-lg" user="grid" aria-describedby="dataTable_info">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Info</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>O Price</th>
                                <th>Price</th>
                                <th>C Price</th>
                                <th>W Discount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fproducts as $fproduct)
                                <tr>
                                    <td>{{ $fproduct->name ?? "" }}</td>
                                    <td>{{ $fproduct->info ?? "" }}</td>
                                    <td>{{ $fproduct->category->name ?? "" }}</td>
                                    <td>
                                        <img src="{{ asset("public/images/".$fproduct->image) }}" width="100">
                                    </td>
                                    <td>{{ number_format($fproduct->old_price) }}</td>
                                    <td>{{ number_format($fproduct->price) }}</td>
                                    <td>{{ number_format($fproduct->come_price) }}</td>
                                    <td>{{ number_format($fproduct->wallet_discount) }}</td>
                                    <td>{{ $fproduct->status ?? "" }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="" href="{{ route('fproduct.show',$fproduct->id) }}"
                                               style="margin-right: 7px">
                                                <span class="fa fa-eye"></span>
                                            </a>

                                            <a class="" href="{{ route('fproduct.edit',$fproduct->id) }}"
                                               style="margin-right: 2px">
                                                <span class="fa fa-edit" style="color: #562bb0"></span>
                                            </a>

                                            <form action="{{ route("fproduct.destroy", $fproduct->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button type="button"
                                                        style='display:inline; border:none; background: none'
                                                        onclick="if (confirm('Вы уверены?')) { this.form.submit() } "><span
                                                        class="fa fa-trash"></span></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfooter>
                                <tr>
                                    <td colspan="12">
                                        {{ $fproducts->withQueryString()->links()   }}
                                    </td>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
