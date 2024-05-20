@extends('layouts.admin')

@section('content')
    <div class="card mb-12 mb-xl-12" id="kt_profile_details_view" style="margin: 10px; padding: 10px">
        <div class="row">
            <div class="d-flex justify-content-between margin-tb">
                <h2>Lang Management</h2>
                <div>
                    <a class="btn btn-success" href="{{ route('lang.create') }}">Create</a>
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
                <th>Name</th>
                <th>Status</th>
                <th></th>
            </tr>
            @foreach ($langs as $key => $lang)
                <tr>
                    <td>{{ $lang->name }}</td>
                    <td>{{ \App\Helpers\StatusHelper::langStatusGet($lang->status) }}</td>
                    <td>
                        <div class="btn-group">
                            <a class="" style="margin-right: 10px" href="{{ route('lang.show',$lang->id) }}">
                                <span class="fa fa-eye"></span>
                            </a>
                            <a class="" style="margin-right: 2px" href="{{ route('lang.edit',$lang->id) }}">
                                <span class="fa fa-edit" style="color: #562bb0"></span>
                            </a>

                            <form action="{{ route("lang.destroy", $lang->id) }}" method="POST">
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
                    {{ $langs->links() }}
                </td>
            </tr>
        </tfooter>
    </div>

@endsection
