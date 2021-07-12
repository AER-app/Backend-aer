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
                        {{ method_field('PUT') }}

                <div class="form-group">
                    <label for="isi">Isi</label>
                    <div class="input-group">
                        <textarea type="text" name="isi" id="isi" class="form-control"
                            placeholder="isi" style="min-height:200px;" required></textarea>
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
