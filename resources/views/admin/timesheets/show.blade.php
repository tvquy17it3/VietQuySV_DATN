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
            {{ csrf_field() }}
            <h1 class="title">Chi tiết thông tin</h1>
            <div class="media align-items-center py-3 mb-3">
              <img src="{{ $timesheets->employee->user->profile_photo_url }}" alt="{{ $timesheets->employee->user->name }}" class="d-block ui-w-100 rounded-circle">
              <div class="media-body ml-4">
                <h4 class="font-weight-bold mb-0">{{ $timesheets->employee->user->name}}<br><span class="text-muted font-weight-normal">{{ $timesheets->employee->user->email}}</span></h4>
                <div class="text-muted mb-2">ID: {{ $timesheets->employee->id}}</div>
                <a href="{{route('admin.edit-employee',['id'=>$timesheets->employee->user->id])}}" class="btn btn-primary btn-sm">Sửa</a>&nbsp;
                <a href="{{ route('admin.employee-profile',['employee'=>$timesheets->employee->id]) }}" class="btn btn-default btn-sm">Xem</a>&nbsp;
              </div>
            </div>

            <div class="card mb-4">
              <div class="card-body">
                <div>@livewire('edit-timesheet', ['timesheet' => $timesheets])</div>
                <table class="table user-view-table m-0">
                  <tbody>
                    <tr>
                      <td>Check In:</td>
                      <td>{{ date("d/m/Y - H:i", strtotime($timesheets->check_in)) }}
                      </td>
                    </tr>
                    <tr>
                      <td>Checkout</td>
                      @if ($timesheets->status)
                        <td>{{  date("d/m/Y - H:i", strtotime($timesheets->check_out)) }}&nbsp;<span class="fa fa-check text-primary"></span></td>
                      @else
                        <td>
                          <span class="help-block" style="color:red">
                            Chưa check out
                          </span>
                        </td>
                      @endif
                    </tr>
                    <tr>
                      <td>Số giờ</td>
                      <td>{{ $timesheets->hour}} tiếng</td>
                    </tr>
                    <tr>
                      <td>Đi muộn:</td>
                      @if ($timesheets->late == 0)
                        <td>Không</td>
                      @else
                        <td><span class="help-block" style="color:red">Muộn {{ $timesheets->late }}&nbsp; phút</span></td>
                      @endif
                    </tr>
                    <tr>
                      <td>Tọa độ:</td>
                      <td>{{ $timesheets->location}}</td>
                    </tr>
                    <tr>
                      <td>Ghi chú</td>
                      <td>
                        <?php $notes = explode("&", $timesheets->note) ?>
                        @foreach ($notes as $note)
                        <p class="text-info">{{$note}}</p>
                        @endforeach
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr class="border-light m-0">
            </div>
            <div class="row">
              @foreach ($timesheets->timesheet_details as $detail)
                <div class="card" style="width: 16rem;">
                  <img class="card-img-top" src="{{ $detail->img }}" alt="Card image cap">
                  <div class="card-body">
                    <h5 class="card-title">{{ date("H:i:s - d/m/Y", strtotime($detail->created_at))}}</h5>
                    <p class="card-text">{{ $detail->note }}</p>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Face: {{ $detail->confidence }}%</li>
                    <li class="list-group-item">Vi tri: {{ "[".$detail->latitude.",".$detail->longitude."]" }}</li>
                    <li class="list-group-item">Khoảng cách: {{ $detail->distance }}m</li>
                  </ul>
                  <div class="card-body">
                    <a href="http://maps.google.com/maps?z=12&t=m&q={{ ($detail->latitude.','.$detail->longitude) }}"  class="btn btn-primary" target="_blank">Xem trên bản đồ</a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div>
</div>
@endsection
@section('scripts')
<script>
    toastr.options = {
      "newestOnTop": true,
      "progressBar": true,
      "onclick": null,
    }

    window.addEventListener('noti-error', event=>{
        toastr.error(event.detail.message,'Error!!');
    });

    window.addEventListener('noti', event=>{
        toastr.success(event.detail.message,'Success!!');
    })

    window.addEventListener('show_editTimeSheetsModal', event=>{
        $('#editTimeSheetsModal').modal('show');
    })

    window.addEventListener('hide_editTimeSheetsModal', event=>{
        $('#editTimeSheetsModal').modal('hide');
        toastr.success(event.detail.message,'Success!!');
    })
</script>
@endsection
<!-- https://www.bootdey.com/snippets/view/view-user-information#html -->
