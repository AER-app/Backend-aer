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
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div id="mapInput" style="width: 100%; height: 320px; border-radius: 3px;"></div>
                        <p>klik satu kali untuk menentukan posisi</p>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <form class="needs-validation" novalidate="" action="{{ route('lapak.create') }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <div class="input-group">
                                    <input type="number" step="any" id="lat" name="latitude" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <div class="input-group">
                                    <input name="longitude" step="any" id="leng" type="number" class="form-control"
                                        required>
                                </div>
                            </div>
                    </div>
                </div>
                
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <div class="input-group">
                            <input name="nama" type="text" class="form-control" placeholder="Nama" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama_usaha">Nama Usaha</label>
                        <div class="input-group">
                            <input name="nama_usaha" type="text" class="form-control" placeholder="Nama Usaha" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <input name="email" type="text" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No Telepon</label>
                        <div class="input-group">
                            <input name="no_telp" type="text" class="form-control" placeholder="No Telepon" required>
                        </div>
                        <small class="text-danger float-right">Pastikan nomor telepon belum terdaftar dalam sistem</small>
                    </div>
                    <div class="form-group">
                        <label for="nomor_rekening">Nomor Rekening</label>
                        <div class="input-group">
                            <input name="nomor_rekening" type="text" class="form-control" placeholder="No Telepon" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <div class="input-group">
                            <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jenis_usaha">Jenis Usaha</label>
                        <div class="input-group">
                            <input type="text" name="jenis_usaha" class="form-control" placeholder="Jenis Usaha"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <small class="text-muted">Opsioanal</small><br>
                        <label for="keterangan">Keterangan</label>
                        <div class="input-group">
                            <textarea type="text" name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kecamatan">Kecamatan</label>
                        <div class="input-group">
                            <select name="id_kecamatan1" id="kecamatan" type="text" class="form-control">
                                <option value="" selected disabled>- Kecamatan -</option>
                                @foreach($kecamatan as $datas)
                                <option value="{{$datas->id}}" >{{$datas->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kecamatan">Kecamatan Terdekat</label>
                        <div class="input-group">
                            <select name="id_kecamatan2" id="kecamatan" type="text" class="form-control">
                                <option value="" selected disabled>- Kecamatan Terdekat -</option>
                                @foreach($kecamatan as $datas)
                                <option value="{{$datas->id}}" >{{$datas->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <small class="text-muted float-right">File max upload tidak lebih 512 Kb</small><br>
                    <div class="form-group">
                        <label for="foto_usaha">Foto Usaha</label>
                        <div class="input-group">
                            <input name="foto_usaha" type="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="foto_ktp">Foto KTP</label>
                        <div class="input-group">
                            <input name="foto_ktp" type="file" class="form-control" required>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="foto_umkm">Foto UMKM</label>
                        <div class="input-group">
                            <input name="foto_umkm" type="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="foto_npwp">Foto NPWP</label>
                        <div class="input-group">
                            <input name="foto_npwp" type="file" class="form-control" required>
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