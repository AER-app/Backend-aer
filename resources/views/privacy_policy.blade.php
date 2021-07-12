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
                <h5 class="pl-3">Privacy Policy</h5>
            </div>
            <h5 class="pt-3 text-center">PRIVACY POLICY</h5>
            <h6 class="text-center">as of {{$create}}</h6>
            @foreach ($data as $data)
                <div style="color:black;">
                    <div class="p-5 pt-1 mb-5" style="text-align: justify;">
                        <?php
                            $string = $data->isi;
                            $pattern  = ["/\r\n/", "/_([\w\s]+)_/", "/\*([\w\s]+)\*/", "/\~([\w\s]+)\~/"];
                            $replacement = ['</br>', '<i>$1</i>', '<strong>$1</strong>', '<strike>$1</strike>'];
                            echo preg_replace($pattern, $replacement, $string);
                        ?>
                    </div>
                </div>
            @endforeach 

        </div>
    </div>

    
    <script src="{{ asset('js/app.js') }}?{{ uniqid() }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script src="{{ asset('assets/js/stisla.js') }}"></script>

</body>

</html>
