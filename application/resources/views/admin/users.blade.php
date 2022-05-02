@extends('layouts.admin')

@section('meta')
    <title>Users | Workday Time Clock</title>
    <meta name="description" content="Workday Users">
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
                        {{ __("Users") }}
                    </h2>
                </div>

                <div class="col-md-6 mb-1 text-end">
                    <a href="{{ url('admin/user/roles') }}" class="btn btn-outline-success btn-sm me-2">
                        <i class="fas fa-users"></i><span class="button-with-icon">{{ __("Roles") }}</span>
                    </a>
                    <a href="{{ url('admin/users/add') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i><span class="button-with-icon">{{ __("Add") }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table width="100%" class="table datatables-table custom-table-ui" data-order='[[ 0, "desc" ]]'>
                <thead>
                    <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Email") }}</th>
                        <th>{{ __("Role") }}</th>
                        <th>{{ __("Type") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($users_roles)
                    @foreach ($users_roles as $data)
                    <tr>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->email }}</td>
                        <td>{{ $data->role_name }}</td>
                        <td> @if($data->acc_type == 2) Admin @else Employee @endif </td>
                        <td>
                            @if($data->status == '1') 
                                Enabled
                            @else
                                Disabled
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ url('admin/users/edit') }}/{{ $data->id }}" class="btn btn-outline-secondary btn-sm btn-rounded"><i class="fas fa-pen"></i></a>

                            <a href="#" data-url="{{ url('admin/users/delete') }}/{{ $data->id }}" class="btn btn-outline-secondary btn-sm btn-rounded btnDelete" data-bs-toggle="modal" data-bs-target="#confirmationModal"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/initiate-datatables.js') }}"></script> 
    <script src="{{ asset('assets/js/confirmationModal.js') }}"></script>
@endsection