<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <!-- Header Modal -->
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Barang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <!-- Body Modal -->
        <div class="modal-body">
            <!-- Menampilkan Kategori Barang -->
            <div class="form-group">
                <label>Kategori Barang</label>
                <p>{{ $barang->kategori->kategori_nama ?? 'Tidak Ada Kategori' }}</p>
            </div>
            
            <!-- Menampilkan Kode Barang -->
            <div class="form-group">
                <label>Kode Barang</label>
                <p>{{ $barang->barang_kode ?? 'Tidak Ada Kode' }}</p>
            </div>

            <!-- Menampilkan Nama Barang -->
            <div class="form-group">
                <label>Nama Barang</label>
                <p>{{ $barang->barang_nama ?? 'Tidak Ada Nama Barang' }}</p>
            </div>

            <!-- Menampilkan Harga Beli -->
            <div class="form-group">
                <label>Harga Beli</label>
                <p>{{ isset($barang->harga_beli) ? number_format($barang->harga_beli, 0, ',', '.') : 'Tidak Ada Harga Beli' }}</p>
            </div>

            <!-- Menampilkan Harga Jual -->
            <div class="form-group">
                <label>Harga Jual</label>
                <p>{{ isset($barang->harga_jual) ? number_format($barang->harga_jual, 0, ',', '.') : 'Tidak Ada Harga Jual' }}</p>
            </div>
        </div>
        
        <!-- Footer Modal -->
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>
