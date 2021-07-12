@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Order</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Order</a></div>
                <div class="breadcrumb-item">Detail</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Orderan {{ $data->id }}!</h2>
            <p class="section-lead">
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
                
            </p>
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Orderan</h4>
                        </div>
                        <div class="card-body">
                            <table style="width:100%">
                                <tr>
                                    <td>Kode Order</td>
                                    <td>:</td>
                                    <td>{{ $data->kode_order }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Customer</td>
                                    <td>:</td>
                                    <td>{{ $data->customer->user->nama }}</td>
                                </tr>
                                <tr>
                                    <td>No Telepon Customer</td>
                                    <td>:</td>
                                    <td>{{ $data->customer->user->no_telp }}</td>
                                </tr>
                                @if ($data->id_driver)
                                <tr>
                                    <td>Nama Driver</td>
                                    <td>:</td>
                                    <td>{{ $data->driver->user->nama }}</td>
                                </tr>
                                <tr>
                                    <td>No Telepon Driver</td>
                                    <td>:</td>
                                    <td>{{ $data->driver->user->no_telp }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td>Tidak Dapat Driver</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Nama Lapak</td>
                                    <td>:</td>
                                    <td>{{ $data->lapak->nama_usaha }}</td>
                                </tr>
                                <tr>
                                    <td>No Telepon Lapak</td>
                                    <td>:</td>
                                    <td>{{ $data->lapak->user->no_telp }}</td>
                                </tr>
                                <tr>
                                    <td>Ongkir</td>
                                    <td>:</td>
                                    <td>{{ $data->ongkir }}</td>
                                </tr>
                                <tr>
                                    <td>Total Harga</td>
                                    <td>:</td>
                                    <td>{{ $data->total_harga }}</td>
                                </tr>
                                <tr>
                                    <td>Status Order</td>
                                    <td>:</td>
                                    <td>{{ $data->status_order }}</td>
                                </tr>
                                <tr>
                                    <td>Menu yang dipesan</td>
                                    <td>:</td>
                                    <td>
                                        @foreach ($data->order_detail as $v)
                                            {{ $v->menu->nama_menu }} ({{ $v->jumlah_pesanan }})</br>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="float-right">
                                        <a href="{{ route('order') }}" class="btn btn-icon icon-left btn-info"><i class="fas fa-arrow-left"></i> kembali</a>
                                    </td>
                                </tr>
                            </table>
                            {{-- <h6>Kode Order : {{ $data->customer->user->nama }}</h6>
                            <h6>Nama Customer : {{ $data->customer->user->nama }}</h6> --}}
                            {{-- <h6>No Telepon Customer : {{ $data->customer->user->no_telp }}</h6> --}}
                            {{-- @if ($data->id_driver)
                                <h6>Nama Driver : {{ $data->driver->user->nama }}</h6>
                                <h6>No Telepon Driver : {{ $data->driver->user->no_telp }}</h6>
                            @else --}}
                                {{-- <h6>Belum ada Driver</h6>
                            @endif --}}
                            {{-- <h6>Nama Lapak : {{ $data->lapak->nama_usaha }}</h6> --}}
                            {{-- <h6>No Telepon Lapak : {{ $data->lapak->user->no_telp }}</h6> --}}
                            {{-- <h6>Ongkir : {{ $data->ongkir }}</h6> --}}
                            {{-- <h6>Total Harga : {{ $data->total_harga }}</h6> --}}
                            {{-- <h6>Menu yang dipesan :
                                @foreach ($data->order_detail as $v)
                                    {{ $v->menu->nama_menu }} ({{ $v->jumlah_pesanan }})</br>
                                @endforeach
                            </h6> --}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Jastip</h4>
                        </div>
                        <div class="card-body">
                            <h6>Ada {{ $data->jumlah_jastip }} Jastip</h6>
                            @if ($data->jastip)
                                <table style="width:100%">
                                    @foreach ($data->jastip as $val)
                                    <tr>
                                        <td>Nama Customer</td>
                                        <td>:</td>
                                        <td>{{ $val->customer->user->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>No Telepon Customer</td>
                                        <td>:</td>
                                        <td>{{ $val->customer->user->no_telp }}</td>
                                        <td class="float-right" >
                                            <a href="{{ route('order.jastip.delete', $val->id)}}" class="btn btn-sm btn-icon icon-left btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>---</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            </form>
    </section>
@endsection
