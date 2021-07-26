@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Orderan Offline</h1>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @error('foto_slideshow')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="section-body">
            <div class="row p-3">
                <div class="card col-12">
                    <div class="card-header">
                        <div class="section-title mt-0 mb-0">Data Orderan Offline</div>
                        <a href="{{route('admin_order_offline.create')}}" style="margin-left: auto;">
                            <button class="btn btn-success fas fa-plus fa-2x"
                                title="Tambahkan disini"></button>
                        </a>

                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Customer Offline</th>
                                    <th scope="col">Nama Driver</th>
                                    <th scope="col">No Telp Driver</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Nama Lapak</th>
                                    <th scope="col">Catatan</th>
                                    <th scope="col">Ongkir</th>
                                    <th scope="col">Jarak</th>
                                    <!--<th style="display:none;">id</th>-->
                                    <th scope="col">Tanggal Order</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach ($data as $data)
                                    <tr>
                                        <td scope="row">{{ $no++ }}</td>
                                        <td>{{ $data->customer_offline->nama }}</td>
                                        <td>
                                            @if($data->id_driver)
                                                {{$data->driver->user->nama}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->id_driver)
                                                {{$data->driver->user->no_telp}}
                                            @else
                                                -
                                            @endif
                                        </td>
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
                                        <td>{{ $data->nama_lapak }}</td>
                                        <td>{{ $data->catatan }}</td>
                                        <td>{{ $data->ongkir }}</td>
                                        <td>{{ $data->jarak }}</td>
                                        <td>{{ $data->created_at->diffForHumans() }}</td>
                                        <!--<td style="display:none;">{{$data->id}}</td>-->
                                        <td class="text-center" width="15%">
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
            var url = '{{route("admin_order_offline.delete", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

    <!-- ============================ Edit Data ========================== -->
    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable();
            table.on('click', '.edit', function() {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }
                var data = table.row($tr).data();
                console.log(data);
                $('#jenis_promo').val(data[1]);
                $('#persen_promo').val(data[2]);
                $('#batas_durasi').val(data[3]);
                $('#editForm').attr('action', 'promo_ongkir/update' + data[4]);
                $('#editModal').modal('show');
            });
        });
    </script>
    <!-- ============================ End Edit Data ===================== -->

@endsection
