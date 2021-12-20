<div>
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
                    <th class="column-title">Họ Tên </th>
                    <th class="column-title">Check in</th>
                    <th class="column-title">Check out </th>
                    <th class="column-title">Số giờ</th>
                    <th class="column-title no-link last"><span class="nobr">Xem</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                    @foreach($timesheets as $values)
                        <tr>
                            <td>{{$values->employee->user->name}}</td>
                            <td>{{$values->check_in}}</td>
                            <td>{{$values->check_out}}</td>
                            <td>{{$values->hour}}</td>
                            <td>
                                <a href="{{ route('admin.view-timesheets-detail', ['id' => $values->id]) }}" type="button" class="btn btn-primary btn-sm">
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
