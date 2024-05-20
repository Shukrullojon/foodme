@extends('layouts.admin')

@section('content')
    <div class="card mb-12 mb-xl-12" id="kt_profile_details_view" style="margin: 10px; padding: 10px">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit Circle Card</h2>
                </div>
            </div>
        </div>
    </div>


    <div class="card mb-12 mb-xl-12" id="kt_profile_details_view" style="margin: 10px; padding: 10px">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {!! Form::model($circlecard, ['method' => 'PATCH','route' => ['ccard.update', $circlecard->id], 'enctype' => 'multipart/form-data']) !!}
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Circle:</strong>{!! Form::label('*',"*",['style'=>"color:red"]) !!}
                    {!! Form::select('circle_id',$circles, $circlecard->circle_id, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Card:</strong>{!! Form::label('*',"*",['style'=>"color:red"]) !!}
                    {!! Form::select('card_id',$cards, $circlecard->card_id, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Gif:</strong>{!! Form::label('*',"*",['style'=>"color:red"]) !!}
                    {!! Form::file('gif', null, array('placeholder' => 'Gif','class' => 'form-control')) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Salary:</strong>{!! Form::label('*',"*",['style'=>"color:red"]) !!}
                    {!! Form::select('salary', \App\Helpers\StatusHelper::$circleCardSalary,$circlecard->salary, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Start:</strong>{!! Form::label('*',"*",['style'=>"color:red"]) !!}
                    {!! Form::select('start', \App\Helpers\StatusHelper::$circleCardStart,$circlecard->start, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>{!! Form::label('*',"*",['style'=>"color:red"]) !!}
                    {!! Form::select('status', \App\Helpers\StatusHelper::$circleCardStatus,$circlecard->status, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <br>
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

@endsection
