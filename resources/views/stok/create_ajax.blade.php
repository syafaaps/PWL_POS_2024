<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<form action="{{ url('/stok/store_ajax') }}" method="POST" id="form-tambah-stok">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Supplier selection with Select2 -->
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                        <option value="">- Pilih Supplier -</option>
                        @foreach($supplier as $s)
                            <option value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                </div>
                <!-- Barang selection with Select2 -->
                <div class="form-group">
                    <label>Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control select2" required>
                        <option value="">- Pilih Barang -</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="error-text form-text text-danger"></small>
                </div>
                <!-- User selection with Select2 -->
                <div class="form-group">
                    <label>User</label>
                    <select name="user_id" id="user_id" class="form-control select2" required>
                        <option value="">- Pilih User -</option>
                        @foreach($user as $u)
                            <option value="{{ $u->user_id }}">{{ $u->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <!-- Stok Tanggal -->
                <div class="form-group">
                    <label>Tanggal Stok</label>
                    <input type="datetime-local" class="form-control" id="stok_tanggal" name="stok_tanggal" required>
                    <small id="error-stok_tanggal" class="error-text form-text text-danger"></small>
                </div>
                <!-- Jumlah Stok -->
                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" name="stok_jumlah" id="stok_jumlah" class="form-control" required>
                    <small id="error-stok_jumlah" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<!-- Include Select2 and jQuery Validation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    // Initialize Select2 for supplier, barang, and user dropdowns
    $('#supplier_id').select2({
        placeholder: "- Pilih Supplier -",
        allowClear: true
    });
    $('#barang_id').select2({
        placeholder: "- Pilih Barang -",
        allowClear: true
    });
    $('#user_id').select2({
        placeholder: "- Pilih User -",
        allowClear: true
    });

    // Form validation and submission with AJAX
    $("#form-tambah-stok").validate({
        rules: {
            supplier_id: { required: true, number: true },
            barang_id: { required: true, number: true },
            user_id: { required: true, number: true },
            stok_tanggal: { required: true, date: true },
            stok_jumlah: { required: true, number: true }
        },
        submitHandler: function(form) {
            console.log('Form valid, siap dikirim');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    console.log(response);  // Log response untuk melihat apa yang dikembalikan
                    if(response.status){
                        $('#modal-master').modal('hide'); // Tutup modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataStok.ajax.reload(); // Reload tabel stok
                    } else {
                        $('.error-text').text(''); // Kosongkan pesan error sebelumnya
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]); // Tampilkan pesan error
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Tampilkan error di console
                    console.error('Terjadi error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                }
                // error: function(xhr) {
                //     console.log(xhr.responseText);  // Log pesan error untuk debugging
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Terjadi Kesalahan',
                //         text: 'Ada masalah pada server, coba lagi nanti.'
                //     });
                // }
            });
            return false; // Cegah form dikirim secara normal
        }
    });
});
</script>
