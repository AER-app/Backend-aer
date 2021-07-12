@extends('layouts.admin-master')

@section('title')
    Order
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Order</h1>
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
        <div class="section-body">
            <div class="row p-3">
                <div class="card col-12">
                    <div class="card-header">
                        <div class="section-title mt-0 mb-0">Data Order</div>

                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr style="text-align:center">
                                    <th scope="col">No</th>
                                    <th scope="col">Kode Order</th>
                                    <th scope="col">Nama Customer</th>
                                    <th scope="col">Nama Lapak</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Status Order</th>
                                    <th scope="col">Jumlah jastip</th>
                                    <th scope="col">Tanggal Order</th>
                                    <th scope="col"></th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach ($data as $data)
                                    <tr>
                                        <th scope="row">{{ $no++ }}</th>
                                        <td>{{ $data->kode_order }}</td>
                                        <td>{{ $data->customer->user->nama }}</td>
                                        <td>{{ $data->lapak->nama_usaha }}</td>
                                        <td>{{ $data->total_harga }}</td>
                                        @if($data->status_order == 1)
                                            <td><span class="badge badge-secondary">Sedang menunggu driver</span></td>
                                        @elseif($data->status_order == 2)
                                            <td><span class="badge badge-light">Sudah mendapatkan driver</span><span class="badge badge-light">Driver menuju ke lapak</span></td>
                                        @elseif($data->status_order == 3)
                                            <td><span class="badge badge-info">Lapak Menyiapkan Pesanan</span></td>
                                        @elseif($data->status_order == 4)
                                            <td><span class="badge badge-warning">Pesanan sedang diantar</span></td>
                                        @elseif($data->status_order == 5)
                                            <td><span class="badge badge-success">Orderan Selesai</span></td>
                                        @endif
                                        
                                        <td>{{ $data->jumlah_jastip }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td>{{ $data->created_at->diffForHumans() }}</td>
                                        <td width="10%" class="text-center">
                                            <a href="{{ route('order.detail', ['id' => $data->id]) }}">
                                                <button class="edit btn btn-warning btn-sm fa fa-user"
                                                    title="Detail"></button>
                                            </a>
                                            <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$data->id}})" data-target="#DeleteModal">
                                                <button class="btn btn-danger btn-sm fa fa-trash" title="Hapus"></button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>

    <div id="DeleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="" id="deleteForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <p>Apakah anda yakin ingin menghapus Orderan ini ?</p>
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                        <button type="submit" name="" class="btn btn-danger float-right mr-2" data-dismiss="modal" onclick="formSubmit()">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{route("order.delete", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

@endsection
