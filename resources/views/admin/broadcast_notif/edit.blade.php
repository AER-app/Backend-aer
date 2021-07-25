<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <form method="POST" action="" class="needs-validation" novalidate="" id="editForm"
                        enctype="multipart/form-data">

                {{ csrf_field() }}
                 <div class="form-group">
                        <label for="jenis_promo">Jenis Promo</label>
                     <div class="input-group">
                            <select name="role" id="role" type="text" class="form-control">
                                <option value="" selected disabled>- Pilih Role -</option>
                               
                                <option >customer</option>
                                <option >driver</option>
                                <option >lapak</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jdudul">Judul</label>
                        <div class="input-group">
                            <input name="judul" id="judul" type="text" class="form-control" placeholder="judul" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi</label>
                        <div class="input-group">
                            <input name="isi" id="isi" type="text" class="form-control" placeholder="isi"  required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar</label>
                        <div class="input-group">
                            <input name="gambar" type="file" class="form-control">
                        </div>
                    </div>
                    
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Edit</button>
                    <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
