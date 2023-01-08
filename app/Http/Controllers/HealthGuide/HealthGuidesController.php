<?php

namespace App\Http\Controllers\HealthGuide;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
// Models
use App\Models\HcSymptom;
use App\Models\HealthCondition;
use App\Models\Intervention;
use App\Models\MediaHealthCondition;
use App\Models\MediaSymptom;
use App\Models\OrganSystem;
use App\Models\OrganSystemSymptom;
use App\Models\Symptom;
use App\Models\User;
use App\Models\UserRoles;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class HealthGuidesController extends Controller
{
    /**
     *  HealthGuide Method loads the Users Details Table and OrganSystems Table.
    **/
    public function GetRoles()
    {
        $data = UserRoles::where('user_id',auth()->user()->id)->get();
        $role_arr = [];
        foreach ($data as $key) {
            array_push($role_arr, $key->role);
        }

        return $role_arr;
    }

    public function HealthGuide(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('HealthGuides/Systems', [
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'OrganSystems' => OrganSystem::orderBy('name', 'asc')->get(),
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }

    /**
     *  SpecificSystem Method loads the Health Conditions per System
    **/
    public function SpecificSystem(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('HealthGuides/SpecificSystems', [
                'HealthConditions' => HealthCondition::with('hcInterventions')
                ->with('symptoms')
                ->with('mediaHealthCondition')
                ->where('organ_system_id',$request->id)
                ->orderBy('name', 'asc')
                ->paginate(5)
                ->setPath('')   //for prod ip to url handling
                ->appends($request->query()),
                'Type' => $request->type,
                'OrganSystemID' => $request->id,
                'Symptoms' => Symptom::with('organSystemsSymptoms')->orderBy('name', 'asc')->get(),
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }

        /**
     *  SaveCreatedHC Method saves the created for Health Conditions
    **/
    public function SaveCreatedHC(Request $request)
    {
        ob_start('ob_gzhandler');

            $request->validate([
                'name' => 'required',
                // 'local_term' => 'required',
                'description' => 'required',
                'advice_to_farmer' => 'required',
                'symptoms' => 'required',
                'how_to_diganose' => 'required',
                'treatment' => 'required',
                'attached_media_health_condition' => 'required',
            ]);

            DB::beginTransaction();
            // Update HealthCondition

            try {

                // return $request;
                if($request->zoonotic == null){
                    $request->zoonotic = 0;
                }

                $HealthCondition = new HealthCondition;
                $HealthCondition->name = $request->name;
                $HealthCondition->local_term = $request->local_term;
                $HealthCondition->organ_system_id = $request->organ_system_id;
                $HealthCondition->description = $request->description;
                // how_to_diganose - keep
                $HealthCondition->how_to_diganose = $request->how_to_diganose;
                $HealthCondition->treatment = $request->treatment;
                $HealthCondition->advice_to_farmer = $request->advice_to_farmer;
                $HealthCondition->zoonotic = $request->zoonotic;
                $HealthCondition->preventive_measure = '';
                $HealthCondition->save();

                // If differential symptoms -> create upsert for diff column
                // 1 by default
                if($request->differentialSymptoms != null){
                    foreach ($request->differentialSymptoms as $key) {
                        // Update HC_Symptoms
                        HcSymptom::updateOrInsert(
                            [ 'health_condition_id' => $HealthCondition->id, 'symptom_id' => $key ],
                            [ 'differential' => 1 ]
                        );
                    }
                }

                if($request->symptoms != null){

                    foreach ($request->symptoms as $key) {
                        // Update HC_Symptoms
                        HcSymptom::updateOrInsert(
                            [ 'health_condition_id' => $HealthCondition->id, 'symptom_id' => $key ],
                            [ 'health_condition_id' => $HealthCondition->id, 'symptom_id' => $key ]
                        );
                        // Update OrgaSysSymptoms
                        OrganSystemSymptom::updateOrInsert(
                            [ 'organ_system_id' => $request->organ_system_id, 'symptom_id' => $key ],
                            [ 'organ_system_id' => $request->organ_system_id, 'symptom_id' => $key ]
                        );
                    }
                }

                if($request->hc_interventions != null){
                    // return $request->hc_interventions;
                    foreach ($request->hc_interventions as $key => $value) {
                        $Interventions = new Intervention;
                        $Interventions->health_condition_id = $HealthCondition->id;
                        $Interventions->description = $value['description'];
                        $Interventions->need_license = $value['need_license'];
                        $Interventions->pregnant_applicable = $value['pregnant_applicable'];
                        $Interventions->save();
                    }
                }

                if($request->hasFile('attached_media_health_condition')) {
                    $length = 5;
                    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
                    // return $request->attached_media_health_condition;
                    
                    foreach ($request->attached_media_health_condition as $key) {
                        $ext = $key->getClientOriginalExtension();
                        $filename = 'HC-'. Carbon::now()->format('Y-m-d') . substr(str_shuffle(str_repeat($pool, 5)), 0, $length) . '.'.$ext;
            
                        $Upload = new MediaHealthCondition;
                        $Upload->health_condition_id = $HealthCondition->id;
                        $Upload->path_name = "profile/hc/$request->organ_system_id/$HealthCondition->id/$filename";
                        $Upload->type = $ext;
                        $Upload->save();

                        Storage::disk("public")->put("profile/hc/$request->organ_system_id/$HealthCondition->id/$filename",file_get_contents($key));
                        // Determine what table to inserted via variable "$type"
                        // Check if has existing files
                        // Delete if Exists
                        // Save or Update file to table
                    }
                }

                // return redirect()->back();

                DB::commit();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
        ob_end_flush();
    }

    /**
     *  SaveEditedHC Method saves the edits for Health Conditions
    **/
    public function SaveEditedHC(Request $request)
    {
        
        ob_start('ob_gzhandler');
            DB::beginTransaction();
            // Update HealthCondition
            try {
                // 1 big array of symptoms
                $all_symptoms = [];

                $default_hc_symptom = HcSymptom::select('id','symptom_id')->where('health_condition_id', $request->id)->get();
                $default_hc_symptom_array = [];
                foreach ($default_hc_symptom as $key => $value) {
                    array_push($default_hc_symptom_array, $value['symptom_id']);
                    array_push($all_symptoms, $value['symptom_id']);

                }
                $default_interventions_array = [];
                $default_interventions = Intervention::where('health_condition_id', $request->id)->get();
                foreach ($default_interventions as $key => $value) {
                    array_push($default_interventions_array, $value['id']);
                }

                // Check for HC Name
                if($request->name != null){
                    HealthCondition::where('id', $request->id)->update(['name' => $request->name]);
                }
                // Check for HC Description
                if($request->description != null){
                    HealthCondition::where('id', $request->id)->update(['description' => $request->description]);
                }

                // Check for HC Description
                if($request->zoonotic != null){
                    HealthCondition::where('id', $request->id)->update(['zoonotic' => $request->zoonotic]);
                }

                // Check for Local Term
                if($request->local_term != null){
                    HealthCondition::where('id', $request->id)->update(['local_term' => $request->local_term]);
                }else{
                    HealthCondition::where('id', $request->id)->update(['local_term' => '']);
                }
                
                // Check for HC Diagnose
                if($request->how_to_diganose != null){
                    HealthCondition::where('id', $request->id)->update(['how_to_diganose' => $request->how_to_diganose]);
                }
                // Check for HC Treatment
                if($request->treatment != null){
                    HealthCondition::where('id', $request->id)->update(['treatment' => $request->treatment]);
                }
                // Check for HC Advice
                if($request->advice_to_farmer != null){
                    HealthCondition::where('id', $request->id)->update(['advice_to_farmer' => $request->advice_to_farmer]);
                }

                $hc_symptom = [];
                $diff_symp = [];

                $default_hc_differential_symptom = HcSymptom::select('id','symptom_id')->where('health_condition_id', $request->id)->where('differential', 1)->get();
                $default_hc_differential_symptom_array = [];
                foreach ($default_hc_differential_symptom as $key => $value) {
                    array_push($default_hc_differential_symptom_array, $value['symptom_id']);
                    array_push($all_symptoms, $value['symptom_id']);

                }

                // Default Symptoms and add or deleted symptoms
                if($request->symptoms != null){
                    foreach ($request->symptoms as $key => $value) {
                        array_push($hc_symptom, intval($value['id']));
                        array_push($all_symptoms, intval($value['id']));
                    }
                }

                if($request->differentialSymptoms != null){
                    foreach ($request->differentialSymptoms as $key => $value) {
                        array_push($diff_symp, intval($value['id']));
                        array_push($all_symptoms, intval($value['id']));
                    }
                }

                $get_deleted_diff_symptoms = array_values(array_diff($default_hc_differential_symptom_array,$diff_symp));
                $get_deleted_symptoms = array_values(array_diff($default_hc_symptom_array,$hc_symptom));
                
                $unique_array_symptoms = array_unique($all_symptoms);
                $deleted_hcSymp = array_values(array_diff($unique_array_symptoms,$hc_symptom));
                $deleted_hcDiffSymp = array_values(array_diff($deleted_hcSymp,$diff_symp));
                $ToBeDeleted = $deleted_hcDiffSymp;
                $final_symptoms = array_values(array_diff($unique_array_symptoms,$ToBeDeleted));
                // return $ToBeDeleted;

                foreach ($final_symptoms as $key => $value) {
                    // Update HC_Symptoms
                    HcSymptom::updateOrInsert([
                        'health_condition_id' => $request->id,
                        'symptom_id' => $value,
                    ],
                    [
                        'health_condition_id' => $request->id,
                        'symptom_id' => $value,
                    ]);
                    // Update OrgaSysSymptoms
                    OrganSystemSymptom::updateOrInsert([
                        'organ_system_id' => $request->organ_system_id,
                        'symptom_id' => $value,
                    ]);
                }

                foreach ($diff_symp as $key => $value) {
                    // Update HC_Symptoms
                    HcSymptom::updateOrInsert([
                        'health_condition_id' => $request->id,
                        'symptom_id' => $value,
                    ],
                    [
                        'differential' => 1,
                    ]);
                }

                // Conduct Delete of Unwanted HC Symp and OS Symp
                HcSymptom::where('health_condition_id',$request->id)->whereIn('symptom_id', $ToBeDeleted)->delete();
                OrganSystemSymptom::where('organ_system_id',$request->organ_system_id)->whereIn('symptom_id', $ToBeDeleted)->delete();

                if($request->hc_interventions != null){
                    $hc_interventions = [];
                    foreach ($request->hc_interventions as $key => $value) {
                        if(empty($value['id']) == false){
                            array_push($hc_interventions, $value['id']);
                            // Update Intervention
                            Intervention::where('id',$value['id'])->update([
                                'health_condition_id' => $request->id,
                                'description' => $value['description'],
                                'need_license' => intval($value['need_license']),
                                'pregnant_applicable' => intval($value['pregnant_applicable']),
                            ]);
                        }else{
                            // return "True";
                            $Interventions = new Intervention;
                            $Interventions->health_condition_id = $request->id;
                            $Interventions->description = $value['description'];
                            $Interventions->need_license = $value['need_license'];
                            $Interventions->pregnant_applicable = $value['pregnant_applicable'];
                            $Interventions->save();
                        }
                    }
                    // Conduct Delete of Unwanted HC Interventionsr
                    Intervention::where('id', array_values(array_diff($default_interventions_array,$hc_interventions)))->delete();
                }

                if ($request->hasFile('attached_media_health_condition')) {
                    $length = 5;
                    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
                    
                    foreach ($request->attached_media_health_condition as $key) {
                        $ext = $key->getClientOriginalExtension();

                        $filename = 'HC-'. Carbon::now()->format('Y-m-d') . substr(str_shuffle(str_repeat($pool, 5)), 0, $length) . '.'.$ext;
            
                        $Upload = new MediaHealthCondition;
                        $Upload->health_condition_id = $request->id;
                        $Upload->path_name = "profile/hc/$request->organ_system_id/$request->id/$filename";
                        $Upload->type = $ext;
                        $Upload->save();

                        Storage::disk("public")->put("profile/hc/$request->organ_system_id/$request->id/$filename",file_get_contents($key));
                        // Determine what table to inserted via variable "$type"
                        // Check if has existing files
                        // Delete if Exists
                        // Save or Update file to table
                    }
                }

                if ($request->editedSpecificHCFormMediaArr != null) {
                    foreach ($request->editedSpecificHCFormMediaArr as $key) {
                        MediaHealthCondition::where('id', $key['id'])->delete();
                        Storage::disk('public')->delete($key['path_name']);
                    }
                }

                DB::commit();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
        ob_end_flush();
    }

    /**
     *  Symptoms Method loads the Users Symptoms Table.
    **/
    public function Symptoms(Request $request)
    {
        ob_start('ob_gzhandler');

            $query = Symptom::query();
            if(request('searchSymptoms')){
                $query->where('name','LIKE', '%'.request('searchSymptoms').'%')->get();
            }

            if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
                $query->orderBy(request('field'),request('direction'));
            }

            return Inertia::render('HealthGuides/Symptoms', [
                'Symptoms' => $query->with(['organSystemsSymptoms' => function($organSystemsSymptoms){
                    return $organSystemsSymptoms->with('organSystems')->get();
                }])
                ->with(['parentSymptom' => function($ps){
                    return $ps->select('id','name','parent_symptom')->get();
                }])
                ->with('mediaSymptoms')
                ->orderBy('name', 'asc')
                ->paginate()
                ->setPath('')   //for prod ip to url handling
                ->appends($request->query()),
                'Systems' => OrganSystem::get(),
                'AllSymptoms' => Symptom::orderBy('name', 'asc')->get(),
                'Filters' => request()->all(['searchSymptoms','field','direction']),
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }

    /**
     *  SaveEditedHC Method saves the edits for Health Conditions
    **/
    public function SaveEditedSymptom(Request $request)
    {
        ob_start('ob_gzhandler');
        DB::beginTransaction();
        // Update Symptoms
        try {
            // return $request;
            if ($request->name != null) {
                Symptom::where('id', $request->id)->update(['name' => $request->name]);
            }

            if ($request->local_term != null) {
                Symptom::where('id', $request->id)->update(['local_term' => $request->local_term]);
            }else{
                Symptom::where('id', $request->id)->update(['local_term' => '']);
            }

            if ($request->parent_symptom != null) {
                Symptom::where('id', $request->id)->update(['parent_symptom' => $request->parent_symptom['id']]);
            }else{
                Symptom::where('id', $request->id)->update(['parent_symptom' => 0]);
            }

            $default_organ_system_symptom = OrganSystemSymptom::where('symptom_id', $request->id)
            ->get();

            if ($request->organ_systems_symptoms != null) {
                $default_systems = [];
                $request_systems = [];
                foreach ($default_organ_system_symptom as $key) {
                    array_push($default_systems, intval($key['organ_system_id']));
                }
                foreach ($request->organ_systems_symptoms as $key => $value) {
                    array_push($request_systems, intval($value['organ_system_id']));
                    // Update OrgaSysSymptoms
                    OrganSystemSymptom::updateOrInsert([
                        'organ_system_id' => intval($value['organ_system_id']),
                        'symptom_id' =>  $request->id,
                    ]);
                }
                OrganSystemSymptom::where('symptom_id',$request->id)->whereIn('organ_system_id', array_values(array_values(array_diff($default_systems,$request_systems))))->delete();
            }

            if($request->hasFile('attached_media_symptoms')) {
                // profile/symptoms/symptom id/symp.png
                $length = 5;
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
                $system_id = $request->organ_systems_symptoms[0]['organ_system_id'];
                
                foreach ($request->attached_media_symptoms as $key) {
                    $ext = $key->getClientOriginalExtension();
                    $filename = 'Symptom-'. Carbon::now()->format('Y-m-d') . substr(str_shuffle(str_repeat($pool, 5)), 0, $length) . '.'.$ext;
        
                    $Upload = new MediaSymptom;
                    $Upload->symptom_id = $request->id;
                    $Upload->path_name = "profile/symptoms/$system_id/$request->id/$filename";
                    $Upload->type = $ext;
                    $Upload->save();

                    Storage::disk("public")->put("profile/symptoms/$system_id/$request->id/$filename",file_get_contents($key));
                }
            }
            if ($request->editedSpecificSymptomFormMediaArr != null) {
                foreach ($request->editedSpecificSymptomFormMediaArr as $key) {
                    MediaSymptom::where('id', $key['id'])->delete();
                    Storage::disk('public')->delete($key['path_name']);
                }
            }

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        ob_end_flush();
    }

    /**
     *  SaveEditedHC Method saves the edits for Health Conditions
    **/
    public function CreateSymptom(Request $request)
    {
        ob_start('ob_gzhandler');
        DB::beginTransaction();
        // Update HealthCondition
        try {
            // return $request;
            // If parent_symptoms is not null
            if($request->local_term == null){
                $request->local_term = '';
            }
            
            if($request->parent_symptom == null){
                $notif = new Symptom;
                $notif->name = $request->name;
                $notif->local_term = $request->local_term;
                $notif->parent_symptom = 0;
                $notif->save();
            }else{
                $notif = new Symptom;
                $notif->name = $request->name;
                $notif->local_term = $request->local_term;
                $notif->parent_symptom = $request->parent_symptom['id'];
                $notif->save();
            }
            // return $request->organ_systems_symptoms;
            foreach ($request->organ_systems_symptoms as $key => $value) {
                // Update OrgaSysSymptoms
                OrganSystemSymptom::updateOrInsert([
                    'organ_system_id' => intval($value['organ_system_id']),
                    'symptom_id' =>  $notif->id,
                ]);
            }

            if ($request->hasFile('attached_media_symptoms')) {
                // profile/symptoms/symptom id/symp.png
                $length = 5;
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
                
                foreach ($request->attached_media_symptoms as $key) {
                    $ext = $key->getClientOriginalExtension();
                    $filename = 'Symptom-'. Carbon::now()->format('Y-m-d') . substr(str_shuffle(str_repeat($pool, 5)), 0, $length) . '.'.$ext;

                    $system_id = $request->organ_systems_symptoms[0]['organ_system_id'];

                    $Upload = new MediaSymptom;
                    $Upload->symptom_id = $notif->id;
                    $Upload->path_name = "profile/symptoms/$system_id/$request->id/$filename";
                    $Upload->type = $ext;
                    $Upload->save();

                    Storage::disk("public")->put("profile/symptoms/$system_id/$request->id/$filename",file_get_contents($key));
                }
            }

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        ob_end_flush();
    }
    // Custom Functions

    // Custom Functions for DataManipulations/Updates/etc
    public function storeMedia($type,$system,$media_hc)
    {
        // Loop to every File
        foreach ($media_hc as $key) {
            // $file_name = $key->getClientOriginalName();
            // Determine what table to inserted via variable "$type"
            // Check if has existing files
            // Delete if Exists
            // Save or Update file to table
        }
    }

}
