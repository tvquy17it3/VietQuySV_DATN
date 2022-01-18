<div>
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2 style="margin-top: 10px;">Hôm nay: {{ $today_date }}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="margin-right: 10px;">
                        <input type="date" value="{{ $date }}" class="form-control" wire:model="date">
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
        </div>
    </div>

    <div class="row" style="display: block;">
        <div class="col-md-6 col-sm-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Chưa check in<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">#</th>
                                <th class="column-title">Họ Tên </th>
                                <th class="column-title">Điện thoại</th>
                                <th class="column-title">Email</th>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $stt1 = 0 ?>
                            @foreach($employees_notcheckin as $values)
                            <tr>
                                <td>{{ $stt1+=1 }}</td>
                                <td>{{$values->user->name}}</td>
                                <td>{{$values->phone}}</td>
                                <td>{{$values->user->email}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Đã check in<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">#</th>
                                <th class="column-title">Họ Tên </th>
                                <th class="column-title">Điện thoại</th>
                                <th class="column-title">Check in</th>
                                <th class="column-title">Check out </th>
                                <th class="column-title">Số giờ</th>
                                <th class="column-title">Đi muộn</th>
                                <th class="column-title no-link last"><span class="nobr">Hành động</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $stt2 = 0 ?>
                            @foreach($employees_checkin as $values)
                            <tr>
                                <td>{{ $stt2+=1 }}</td>
                                <td>{{$values->employee->user->name}}</td>
                                <td>{{$values->employee->phone}}</td>
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
                                    <a type="button"
                                        onclick="confirm('Bạn có chắc chắn xóa?') || event.stopImmediatePropagation()"
                                        wire:click="confirmRemoved('{{ $values->id}}')" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
