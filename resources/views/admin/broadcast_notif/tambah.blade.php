<div class="modal fade" tabindex="-1" role="dialog" id="modalCreate">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Broadcast Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate="" action="{{ route('broadcast_notif.create') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="role">Pilih Role Tujuan Broadcast</label>
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
                        <label for="judul">Judul</label>
                        <div class="input-group">
                            <input name="judul" type="text" class="form-control" placeholder="Judul" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi</label>
                        <div class="input-group">
                            <input name="isi" type="text" class="form-control" placeholder="isi" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar</label>
                        <div class="input-group">
                            <input name="gambar" type="file" class="form-control">
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