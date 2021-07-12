<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AER | Privacy Policy</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/Aer-fill.jpeg') }}">
</head>

<body>
    
    <div id="app" style="background: white;">
        <div class="main-wrapper">
            <div class="pl-5 p-4" style=" background: #ed3237; color: white;">
                <h3 class="pl-3">AER</h3>
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
            
            <div class="col-12 mt-4" style="height: 100%;">
                <h5 class="pl-3">Form Masukan</h5>
                
                <form class="needs-validation" novalidate="" action="{{ route('testimoni.create') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <div class="input-group">
                            <input name="nama" type="text" class="form-control" placeholder="Nama" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <input name="email" type="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isi">Masukan</label>
                        <div class="input-group">
                            <textarea name="isi" type="text" class="form-control" placeholder="Masukan" style="height: 20%;" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Tambah</button>
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    
    <script src="{{ asset('js/app.js') }}?{{ uniqid() }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script src="{{ asset('assets/js/stisla.js') }}"></script>

</body>

</html>
