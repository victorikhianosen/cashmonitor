<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<!-- jQuery (required for Toastr) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        @if(session('success'))
            toastr.success(@json(session('success')));
        @endif

        @if(session('error'))
            toastr.error(@json(session('error')));
        @endif

        @if(session('info'))
            toastr.info(@json(session('info')));
        @endif

        @if(session('warning'))
            toastr.warning(@json(session('warning')));
        @endif
    });
</script>
