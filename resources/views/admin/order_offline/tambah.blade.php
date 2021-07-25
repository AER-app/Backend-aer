@extends('layouts.admin-master')

@section('title')
    Tambah Order Offline
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.min.css" integrity="sha512-7zxVEuWHAdIkT2LGR5zvHH7YagzJwzAurFyRb1lTaLLhzoPfcx3qubMGz+KffqPCj2nmfIEW+rNFi++c9jIkxw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Orderan Offline
            </h1>
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
        @error('status')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="section-body">
            <h2 class="section-title">Tambah Orderan</h2>

            <form action="{{ route('admin_order_offline.store') }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                
                <div class="card">
                    <div class="card-body">
                
                    <div class="form-group">
                        <label for="no_hp">No Handphone</label>
                        <div class="input-group">
                            @if (session('no_telp'))
                                <input name="no_telp" type="text" class="form-control" placeholder="No Handphone" value="{{ session('no_telp') }}" readonly>
                            @elseif(!$customer)
                                <input name="no_telp" type="text" class="form-control" placeholder="No Handphone" required>
                            @endif
                        </div>
                    </div>
                    @if(session('nama_customer'))
                    @else
                        <button type="submit" width="25px" class="mb-3 btn btn-danger" name="CariNoTelp" value="cari" title="Cari">Cari</button>
                    @endif
                    
                    <div class="form-group">
                        <label for="nama_customer">Nama Customer</label>
                        <div class="input-group">
                            @if(session('nama_customer'))
                                <input name="nama_customer" type="text" class="form-control" placeholder="Nama Customer" value="{{ session('nama_customer') }}" readonly>
                            @elseif(!$customer)
                            <input name="nama_customer" type="text" class="form-control" placeholder="Nama Customer">
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude_cus">Latitude Customer</label>
                                <div class="input-group">
                                @if(session('latitude_cus'))
                                    <input type="text" name="latitude_cus" class="form-control" value="{{ session('latitude_cus')}}">
                                @else
                                    <input type="text" name="latitude_cus" class="form-control">
                                @endif
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude_cus">Longitude Cutomer</label>
                                <div class="input-group">
                                @if(session('longitude_cus'))
                                    <input name="longitude_cus" type="text" class="form-control" value="{{ session('longitude_cus')}}" >
                                @else
                                    <input name="longitude_cus" type="text" class="form-control" >
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama_lapak">Nama Lapak</label>
                        <div class="input-group">
                            @if(session('nama_lapak'))
                                <input data-provide="typeahead" name="nama_lapak" type="text" class="typeahead form-control" value="{{ session('nama_lapak') }}">
                            @else
                                <input data-provide="typeahead" name="nama_lapak" type="text" class="typeahead form-control" placeholder="Nama Lapak" autocomplete="off">
                            @endif
                        </div>
                    </div>
                    @if(! session('latitude_lap'))
                        <div class="col-12">
                            <div id="mapInput" style="width: 100%; height: 320px; border-radius: 3px;"></div>
                            <p>klik satu kali untuk menentukan posisi</p>
                        </div>
                    @endif
                    
                    @if(session('latitude_lap'))
                        <input name="latitude_lap" value="{{ session('latitude_lap') }}" type="hidden" class="form-control">
                    @else
                        <input name="latitude_lap" type="hidden" class="form-control" id="lat">
                    @endif
                    
                    @if(session('longitude_lap'))
                        <input name="longitude_lap" value="{{ session('longitude_lap') }}" type="hidden" class="form-control">
                    @else
                        <input name="longitude_lap" type="hidden" class="form-control" id="leng">
                    @endif
                    
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <div class="input-group">
                            
                            @if(session('catatan'))
                                <textarea type="text" name="catatan" style="height:70px" class="form-control" placeholder="Catatan" >{{ session('catatan') }}</textarea>
                            @else
                                <textarea type="text" name="catatan" style="height:70px" class="form-control" placeholder="Catatan" ></textarea>
                            @endif    
                        </div>
                    </div>
                    
                    @if(! session('latitude_lap'))
                        <button type="submit" width="25px" class="mb-3 btn btn-danger" name="HitungJarak" value="cari" title="Cari">Hitung Jarak</button>
                    @endif

                    <div class="form-group">
                        <label for="jarak">Jarak</label>
                        <div class="input-group">
                            @if(session('jarak'))
                                <input name="jarak" type="text" class="form-control" value="{{ session('jarak') }}" readonly>
                            @else
                                <input name="jarak" type="text" class="form-control" placeholder="Jarak" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ongkir">Ongkir</label>
                        <div class="input-group">
                            @if(session('jarak'))
                                <input name="ongkir" type="text" class="form-control" value="{{ session('ongkir') }}" readonly>
                            @else
                                <input name="ongkir" type="text" class="form-control" placeholder="Ongkir" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Tambah</button>
                        <a href="{{route('kelola.order-offline')}}" class="btn btn-warning">Kembali</a>
                    </div>
                    
                    </div>  
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
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
                    lat: -8.217194696825992,
                    lng: 114.34950976348047
                },
                zoom: 11.5
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
    
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>-->
    
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script> 
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.min.js" integrity="sha512-Rc24PGD2NTEGNYG/EMB+jcFpAltU9svgPcG/73l1/5M6is6gu3Vo1uVqyaNWf/sXfKyI0l240iwX9wpm6HE/Tg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
    
    <script type="text/javascript">
        var path = "{{ route('autocomplete') }}";
        $('input.typeahead').typeahead({
            source:  function (q, process) {
            return $.get(path, { q: q }, function (data) {
                    return process(data);
                    
                          console.log(data);
                });
            }
            
        });
    </script>
    
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv-h2II7DbFQkpL9pDxNRq3GWXqS5Epts&callback=initialize"
        type="text/javascript"></script>
        
@endsection
