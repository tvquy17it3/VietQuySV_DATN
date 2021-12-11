@extends('layouts.admin')
@section('title', 'Change Shifts')
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
          <h1 class="title">Thay đổi thời gian</h1>
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
          @foreach ($shifts as $values)
            <form method="POST" action="{{ route('admin.update_shifts', ['shift' => $values->id]) }}">
              {{ csrf_field() }}
              <div class="field item form-group">
                <label class="col-form-label col-md-3 col-sm-3  label-align">Ca làm việc<span class="required"></span></label>
                <div class="col-md-2 col-sm-2">
                  <input class="form-control" type="text" name="name" value="{{ old('name', $values->name) }}">
                </div>
                <div class="col-md-2 col-sm-2">
                  <input class="form-control" class='date' type="time" name="check_in" required='required' value="{{ old('check_in', (\Carbon\Carbon::createFromFormat('H:i:s',$values->check_in)->format('h:i'))) }}">
                </div>
                <div class="col-md-2 col-sm-2">
                  <input class="form-control" class='date' type="time" name="check_out" required='required' value="{{ old('check_out', (\Carbon\Carbon::createFromFormat('H:i:s',$values->check_out)->format('h:i'))) }}">
                </div>
                <div class="col-md-1 col-sm-1">
                  <button type='submit' class="btn btn-primary">Lưu</button>
                </div>
              </div>
            </form><br/>
          @endforeach
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
