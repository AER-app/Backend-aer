@extends('layouts.admin-master')

@section('title')
    Driver
@endsection

@section('css')
    <style>
        #map {
            height: 450px;
        }

        ;

    </style>
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
                    <div class="card-body table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr style="text-align:center">
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">No Telepon</th>
                                    <th scope="col">Jenis Motor</th>
                                    <th scope="col">Plat Nomor</th>
                                    <th scope="col">Kecamatan</th>
                                    <th scope="col">Saldo</th>
                                    <th scope="col">Status</th>
                                    <th>Aksi</th>
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
                                        <td>{{ $data->kecamatan1->name }}</td>
                                        <td>Rp.{{ number_format($data->saldo, 2) }}</td>
                                        <td class="text-center">
                                        @if($data->status_driver == 1)
                                            @if($data->status_order_driver == 1)
                                            <span class="badge badge-info">Sedang<br> Menerima<br> Orderan</span>
                                            @else
                                            <span class="badge badge-success">Aktif</span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                        </td>
                                        <td width="15%" class="text-center">
                                            <a href="{{ route('driver.detail', ['id' => $data->id]) }}">
                                                <button class="edit btn btn-warning btn-sm fa fa-eye"
                                                    title="Detail"></button>
                                            </a>
                                            <a href="#" data-toggle="modal" onclick="saldoData({{$data->id}})" data-target="#SaldoModal">
                                                <button class="btn btn-success btn-sm fa fa-wallet" title="Input saldo here"></button>
                                            </a>
                                            {{-- @if ($data->user->status == 1)
                                                <a href="#">
                                                    <button class="btn btn-success btn-sm fa fa-file-signature" title="approved"></button>
                                                </a>
                                            @else    
                                                <a href="#" data-toggle="modal" onclick="approveData({{$data->user->id}})" data-target="#ApproveModal">
                                                    <button class="btn btn-danger btn-sm fa fa-file-excel" title="approve here"></button>
                                                </a>
                                            @endif --}}
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
                        <p>Apakah anda yakin ingin menghapus Driver ini ?</p>
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                        <button type="submit" name="" class="btn btn-danger float-right mr-2" data-dismiss="modal" onclick="formSubmit()">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div id="SaldoModal" class="modal fade" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="" id="saldoForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">isi Saldo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="form-group">
                            <label for="saldo">Nominal</label>
                            <div class="input-group">
                                <input name="saldo" type="text" class="form-control" placeholder="Nominal" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                        <button type="submit" name="" class="btn btn-success float-right mr-2" data-dismiss="modal" onclick="formSubmit()">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.driver.tambah')
@endsection

@section('scripts')

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{route("driver.delete", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
    
    <script type="text/javascript">
        function saldoData(id) {
            var id = id;
            var url = '{{route("driver.saldo", ":id") }}';
            url = url.replace(':id', id);
            $("#saldoForm").attr('action', url);
        }

        function formSubmit() {
            $("#saldoForm").submit();
        }
    </script>

    <!-- ====================== Input Map ====================== -->

    <script>
        function initialize() {
            //Cek Support Geolocation
            if (navigator.geolocation) {
                //Mengambil Fungsi golocation
                navigator.geolocation.getCurrentPosition(lokasi);
            } else {
                swal("Maaf Browser tidak Support HTML 5");
            }
            //Variabel Marker
            var marker;

            function taruhMarker(peta, posisiTitik) {

                if (marker) {
                    // pindahkan marker
                    marker.setPosition(posisiTitik);
                } else {
                    // buat marker baru
                    marker = new google.maps.Marker({
                        position: posisiTitik,
                        map: peta,
                        icon: 'https://img.icons8.com/plasticine/40/000000/marker.png',
                    });
                }

            }   
            //Buat Peta
            var peta = new google.maps.Map(document.getElementById("mapInput"), {
                center: {
                    lat: -8.408698,
                    lng: 114.2339090
                },
                zoom: 9
            });
            //Fungsi untuk geolocation
            function lokasi(position) {
                //Mengirim data koordinat ke form input
                document.getElementById("lat").value = position.coords.latitude;
                document.getElementById("leng").value = position.coords.longitude;
                //Current Location
                var lat = position.coords.latitude;
                var long = position.coords.longitude;
                var latlong = new google.maps.LatLng(lat, long);
                //Current Marker 
                var currentMarker = new google.maps.Marker({
                    position: latlong,
                    icon: 'https://img.icons8.com/plasticine/40/000000/user-location.png',
                    map: peta,
                    title: "Anda Disini"
                });
                //Membuat Marker Map dengan Klik
                var latLng = new google.maps.LatLng(-8.408698, 114.2339090);

                var addMarkerClick = google.maps.event.addListener(peta, 'click', function(event) {


                    taruhMarker(this, event.latLng);

                    //Kirim data ke form input dari klik
                    document.getElementById("lat").value = event.latLng.lat();
                    document.getElementById("leng").value = event.latLng.lng();

                });
            }

        }

    </script>
    <!-- ====================== End Input Map ====================== -->


    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv-h2II7DbFQkpL9pDxNRq3GWXqS5Epts&callback=initialize"
        type="text/javascript"></script>

@endsection
