<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FarmerConsent;
use App\Models\Connection;
use App\Models\VisitRating;
use Illuminate\Http\Request;

use DateTime;

class SmsController extends Controller
{
    
    public function createConnectionToken(){

        /*
        $connection = Connection::find(1);

        return response([
            'user' => $connection,
            'token' => $connection->createToken($connection->name)->plainTextToken,
        ]); */

        return 'This API is Disabled';

    }


    public function setFarmerConsentViaSms(){

        
        $farmerConsent = FarmerConsent::create([
            
            'type' => request('type'),
            'method' => 'sms',
            'details' => request('sender'),
            'consent' => request('consent'),
        ]);
        

        return 'Saved consent: '.$farmerConsent->id;

    }


    public function setHealthRatingViaSms(){


        $sid = request('sid');

        $visitRating = VisitRating::where('sid', $sid)->first();

        if($visitRating != null){

            $visitRating->farmer_reply = request('farmer_reply');
            $visitRating->rating = request('rating');
            $visitRating->updated_at = new DateTime();
            $visitRating->status = 'rated';

            $visitRating->save();

            return 'Saved Rating: '.$visitRating->rating;
        }

        return 'Record not existing: '.$sid;

    }


}
