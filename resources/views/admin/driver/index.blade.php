@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Driver</h1>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @error('foto_ktp')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @error('foto_kk')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @error('foto_sim')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @error('foto_stnk')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @error('foto_motor')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="section-body">
            <div class="row">
                <div class="card col-12">
                    <div class="card-header">
                        <div class="section-title mt-0 mb-0">Data Driver</div>
                        <button data-toggle="modal" data-target="#modalCreate" class="btn btn-success fas fa-plus fa-2x"
                            title="Tambahkan disini" style="margin-left: auto;"></button>

                    </div>
                    <div class="card-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Jenis Motor</th>
                                    <th scope="col">Plat Nomor</th>
                                    <th scope="col">Warna Motor</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach ($data as $data)
                                    <tr>
                                        <th scope="row">{{ $no++ }}</th>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->alamat }}</td>
                                        <td>{{ $data->jenis_motor }}</td>
                                        <td>{{ $data->plat_nomor }}</td>
                                        <td>{{ $data->warna_motor }}</td>
                                        <td class="text-center">
                                            <button class="edit btn btn-warning btn-sm fa fa-user" title="Detail"></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>

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
                    <form class="needs-validation" novalidate="" action="{{ route('driver.create') }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
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
                                <input name="no_telp" type="text" class="form-control" placeholder="No Telepon" required>
                            </div>
                            <small class="text-danger">Pastikan nomor telepon belom terdaftar dalam sistem</small>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <div class="input-group">
                                <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
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
                                <input type="text" name="plat_nomor" class="form-control" placeholder="Plat Nomor" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="warna_motor">Warna Motor</label>
                            <div class="input-group">
                                <input type="text" name="warna_motor" class="form-control" placeholder="Warna Motor"
                                    required>
                            </div>
                        </div>

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
                            <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
