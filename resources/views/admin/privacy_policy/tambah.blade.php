<div class="modal fade" tabindex="-1" role="dialog" id="modalCreate">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Data Privacy Policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate="" action="{{ route('privacy_policy.store') }}" method="POST"
                    enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                    <div class="form-group">
                        <label for="deskripsi_slideshow">Isi</label>
                        <div class="input-group">
                            <textarea name="isi" type="text" class="form-control" style="height: 70px" placeholder="" required></textarea>
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