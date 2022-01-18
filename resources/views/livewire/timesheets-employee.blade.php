<div>
  <div class="w-full" style="height: 60%;">
    <div class="px-10" id="chart"></div>
  </div>
  <div class="card">
    <div class="row no-gutters row-bordered">
      <div class="d-flex col-md align-items-center">
        <a href="javascript:void(0)" class="card-body d-block text-body">
          <div class="text-muted small line-height-1"></div>
          <div class="text-xlarge">Lịch sử chấm công</div>
        </a>
      </div>
    </div>
    <hr class="border-light m-0">
    <div class="card-body">
      <table class="table table-striped jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title">Tháng</th>
            <th class="column-title">Check in</th>
            <th class="column-title">Check out </th>
            <th class="column-title">Số giờ</th>
            <th class="column-title">Đi muộn</th>
            <th class="column-title no-link last"><span class="nobr">Xem</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php $month = 0; ?>
          @foreach($timesheets as $values)

          @if ($month == 0)
          <?php $month = date("m/Y", strtotime($values->check_in)); ?>
          <tr>
            <td colspan="6" class="p-3 mb-2 bg-secondary text-white text-center"><b>{{$month}}</b></td>
          </tr>
          @endif
          @if ($month == date("m/Y", strtotime($values->check_in)))
          <tr>
            <td></td>
            <!-- <td>{$values->employee->user->name}</td> -->
            <td>{{ date("d/m/Y - H:i", strtotime($values->check_in))}}</td>
            <td>{{ date("d/m/Y - H:i", strtotime($values->check_out))}}</td>
            <td>{{$values->hour}}</td>
            <td style="color:red">
                @if ($values->late > 0)
                    {{$values->late}} phút
                @endif
            </td>
            <td>
              <a href="{{ route('admin.view-timesheets-detail', ['id' => $values->id]) }}" type="button"
                class="btn btn-primary btn-sm">
                <i class="fa fa-eye" aria-hidden="true"></i>
              </a>
            </td>
          </tr>
          @else
          <?php
              $month = date("m/Y", strtotime($values->check_in))
          ?>
          <tr>
            <td colspan="6" class="p-3 mb-2 bg-secondary text-white text-center"><b>{{$month}}</b></td>
          </tr>
          <tr>
            <td></td>
            <td>{{ date("d/m/Y - H:i", strtotime($values->check_in))}}</td>
            <td>{{ date("d/m/Y - H:i", strtotime($values->check_out))}}</td>
            <td>{{$values->hour}}</td>
            <td style="color:red">
                @if ($values->late > 0)
                    {{$values->late}} phút
                @endif
            </td>
            <td>
              <a href="{{ route('admin.view-timesheets-detail', ['id' => $values->id]) }}" type="button"
                class="btn btn-primary btn-sm">
                <i class="fa fa-eye" aria-hidden="true"></i>
              </a>
            </td>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">
    var options = {
    chart: {
        type: 'line',
        height: '350px',
        animations: {
        enabled: false,
        }
    },

    series: [{
        name: 'Số lần Check In',
        data: @json($counts)
    },
    {
        name: 'Tổng giờ làm',
        data: @json($sum_hours)
    },
    {
        name: 'Tổng giờ đi muộn',
        data: @json($late)
    }],

    xaxis: {
        categories: @json($months)
    },
    title: {
        text: 'Thống kê theo tháng',
    },
    noData: {
        text: 'Loading...'
    }
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
