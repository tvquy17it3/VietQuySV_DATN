<div>
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2 style="margin-top: 10px;">Hôm nay: {{ $today_date }}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="margin-right: 10px;">
                        <input type="month" value="{{ $month }}" class="form-control" wire:model="month">
                    </li>
                    <li style="margin-right: 10px;">
                        <select name="department" class="form-control" wire:model="select_department" >
                        @foreach ($departments as $item)
                            <option value="{{ $item->id }}"> {{ $item->name }} </option>
                        @endforeach
                        </select>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">#</th>
                                <th class="column-title">Họ Tên </th>
                                <th class="column-title">Email</th>
                                <th class="column-title">Điện thoại</th>
                                <th class="column-title">Số lần check in</th>
                                <th class="column-title">Giờ làm</th>
                                <th class="column-title">Đi muộn</th>
                                <th class="column-title">Số giờ đi muộn</th>
                                <th class="column-title no-link last"><span class="nobr">Xem</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $stt1 = 0 ?>
                            @foreach($employees as $values)
                                <tr>
                                    <td>{{ $stt1+=1 }}</td>
                                    <td>{{$values->user->name}}</td>
                                    <td>{{$values->user->email}}</td>
                                    <td>{{$values->phone}}</td>
                                    <td>{{$values->timesheets_count}}</td>
                                    @if (!$values->timesheets->isEmpty())
                                        <?php $late = 0; $sum_late = 0; $hours=0; ?>
                                        @foreach ($values->timesheets as $ts)
                                            @if ($ts->late > 0)
                                                <?php $late+=1;  $sum_late+=$ts->late; ?>
                                            @endif
                                            <?php $hours+=$ts->hour; ?>
                                        @endforeach
                                        <td>{{ round($hours) }}</td>
                                        <td>{{ $late }}</td>
                                        <td>{{ round( $sum_late/60, 1, PHP_ROUND_HALF_UP) }}</td>
                                    @else
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                    @endif
                                    <td>
                                        <a href="{{ route('admin.employee-profile',['employee'=>$values->id]) }}" type="button" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <div>
</div>
