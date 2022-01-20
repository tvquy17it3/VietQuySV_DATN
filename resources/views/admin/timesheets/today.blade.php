@extends('layouts.admin')
@section('title', 'Today')

@section('content')
    <div class="content">
        @livewire('today-timesheet')
    </div>
@endsection
@section('scripts')
<script>
    toastr.options = {
      "newestOnTop": true,
      "progressBar": true,
      "onclick": null,
    }

    window.addEventListener('noti', event=>{
        toastr.success(event.detail.message,'Success!!');
    })

    window.addEventListener('noti-error', event=>{
        toastr.error(event.detail.message,'Error!!');
    })

    window.addEventListener('hide-modal-noti-error', event=>{
        $('#confirm-delete').modal('hide');
        toastr.error(event.detail.message,'Error!!');
    })
</script>
@endsection
