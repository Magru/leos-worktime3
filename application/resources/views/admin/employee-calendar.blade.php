@extends('layouts.admin', ['body_class' => 'calendar_view'])


@section('meta')
    <title>נוכחות | LEOS</title>
    <meta name="description" content="Workday Attendance">
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/airdatepicker/css/datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('/assets/vendor/select2-bootstrap-5/select2-bootstrap-5-theme.min.css') }}">
@endsection

@section('content')

    <div class="container h-100">
        <div class="row h-100">
            <div class="col-12 d-flex align-items-center">
                <h1>{{ $employee->firstname . ' ' . $employee->lastname }}</h1>

                <div class="d-flex align-items-center px-5">
                    <span>125%</span>
                    <span class="h_125_circle mx-1"></span>

                    <span>150%</span>
                    <span class="h_150_circle mx-1"></span>
                </div>
            </div>
            <div class="col-12 h-100 pb-5">
                <div id="datepicker" class="h-100"></div>
            </div>
        </div>
    </div>

    <script>
        const entries = [
            @if($entries)
                @foreach($entries as $_e)
                    {
                        realhours : '{{ $_e->realhours }}',
                        h_125: {{ $_e->h_125 }},
                        h_150: {{ $_e->h_150 }},
                        date : new Date({{ strtotime($_e->date) }}000),
                        timein: @if($_e->timein) new Date({{ strtotime($_e->timein) }}000) @else null @endif,
                        timeout: @if($_e->timeout) new Date({{ strtotime($_e->timeout) }}000) @else null @endif,
                    },
                @endforeach
            @endif
        ];
    </script>

@endsection

@section('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="{{ asset('assets/vendor/jquery-datepicker/jquery-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datepicker/jquery-datepicker-he.js') }}"></script>
@endsection
