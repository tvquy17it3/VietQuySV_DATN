@extends('layouts.admin')
@section('title', 'Accounts')

@section('content')
    <div class="content">
        @livewire('manager-account')
    </div>
@endsection
@section('scripts')
<script>
    toastr.options = {
      "newestOnTop": true,
      "progressBar": true,
      "onclick": null,
    }

    window.addEventListener('show-delete-modal', event=>{
        $('#confirm-delete').modal('show');
    })

    window.addEventListener('hide-delete-modal', event=>{
        $('#confirm-delete').modal('hide');
        toastr.success(event.detail.message,'Success!!');
    })

    window.addEventListener('show-restore-modal', event=>{
        $('#confirm-restore').modal('show');
    })

    window.addEventListener('hide-restore-modal', event=>{
        $('#confirm-restore').modal('hide');
        toastr.success(event.detail.message,'Success!!');
    })

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
