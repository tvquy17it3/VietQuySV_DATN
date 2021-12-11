<div>
  <div class="page-title">
    <div class="title_left">
      <h3>Manage<small> Timesheets</small></h3>
    </div>
    <div class="title_right">
      <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
        <div class="input-group">
          <input class="form-control" wire:model.debounce.500ms="search" type="text" placeholder="Search employee..." />
          <span class="input-group-btn">
            <button class="btn btn-secondary" type="button">Go!</button>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="col-md-12 col-sm-12  ">
    <div class="x_panel">
      <div class="x_title">
        <h2><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTimeSheetsModal" wire:click="reset_att">Thêm</button> </h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="margin-right: 10px;">
            <input type="date" value="{{ $date }}" class="form-control" wire:model="date">
          </li>
          <li style="margin-right: 10px;">
            <select name="shirft" class="form-control" wire:model="select_shifts" >
              <option value="0"> Cả ngày </option>
              @foreach ($shifts as $item)
                <option value="{{ $item->id }}"> {{ $item->getTimeCheck() }} </option>
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
                <!-- <th>
                  <input type="checkbox" id="check-all" class="flat">
                </th> -->
                <th class="column-title">Họ Tên </th>
                <th class="column-title">Email</th>
                <th class="column-title">Điện thoại</th>
                <!-- <th class="column-title">Bộ phận</th> -->
                <th class="column-title">Check in</th>
                <th class="column-title">Check out </th>
                <th class="column-title">Số giờ</th>
                <th class="column-title no-link last"><span class="nobr">Hành động</span>
                </th>
              </tr>
            </thead>

            <tbody>
                @foreach($timesheets as $values)
                    <tr>
                        <!-- <td class="a-center ">
                            <input type="checkbox" class="flat" name="table_records">
                        </td> -->
                        <td>{{$values->employee->user->name}}</td>
                        <td>{{$values->employee->user->email}}</td>
                        <td>{{$values->employee->phone}}</td>
                        <!-- <td>{{$values->employee->department->name}}</td> -->
                        <td>{{$values->check_in}}</td>
                        <td>{{$values->check_out}}</td>
                        <td>{{$values->hour}}</td>
                        <td>
                            <a href="{{ route('admin.view-timesheets-detail', ['id' => $values->id]) }}" type="button" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <a href="" type="button" class="btn btn-primary btn-sm" wire:click.prevent="show_edit('{{ $values->employee->user->email }}', {{ $values->id}},'{{$values->shift_id }}','{{$values->check_in }}','{{$values->check_out }}')">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
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
        <div style="float: right;">
          {!! $timesheets->links() !!}
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="newTimeSheetsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  wire:ignore.self>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form wire:submit.prevent="save">
          <div class="modal-body">
            <div class="form-group">
              <div>
                <input class="form-control" type="text" placeholder="Search Employee..." wire:model="input_search" list="employees"/>
                <datalist id="employees" required='required'>
                    @foreach ($data_search as $item1)
                      <option value="{{ $item1->user->email }}">{{$item1->user->name." | ".$item1->phone}}</option>
                    @endforeach
                </datalist>
              </div>
            </div>
            @error('employee_id') <span class="error text-danger">Hãy chọn email</span> @enderror
            <div class="form-group" wire:ignore>
              <label for="recipient-name" class="col-form-label">Ca làm việc:</label>
              <select class="form-control" wire:model="shift_id">
                <option value="" selected>Chọn</option>
                @foreach ($shifts as $item)
                  <option value="{{ $item->id }}"> {{ $item->getTimeCheck() }} </option>
                @endforeach
              </select>
            </div>
            @error('shift_id') <span class="error text-danger">Hãy chọn ca làm việc</span> @enderror
            <div class="form-group" wire:ignore>
              <label for="recipient-name" class="col-form-label">Check in:</label>
              <input class="form-control date" type="datetime-local" name="check_in" required='required' wire:model="check_in">
              @error('check_in') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group" wire:ignore>
              <label for="recipient-name" class="col-form-label">Check out:</label>
              <input class="form-control date" type="datetime-local" name="check_out" required='required' wire:model="check_out">
              @error('check_out') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="exampleFormControlTextarea1">Ghi chú</label>
              <textarea class="form-control" rows="3" wire:model="note"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary" id="btn-add">Lưu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editTimeSheetsModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore wire:key="first">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Sửa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form wire:submit.prevent="edit">
          <div class="modal-body">
            <div class="form-group">
              <div>
                <input class="form-control" type="text" placeholder="Search Employee..."  wire:model="email"  value="{{ $this->email }}" readonly/>
              </div>
            </div>
            <div class="form-group" >
              <label for="recipient-name" class="col-form-label">Ca làm việc:</label>
              <select class="form-control" wire:model="shift_id">
                @foreach ($shifts as $item3)
                  <option value="{{ $item3->id }}" {{ ($item3->id == $this->shift_id) ? 'selected' : '' }}> {{ $item3->getTimeCheck() }}</option>
                @endforeach
              </select>
            </div>
            @error('shift_id') <span class="error text-danger">Hãy chọn ca làm việc</span> @enderror
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Check in:</label>
              <input class="form-control date" type="datetime-local" name="check_in" required='required' wire:model="check_in" value="{{ $this->check_in }}">
            </div>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Check out:</label>
              <input class="form-control date" type="datetime-local" name="check_out" required='required' wire:model="check_out" value="{{ $this->check_out }}">
            </div>
            <!-- <div class="form-group">
              <label for="exampleFormControlTextarea1">Ghi chú</label>
              <textarea class="form-control" rows="3"></textarea>
            </div> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary" id="btn-add">Lưu</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
