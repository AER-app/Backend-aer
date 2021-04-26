<div class="modal fade" tabindex="-1" role="dialog" id="modalCreate">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Driver</h5>
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
                        <form class="needs-validation" novalidate="" action="{{ route('driver.create') }}"
                            method="POST" enctype="multipart/form-data">
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
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <input name="email" type="text" class="form-control" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="no_telp">No Telepon</label>
                                <div class="input-group">
                                    <input name="no_telp" type="text" class="form-control" placeholder="No Telepon"
                                        required>
                                </div>
                                <small class="text-danger float-right">Pastikan nomor telepon belum terdaftar dalam
                                    sistem</small>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <div class="input-group">
                                    <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <div class="input-group">
                                    <select name="id_kecamatan1" id="kecamatan" type="text" class="form-control">
                                        <option value="" selected disabled>- Kecamatan -</option>
                                        @foreach ($kecamatan as $datas)
                                            <option value="{{ $datas->id }}">{{ $datas->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan Terdekat</label>
                                <div class="input-group">
                                    <select name="id_kecamatan2" id="kecamatan" type="text" class="form-control">
                                        <option value="" selected disabled>- Kecamatan Terdekat -</option>
                                        @foreach ($kecamatan as $datas)
                                            <option value="{{ $datas->id }}">{{ $datas->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jenis_motor">Jenis Motor</label>
                                <div class="input-group">
                                    <input type="text" name="jenis_motor" class="form-control" placeholder="Jenis Motor"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plat_nomor">Plat Nomor</label>
                                <div class="input-group">
                                    <input type="text" name="plat_nomor" class="form-control" placeholder="Plat Nomor"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="warna_motor">Warna Motor</label>
                                <div class="input-group">
                                    <input type="text" name="warna_motor" class="form-control" placeholder="Warna Motor"
                                        required>
                                </div>
                            </div>
                            <a class="text-muted float-right">File max upload tidak lebih dari 512 Kb</a>
                            <div class="form-group">
                                <label for="foto_ktp">Foto KTP</label>
                                <div class="input-group">
                                    <input name="foto_ktp" type="file" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto_kk">Foto KK</label>
                                <div class="input-group">
                                    <input name="foto_kk" type="file" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto_sim">Foto SIM</label>
                                <div class="input-group">
                                    <input name="foto_sim" type="file" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto_stnk">Foto STNK</label>
                                <div class="input-group">
                                    <input name="foto_stnk" type="file" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto_motor">Foto Motor</label>
                                <div class="input-group">
                                    <input name="foto_motor" type="file" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Tambah</button>
                                <button type="button" class="btn btn-secondary float-right"
                                    data-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
