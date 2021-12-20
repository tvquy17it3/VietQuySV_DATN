@extends('layouts.admin')
@section('title', 'Xem hồ sơ')
@section('css')
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
  body{
    margin-top:20px;
    background: #f5f5f5;
  }

  .ui-w-100 {
      width: 100px !important;
      height: auto;
  }

  .card {
      background-clip: padding-box;
      box-shadow: 0 1px 4px rgba(24,28,33,0.012);
  }

  .user-view-table td:first-child {
      width: 9rem;
  }
  .user-view-table td {
      padding-right: 0;
      padding-left: 0;
      border: 0;
  }

  .text-light {
      color: #babbbc !important;
  }

  .card .row-bordered>[class*=" col-"]::after {
      border-color: rgba(24,28,33,0.075);
  }

  .text-xlarge {
      font-size: 170% !important;
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
            <h1 class="title">Thông tin</h1>
            <div class="media align-items-center py-3 mb-3">
              <img src="{{ $employee->user->profile_photo_url }}" alt="{{ $employee->user->name }}" class="d-block ui-w-100 rounded-circle">
              <div class="media-body ml-4">
                <h4 class="font-weight-bold mb-0">{{ $employee->user->name }}<span class="text-muted font-weight-normal"></span></h4>
                <div class="text-muted mb-2">ID: {{ $employee->id }}</div>
                <a href="{{ route('admin.edit-employee',['id'=>$employee->id]) }}" class="btn btn-primary btn-sm">Sửa</a>&nbsp;
              </div>
            </div>

            <div class="card mb-4">
              <div class="card-body">
                <table class="table user-view-table m-0">
                  <tbody>
                    <tr>
                      <td>Họ tên:</td>
                      <td>{{ $employee->user->name }}</td>
                    </tr>
                    <tr>
                      <td>Email:</td>
                      <td>{{ $employee->user->email }}</td>
                    </tr>
                    <tr>
                      <td>Điện thoại:</td>
                      <td>{{ $employee->phone }}</td>
                    </tr>
                    <tr>
                      <td>Địa chỉ:</td>
                      <td>{{ $employee->address }}</td>
                    </tr>
                    <tr>
                      <td>Giới tính:</td>
                      <td>{{ $employee->gender == "M" ? "Nam" : "Nữ" }}</td>
                    </tr>
                    <tr>
                      <td>Ngày sinh:</td>
                      <td>{{ $employee->birth_date }}</td>
                    </tr>
                    <tr>
                      <td>Ngày vào:</td>
                      <td>{{ $employee->from_date }}</td>
                    </tr>
                    <tr>
                      <td>Phòng ban</td>
                      <td>{{ $employee->department->name }}</td>
                    </tr>
                    <tr>
                      <td>Vị trí</td>
                      <td>{{ $employee->position->name }}</td>
                    </tr>
                    <tr>
                      <td>Ngày tạo hồ hơ</td>
                      <td>{{ $employee->created_at->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                      <td>Cập nhật</td>
                      <td>{{ $employee->updated_at->format('Y-m-d') }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr class="border-light m-0">
            </div>
            @livewire('timesheets-employee', ['employee_id' => $employee->id])
          </div>
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
  </script>
@endsection
