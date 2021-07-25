<div class="modal fade" tabindex="-1" role="dialog" id="modalCreate">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Promo Ongkir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate="" action="{{ route('promo_ongkir.create') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="jenis_promo">Jenis Promo</label>
                        <div class="input-group">
                            <select name="jenis_promo" id="jenis_promo" type="text" class="form-control">
                                <option value="" selected disabled>- Jenis Promo -</option>
                               
                                <option value="1" >Orderan Biasa</option>
                                <option value="2" >Orderan Jastip</option>
                                <option value="3" >Orderan Posting Driver</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="persen_promo">Persen Promo</label>
                        <div class="input-group">
                            <input name="persen_promo" type="text" class="form-control" placeholder="Persen Promo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="batas_durasi">Batas Durasi</label>
                        <div class="input-group">
                            <input name="batas_durasi" type="datetime-local" class="form-control" placeholder="Durasi           ex: 24" min="{{Carbon\Carbon::now()->format('Y-m-d\TH:i')}}" required>
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