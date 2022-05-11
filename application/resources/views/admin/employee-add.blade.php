@extends('layouts.admin')

@section('meta')
    <title>Add New Employee | Workday Time Clock</title>
    <meta name="description" content="Workday Add New Employee">
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
                            {{ __("Employee Profile") }}
                        </h2>
                    </div>

                    <div class="col-md-6 mb-1 text-start">
                        <a href="{{ url('/admin/employee') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-left"></i><span class="button-with-icon">{{ __("Return") }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <form action="{{ url('admin/employee/store') }}" method="post" class="needs-validation" autocomplete="off"
                  novalidate enctype="multipart/form-data" accept-charset="utf-8">
                @csrf
                <div class="card-header"></div>
                <div class="card-body">
                    <p class="lead">{{ __("Personal Information") }}</p>
                    <hr>
                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="firstname" class="form-label">{{ __("First Name") }}</label>
                            <input type="text" name="firstname" value="" class="form-control text-uppercase" required>
                        </div>
                        @if(0)
                            <div class="mb-3 col-md-2">
                                <label for="mi" class="form-label">{{ __("Middle Name") }}</label>
                                <input type="text" name="mi" value="" class="form-control text-uppercase">
                            </div>
                        @endif
                        <div class="mb-3 col-md-6">
                            <label for="lastname" class="form-label">{{ __("Last Name") }}</label>
                            <input type="text" name="lastname" value="" class="form-control text-uppercase" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="gender" class="form-label">{{ __("Gender") }}</label>
                            <select name="gender" class="form-select text-uppercase">
                                <option value="" disabled selected>בחר...</option>
                                <option value="MALE">זכר</option>
                                <option value="FEMALE">נקבה</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="civilstatus" class="form-label">{{ __("Civil Status") }}</label>
                            <select name="civilstatus" class="form-select text-uppercase">
                                <option value="" disabled selected>בחר...</option>
                                <option value="SINGLE">{{ __("Single") }}</option>
                                <option value="MARRIED">{{ __("Married") }}</option>
                                <option value="ANNULLED">{{ __("Annulled") }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="emailaddress" class="form-label">{{ __("Email Address") }} <small
                                        class="text-muted">(אישי)</small></label>
                            <input type="email" name="emailaddress" value="" class="form-control text-lowercase"
                                   required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="mobileno" class="form-label">{{ __("Mobile Number") }}</label>
                            <input type="text" name="mobileno" value="" class="form-control text-uppercase">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="birthday" class="form-label">{{ __("Date of Birth") }}</label>
                            <input type="text" name="birthday" value=""
                                   class="airdatepicker form-control text-uppercase" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="age" class="form-label">{{ __("Age") }}</label>
                            <input type="text" name="age" value="" class="form-control text-uppercase">
                        </div>
                    </div>

                    <div class="row">
                        @if(0)
                            <div class="mb-3 col-md-6">
                                <label for="nationalid" class="form-label">{{ __("National ID") }}</label>
                                <input type="text" name="nationalid" value="" class="form-control text-uppercase">
                            </div>
                        @endif

                        <div class="mb-3 col-md-6">
                            <label for="birthplace" class="form-label">{{ __("Place of Birth") }}</label>
                            <input type="text" name="birthplace" value="" class="form-control text-uppercase">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="homeaddress" class="form-label">{{ __("Home Address") }}</label>
                            <input type="text" name="homeaddress" value="" class="form-control text-uppercase">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="imagefile" class="form-label">{{ __("Upload Profile photo") }}</label>
                            <input class="form-control text-uppercase" type="file" name="image" id="imagefile"
                                   accept="image/png, image/jpeg, image/jpg">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="idno" class="form-label">{{ __("ID Number") }}</label>
                            <input type="text" name="idno" value="" class="form-control text-uppercase" required>
                        </div>
                    </div>


                    <p class="lead mt-4">{{ __("Designation") }}</p>
                    <hr>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="company" class="form-label">{{ __("Company") }}</label>
                            <select name="company" class="form-select text-uppercase">
                                <option value="" disabled selected>בחר...</option>
                                @isset($company)
                                    @foreach ($company as $data)
                                        <option value="{{ $data->company }}"> {{ $data->company }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="department" class="form-label">{{ __("Department") }}</label>
                            <select name="department" class="form-select text-uppercase">
                                <option value="" disabled selected>בחר...</option>
                                @isset($department)
                                    @foreach ($department as $data)
                                        <option value="{{ $data->department }}"> {{ $data->department }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="jobtitle" class="form-label">{{ __("Job Title") }}</label>
                            <select name="jobtitle" class="form-select text-uppercase">
                                <option value="" disabled selected>בחר...</option>
                                @isset($jobtitle)
                                    @foreach ($jobtitle as $data)
                                        <option value="{{ $data->jobtitle }}"> {{ $data->jobtitle }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="companyemail" class="form-label">{{ __('Email Address') }} <small
                                        class="text-muted">({{ __("Company") }})</small></label>
                            <input type="text" name="companyemail" value="" class="form-control text-lowercase">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="leaveprivilege" class="form-label">{{ __("Leave Group") }}</label>
                            <select name="leaveprivilege" class="form-select text-uppercase">
                                <option value="" disabled selected>בחר...</option>
                                @isset($leavegroup)
                                    @foreach ($leavegroup as $data)
                                        <option value="{{ $data->id }}"> {{ $data->leavegroup }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>


                    <p class="lead mt-4">{{ __("Employment Information") }}</p>
                    <hr class="mt-0">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="employmenttype" class="form-label">{{ __("Employment Type") }}</label>
                            <select name="employmenttype" class="form-select text-uppercase" required>
                                <option value="" disabled selected>בחר...</option>
                                <option value="Regular">{{ __("Regular") }}</option>
                                <option value="Trainee">{{ __("Trainee") }}</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="employmentstatus" class="form-label">{{ __("Employment Status") }}</label>
                            <select name="employmentstatus" class="form-select text-uppercase" required>
                                <option value="" disabled selected>בחר...</option>
                                <option value="Active">{{ __("Active") }}</option>
                                <option value="Archived">{{ __("Archived") }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="startdate" class="form-label">{{ __("Official Start Date") }}</label>
                            <input type="text" name="startdate" value=""
                                   class="airdatepicker form-control text-uppercase" placeholder="YYYY-MM-DD"
                                   data-position="top left">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="dateregularized" class="form-label">{{ __("Date of Regularization") }}</label>
                            <input type="text" name="dateregularized" value=""
                                   class="airdatepicker form-control text-uppercase" placeholder="YYYY-MM-DD"
                                   data-position="top left">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" value="1" checked id="rest_calc" name="rest_calc">
                                <label class="form-check-label" for="rest_calc">חישוב הפסקה</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i><span class="button-with-icon">{{ __("Save") }}</span>
                    </button>
                    <a href="{{ url('/admin/employee') }}" class="btn btn-secondary">
                        <i class="fas fa-times-circle"></i><span class="button-with-icon">{{ __("Cancel") }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/validate-form.js') }}"></script>
    <script src="{{ asset('assets/vendor/airdatepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/airdatepicker/js/i18n/datepicker.en.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-airdatepicker.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-bootstrap-custom-file-input.js') }}"></script>
@endsection