<div class="modal fade" tabindex="-1" role="dialog" id="modalCreate">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Kategori Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate="" action="{{ route('kategori_menu.create') }}"
                            method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <div class="input-group">
                            <input name="nama_kategori" type="text" class="form-control" placeholder="Nama Kategori" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <div class="input-group">
                            <select name="jenis" id="jenis" type="text" class="form-control">
                                <option value="" selected disabled>- Jenis -</option>
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Tambah</button>
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>