@extends('layouts.admin')
@section('title', 'Create Employee')
@section('css')
<meta name="csrf_token" content="{{ csrf_token() }}" />
<style>
  .title {
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 3px;
    font-size: 3.2em;
    line-height: 48px;
    padding-bottom: 48px;
    color: #5543ca;
    background: #5543ca;
    background: -moz-linear-gradient(left, #f4524d 0%, #5543ca 100%) !important;
    background: -webkit-linear-gradient(left, #f4524d 0%, #5543ca 100%) !important;
    background: linear-gradient(to right, #f4524d 0%, #5543ca 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
  }
  .notice-errors{
    margin: 10px;
    margin-left: auto;
    margin-right: auto;
    width: 60%;
    align-items: center;
  }
</style>
@endsection
@section('content')
<div class="content">
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="x_panel">
        <div class="x_title">
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form method="POST" action="{{ route('admin.store-employee') }}">
            {{ csrf_field() }}
            <h1 class="title">Thêm thông tin</h1>
            <div class="notice-errors">
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
            </div>
            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3  label-align">Họ tên<span class="required">*</span></label>
              <div class="col-md-6 col-sm-6">
                <input class="form-control" name="name" value="{{ old('name') }}" required autofocus/>
              </div>
            </div>
            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3  label-align">Số điện thoại<span
                  class="required">*</span></label>
              <div class="col-md-6 col-sm-6">
                <input class="form-control" type="number" name="phone" value="{{ old('phone') }}" required>
              </div>
            </div>

            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3  label-align">Địa chỉ<span
                  class="required">*</span></label>
              <div class="col-md-6 col-sm-6">
                <input class="form-control" type="text" name="address" value="{{ old('address') }}" required='required' />
              </div>
            </div>

            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3  label-align">Ngày sinh<span class="required">*</span></label>
              <div class="col-md-3 col-sm-3">
                <input class="form-control" class='date' type="date" name="birth_date" required='required' value="{{ old('birth_date') }}">
              </div>
              <div class="col-md-3 col-sm-3">
                <select id="inputGender" name="gender" class="form-control" required>
                  <option value="" disabled selected>Chọn giới tính</option>
                  <option value="M" @if (old('gender') == "M") {{ 'selected' }} @endif>Nam</option>
                  <option value="F" @if (old('gender') == "F") {{ 'selected' }} @endif>Nữ</option>
                </select>
              </div>
            </div>

            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align">Ngày vào<span class="required">*</span></label>
              <div class="col-md-3 col-sm-3">
                <input class="form-control" class='date' type="date" name="from_date" required='required' value="{{ old('from_date') }}">
              </div>
              <div class="col-md-3 col-sm-3">
                <input type="number" class="form-control" name="salary" min="0.00" step="1000000" max="1000000000" placeholder="Nhập lương" required='required' value="{{ old('salary') }}">
              </div>
            </div>

            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align">Bộ phận<span class="required">*</span></label>
              <div class="col-md-3 col-sm-3">
                <select name="department_id" class="form-control" required='required'>
                  <option value="" disabled selected>Chọn bộ phận</option>
                    @foreach ($departments as $item)
                      <option value="{{ $item->id }}" @if (old('department_id') == $item->id) {{ 'selected' }} @endif> {{ $item->name }} </option>
                    @endforeach
                </select>
              </div>
              <div class="col-md-3 col-sm-3">
                <select name="position_id" class="form-control" required='required'>
                  <option value="" disabled selected>Chọn vị trí</option>
                  @foreach ($positions as $item2)
                    <option value="{{ $item2->id }}" @if (old('position_id') == $item2->id) {{ 'selected' }} @endif> {{ $item2->name }} </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align">Email<span class="required">*</span></label>
              <div class="col-md-6 col-sm-6">
                <input class="form-control" name="email" required="required" type="email" value="{{ old('email') }}"/>
              </div>
            </div>

            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align">Mật khẩu<span
                  class="required">*</span></label>
              <div class="col-md-6 col-sm-6">
                <input class="form-control" type="password" name="password" id="password" required="required" />
                <span style="position: absolute;right:15px;top:7px;" onclick="hideshow()">
                  <i id="slash" class="fa fa-eye-slash"></i>
                  <i id="eye" class="fa fa-eye"></i>
                </span>
              </div>
            </div>

            <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3  label-align">NHập lại mật khẩu<span
                  class="required">*</span></label>
              <div class="col-md-6 col-sm-6">
                <input class="form-control" type="password" name="password_confirmation" required="required" />
              </div>
            </div>
            <div class="ln_solid">
              <div class="form-group">
                <div class="col-md-6 offset-md-3">
                  <button type='submit' class="btn btn-primary">Submit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script>
    toastr.options = {
      "newestOnTop": true,
      "progressBar": true,
      "onclick": null,
    }

    $(document).ready(function() {
      var check = "{{\Session::has('success')}}";
      if (check != "") {
        toastr["success"]("{!! \Session::get('success') !!}");
      }
    });

    function hideshow() {
      var password = document.getElementById("password");
      var slash = document.getElementById("slash");
      var eye = document.getElementById("eye");

      if (password.type === 'password') {
        password.type = "text";
        slash.style.display = "block";
        eye.style.display = "none";
      } else {
        password.type = "password";
        slash.style.display = "none";
        eye.style.display = "block";
      }
    }
  </script>
@endsection
