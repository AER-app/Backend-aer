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
            <div class="row p-3">
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
                                    <th scope="col">Email</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">No Telepon</th>
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
                                        <td>{{ $data->user->nama }}</td>
                                        <td>{{ $data->user->email }}</td>
                                        <td>{{ $data->alamat }}</td>
                                        <td>{{ $data->user->no_telp }}</td>
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

    @include('admin.driver.tambah')
@endsection
