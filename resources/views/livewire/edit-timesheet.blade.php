<div>
  <div>
    <button class="btn btn-secondary float-right" data-toggle="modal" data-target="#editTimeSheetsModal">Sửa</button>
  </div>
  <div class="modal fade" id="editTimeSheetsModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore
    wire:key="first">
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
                <input class="form-control" type="text" placeholder="Search Employee..." wire:model="email"
                  value="{{ $this->email }}" readonly />
              </div>
            </div>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Ca làm việc:</label>
              <select class="form-control" wire:model="shift_id">
                @foreach ($shifts as $item3)
                <option value="{{ $item3->id }}" {{ ($item3->id == $this->shift_id) ? 'selected' : '' }}>
                  {{ $item3->getTimeCheck() }}</option>
                @endforeach
              </select>
            </div>
            @error('shift_id') <span class="error text-danger">Hãy chọn ca làm việc</span> @enderror
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Check in:</label>
              <input class="form-control date" type="datetime-local" name="check_in" required='required'
                wire:model="check_in" value="{{ $this->check_in }}">
            </div>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Check out:</label>
              <input class="form-control date" type="datetime-local" name="check_out" required='required'
                wire:model="check_out" value="{{ $this->check_out }}">
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
