@extends('layouts.personal')

@section('meta')
    <title>Attendance | Workday Time Clock</title>
    <meta name="description" content="Workday Attendance">
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/airdatepicker/css/datepicker.min.css') }}">
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 page-header">
            <div class="row g-1">
                <div class="col-md-6 mb-1">
                    <h2 class="page-title">
                        {{ __("Attendance") }}
                    </h2>
                </div>

                <div class="col-md-6 mb-1 d-flex justify-content-end align-items-center">
                    <a href="{{ url('/webclock') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="fas fa-clock"></i><span class="button-with-icon">{{ __("Web Clock") }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('personal/attendance') }}" method="post" class="needs-validation" novalidate autocomplete="off" accept-charset="utf-8">
                @csrf
                <div class="col-md-12">
                  <div class="row g-1">
                      <div class="col-sm-2">
                        <input name="start" type="text" class="airdatepicker form-control form-control-sm mr-1" value="" placeholder="מ-" required>
                      </div>

                      <div class="col-sm-2">
                        <input name="end" type="text" class="airdatepicker form-control form-control-sm mr-1" value="" placeholder="עד-" required>
                      </div>

                      <div class="col-sm-2">
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-filter"></i><span class="button-with-icon">{{ __("Filter") }}</span>
                        </button>
                      </div>
                  </div>
                </div>
            </form>

            <table width="100%" class="table datatables-table custom-table-ui" data-order='[[ 0, "desc" ]]'>
                <thead>
                    <tr>
                        <th>{{ __("Date") }}</th>
                        <th>{{ __("Clock In") }}</th>
                        <th>{{ __("Clock Out") }}</th>
                        <th>{{ __("Total Hours") }}</th>
                        <th>{{ __("Status") }} <span class="text-muted">({{ __("In") }}/{{ __("Out") }})</span></th>
                    </tr>
                </thead>
                <tbody>
                    @isset($attendance)
                        @foreach($attendance as $data)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($data->date)) }}</td>
                                <td>
                                    @php
                                        if($data->timein !== null) {
                                            if ($time_format == 12) {
                                                echo e(date('h:i:s A', strtotime($data->timein)));
                                            } else {
                                                echo e(date('H:i:s', strtotime($data->timein)));
                                            }
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        if($data->timeout !== null) {
                                            if ($time_format == 12) {
                                                echo e(date('h:i:s A', strtotime($data->timeout)));
                                            } else {
                                                echo e(date('H:i:s', strtotime($data->timeout)));
                                            }
                                        }
                                    @endphp
                                </td>
                                <td>
                                @isset($data->realhours)
                                    {{$data->realhours}}
                                @endisset
                                </td>
                                <td>
                                    @if($data->status_timein !== null && $data->status_timeout !== null) 
                                        <span class="@if($data->status_timein == 'Late In') text-warning @else text-primary @endif">{{ $data->status_timein }}</span> / 
                                        <span class="@if($data->status_timeout == 'Early Out') text-danger @else text-success @endif">{{ $data->status_timeout }}</span> 
                                    @elseif($data->status_timein == 'Late In') 
                                        <span class="text-warning">{{ $data->status_timein }}</span>
                                    @else 
                                        <span class="text-primary">{{ $data->status_timein }}</span>
                                    @endif 
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
            <small class="text-muted">{{ __("Only 250 recent records will be displayed use Date range filter to get more records") }}</small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/initiate-datatables.js') }}"></script> 
    <script src="{{ asset('/assets/js/validate-form.js') }}"></script>
    <script src="{{ asset('assets/vendor/airdatepicker/js/datepicker.min.js') }}"></script> 
    <script src="{{ asset('assets/vendor/airdatepicker/js/i18n/datepicker.en.js') }}"></script> 
    <script src="{{ asset('/assets/js/initiate-airdatepicker.js') }}"></script>
@endsection