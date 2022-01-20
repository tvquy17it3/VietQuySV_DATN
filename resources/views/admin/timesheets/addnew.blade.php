@extends('layouts.admin')
@section('title', 'New')

@section('content')
    <div class="content">
        @livewire('addnew-timesheet')
    </div>
@endsection
@section('scripts')
<script>
    toastr.options = {
      "newestOnTop": true,
      "progressBar": true,
      "onclick": null,
    }

    window.addEventListener('noti-error', event=>{
        toastr.error(event.detail.message,'Error!!');
    });

    window.addEventListener('noti', event=>{
        toastr.success(event.detail.message,'Success!!');
    })
</script>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[list]').forEach( function (formfield) {
        var datalist = document.getElementById(formfield.getAttribute('list'));
        var lastlength = formfield.value.length;
        var checkInputValue = function (inputValue) {
            if (inputValue.length - lastlength > 1) {
            datalist.querySelectorAll('option').forEach( function (item) {
                if (item.value === inputValue) {
                    // formfield.form.submit();
                    Livewire.emit('postAdded', item.value);
                }
            });
            }
            lastlength = inputValue.length;
        };
        formfield.addEventListener('input', function () {
            checkInputValue(this.value);
        }, false);
    });
</script>
@endpush
