@extends('user.layouts.app')
@section('title', 'Edit Role - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Edit Role</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.roles') }}">Roles</a></li>
               <li class="breadcrumb-item active">Edit Role</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.edit_role', ['id' => $role->role_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.roles') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="role_form" action="{{ route('user.edit_role', ['id' => $role->role_id]) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Edit Role</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-12 col-md-12">
                              <div class="mb-3">
                                 <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="role_name" class="form-control" value="{{ $role->role_name }}" autocomplete="off">
                                 @if($errors->has('role_name'))
                                 <div>{{ $errors->first('role_name') }}</div>
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <div class="">
                                    <table class="table table-bordered table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center">Modules</th>
                                                <th>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="w-100 text-center">Permissions</span>
                                                        <div class="form-check mb-0">
                                                            <input type="checkbox" class="form-check-input" id="select_all_permissions">
                                                            <label class="form-check-label fw-semibold" for="select_all_permissions">All</label>
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($main_modules as $main_module)
                                            <tr style="text-align: center">
                                                <td colspan="2"><b>{{ $main_module->mmodule_name }}</b></td>
                                            </tr>
                                            @if(isset($modules[$main_module->mmodule_id]))
                                            @foreach($modules[$main_module->mmodule_id] as $module)
                                            <tr>
                                                <td><b>{{ $module['name'] }}</b></td>
                                                <td>
                                                    <div class="row" style="padding-top: 10px;">
                                                        @if(isset($permissions[$module['id']]))
                                                        @foreach($permissions[$module['id']] as $permission)
                                                        <div class="col-md-2">
                                                            <div class="form-check">
                                                                @if(isset($role_permissions) && in_array($permission['id'],$role_permissions))
                                                                <input type="checkbox" name="permissions[]" class="form-check-input permission_select" value="{{ $permission['id'] }}" id="permission_{{ $permission['id'] }}" checked>
                                                                @else
                                                                <input type="checkbox" name="permissions[]" class="form-check-input permission_select" value="{{ $permission['id'] }}" id="permission_{{ $permission['id'] }}">
                                                                @endif
                                                                <label class="form-check-label" for="permission_{{ $permission['id'] }}">
                                                                    {{ $permission['name'] }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>
                  <div class="d-flex justify-content-end flex-wrap align-items-center gap-2">
                     <button type="submit" name="submit" class="btn btn-primary" id="save-basic-info" value="submit">Submit</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script>
    $(function () {
        $('#select_all_permissions').on('change', function () {
            $('.permission_select').prop('checked', $(this).is(':checked'));
        });

        $('.permission_select').on('change', function () {
            var isAllChecked = $('.permission_select').length === $('.permission_select:checked').length;
            $('#select_all_permissions').prop('checked', isAllChecked);
        }).trigger('change');
    });
</script>
@endsection
