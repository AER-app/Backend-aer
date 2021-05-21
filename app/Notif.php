<?php

namespace App;

class Notif
{
    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    }

    public function sendDriver($arrayToken, $orderID, $namaLapak, $pesan, $judul)
    {
        $array = array(
            "registration_ids" => $arrayToken,
            "data" => ["message" => $pesan, "title" => $judul, "orderID" => $orderID]
        );
        $field = json_encode($array);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $field,
            CURLOPT_HTTPHEADER => array(
                'Authorization: key=AAAA1FxMLT8:APA91bENWtRqMrIbZJ_XI8beVOX21m_qWgnwh1UrYghEXneTdlnqR54xZ8AN1U4wFSbvM08fBtJZIFYZB8SytkaP9DJg_THfssvYivUymxeDynk6E4NFUkcBGjquyt_FQMMPLjNbt2lY',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }

    public function sendLapak($tokenLapak, $namaLapak, $pesan, $judul)
    {
        $array = array(
            "to" => $tokenLapak,
            "notification" => ["body" => $pesan, "title" => $judul],
        );
        $field = json_encode($array);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $field,
            CURLOPT_HTTPHEADER => array(
                'Authorization: key=AAAA1FxMLT8:APA91bENWtRqMrIbZJ_XI8beVOX21m_qWgnwh1UrYghEXneTdlnqR54xZ8AN1U4wFSbvM08fBtJZIFYZB8SytkaP9DJg_THfssvYivUymxeDynk6E4NFUkcBGjquyt_FQMMPLjNbt2lY',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }

    public function sendCustomer($tokenCus, $namaCustomer, $pesan, $judul)
    {
        $array = array(
            "to" => $tokenCus,
            "notification" => ["body" => $pesan, "title" => $judul],
        );
        $field = json_encode($array);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $field,
            CURLOPT_HTTPHEADER => array(
                'Authorization: key=AAAA1FxMLT8:APA91bENWtRqMrIbZJ_XI8beVOX21m_qWgnwh1UrYghEXneTdlnqR54xZ8AN1U4wFSbvM08fBtJZIFYZB8SytkaP9DJg_THfssvYivUymxeDynk6E4NFUkcBGjquyt_FQMMPLjNbt2lY',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }
}
