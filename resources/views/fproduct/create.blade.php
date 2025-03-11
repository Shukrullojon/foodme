@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Fproduct</h3>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'fproduct.store','method'=>'POST','enctype' => 'multipart/form-data']) !!}
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="name"><strong>Name:</strong></label>{!! Form::label('name',"*",['style'=>"color:red"]) !!}
                                    {!! Form::text('name', null, ['autocomplete'=>'OFF','id'=>'name','placeholder' => 'Name','required'=>true,'class' => "form-control ".($errors->has('name') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('name'))
                                        <span class="error invalid-feedback">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="info"><strong>Info:</strong></label>{!! Form::label('info',"*",['style'=>"color:red"]) !!}
                                    {!! Form::text('info', null, ['autocomplete'=>'OFF','id'=>'info','placeholder' => 'Info','class' => "form-control ".($errors->has('info') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('info'))
                                        <span class="error invalid-feedback">{{ $errors->first('info') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="image"><strong>Image:</strong></label>{!! Form::label('image',"*",['style'=>"color:red"]) !!}<br>
                                    {!! Form::file('image', null, ['autocomplete'=>'OFF','id'=>'image','placeholder' => 'Image','required'=>true,'class' => "form-control ".($errors->has('image') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('image'))
                                        <span class="error invalid-feedback">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="status"><strong>Status:</strong></label>{!! Form::label('status',"*",['style'=>"color:red"]) !!}
                                    {!! Form::select('status', \App\Models\Fproduct::$statuses,null, ['autocomplete'=>'OFF','id'=>'status','required'=>true,'class' => "form-control ".($errors->has('status') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('status'))
                                        <span class="error invalid-feedback">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="category_id"><strong>Category:</strong></label>{!! Form::label('category_id',"*",['style'=>"color:red"]) !!}
                                    {!! Form::select('category_id',$fcategories, null, ['autocomplete'=>'OFF','id'=>'category_id','class' => "form-control ".($errors->has('category_id') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('category_id'))
                                        <span class="error invalid-feedback">{{ $errors->first('category_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="old_price"><strong>Old Price:</strong></label>{!! Form::label('old_price',"*",['style'=>"color:red"]) !!}
                                    {!! Form::text('old_price', null, ['autocomplete'=>'OFF','id'=>'price','placeholder' => 'Old Price','required'=>true,'class' => "form-control ".($errors->has('old_price') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('old_price'))
                                        <span class="error invalid-feedback">{{ $errors->first('old_price') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="price"><strong>Price:</strong></label>{!! Form::label('price',"*",['style'=>"color:red"]) !!}
                                    {!! Form::text('price', null, ['autocomplete'=>'OFF','id'=>'price','placeholder' => 'Price','required'=>true,'class' => "form-control ".($errors->has('price') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('price'))
                                        <span class="error invalid-feedback">{{ $errors->first('price') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="come_price"><strong>Come Price:</strong></label>{!! Form::label('come_price',"*",['style'=>"color:red"]) !!}
                                    {!! Form::text('come_price', null, ['autocomplete'=>'OFF','id'=>'come_price','placeholder' => 'Come Price','required'=>true,'class' => "form-control ".($errors->has('come_price') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('come_price'))
                                        <span class="error invalid-feedback">{{ $errors->first('come_price') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="wallet_discount"><strong>Wallet Discount:</strong></label>{!! Form::label('wallet_discount',"*",['style'=>"color:red"]) !!}
                                    {!! Form::text('wallet_discount', null, ['autocomplete'=>'OFF','id'=>'wallet_discount','placeholder' => 'Wallet Discount', 'class' => "form-control ".($errors->has('wallet_discount') ? 'is-invalid' : '')]) !!}
                                    @if($errors->has('wallet_discount'))
                                        <span class="error invalid-feedback">{{ $errors->first('wallet_discount') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <br>
                                <button type="submit" class="btn btn-primary form-control">Save</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
