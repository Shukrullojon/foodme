@extends('layouts.admin')

@section('content')
    <div class="card mb-12 mb-xl-12" id="kt_profile_details_view" style="margin: 10px; padding: 10px">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Show Circle Card</h2>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Circle:</strong>
                    {{ $circlecard->circle->name ?? '' }}
                </div>

                <div class="form-group">
                    <strong>Card:</strong>
                    {{ $circlecard->card->name ?? '' }}
                </div>

                <div class="form-group">
                    <strong>Salry:</strong>
                    {{ \App\Helpers\StatusHelper::circleCardSalaryGet($circlecard->salary) }}
                </div>

                <div class="form-group">
                    <strong>Start:</strong>
                    {{ \App\Helpers\StatusHelper::circleCardStartGet($circlecard->start) }}
                </div>

                <div class="form-group">
                    <strong>Status</strong>
                    {{ \App\Helpers\StatusHelper::circleCardStatusGet($circlecard->status) }}
                </div>
            </div>
        </div>
    </div>
@endsection
