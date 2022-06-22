@extends('layouts.admin')

@section('meta')
    <title>הודעות | Workday Time Clock</title>
    <meta name="description" content="Workday Reports">
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 page-header">
                <div class="row g-1">
                    <div class="col-md-6 mb-1">
                        <h2 class="page-title">
                            הודעות
                        </h2>
                    </div>

                    <div class="col-md-6 mb-1 text-end">

                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table width="100%" class="table datatables-table" data-order='[[ 0, "asc" ]]'>
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>תאריך</th>
                        <th>תוכן</th>
                        <th>מחלקות</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($messages)
                        @foreach($messages as $_m)
                            <tr>
                                <td>
                                    {{ $_m->id }}
                                </td>
                                <td>
                                    {{ date('d/m/Y H:i', strtotime($_m->created_at)) }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center w-100">
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-html="true" title="{{ $_m->msg }}">
                                            הודעה
                                        </button>
                                    </div>
                                </td>
                                <th>
                                    @foreach(json_decode($_m->departments) as $_d)
                                        <span class="badge bg-secondary">
                                            {{ $_d }}
                                        </span>
                                    @endforeach
                                </th>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('assets/js/initiate-datatables.js') }}"></script>
@endsection
