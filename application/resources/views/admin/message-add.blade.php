@extends('layouts.admin')

@section('meta')
    <title>הודעה חדשה</title>
    <meta name="description" content="Workday Edit Leave">
@endsection

@section('styles')
    <script src="https://cdn.tiny.cloud/1/p3znvo2hy8sv204ffo3j3ezqoh7s0equvpbpk1lwadopxasw/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 page-header">
                <div class="row g-1">
                    <div class="col-md-6 mb-1">
                        <h2 class="page-title">
                            הודעה חדשה
                        </h2>
                    </div>

                    <div class="col-md-6 mb-1 text-end">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('message.store') }}" method="post" class="needs-validation" autocomplete="off"
                  novalidate accept-charset="utf-8">
                @csrf
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <textarea name="message" id="tiny"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 row">
                                <div class="col-md-6">
                                    <h3 class="mb-3">
                                        מחלקות
                                    </h3>
                                    <div class="btn-group-vertical" role="group"
                                         aria-label="Basic checkbox toggle button group">
                                        @if($departments)
                                            @foreach($departments as $_d)
                                                <input type="checkbox" class="btn-check" name="departments[]"
                                                       value="{{ $_d->department }}" id="dep-{{ $_d->id }}" autocomplete="off">
                                                <label class="btn btn-outline-primary"
                                                       for="dep-{{ $_d->id }}">{{ $_d->department }}</label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-3">
                                        תוקף ההודעה
                                    </h3>
                                    <label for="valid_date">תאריך</label>
                                    <input name="valid_date" class="form-control" id="valid_date" type="date">
                                </div>
                            </div>
                        </div>
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
    <script src="{{ asset('/assets/js/he_IL.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea#tiny',
            language: 'he_IL'
        });
    </script>
@endsection
