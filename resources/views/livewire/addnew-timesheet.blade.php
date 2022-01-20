<div>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="save">
                @if($errors->has('check_out'))
                    <div class="error" hidden>{{ $errors->first('check_out') }}</div>
                @endif
                <div class="modal-body">
                    <div class="form-group">
                        <div>
                        <input class="form-control" type="text" placeholder="Search" wire:model="input_search" list="employees"/>
                        <datalist id="employees" required='required'>
                            @foreach ($data_search as $item1)
                                <option value="{{ $item1->user->email }}">{{$item1->user->name." | ".$item1->phone}}</option>
                            @endforeach
                        </datalist>
                        </div>
                    </div>
                    @error('employee_id') <span class="error text-danger">Hãy chọn email</span> @enderror
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Ca làm việc:</label>
                        <select class="form-control" wire:model="shift_id">
                        <option value="" selected>Chọn</option>
                        @foreach ($shifts as $item)
                            <option value="{{ $item->id }}"> {{ $item->getTimeCheck() }} </option>
                        @endforeach
                        </select>
                    </div>
                    @error('shift_id') <span class="error text-danger">Hãy chọn ca làm việc</span> @enderror
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Check in:</label>
                        <input class="form-control date" type="datetime-local" name="check_in" required='required' wire:model="check_in">
                        @error('check_in') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
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
                    <button type="submit" class="btn btn-primary" id="btn-add">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
