<script>
    // Script untuk menampilkan konten modal create dengan AJAX
    $(document).on("click", ".create-modal-btn", function() {
        $.get("{{ route('data-penduduk/create') }}", function(data) {
            $('#createModal .modal-content').html(data);
            $('#createModal').modal('show');
        });
    });
</script>