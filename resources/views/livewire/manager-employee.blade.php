<div>
    <div class="page-title">
        <div class="title_left">
            <h3>Manage<small> Employees</small></h3>
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
                                <table id="datatable-fixed-header" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID Employee</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Date Of Birth</th>
                                            <th>Phone</th>
                                            <th>From date</th>
                                            <th>Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $values)
                                        <tr>
                                            <td>{{$values->id}}</td>
                                            <td>{{$values->user->name}}</td>
                                            <td>{{$values->user->email}}</td>
                                            <td>{{$values->birth_date}}</td>
                                            <td>{{$values->phone}}</td>
                                            <td>{{$values->from_date}}</td>
                                            <td>{{$values->department_id}}</td>
                                            <td>
                                                <a href="{{route('admin.view-timesheet',['employee'=>$values->id])}}" type="button" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{route('admin.edit-employee',['id'=>$values->id])}}" type="button" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
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
                <div style="float: right;">
                    {!! $employees->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
