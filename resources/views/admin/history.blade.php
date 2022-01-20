@extends('layouts.admin')
@section('title', 'Xem lịch sử xóa')
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
          <h2 class="title">Xem lịch sử xóa</h2>
          <div class="x_content">
            <div class="table-responsive">
              <table class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr class="headings">
                  <th class="column-title">#</th>
                    <th class="column-title">Họ Tên </th>
                    <th class="column-title">Email</th>
                    <th class="column-title">Điện thoại</th>
                    <th class="column-title">Check in</th>
                    <th class="column-title">Ngày tạo</th>
                    <th class="column-title no-link last"><span class="nobr">Xem</span>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php $stt=0; ?>
                  @foreach($timesheets as $values)
                  <tr>
                    <td>{{ $stt+=1 }}</td>
                    <td>{{ $values->employee->user->name }}</td>
                    <td>{{ $values->employee->user->email }}</td>
                    <td>{{ $values->employee->phone }}</td>
                    <td>{{ date("d/m/Y - H:i", strtotime($values->check_in)) }}</td>
                    <td>{{ date("d/m/Y - H:i", strtotime( $values->created_at)) }}</td>
                    <td>
                      <a href="{{ route('admin.view-timesheets-detail', ['id' => $values->id]) }}" type="button"
                        class="btn btn-primary btn-sm">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div style="float: right;">
              {!! $timesheets->links() !!}
            </div>
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
