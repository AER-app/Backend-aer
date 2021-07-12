@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Profile</div>
            </div>
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
            <h2 class="section-title">Lapak {{ $user->nama }}!</h2>
            <p class="section-lead">
                Change information about Lapak on this page.
            </p>


            <form method="POST" action="{{ route('lapak.update', ['id' => $data->id])}}" class="needs-validation"
                novalidate="" enctype="multipart/form-data">
                @csrf
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img alt="image" height="100px" width="100px" src="{{ $data->ambilGambarProfile() }}"
                                    class="rounded-circle profile-widget-picture">
                                    <div class="profile-widget-items">
                                        <div class="profile-widget-item">
                                            <div class="profile-widget-item-label">Posting</div>
                                            <div class="profile-widget-item-value">187</div>
                                        </div>
                                        <div class="profile-widget-item">
                                            <div class="profile-widget-item-label">Order</div>
                                        <div class="profile-widget-item-value">6,8K</div>
                                    </div>
                                </div>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto Profile</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_profile" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">{{ $user->nama }}
                                    <div class="text-muted d-inline font-weight-normal">
                                        <div class="slash"></div> Lapak Aer Daerah {{ $data->kecamatan1->name }} ,
                                        {{ $data->kecamatan2->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center" style="margin-top: -30px">
                                <div class="font-weight-bold mb-2">Foto Kartu Tanda Penduduk</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarKtp() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto KTP</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_ktp" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer text-center" style="margin-top: -20px">
                                <div class="font-weight-bold mb-2">Foto UMKM</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarUmkm() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto UMKM</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_umkm" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer text-center" style="margin-top: -20px">
                                <div class="font-weight-bold mb-2">Foto Nomor Pokok Wajib Pajak</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarNpwp() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto NPWP</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_npwp" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer text-center" style="margin-top: -20px">
                                <div class="font-weight-bold mb-2">Foto Usaha</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarUsaha() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto Usaha</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_usaha" type="file" class="form-control">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Username</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $user->nama }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the nama
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>No Telepon</label>
                                        <input type="text" name="no_telp" class="form-control"
                                            value="{{ $user->no_telp }}" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the no telepon
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nama Pemilik usaha</label>
                                        <input type="text" name="nama_pemilik_usaha" class="form-control" value="{{ $data->nama_pemilik_usaha }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the email
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nama Usaha</label>
                                        <input type="text" name="nama_usaha" class="form-control"
                                            value="{{ $data->nama_usaha }}" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the Nama Usaha
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control" value="{{ $user->email }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the email
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control summernote-simple">{{ $data->alamat }}</textarea>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Keterangan</label>
                                        <textarea name="keterangan" class="form-control summernote-simple">{{ $data->keterangan }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nomor Rekening</label>
                                        <input type="text" name="nomor_rekening" class="form-control" value="{{ $data->nomor_rekening }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the Nomor Rekening
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nama Pemilik Rekening</label>
                                        <input type="text" name="nama_pemilik_rekening" class="form-control" value="{{ $data->nama_pemilik_rekening }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the no Nama Pemilik Rekening
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Kecamatan</label>
                                        <div class="input-group">
                                            <select name="id_kecamatan1" id="id_kecamatan1" type="text"
                                                class="form-control">
                                                <option value="" selected disabled>- Kecamatan -</option>
                                                @foreach ($kecamatan as $datas)
                                                    <option value="{{ $datas->id }}" @if ($datas->id == $data->id_kecamatan1) {{ 'selected="selected"' }} @endif>
                                                        {{ $datas->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Kecamatan Terdekat</label>
                                        <div class="input-group">
                                            <select name="id_kecamatan2" id="id_kecamatan2" type="text"
                                                class="form-control">
                                                <option value="" selected disabled>- Kecamatan Terdekat -</option>
                                                @foreach ($kecamatan as $datas)
                                                    <option value="{{ $datas->id }}" @if ($datas->id == $data->id_kecamatan2) {{ 'selected="selected"' }} @endif>
                                                        {{ $datas->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{route('lapak')}}" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
