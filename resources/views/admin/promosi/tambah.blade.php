<div class="modal fade" tabindex="-1" role="dialog" id="modalCreate">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Lapak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate="" action="{{ route('lapak.create') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="judul_slideshow">Judul Slideshow</label>
                        <div class="input-group">
                            <input name="judul_slideshow" type="text" class="form-control" placeholder="Judul Slideshow" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_slideshow">Deskripsi Slideshow</label>
                        <div class="input-group">
                            <input name="deskripsi_slideshow" type="text" class="form-control" placeholder="Deskripsi Slideshow" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="link">Link</label>
                        <div class="input-group">
                            <input name="link" type="text" class="form-control" placeholder="Link" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="menu">Menu</label>
                        <div class="input-group">
                            <input name="menu" type="text" class="form-control" placeholder="Menu" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <div class="input-group">
                            <input name="kategori" type="text" class="form-control" placeholder="Kategori" required>
                        </div>
                    </div>

                    <small class="text-muted float-right">File max upload tidak lebih 512 Kb</small><br>
                    <div class="form-group">
                        <label for="foto_usaha">Foto Slideshow</label>
                        <div class="input-group">
                            <input name="foto_usaha" type="file" class="form-control" required>
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