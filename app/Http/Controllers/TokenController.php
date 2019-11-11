<?php

namespace App\Http\Controllers;

class TokenController extends Controller
{
    public function gerar(){
        // Homologação

        //https://oauth.hm.bb.com.br/oauth/token
        // ClientID:
        // eyJpZCI6IjgwNDNiNTMtZjQ5Mi00YyIsImNvZGlnb1B1YmxpY2Fkb3IiOjEwOSwiY29kaWdvU29mdHdhcmUiOjEsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxfQ

        // Client Secret:
        // eyJpZCI6IjBjZDFlMGQtN2UyNC00MGQyLWI0YSIsImNvZGlnb1B1YmxpY2Fkb3IiOjEwOSwiY29kaWdvU29mdHdhcmUiOjEsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxLCJzZXF1ZW5jaWFsQ3JlZGVuY2lhbCI6MX0

        $clientID = 'eyJpZCI6IjgwNDNiNTMtZjQ5Mi00YyIsImNvZGlnb1B1YmxpY2Fkb3IiOjEwOSwiY29kaWdvU29mdHdhcmUiOjEsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxfQ';
        $clientSecret = 'eyJpZCI6IjBjZDFlMGQtN2UyNC00MGQyLWI0YSIsImNvZGlnb1B1YmxpY2Fkb3IiOjEwOSwiY29kaWdvU29mdHdhcmUiOjEsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxLCJzZXF1ZW5jaWFsQ3JlZGVuY2lhbCI6MX0';
        $url = 'https://oauth.hm.bb.com.br/oauth/token';

        $encode = base64_encode($clientID.":".$clientSecret);

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_POST => true,
			CURLOPT_TIMEOUT => 10000,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=cobranca.registro-boletos',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . $encode,
				'Cache-Control: no-cache'
            )
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $exe = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($exe);

        // Retornando o token ou erro
        if(isset($res->error)){
            return $exe;
        }else{
            return $exe;
        }
    }
}
