@extends('layouts.admin')
@section('title', 'Change Location')
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
          <h1 class="title">Thay đổi vị trí</h1>
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
            <form method="POST" action="{{ route('admin.update_location', ['location' => $geolocation->id]) }}">
              {{ csrf_field() }}
              <div class="field item form-group">
                <label class="col-form-label col-md-3 col-sm-3  label-align">Latitude<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6">
                  <input class="form-control" name="latitude" value="{{ $geolocation->latitude }}" required='required'/>
                </div>
              </div>
              <div class="field item form-group">
                <label class="col-form-label col-md-3 col-sm-3  label-align">Longitude<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6">
                  <input class="form-control" name="longitude" required='required' value="{{ $geolocation->longitude }}"/>
                </div>
              </div>
              <div class="field item form-group">
                <label class="col-form-label col-md-3 col-sm-3  label-align">Tối đa (m)<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6">
                  <input type="number" class="form-control" name="max_distance" min="0" step="10" max="2000000" required='required' value="{{ $geolocation->max_distance }}"/>
                </div>
              </div>
              <div class="field item form-group">
              <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                <div  class="col-md-6 col-sm-6">
                    <button type='submit' class="btn btn-primary">Lưu</button>
                    <a href="http://maps.google.com/maps?z=12&t=m&q={{ ($geolocation->latitude.','.$geolocation->longitude) }}"  class="btn btn-light" target="_blank">Xem</a>
                </div>
              </div>
            </form><br/>
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
