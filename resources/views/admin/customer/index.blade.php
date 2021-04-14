@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Customer</h1>
        </div>
        <div class="section-body">
            <div class="row p-3">
                <div class="card col-12">
                    <div class="card-header">
                        <div class="section-title mt-0 mb-0">Data Customer</div>
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
                                    <th scope="col">Foto KTP</th>
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
                                        <td class="text-center align-middle">
                                            @if($data->foto_ktp)
                                            <img height="100" src="{{$data->ambilGambar()}}"></img>
                                            @else
                                            -
                                            @endif
                                        </td>
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
@endsection
