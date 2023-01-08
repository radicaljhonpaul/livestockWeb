<?php

namespace App\Listeners;

use App\Events\SyncedDiagnosisLog;
use App\Models\VisitRating;
use App\Models\ConfigSetting;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

use DateInterval;
use DateTime;


class SendDiagnosisRatingSms
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SyncedDiagnosisLog $event)
    {

        $diagnosisLog = $event->diagnosisLog;
     

        //Only send message to (1) CLOSED cases and (2) farmer records with number and (3) consent
        if($diagnosisLog->status != null && $diagnosisLog->status == 'CL' && $diagnosisLog->livestock->farmer->sms_consent == 1 && $diagnosisLog->livestock->farmer->mobile_number != null && $diagnosisLog->livestock->farmer->mobile_number != ""){
 
                //set date filters - clean dates to remove time period
                $dateFrom = new DateTime($diagnosisLog->visit_date);
                $dateFrom = $dateFrom->format('Y-m-d');
                $dateTo = new DateTime($diagnosisLog->visit_date);
                $dateTo->add(new DateInterval('P1D'));
                $dateTo = $dateTo->format('Y-m-d');

                //check if message has been sent for same farmer with same visit date
                $visitRating = VisitRating::where('farmer_id', $diagnosisLog->livestock->farmer_id)
                                            ->whereBetween('visit_date', [$dateFrom,  $dateTo])
                                            ->get();
                
                 //Only send message once per farmer per day
                if($visitRating == null || sizeOf($visitRating) == 0){
               
                //Get Config Settings for building SMS setup
                $configSettings = ConfigSetting::where('key', 'LIKE', 'sms%')->get(['key', 'value']);

                //Set Default Config Values
                $message = "DEFAULT_MSG. Please check config_settings table for key: sms_diagnosis_rating_msg";
                $pcc_number = "";
                $flowUrl = "";
                $twilioUser = "";
                $twilioPass = "";
                $send_rating = "false";     //by default send to false (assume testing unless explicitly set to true in DB)
                $twilioAction = "health_rating";

                foreach($configSettings as $setting){
                    
                    if($setting->key == "sms_diagnosis_rating_msg"){
                        $message = $setting->value;
                    }
                    else if($setting->key == "sms_twilio_number"){
                        $twilio_number =  $setting->value;
                    } 
                    else if($setting->key == "sms_twilio_rating_flow_url"){
                        $flowUrl =  $setting->value;
                    }
                    else if($setting->key == "sms_twilio_auth_user"){
                        $twilioUser =  $setting->value;
                    }
                    else if($setting->key == "sms_twilio_auth_pass"){
                        $twilioPass =  $setting->value;
                    }
                    else if($setting->key == "sms_send_rating"){
                        $send_rating =  $setting->value;
                    }
                    else if($setting->key == "sms_diagnosis_rating_action"){
                        $twilioAction =  $setting->value;
                    }

                }

                //customize message depending on diagnosis log info & who visited
                $visitDate = date_create($diagnosisLog->visit_date)->format('M d, Y');

                $message = str_replace("<last_name>",  $diagnosisLog->assignedTo->last_name, $message);
                $message = str_replace("<first_name>",  $diagnosisLog->assignedTo->first_name, $message);
                $message = str_replace("<visit_date>",  $visitDate, $message);
                $message = str_replace("<pcc_number>",  $twilio_number, $message);
        
                //need to indicate that action is health_rating
                $parameter = [
                    'custom_msg'=> $message,
                    'action'=> $twilioAction
                ];

                //check db setting if sms setting is set to true
                if($send_rating == 'true'){

                    $responseRaw = Http::withBasicAuth($twilioUser, $twilioPass)->asForm()->post($flowUrl, [
                        'To' => '+63'.$diagnosisLog->livestock->farmer->mobile_number,
                        'From' => '+63'.$twilio_number,
                        'Parameters' => json_encode($parameter)
                    ]);

                    
                    $response = json_decode($responseRaw);

                    //Insert to DB
                    $data = [
                                [
                                'message'=> json_encode($parameter), 
                                'diagnosis_log_id' => $diagnosisLog->id, 
                                'visit_date' => $diagnosisLog->visit_date, 
                                'livestock_id' =>$diagnosisLog->livestock_id, 
                                'farmer_id' => $diagnosisLog->livestock->farmer_id,
                                'flow_id' => $response->flow_sid ?? null,
                                'sid' => $response->sid ?? null,
                                'status' => $response->status,
                                'to_number' => $diagnosisLog->livestock->farmer->mobile_number,
                                'type' => $twilioAction,
                                'assigned_to' => $diagnosisLog->assigned_to,
                                'created_by' => $diagnosisLog->created_by,
                                ]
                                
                        ];

                    VisitRating::insert($data);

                }


            } 
      

        } // only send message to closed cases for farmer records with number and consent
    }
}
