@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Lapak</h1>
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
        @error('foto_usaha')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @error('foto_umkm')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="section-body">
            <div class="row p-3">
                <div class="card col-12">
                    <div class="card-header">
                        <div class="section-title mt-0 mb-0">Data Lapak</div>
                        <button data-toggle="modal" data-target="#modalCreate" class="btn btn-success fas fa-plus fa-2x"
                            title="Tambahkan disini" style="margin-left: auto;"></button>

                    </div>
                    <div class="card-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Nama Usaha</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">No Telepon</th>
                                    <th scope="col">Jam Operasional</th>
                                    <th scope="col">Jenis Usaha</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach ($data as $data)
                                    <tr>
                                        <th scope="row">{{ $no++ }}</th>
                                        <td>{{ $data->user->nama }}</td>
                                        <td>{{ $data->nama_usaha }}</td>
                                        <td>{{ $data->user->email }}</td>
                                        <td>{{ $data->alamat }}</td>
                                        <td>{{ $data->user->no_telp }}</td>
                                        <td>{{ $data->jam_operasional }}</td>
                                        <td>{{ $data->jenis_usaha }}</td>
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

    @include('admin.lapak.tambah')
@endsection
