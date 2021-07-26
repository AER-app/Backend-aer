@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Promo Ongkir</h1>
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
                        <div class="section-title mt-0 mb-0">Data Promo Ongkir</div>
                        <button data-toggle="modal" data-target="#modalCreate" class="btn btn-success fas fa-plus fa-2x"
                            title="Tambahkan disini" style="margin-left: auto;"></button>

                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Jenis Promo</th>
                                    <th scope="col">Persen Promo</th>
                                    <th scope="col">Batas Promo</th>
                                    <th style="display:none;">id</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach ($data as $data)
                                    <tr>
                                        <th scope="row">{{ $no++ }}</th>
                                            
                                        @if($data->jenis_promo == 1)
                                            <td>Orderan</td>
                                        @elseif($data->jenis_promo == 2)
                                            <td>Jastip</td>
                                        @else
                                            <td>Orderan Posting Driver</td>
                                        @endif
                                        <td>{{ $data->persen_promo }}</td>
                                        <td>{{ $data->batas_durasi }}</td>
                                        <td style="display:none;">{{$data->id}}</td>
                                        <td class="text-center">
                                            <button class="edit btn btn-warning btn-sm fa fa-edit mr-1" title="Edit disini"></button>
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
                        <p>Apakah anda yakin ingin menghapus Promo Ongkir ini ?</p>
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                        <button type="submit" name="" class="btn btn-danger float-right mr-2" data-dismiss="modal" onclick="formSubmit()">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    @include('admin.promo_ongkir.edit')
    @include('admin.promo_ongkir.tambah')
@endsection
@section('scripts')
    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{route("promo_ongkir.delete", ":id") }}';
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
