@extends('layouts.admin-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Promosi</h1>
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
                        <div class="section-title mt-0 mb-0">Data Promosi</div>
                        <button data-toggle="modal" data-target="#modalCreate" class="btn btn-success fas fa-plus fa-2x"
                            title="Tambahkan disini" style="margin-left: auto;"></button>

                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Judul Slideshow</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Link</th>
                                    <th scope="col">Menu</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1 @endphp
                                @foreach ($data as $data)
                                    <tr>
                                        <th scope="row">{{ $no++ }}</th>
                                        <td>{{ $data->judul_slideshow }}</td>
                                        <td>{{ $data->deskripsi_slideshow }}</td>
                                        <td>
                                            <img height="70" id="myImg" src="{{ $data->ambilGambarSlideshow() }}" data-toggle="modal" data-target="#myModal"></img>
                                        </td>
                                        <td>{{ $data->link }}</td>
                                        <td>{{ $data->menu }}</td>
                                        <td>{{ $data->kategori }}</td>
                                        <td class="text-center">
                                            <a href="#">
                                                <button class="btn btn-success btn-sm fa fa-file-signature" title="approved"></button>
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

    <div id="ApproveModal" class="modal fade" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="" id="approveForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <p>Apakah anda yakin ingin menyetujui promosi ini ?</p>
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                        <button type="submit" name="" class="btn btn-success float-right mr-2" data-dismiss="modal" onclick="formSubmit()">Approve</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.promosi.tambah')
@endsection
@section('scripts')
    <script type="text/javascript">
        function approveData(id) {
            var id = id;
            var url = '{{route("promosi.update", ":id") }}';
            url = url.replace(':id', id);
            $("#approveForm").attr('action', url);
        }
        function formSubmit() {
            $("#approveForm").submit();
        }
    </script>
@endsection
