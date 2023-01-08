<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisLog;
use App\Models\DiagnosisLogHealthCondition;
use App\Models\DiagnosisLogIntervention;
use App\Models\DiagnosisLogSymptom;
use Carbon\Carbon;

use App\Models\AdminLevelTwo;
use App\Models\AdminLevelThree;
use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\MediaDiagnosisLog;
use App\Models\UserAdminLevelTwo;
use DateInterval;
use DateTime;
use Hamcrest\Core\IsTypeOf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

// For Archiving
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

//For Events
use App\Events\SyncedDiagnosisLog;

class DiagnosisController extends Controller
{

    public function synched_up(Request $request){
        ob_start('ob_gzhandler');

        //Check if diagnosisLogEntity is existing
        if($request->has('diagnosisLogEntity') == false){
            return response(['message' => "No Records to Sync"], 200);
        }

        DB::beginTransaction();

        try {
            // If uuid exist update or insert
            if($request->has('diagnosisLogEntity')) {
                DiagnosisLog::updateOrInsert(
                    [
                        'external_id' => $request->diagnosisLogEntity['external_uuid']
                    ],
                    [
                        'visit_date' => $request->diagnosisLogEntity['visit_date'], 
                        'activity_type' => $request->diagnosisLogEntity['activity_type'] ?? null,
                        'status' => $request->diagnosisLogEntity['status'] ?? null,
                        'assessment' => $request->diagnosisLogEntity['assessment'] ?? null,
                        'notes' => $request->diagnosisLogEntity['notes'] ?? null,
                        'created_by' => $request->diagnosisLogEntity['created_by'], 
                        'authorized_by' => $request->diagnosisLogEntity['authorized_by'] ?? null,
                        'authorization_via' => $request->diagnosisLogEntity['authorization_via'] ?? null,
                        'assigned_to' => $request->diagnosisLogEntity['assigned_to'], 
                        'livestock_id' => $request->diagnosisLogEntity['livestock_id'],
                        'is_pregnant' => $request->diagnosisLogEntity['is_pregnant'] ?? null,
                        'date_closed' => $request->diagnosisLogEntity['date_closed'] ?? null,
                        'updated_at' => Carbon::now(),
                    ]
                );
            }

            // Check Diagnosis Log for uuid
            $uuid = DiagnosisLog::where('external_id', $request->diagnosisLogEntity['external_uuid'])->get();
            
            if(!sizeof($uuid) == 0){

                    //Move this section inside if statement to avoid null issues

                    // Delete all Record for 'diagnosis_log_id' == $uuid[0]->id                    
                    // // Get array of diagnosis_log_symptoms
                    $diagnosisHealthCondition = DiagnosisLogHealthCondition::where('diagnosis_log_id',$uuid[0]->id)->get();
                    $diagnosisHealthConditionIDArray = [];
                    $diagnosisIntervention = DiagnosisLogIntervention::where('diagnosis_log_id',$uuid[0]->id)->get();
                    $diagnosisInterventionIDArray = [];
                    $diagnosisSymptom = DiagnosisLogSymptom::where('diagnosis_log_id',$uuid[0]->id)->get();
                    $diagnosisSymptomIDArray = [];
                    
                    foreach($diagnosisHealthCondition as $key){
                        array_push($diagnosisHealthConditionIDArray, $key->health_condition_id);
                    }
                    
                    foreach($diagnosisIntervention as $key){
                        array_push($diagnosisInterventionIDArray, $key->intervention_id);
                    }

                    foreach($diagnosisSymptom as $key){
                        array_push($diagnosisSymptomIDArray, $key->symptom_id);
                    }

                // Not empty obj
                // If uuid exist update or insert
                $diagnosisHealthConditionPivotsArr = [];
                if ($request->has('diagnosisHealthConditionPivots')) {
                    foreach($request->diagnosisHealthConditionPivots as $key){
                        array_push($diagnosisHealthConditionPivotsArr, $key['health_condition_id']);
                        DiagnosisLogHealthCondition::updateOrInsert(
                            [
                                'diagnosis_log_id' => $uuid[0]->id, 'health_condition_id' => $key['health_condition_id'],
                            ],
                            [
                                'health_condition_id' => $key['health_condition_id'],
                            ]
                        );
                    }
                    DiagnosisLogHealthCondition::whereIn('health_condition_id', array_values(array_diff($diagnosisHealthConditionIDArray,$diagnosisHealthConditionPivotsArr)))->where('diagnosis_log_id', $uuid[0]->id)->delete();
                }

                // // If uuid exist update or insert
                $diagnosisInterventionPivotsArr = [];
                if ($request->has('diagnosisInterventionPivots')) {
                    foreach($request->diagnosisInterventionPivots as $key){
                        array_push($diagnosisInterventionPivotsArr, $key['intervention_id']);
                        DiagnosisLogIntervention::updateOrInsert(
                            [
                                'diagnosis_log_id' => $uuid[0]->id, 'intervention_id' => $key['intervention_id'],
                            ],
                            [
                                'intervention_id' => $key['intervention_id'],
                            ]
                        );
                    }
                    // Conduct Delete of Unwanted DiagnosisLogIntervention
                    DiagnosisLogIntervention::whereIn('intervention_id', array_values(array_diff($diagnosisInterventionIDArray,$diagnosisInterventionPivotsArr)))->where('diagnosis_log_id', $uuid[0]->id)->delete();
                }

                // // If uuid exist update or insert
                $diagnosisSymptomPivotsArr = [];
                if ($request->has('diagnosisSymptomPivots')) {
                    foreach($request->diagnosisSymptomPivots as $key){
                        array_push($diagnosisSymptomPivotsArr, $key['symptom_id']);
                        DiagnosisLogSymptom::updateOrInsert(
                            [
                                'diagnosis_log_id' => $uuid[0]->id, 'symptom_id' => $key['symptom_id'],
                            ],
                            [
                                'symptom_id' => $key['symptom_id'],
                            ]
                        );
                    }
                    // Conduct Delete of Unwanted DiagnosisLogIntervention
                    DiagnosisLogSymptom::whereIn('symptom_id', array_values(array_diff($diagnosisSymptomIDArray,$diagnosisSymptomPivotsArr)))->where('diagnosis_log_id', $uuid[0]->id)->delete();
                }   
            
                DB::commit();

                //pccp2-183 - trigger sms notification
                event(new SyncedDiagnosisLog($uuid[0]));

                return response([   'message' => "Synched Up Successfully",
                                    'id' => $uuid[0]->id
                                ], 200);
            
            }

            DB::rollback();
            return response(['message' => "Diagnosis Log ".$request->diagnosisLogEntity['external_uuid']." not dected. Sync not successful"], 401);
        
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 401);
        }
        ob_end_flush();
    }

    public function synched_up_multiple(Request $request){
        
        ob_start('ob_gzhandler');
        DB::beginTransaction();

        $successInsertIds = [];

        // Update HealthCondition
        try {

            $result_array = [];
            $data = $request->all();

            for($i=0; $i < sizeof($data); $i++) { 
                try {
                    //code...
                    if (in_array($data[$i]['diagnosisLogEntity'], $data[$i])){

                        DiagnosisLog::updateOrInsert(
                            [
                                'external_id' => $data[$i]['diagnosisLogEntity']['external_uuid']
                            ],
                            [
                                'visit_date' => $data[$i]['diagnosisLogEntity']['visit_date'], 
                                'activity_type' => $data[$i]['diagnosisLogEntity']['activity_type'] ?? null, 
                                'status' => $data[$i]['diagnosisLogEntity']['status'] ?? null, 
                                'assessment' => $data[$i]['diagnosisLogEntity']['assessment'] ?? null, 
                                'notes' => $data[$i]['diagnosisLogEntity']['notes'] ?? null, 
                                'created_by' => $data[$i]['diagnosisLogEntity']['created_by'], 
                                'authorized_by' => $data[$i]['diagnosisLogEntity']['authorized_by'] ?? null, 
                                'authorization_via' => $data[$i]['diagnosisLogEntity']['authorization_via'] ?? null, 
                                'assigned_to' => $data[$i]['diagnosisLogEntity']['assigned_to'], 
                                'livestock_id' => $data[$i]['diagnosisLogEntity']['livestock_id'],
                                'is_pregnant' => $data[$i]['diagnosisLogEntity']['is_pregnant'] ?? null,
                                'date_closed' => $data[$i]['diagnosisLogEntity']['date_closed'] ?? null,
                                'updated_at' => Carbon::now(),
                            ]
                        );
                    }

                    
                    // Get last id then increment
                    $uuid = DiagnosisLog::where('external_id', $data[$i]['diagnosisLogEntity']['external_uuid'])->get();
                    
                    // pccp2-183 - 
                    event(new SyncedDiagnosisLog($uuid[0])); 

                    $diagnosisHealthCondition = DiagnosisLogHealthCondition::where('diagnosis_log_id',$uuid[0]->id)->get();
                    $diagnosisHealthConditionIDArray = [];
                    $diagnosisIntervention = DiagnosisLogIntervention::where('diagnosis_log_id',$uuid[0]->id)->get();
                    $diagnosisInterventionIDArray = [];
                    $diagnosisSymptom = DiagnosisLogSymptom::where('diagnosis_log_id',$uuid[0]->id)->get();
                    
                    $diagnosisSymptomIDArray = [];
    
                    foreach($diagnosisHealthCondition as $key){
                        array_push($diagnosisHealthConditionIDArray, $key->health_condition_id);
                    }
                    
                    foreach($diagnosisIntervention as $key){
                        array_push($diagnosisInterventionIDArray, $key->intervention_id);
                    }
        
                    foreach($diagnosisSymptom as $key){
                        array_push($diagnosisSymptomIDArray, $key->symptom_id);
                    }
    
                    if(!sizeof($uuid) == 0){
                        // return "not 0";
                        // Not empty obj
                        // If uuid exist update or insert
                        $diagnosisHealthConditionPivotsArr = [];

                        if (in_array($data[$i]['diagnosisHealthConditionPivots'], $data[$i])) {
                            
                            foreach($data[$i]['diagnosisHealthConditionPivots'] as $key){
                                array_push($diagnosisHealthConditionPivotsArr, $key['health_condition_id']);
                                DiagnosisLogHealthCondition::updateOrInsert(
                                    [
                                        'diagnosis_log_id' => $uuid[0]->id, 'health_condition_id' => $key['health_condition_id'],
                                    ],
                                    [
                                        'health_condition_id' => $key['health_condition_id'],
                                    ]
                                );
                            }
                            DiagnosisLogHealthCondition::whereIn('health_condition_id', array_values(array_diff($diagnosisHealthConditionIDArray,$diagnosisHealthConditionPivotsArr)))->where('diagnosis_log_id', $uuid[0]->id)->delete();
                        }
        
                        // // If uuid exist update or insert
                        $diagnosisInterventionPivotsArr = [];
                        if (in_array($data[$i]['diagnosisInterventionPivots'], $data[$i])) {
                            foreach($data[$i]['diagnosisInterventionPivots'] as $key){
                                array_push($diagnosisInterventionPivotsArr, $key['intervention_id']);
                                DiagnosisLogIntervention::updateOrInsert(
                                    [
                                        'diagnosis_log_id' => $uuid[0]->id, 'intervention_id' => $key['intervention_id'],
                                    ],
                                    [
                                        'intervention_id' => $key['intervention_id'],
                                    ]
                                );
                            }
                            // Conduct Delete of Unwanted DiagnosisLogIntervention
                            DiagnosisLogIntervention::whereIn('intervention_id', array_values(array_diff($diagnosisInterventionIDArray,$diagnosisInterventionPivotsArr)))->where('diagnosis_log_id', $uuid[0]->id)->delete();
                        }
        
                        // // If uuid exist update or insert
                        $diagnosisSymptomPivotsArr = [];
                        if (in_array($data[$i]['diagnosisSymptomPivots'], $data[$i])) {
                            foreach($data[$i]['diagnosisSymptomPivots'] as $key){
                                array_push($diagnosisSymptomPivotsArr, $key['symptom_id']);
                                DiagnosisLogSymptom::updateOrInsert(
                                    [
                                        'diagnosis_log_id' => $uuid[0]->id, 'symptom_id' => $key['symptom_id'],
                                    ],
                                    [
                                        'symptom_id' => $key['symptom_id'],
                                    ]
                                );
                            }
                            // Conduct Delete of Unwanted DiagnosisLogIntervention
                            DiagnosisLogSymptom::whereIn('symptom_id', array_values(array_diff($diagnosisSymptomIDArray,$diagnosisSymptomPivotsArr)))->where('diagnosis_log_id', $uuid[0]->id)->delete();
                        }   
                    }

                    array_push($result_array, (object)[
                        "external_id" =>  $uuid[0]->external_id,
                        "id" => $uuid[0]->id,
                        "status" => "success"
                    ]);

                    //pccp2-183
                    //array_push($successInsertIds, $uuid[0]->id);

    
                } catch (\Exception $e) {
                    // return $e->getMessage();
                    //throw $th;
                    array_push($result_array, (object)[
                        "external_id" =>  $uuid[0]->external_id,
                        "id" => $uuid[0]->id,
                        "status" => "failed",
                        "error_message" => $e->getMessage(),
                    ]);
                }

                // return $EXT_ID;
            }
        
            DB::commit();
            
            // pccp2-183 - Lines needed for event triggering
            /*
            $successRecords = DiagnosisLog::whereIn('id', $successInsertIds)->get();
            foreach($successRecords as $record){
                event(new SyncedDiagnosisLog($record));            
            } */
                    

             
            return response($result_array, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 401);
        }
        ob_end_flush();
    }
    /**
     * Gets all diagnosis log records & sub records ( diagnosis_log_symptom, diagnosis_log_intervention, diagnosis_log_health_condition )
     * based on the assigned location (user_admin_level_two) table
     * 
     * Images are not included as that should be handled separately
     */
    public function diagnosisLog_syncDown(Request $request){

        $dateFrom = new DateTime($request->syncFromDate);
        $xxx = $dateFrom->format('Y-m-d');
        // syncFromDate
        // syncToDate
        $dateTo = new DateTime($request->syncToDate);
        $dateTo->add(new DateInterval('P1D'));
        $add1Day = $dateTo->format('Y-m-d');
		
		$user =  $request->user();
		
		//Fixed Dec 15, 2021 - Concern - Symptoms, Health Conditions & Interventions are not loading
		
		if ($request->has("farmer_id")) {
		 
		 	$DiagnosisLog = DiagnosisLog::with('symptoms:id', 'healthConditions:id', 'interventions:id')
				->whereHas('livestock' , function ($query) use ($user, $request) {
					$query->whereHas('farmer', function ($query) use ($user, $request) {
						$query->where('id', $request->farmer_id)
							   ->whereHas('adminLevelThree', function($query) use ($user) {
									$query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
						});
					});
				})
				->whereNotNull('updated_at')
				->whereBetween('updated_at',[$request->syncFromDate, $add1Day])
				->get();
				
				return $DiagnosisLog;
			
		}	
		else{
		
			$DiagnosisLog = DiagnosisLog::with('symptoms:id', 'healthConditions:id', 'interventions:id')
			->whereHas('livestock' , function ($query) use ($user) {
				$query->whereHas('farmer', function ($query) use ($user) {
					$query->whereHas('adminLevelThree', function($query) use ($user) {
						$query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
					});
				});
			})
			->whereNotNull('updated_at')
			->whereBetween('updated_at',[$request->syncFromDate, $add1Day])
			->get();
			
			return $DiagnosisLog;
		
		}
		
    }

    public function diagnosisLog_syncDownPaginated(Request $request){

        $dateFrom = new DateTime($request->syncFromDate);
        $xxx = $dateFrom->format('Y-m-d');
        // syncFromDate
        // syncToDate
        $dateTo = new DateTime($request->syncToDate);
        $dateTo->add(new DateInterval('P1D'));
        $add1Day = $dateTo->format('Y-m-d');
		
		$user =  $request->user();
		
		//Fixed Dec 15, 2021 - Concern - Symptoms, Health Conditions & Interventions are not loading
		
		if ($request->has("farmer_id")) {
		 
		 	$DiagnosisLog = DiagnosisLog::with('symptoms:id', 'healthConditions:id', 'interventions:id')
				->whereHas('livestock' , function ($query) use ($user, $request) {
					$query->whereHas('farmer', function ($query) use ($user, $request) {
						$query->where('id', $request->farmer_id)
							   ->whereHas('adminLevelThree', function($query) use ($user) {
									$query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
						});
					});
				})
				->whereNotNull('updated_at')
				->whereBetween('updated_at',[$request->syncFromDate, $add1Day])
                ->simplePaginate(50)
                ->setPath(Config::get('app.url').$request->path());    //for prod ip to url handling
				
				return $DiagnosisLog;
			
		}	
		else{
		
			$DiagnosisLog = DiagnosisLog::with('symptoms:id', 'healthConditions:id', 'interventions:id')
			->whereHas('livestock' , function ($query) use ($user) {
				$query->whereHas('farmer', function ($query) use ($user) {
					$query->whereHas('adminLevelThree', function($query) use ($user) {
						$query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
					});
				});
			})
			->whereNotNull('updated_at')
			->whereBetween('updated_at',[$request->syncFromDate, $add1Day])
            ->simplePaginate(50)
            ->setPath(Config::get('app.url').$request->path());    //for prod ip to url handling
			
			return $DiagnosisLog;
		
		}
		
    }


    //TODO: separate api for media related to diagnosis logs
    public function diagnosisLog_media_syncUp(Request $request){

        ob_start('ob_gzhandler');
        DB::beginTransaction();
        // Update HealthCondition
        try {
            // Directory
            // /diagnosis_logs/farmer_id/livestock_id/diagnosislogextid/{actual media}
            // [farmerid]_[carabaocode]_[diagnosislogsexternal_id]_{yyyy-mm-dd}-filenumber.extension

            $total_uploaded = [];
            $uploaded = [];
            $deleted = [];
            $successful_uploaded = [];
            $failed_uploaded = [];
            $images = $request->file('media_diagnosislog');

            $default_media = [];
            $newly_uploaded_default_media = [];

            if($request->hasFile('media_diagnosislog') && $request->media_diagnosislog ){
                $get_splitted_name_for_external_id = explode("_", $images[0]->getClientOriginalName());
                $DIAGNOSIS_LOG = DiagnosisLog::select('id', 'external_id')->where('external_id', $this->trim_brackets($get_splitted_name_for_external_id[2]))->first();

                // Conduct check for filename and push to array
                $MediaLogs = MediaDiagnosisLog::where('diagnosis_log_id', $DIAGNOSIS_LOG->id)->get();

                foreach ($MediaLogs as $key) {
                    array_push($default_media, $key->id);
                    // Removed media from folder
                    Storage::disk('public')->delete($key->path_name);
                }
                // Removed media from medialogs table
                MediaDiagnosisLog::whereIn('id',$default_media)->delete();

                foreach ($images as $key) {
                    $ext = $key->getClientOriginalExtension();
                    $split_ = explode("_", $key->getClientOriginalName());
                    $farmer_id = $this->trim_brackets($split_[0]);
                    $livestock_code = $this->trim_brackets($split_[1]);
                    $diagnosis_log_ext_id = $this->trim_brackets($split_[2]);
        
                    $diagnosis_log_id = DiagnosisLog::select('id', 'external_id')->where('external_id', $diagnosis_log_ext_id)->first();

                    // Fix - Issue for issue only 1 media log record created per diagnosis log (even if there are 2 or more media attachments)
                    MediaDiagnosisLog::updateOrInsert(
                        [
                            'diagnosis_log_id' => $diagnosis_log_id->id, 
                            'path_name' => "profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]",
                        ],
                        [
                            'diagnosis_log_id' => $diagnosis_log_id->id,
                            'path_name' => "profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]",
                            'type' => $ext,
                            'filesize' => $key->getSize(),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ],
                    );

                    Storage::disk("public")->put("profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]",file_get_contents($key));
                    $image = "profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]";
                    
                    array_push($total_uploaded, $key->getClientOriginalName());
                    
                    if(Storage::disk('public')->exists($image)){
                        array_push($uploaded, $key->getClientOriginalName()." : 1");
                        array_push($successful_uploaded, 0);
                    }else{
                        array_push($uploaded, $key->getClientOriginalName()." : 0");
                        array_push($failed_uploaded, 0);
                    }
                }

            };
            
            // another check if empty array
            // if empty delete all media files in the storage where farmer id = N

            DB::commit();
            return response([
                'message' => "Media Files Uploaded",
                'uploaded' => $uploaded,
                'asessment' => sizeof($total_uploaded).'/'.sizeof($successful_uploaded)."  Media Files Uploaded Successfully. ",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 401);
        }
        ob_end_flush();
    
    }

    public function diagnosisLog_media_syncUpWithDeletedFiles(Request $request){

        ob_start('ob_gzhandler');
        DB::beginTransaction();
        // Update HealthCondition
        try {
            // Directory
            // /diagnosis_logs/farmer_id/livestock_id/diagnosislogextid/{actual media}
            // [farmerid]_[carabaocode]_[diagnosislogsexternal_id]_{yyyy-mm-dd}-filenumber.extension

            $total_uploaded = [];
            $uploaded = [];
            $deleted = [];
            $successful_uploaded = [];
            $failed_uploaded = [];
            $images = $request->file('media_diagnosislog');

            $default_media = [];
            $newly_uploaded_default_media = [];

            if($request->has('delete_media_diagnosislog')) {
                // return "request key is not empty";
                if($request->delete_media_diagnosislog != null && sizeof($request->delete_media_diagnosislog) > 0){
                    foreach ($request->delete_media_diagnosislog as $key) {
                        $split_ = explode("_", $key);
                        $farmer_id = $this->trim_brackets($split_[0]);
                        $livestock_code = $this->trim_brackets($split_[1]);
                        $diagnosis_log_ext_id = $this->trim_brackets($split_[2]);
                    
                        $imageForDelete = "profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]";
                        if(Storage::disk('public')->exists($imageForDelete)){
                            array_push($deleted, $key);
                            Storage::disk('public')->delete($imageForDelete);
                        }

                        // Delete in DB.
                        MediaDiagnosisLog::where('path_name', $imageForDelete)->delete();
                    }

                }
            }
            
            if($request->hasFile('media_diagnosislog')){
                $get_splitted_name_for_external_id = explode("_", $images[0]->getClientOriginalName());
                $DIAGNOSIS_LOG = DiagnosisLog::select('id', 'external_id')->where('external_id', $this->trim_brackets($get_splitted_name_for_external_id[2]))->first();

                // Conduct check for filename and push to array

                foreach ($images as $key) {
                    $ext = $key->getClientOriginalExtension();
                    $split_ = explode("_", $key->getClientOriginalName());
                    $farmer_id = $this->trim_brackets($split_[0]);
                    $livestock_code = $this->trim_brackets($split_[1]);
                    $diagnosis_log_ext_id = $this->trim_brackets($split_[2]);
        
                    $diagnosis_log_id = DiagnosisLog::select('id', 'external_id')->where('external_id', $diagnosis_log_ext_id)->first();

                    Storage::disk("public")->put("profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]",file_get_contents($key));
                    $image = "profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]";
                    
                    array_push($total_uploaded, $key->getClientOriginalName());
                    
                    if(Storage::disk('public')->exists($image)){
                        array_push($uploaded, $key->getClientOriginalName()." : 1");
                        array_push($successful_uploaded, 0);
                    }else{
                        array_push($uploaded, $key->getClientOriginalName()." : 0");
                        array_push($failed_uploaded, 0);
                    }

                    // Fix - Issue for issue only 1 media log record created per diagnosis log (even if there are 2 or more media attachments)
                    MediaDiagnosisLog::updateOrInsert(
                        [
                            'diagnosis_log_id' => $diagnosis_log_id->id, 
                            'path_name' => "profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]",
                        ],
                        [
                            'diagnosis_log_id' => $diagnosis_log_id->id,
                            'path_name' => "profile/diagnosis_logs/$farmer_id/$livestock_code/$diagnosis_log_ext_id/$split_[3]",
                            'type' => $ext,
                            'filesize' => $key->getSize(),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ],
                    );

                }

            };
            

            DB::commit();
            return response([
                'message' => "Media Files Uploaded",
                'uploaded' => $uploaded,
                'deleted' => $deleted,
                'asessment' => sizeof($total_uploaded).'/'.sizeof($successful_uploaded)."  Media Files Uploaded Successfully. Total Deleted Files: ". sizeof($deleted),
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 401);
        }
        ob_end_flush();
    
    }

    public function diagnosisLog_media_CheckFileSize(Request $request){

        $dateFrom = new DateTime($request->syncFromDate);
        $xxx = $dateFrom->format('Y-m-d');
        // syncFromDate
        // syncToDate
        $dateTo = new DateTime($request->syncToDate);
        $dateTo->add(new DateInterval('P1D'));
        $add1Day = $dateTo->format('Y-m-d');
	
		//Fix for PCCP2-598 (align method for checking media file size with actual method of getting media diagnosis log so consistent data size)
        $user =  $request->user();
		
        $MediaDiagnosisLog = MediaDiagnosisLog::query()
        //->with('diagnosisLog.livestock')
        ->whereHas('diagnosisLog' , function ($query) use ($user) {
            $query->whereHas('livestock', function ($query) use ($user) {
                $query->whereHas('farmer', function($query) use ($user) {
                    $query->whereHas('adminLevelThree', function($query) use ($user){
                        $query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
                    });
                });
            });
        })
        ->whereNotNull('updated_at')
        ->whereBetween('updated_at',[$request->syncFromDate, $add1Day]);

        if ($request->has("farmer_id")) {

            $MediaDiagnosisLog = MediaDiagnosisLog::query()
            //->with('diagnosisLog.livestock')
            ->whereHas('diagnosisLog' , function ($query) use ($user, $request) {
                $query->whereHas('livestock', function ($query) use ($user, $request) {
                    $query->whereHas('farmer', function($query) use ($user, $request) {
                        $query->where('id', $request->farmer_id)->whereHas('adminLevelThree', function($query) use ($user){
                            $query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
                        });
                    });
                });
            })
            ->whereNotNull('updated_at')
            ->whereBetween('updated_at',[$request->syncFromDate, $add1Day]);
        }

        $data = $MediaDiagnosisLog->get();
	
		
        $FileSizeArray = [];
        foreach ($data as $key) {
            // return $key->mediaDiagnosisLogs[0]->filesize;
            // array_push($FileSizeArray, $key->mediaDiagnosisLogs[0]->filesize);
            array_push($FileSizeArray, $key->filesize);
            
        }

        return response([
            'Total_Size' => $this->GetReadableFileSize(array_sum($FileSizeArray)),
        ], 200);
    }
    
    private function generate_DiagnosisLog_media(Request $request){

        // syncFromDate
        $dateFrom = new DateTime($request->syncFromDate);
        $xxx = $dateFrom->format('Y-m-d');
        // syncToDate
        $dateTo = new DateTime($request->syncToDate);
        $dateTo->add(new DateInterval('P1D'));
        $add1Day = $dateTo->format('Y-m-d');
        
        $user =  $request->user();

        $MediaDiagnosisLog = MediaDiagnosisLog::query()
        ->with('diagnosisLog.livestock')
        ->whereHas('diagnosisLog' , function ($query) use ($user) {
            $query->whereHas('livestock', function ($query) use ($user) {
                $query->whereHas('farmer', function($query) use ($user) {
                    $query->whereHas('adminLevelThree', function($query) use ($user){
                        $query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
                    });
                });
            });
        })
        ->whereNotNull('updated_at')
        ->whereBetween('updated_at',[$request->syncFromDate, $add1Day]);

        if ($request->has("farmer_id")) {

            $MediaDiagnosisLog = MediaDiagnosisLog::query()
            ->with('diagnosisLog.livestock')
            ->whereHas('diagnosisLog' , function ($query) use ($user, $request) {
                $query->whereHas('livestock', function ($query) use ($user, $request) {
                    $query->whereHas('farmer', function($query) use ($user, $request) {
                        $query->where('id', $request->farmer_id)->whereHas('adminLevelThree', function($query) use ($user){
                            $query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
                        });
                    });
                });
            })
            ->whereNotNull('updated_at')
            ->whereBetween('updated_at',[$request->syncFromDate, $add1Day]);
        }

        $data = $MediaDiagnosisLog->get();

        if(sizeof($data) == 0){
            return response([
                'message' => "No Media files to be downloaded.",
            ]);
        }
        
		//add current timestamp to handle media freshness issue
		$current_timestamp = Carbon::now()->timestamp;
		
        $ZipFile = Auth::user()->id.'_'.$request->syncFromDate.'_'.$request->syncToDate.'-'.$current_timestamp.'.zip';

        if (file_exists(public_path('/medialogs/'.$ZipFile))) {
            // Delete a single file
            File::delete($ZipFile);
        }
        
        foreach ($data as $key) {
            // return $key;
            $farmer_id = $key->diagnosisLog->livestock->farmer_id;
            $carabao_code = $key->diagnosisLog->livestock->carabao_code;
            $external_id = $key->diagnosisLog->external_id;
            /**
             * Archiving Process
             */
            // Get real path for our folder
            $rootPath = realpath(public_path("storage/$key->path_name"));
            if (!file_exists(public_path('/medialogs/'))) {
                // path does not exist
                File::makeDirectory(public_path('/medialogs/'));
            }
        
            $zip = new ZipArchive();
            $zip->open(public_path('/medialogs/'.$ZipFile), ZipArchive::CREATE);

            if (File::exists($rootPath)){
                $zip->addFile($rootPath, $farmer_id.'/'.$carabao_code.'/'.$external_id.'/'.basename($rootPath));
            }

        }

        $zip->close();
        
        return $ZipFile;
        
        // return response()->download(public_path('/medialogs/'.$ZipFile));
        //->deleteFileAfterSend(true);    //uncomment this to check if this will resolve issue of getting older version of the media files
    }

    public function download_DiagnosisLog_media_syncDown(Request $request)
    {
        $ZipFile = $this->generate_DiagnosisLog_media($request);
        return response()->download(public_path('/medialogs/'.$ZipFile));
    }

    public function download_DiagnosisLog_media_syncDown_URL(Request $request){

        $ZipFile = $this->generate_DiagnosisLog_media($request);
        $BaseURL = url('/');

        return response([
            'URL' => $BaseURL .'/medialogs/'.$ZipFile,
        ], 200);
    }

    public function trim_brackets(String $value){

        $get_left_bracket = ltrim($value,"[");
        $value = $get_left_bracket;
        $get_right_bracket = rtrim($value,"]");
        $value = $get_right_bracket;

        return $value;
    }

    public function GetReadableFileSize($size,$unit="") {
        if( (!$unit && $size >= 1<<30) || $unit == "GB")
          return number_format($size/(1<<30),2)."GB";
        if( (!$unit && $size >= 1<<20) || $unit == "MB")
          return number_format($size/(1<<20),2)."MB";
        if( (!$unit && $size >= 1<<10) || $unit == "KB")
          return number_format($size/(1<<10),2)."KB";
        return number_format($size)." bytes";
      }
}
