@extends('layouts.admin')
@section('title', 'Month')

@section('content')
    <div class="content">
        @livewire('month-employee')
    </div>
@endsection
@section('scripts')
<script>
    toastr.options = {
      "newestOnTop": true,
      "progressBar": true,
      "onclick": null,
    }
</script>
@endsection
