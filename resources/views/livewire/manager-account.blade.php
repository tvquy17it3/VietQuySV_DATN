<div>
    <div class="page-title">
        <div class="title_left">
            <h3>Manage<small> User</small></h3>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                    <input class="form-control" wire:model.debounce.500ms="search" type="text" placeholder="Search users..." />
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button">Go!</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <div class="col-md-12" style="margin-bottom: 20px;">
                                    <div class="col-md-2">
                                        <div class="d-flex align-items-center ml-4">
                                            <label for="paginate" class="text-nowrap mr-2 mb-0">Hiển thị</label>
                                            <select wire:model="paginate" name="paginate" id="paginate" class="form-control form-control-sm">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="dropdown ml-4">
                                            @if($checked)
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                    Đã chọn <span class="badge badge-light"> {{ count($checked) }} </span>
                                                </button>
                                                <button type="button" class="btn btn-secondary" wire:click="emptyChecked">
                                                    Hủy chọn
                                                </button>
                                            @endif
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" type="button"
                                                    onclick="confirm('Khóa các tài khoản đã chọn?') || event.stopImmediatePropagation()"
                                                    wire:click="blockChecked">
                                                    Khóa tài khoản
                                                </a>
                                                <a class="dropdown-item" type="button"
                                                    onclick="confirm('Bạn có chắc chắn xóa các tài khoản đã chọn. Mọi bài viết của tài khoản này cũng sẽ bị xoá?') || event.stopImmediatePropagation()"
                                                    wire:click="deleteChecked">
                                                    Xóa tài khoản
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table id="datatable-fixed-header" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Email verified at</th>
                                            <th>Created at</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $values)
                                        <tr class="@if($this->isChecked($values->id)) table-primary @endif">
                                             <td>
                                                <input type="checkbox" name="" wire:model="checked" value="{{$values->id}}">
                                            </td>
                                            <td>{{$values->id}}</td>
                                            <td>{{$values->name}}</td>
                                            <td>{{$values->email}}</td>
                                            <td>{{$values->employee ? $values->employee->phone : ""}}</td>
                                            <td>{{$values->roles->name}}</td>
                                            <td>{{$values->email_verified_at}}</td>
                                            <td>{{$values->created_at}}</td>
                                            <td>
                                                <button type="button" wire:click.prevent="confirmUserRemoved({{ $values->id}},'{{$values->email }}')" class="btn btn-danger btn-sm"><i class="fa fa-ban" aria-hidden="true"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="float: right;">
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Khoá tài khoản</h2>
                </div>
                <div class="modal-body">
                    <p>Xác nhận khoá tài khoản: <b><i class="title">{{ $this->email}}</i></b>, tài khoản sẽ bị ẩn, bạn vẫn có thể khôi phục lại.</p>
                    <p>Bạn có muốn tiếp tục! <i class="dataid"></i></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="blockUser" class="btn btn-danger">Block</button>
                </div>
            </div>
        </div>
    </div>
</div>
