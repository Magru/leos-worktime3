@extends('layouts.admin')

@section('meta')
    <title>Edit Leave | Workday Time Clock</title>
    <meta name="description" content="Workday Edit Leave">
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('/assets/vendor/select2-bootstrap-5/select2-bootstrap-5-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/airdatepicker/css/datepicker.min.css') }}">
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 page-header">
                <div class="row g-1">
                    <div class="col-md-6 mb-1">
                        <h2 class="page-title">
                            הוסף חופשה חדשה
                        </h2>
                    </div>

                    <div class="col-md-6 mb-1 text-end">

                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <form action="{{ url('admin/leave/add') }}" method="post" class="needs-validation" autocomplete="off" novalidate accept-charset="utf-8">
                @csrf
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="employee" class="form-label">{{ __("Employee") }}</label>
                        <select name="employee" class="form-select form-select-sm select-search-sm">
                            <option selected disabled>בחר...</option>
                            @isset($employee)
                                @foreach ($employee as $data)
                                    <option value="{{ $data->idno }}">{{ $data->lastname }}
                                        , {{ $data->firstname }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">{{ __("Leave Type") }}</label>
                        <select name="type" class="form-select" required>
                            <option value="" disabled selected>בחר...</option>
                            @isset($leave_type)
                                @foreach ($leave_type as $data)
                                    <option value="{{ $data->leavetype }}" data-id="{{ $data->id }}">{{ $data->leavetype }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="leavefrom" class="form-label">{{ __("Leave From") }}</label>
                            <input type="text" name="leavefrom" value="" class="airdatepicker form-control" placeholder="DD-MM-YYYY" required>
                        </div>
                        <div class="mb-3 col-md-6" >
                            <label for="leaveto" class="form-label">{{ __("Leave Until") }}</label>
                            <input type="text" name="leaveto" value="" class="airdatepicker form-control" placeholder="DD-MM-YYYY" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="returndate" class="form-label">{{ __("Return Date") }}</label>
                        <input type="text" name="returndate" value="" class="airdatepicker form-control" placeholder="DD-MM-YYYY" required>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">{{ __("Reason") }}</label>
                        <textarea rows="5" name="reason" value="" class="form-control text-uppercase" required></textarea>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __("Status") }}</label>
                        <select name="status" class="form-select" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="Approved" @isset($leave->status) @if($leave->status == 'Approved') selected @endif @endisset>{{ __("Approved") }}</option>
                            <option value="Pending" @isset($leave->status) @if($leave->status == 'Pending') selected @endif @endisset>{{ __("Pending") }}</option>
                            <option value="Declined" @isset($leave->status) @if($leave->status == 'Declined') selected @endif @endisset>{{ __("Declined") }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comment">{{ __("Add Comment") }} <small class="text-muted">({{ __("optional") }})</small></label>
                        <textarea name="comment" class="form-control" rows="5">@isset($leave->comment){{ $leave->comment }}@endisset</textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input type="hidden" name="id" value="@isset($leave->id){{ $leave->id }}@endisset">

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i><span class="button-with-icon">{{ __("Update") }}</span>
                    </button>

                    <a href="{{ url('/admin/leave') }}" class="btn btn-secondary">
                        <i class="fas fa-times-circle"></i><span class="button-with-icon">{{ __("Cancel") }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/validate-form.js') }}"></script>
    <script src="{{ asset('/assets/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/airdatepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/airdatepicker/js/i18n/datepicker.en.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-airdatepicker.js') }}"></script>
@endsection
