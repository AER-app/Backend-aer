<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Andoid Bantuan</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/Aer-fill.jpeg') }}">
</head>

<body>
    
    <div id="app">
        <div class="main-wrapper">
            {{-- <h3 class="text-center p-4" style=" background: #ed3237; color: white;">Menggunakan Aplikasi Aer</h3>
            @foreach ($data as $data)
                <div style="color:#ed3237;">
                    <h4 class="pt-4 pr-4 pl-4">{{ $data->judul }}</h4>
                    <div class="pr-4 pt-1 pl-4" style="text-align: justify;">
                        <?php echo str_replace("\r\n", '</br>', $data->isi); ?>
                    </div>
                </div>
            @endforeach --}}

            <div class="col-12 col-md-12 col-lg-12">
                <div class="card" >
                    <div class="card-header mt-2" style=" background: #ed3237; color: white;">
                        <h3 class="text-center p-2">Android Lihat Order</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div id="accordion">
                                    @foreach ($order as $data)
                                        <div class="accordion">
                                            <div class="accordion-header collapsed p-3" role="button" data-toggle="collapse"
                                                data-target="#panel-body-{{$data->id}}" aria-expanded="false">
                                                <h4 style="font-size:18px;">Orderan ke - {{$data->id}}, {{$data->created_at->diffForHumans()}}</h4>
                                            </div>
                                            <div class="accordion-body collapse" id="panel-body-{{$data->id}}" data-parent="#accordion" style="">
                                                <p class="mb-0" style="font-size:15px;">Nama Customer : {{$data->customer->user->nama}}</p>
                                                <p class="mb-0" style="font-size:15px;">Nama Lapak : {{$data->lapak->user->nama}}</p>
                                                @if ($data->id_driver)
                                                    <p class="mb-0" style="font-size:15px;">Nama Driver : {{$data->driver->user->nama}}</p>
                                                    @else
                                                    <p class="mb-0" style="font-size:15px;">Nama Driver : belum ada driver</p>
                                                @endif
                                                <p class="mb-0" style="font-size:15px;">Kode Order : {{$data->kode_order}}</p>
                                                <p class="mb-0" style="font-size:15px;">Status Order : {{$data->status_order}}</p>
                                                <p class="mb-0" style="font-size:15px;">Tanggal/Jam Order : {{$data->created_at->diffForHumans()}}</p>
                                                
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-6">
                                <div id="accordion">
                                    @foreach ($datanotif as $datano)
                                        <div class="accordion">
                                            <div class="accordion-header collapsed p-3" role="button" data-toggle="collapse"
                                                data-target="#panel-body-{{$datano['id_order']}}" aria-expanded="false">
                                                <h4 style="font-size:18px;">Notif Driver Orderan ke - {{$datano['id_order']}}</h4>
                                            </div>
                                            <div class="accordion-body collapse" id="panel-body-{{$datano['id_order']}}" data-parent="#accordion" style="">
                                                @foreach ($datano['notif_driver'] as $data)
                                                    <p class="mb-0" style="font-size:15px;">Driver : {{$data->nama}}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="{{ asset('js/app.js') }}?{{ uniqid() }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script src="{{ asset('assets/js/stisla.js') }}"></script>

</body>

</html>
