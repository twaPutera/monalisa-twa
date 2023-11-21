<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\twaUsersModel;

class wahyuController extends Controller
{
    public function wahyu(){
        

        $user=twaUsersModel::all();
        //dd($user);
        foreach($user as $row){
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://masayu.universitaspertamina.ac.id/api/Data/MasayuByUsername?username=$row->username_sso",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=in6m4d86b13fbcjg7t1aj9e5ir78vvav'
            ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);

            $responseData = json_decode($response, true);
            if(isset($responseData['data'][0]['positions'][0]['unit_kerja'])){
                $update = twaUsersModel::where('username_sso',$row->username_sso)->first()->update([
                    'unit_kerja' => $responseData['data'][0]['positions'][0]['unit_kerja'],
                    'jabatan' => $responseData['data'][0]['positions'][0]['position']
                ]);
                //dd($responseData['data'][0]['positions'][0]['unit_kerja']);
                echo 'sukse';
            }else{
                continue;
            }
            
        }
        //dd($responseData['data'][0]['positions'][0]['position']);
    }
}
