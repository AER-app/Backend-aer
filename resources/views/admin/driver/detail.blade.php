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
            <h2 class="section-title">Driver {{ $user->nama }}!</h2>
            <p class="section-lead">
                Change information about driver on this page.
            </p>

            <form method="POST" action="{{ route('driver.update', ['id' => $data->id])}}" class="needs-validation"
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
                                        <div class="slash"></div> Driver Aer Daerah {{ $data->kecamatan1->name }} ,
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
                                <div class="font-weight-bold mb-2">Foto Kartu Keluarga</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarKk() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto KK</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_kk" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer text-center" style="margin-top: -20px">
                                <div class="font-weight-bold mb-2">Foto Surat Ijin Mengemudi</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarSim() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto SIM</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_sim" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer text-center" style="margin-top: -20px">
                                <div class="font-weight-bold mb-2">Foto Surat tanda Nomor Kendaraan</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarStnk() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto STNK</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_stnk" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer text-center" style="margin-top: -20px">
                                <div class="font-weight-bold mb-2">Foto Motor</div>
                                <img height="200" id="myImg" src="{{ $data->ambilGambarMotor() }}"></img>
                            </div>
                            <a class="text-muted ml-3">Ubah Foto Motor</a>
                            <div class="form-group ml-3 mr-3" style="display:inline-block">
                                <div class="input-group">
                                    <input name="foto_motor" type="file" class="form-control">
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
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $user->nama }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the name
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
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control" value="{{ $user->email }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the email
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Warna Motor</label>
                                        <input type="text" name="warna_motor" class="form-control"
                                            value="{{ $data->warna_motor }}" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the warna motor
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control summernote-simple">{{ $data->alamat }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Jenis Motor</label>
                                        <input type="text" name="jenis_motor" class="form-control" value="{{ $data->jenis_motor }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the jenis motor
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Plat Nomor</label>
                                        <input type="text" name="plat_nomor" class="form-control" value="{{ $data->plat_nomor }}"
                                            required="">
                                        <div class="invalid-feedback">
                                            Please fill in the no plat nomor
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
                                <button class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
