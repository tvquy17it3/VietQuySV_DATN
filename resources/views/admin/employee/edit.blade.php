@extends('layouts.admin')
@section('title', 'Update Employee')
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
        background: -moz-linear-gradient(left,#f4524d  0%,#5543ca 100%) !important;
        background: -webkit-linear-gradient(left,#f4524d  0%,#5543ca 100%) !important;
        background: linear-gradient(to right,#f4524d  0%,#5543ca  100%) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
    }
  .content{
    margin: 100px;
  }
</style>
@endsection
@section('content')
  <div class="content">
    <h1 class="title">Cập nhật thông tin</h1>
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.update-employee', ['employee' => $employee->id, 'user' => $employee->user->id]) }}" onsubmit="return confirm('Xác nhận cập nhật thông tin?');">
      {{ csrf_field() }}
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputName">Họ Tên</label>
          <input type="text" name="name" class="form-control" value="{{$employee->user->name}}" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputEmail">Email</label>
          <input type="email" name="email" class="form-control" value="{{$employee->user->email}}" disabled>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputPhone">Điện thoại</label>
          <input type="text" name="phone" class="form-control" value="{{$employee->phone}}">
        </div>
        <div class="form-group col-md-3">
          <label for="inputGender">Giới tính</label>
          <select id="inputGender" name="gender" class="form-control">
            <option value= "M" {{ ($employee->gender == 'M') ? 'selected' : '' }}>Nam</option>
            <option value= "F" {{ ($employee->gender == 'F') ? 'selected' : '' }}>Nữ</option>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label for="inputBirth">Ngày sinh</label>
          <input type="date" name="birth_date" class="form-control" id="inputZip" value="{{$employee->birth_date}}">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputAddress">Địa chỉ</label>
          <input type="text" name="address" class="form-control" value="{{$employee->address}}">
        </div>
        <div class="form-group col-md-6">
          <label for="inputFromdate">Ngày vào</label>
          <input type="date" name="from_date" class="form-control" value="{{$employee->from_date}}">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputDepartment">Bộ phận</label>
          <select name="department_id" class="form-control">
            @foreach ($departments as $item)
              <option value="{{ $item->id }}" {{ ( $item->id == $employee->department_id) ? 'selected' : '' }}> {{ $item->name }} </option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-4">
          <label for="inputPosition">Vị trí</label>
          <select name="position_id" class="form-control">
            @foreach ($positions as $item2)
              <option value="{{ $item2->id }}" {{ ( $item2->id == $employee->position_id) ? 'selected' : '' }}> {{ $item2->name }} </option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-4">
          <label for="inputSalary">Lương</label>
          <input type="number" class="form-control" name="salary" value="{{$employee->salary}}" min="0.00" step="1000000" max="1000000000">
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
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
        if (check !="") {
            toastr["success"]("{!! \Session::get('success') !!}");
        }
    });
</script>
@endsection
