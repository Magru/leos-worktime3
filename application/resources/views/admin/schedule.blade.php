@extends('layouts.admin')

@section('meta')
    <title>Schedule | Workday Time Clock</title>
    <meta name="description" content="Workday Schedule">
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/select2-bootstrap-5/select2-bootstrap-5-theme.min.css') }}">
@endsection

@section('content')
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModallabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModallabel">{{ __("Confirmation") }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>{{ __("Are you sure you want to delete the record?") }}</p>
      </div>
      <div class="modal-footer">
        <a href="" type="button" class="btn btn-danger modal_URL"><i class="fas fa-check-circle"></i> {{ __("Continue") }}</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle"></i> {{ __("Cancel") }}</button>
      </div> 
    </div>
  </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 page-header">
            <div class="row g-1">
                <div class="col-md-6 mb-1">
                    <h2 class="page-title">
                        {{ __("Schedule") }}
                    </h2>
                </div>

                <div class="col-md-6 mb-1 d-flex align-items-center justify-content-end">
                    <a href="{{ url('/admin/schedule/add') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i><span class="button-with-icon">{{ __("Add") }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/schedule') }}" method="post" class="form-inline responsive-filter-form needs-validation mb-2" novalidate autocomplete="off" accept-charset="utf-8">
                @csrf
                <div class="col-md-12">
                  <div class="row g-1">
                    <div class="col-sm-3">
                        <select name="emp_id" class="form-select form-select-sm select-search-sm" required>
                            <option value="" disabled selected>{{ __('Employee') }}</option>
                            @isset($employee)
                                @foreach ($employee as $data)
                                    <option value="{{ $data->id }}">{{ $data->lastname }}, {{ $data->firstname }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-outline-secondary btn-sm col-md-12">
                            <i class="fas fa-filter"></i><span class="button-with-icon">{{ __("Filter") }}</span>
                        </button>
                    </div>

                    <div class="col-sm-2">
                        <button type="button" id="btnTableExport" class="btn btn-outline-primary btn-sm col-md-12">
                            <i class="fas fa-file-export"></i><span class="button-with-icon">{{ __("Export") }}</span>
                        </button>
                    </div>
                </div>
            </div>
            </form>

            <table width="100%" id="tableExportToCSV" class="table datatables-table custom-table-ui" data-order='[[ 0, "desc" ]]'>
                <thead>
                    <tr>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Start') }}/{{ __("Off Time") }}</th>
                        <th>{{ __('Total Hours') }}</th>
                        <th>{{ __('Rest Days') }}</th>
                        <th>{{ __('From') }}</th>
                        <th>{{ __('Until') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($schedule)
                        @foreach ($schedule as $data)
                        <tr>
                            <td>{{ $data->employee }}</td>
                            <td>
                                @php
                                    if($time_format == 12) {
                                        echo e(date("h:i A", strtotime($data->intime)));
                                        echo " - ";
                                        echo e(date("h:i A", strtotime($data->outime)));
                                    } else {
                                        echo e(date("H:i", strtotime($data->intime)));
                                        echo " - ";
                                        echo e(date("H:i", strtotime($data->outime)));
                                    }
                                @endphp
                            </td>
                            <td>{{ $data->hours }} hr</td>
                            <td>{{ $data->restday }}</td>
                            <td>@php echo e(date('M d, Y', strtotime($data->datefrom))) @endphp</td>
                            <td>@php echo e(date('M d, Y', strtotime($data->dateto))) @endphp</td>
                            <td>
                                @if($data->archive == '0') 
                                    <span class="green">{{ __('Active') }}</span>
                                @else
                                    <span class="teal">{{ __('Archived') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($data->archive == '0') 
                                    <a href="{{ url('admin/schedule/edit/') }}/{{ $data->id }}" class="btn btn-outline-secondary btn-sm btn-rounded"><i class="fas fa-pen"></i></a>
                                    <a href="{{ url('admin/schedule/archive/') }}/{{ $data->id }}" class="btn btn-outline-secondary btn-sm btn-rounded"><i class="fas fa-archive"></i></a>
                                    <a href="#" data-url="{{ url('admin/schedule/delete/') }}/{{ $data->id }}" class="btn btn-outline-secondary btn-sm btn-rounded btnDelete" data-bs-toggle="modal" data-bs-target="#confirmationModal"><i class="fas fa-trash"></i></a>
                                @else
                                    <a href="#" data-url="{{ url('admin/schedule/delete/') }}/{{ $data->id }}" class="btn btn-outline-secondary btn-sm btn-rounded btnDelete" data-bs-toggle="modal" data-bs-target="#confirmationModal"><i class="fas fa-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
            <!-- <small class="text-muted">{{ __("Only 250 recent records will be displayed use Date range filter to get more records") }}</small> -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/initiate-datatables.js') }}"></script> 
    <script src="{{ asset('/assets/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-select2.js') }}"></script>
    <script src="{{ asset('assets/js/table-export-csv.js') }}"></script>
    <script src="{{ asset('assets/js/confirmationModal.js') }}"></script>
@endsection