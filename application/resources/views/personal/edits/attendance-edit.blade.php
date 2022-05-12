@extends('layouts.admin')

@section('meta')
    <title>Attendance Manual Entry | Workday Time Clock</title>
    <meta name="description" content="Workday Attendance Manual Entry">
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/airdatepicker/css/datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/mdtimepicker/mdtimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/select2-bootstrap-5/select2-bootstrap-5-theme.min.css') }}">
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 page-header">
                <div class="row g-1">
                    <div class="col-md-6 mb-1">
                        <h2 class="page-title">
                            בקשה לתיקון
                        </h2>
                    </div>

                    <div class="col-md-6 mb-1 d-flex justify-content-end align-items-center">
                        <a href="{{ url('/admin/attendance') }}" class="btn btn-outline-primary btn-sm float-end">
                            <i class="fas fa-arrow-left"></i><span class="button-with-icon">{{ __("Return") }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('edit-request-send') }}" method="post" class="needs-validation" autocomplete="off" novalidate accept-charset="utf-8">
                @csrf
                <input type="hidden" name="attendance" value="{{ $attendance }}">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __("Employee") }}</label>
                        <select name="name" class="form-select select-search" disabled>
                            <option value="{{ $user->name }}" selected disabled>{{ $user->name }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">{{ __("Date") }}</label>
                        <input type="text" name="date" value="{{ $date }}" class="airdatepicker form-control" disabled placeholder="YYYY-MM-DD" required>
                    </div>

                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="clockin" class="form-label">{{ __("Clock IN") }} <small class="text-muted">({{ __("required") }})</small></label>
                            <input type="text" name="clockin" value="{{ date('H:i', strtotime($in)) }}" class="timepicker form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="clockout" class="form-label">{{ __("Clock OUT") }}</label>
                            <input type="text" name="clockout" value="{{ date('H:i', strtotime($out)) }}" class="timepicker form-control">
                        </div>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <input type="hidden" value="" name="reference">

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i><span class="button-with-icon">שלח</span>
                    </button>

                    <a href="{{ url('/admin/attendance') }}" class="btn btn-secondary">
                        <i class="fas fa-times-circle"></i><span class="button-with-icon">ביטול</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('/assets/js/validate-form.js') }}"></script>
    <script src="{{ asset('/assets/js/get-employee-id.js') }}"></script>
    <script src="{{ asset('assets/vendor/airdatepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/airdatepicker/js/i18n/datepicker.en.js') }}"></script>
    <script src="{{ asset('/assets/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-select2.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-airdatepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/mdtimepicker/mdtimepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-timepicker.js') }}"></script>
@endsection