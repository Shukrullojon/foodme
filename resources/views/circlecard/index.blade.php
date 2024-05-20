@extends('layouts.admin')

@section('content')
    <div class="card mb-12 mb-xl-12" id="kt_profile_details_view" style="margin: 10px; padding: 10px">
        <div class="row">
            <div class="d-flex justify-content-between margin-tb">
                <h2>Circle Card Management</h2>
                <div>
                    <a class="btn btn-success" href="{{ route('ccard.create') }}">Create</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-12 mb-xl-12" id="kt_profile_details_view" style="margin: 10px; padding: 10px">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif


        <table class="table table-bordered table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
            <tr>
                <th>Circle</th>
                <th>Card</th>
                <th>Gif</th>
                <th>Salary</th>
                <th>Start</th>
                <th>Status</th>
                <th></th>
            </tr>
            @foreach ($circlecards as $key => $circlecard)
                <tr>
                    <td>{{ $circlecard->circle->name ?? '' }}</td>
                    <td>{{ $circlecard->card->name ?? '' }}</td>
                    <td>
                        <video width="100" height="100" controls>
                            <source src="{{ asset("public/data/gifs/".$circlecard->gif) }}" type="video/mp4">
                        </video>
                    </td>
                    <td>{{ \App\Helpers\StatusHelper::circleCardSalaryGet($circlecard->salary) }}</td>
                    <td>{{ \App\Helpers\StatusHelper::circleCardStartGet($circlecard->start) }}</td>
                    <td>{{ \App\Helpers\StatusHelper::circleCardStatusGet($circlecard->status) }}</td>
                    <td>
                        <div class="btn-group">
                            <a class="" style="margin-right: 10px" href="{{ route('ccard.show',$circlecard->id) }}">
                                <span class="fa fa-eye"></span>
                            </a>
                            <a class="" style="margin-right: 2px" href="{{ route('ccard.edit',$circlecard->id) }}">
                                <span class="fa fa-edit" style="color: #562bb0"></span>
                            </a>

                            <form action="{{ route("ccard.destroy", $circlecard->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="button" style='display:inline; border:none; background: none' onclick="if (confirm('Вы уверены?')) { this.form.submit() } "><span class="fa fa-trash"></span></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        <tfooter>
            <tr>
                <td colspan="9">
                    {{ $circlecards->links() }}
                </td>
            </tr>
        </tfooter>
    </div>

@endsection
