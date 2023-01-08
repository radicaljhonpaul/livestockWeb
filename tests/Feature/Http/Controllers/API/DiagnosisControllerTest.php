<?php

namespace Tests\Features\Http\Controllers\Api;

use App\Models\User;
use App\Models\Livestock;
use App\Models\AdminLevelTwo;
use App\Models\UserAdminLevelTwo;
use App\Models\DiagnosisLog;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisLogHealthCondition;
use App\Models\DiagnosisLogIntervention;
use App\Models\DiagnosisLogSymptom;
use App\Models\Farmer;
use App\Models\HealthCondition;
use App\Models\Intervention;
use App\Models\MediaDiagnosisLog;
use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserRoles;
use App\Models\VisitLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

use Tests\TestCase;


class DiagnosisControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_diagnosis_sync_down_not_logged_in()
    {
        //$this->withoutExceptionHandling();
        //no logged in user
        $response = $this->json('get', 'api/DiagnosisLogs/syncDown');    
        $response->assertStatus(401);
        
    }

    public function test_diagnosis_sync_down_logged_in()
    {
    
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

        $symptom = Symptom::factory()->create([ 'id' => 1]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 1]);
        $intervention = Intervention::factory()->create([ 'id' => 1]);
        
        $livestock = Livestock::factory()->create([ 'id' => 1 ]);
        $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1]);

        $DiagnosisLogHealthCondition = DiagnosisLogHealthCondition::factory()->create(['diagnosis_log_id' => 1, 'health_condition_id' => 1]);
        $DiagnosisLogSymptom = DiagnosisLogSymptom::factory()->create(['diagnosis_log_id' => 1, 'symptom_id' => 1]);
        $DiagnosisLogIntervention = DiagnosisLogIntervention::factory()->create(['diagnosis_log_id' => 1, 'intervention_id' => 1]);

        // $yawa = UserAdminLevelTwo::factory()->create(['user_id' => 1, 'admin_level_two_id' => $livestock->farmer->adminLevelThree->adminLevelTwo->id]);
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();

        $response = $this->actingAs($user)->json('get', 'api/DiagnosisLogs/syncDown', [
            "syncFromDate" => "2021-09-15",
            "syncToDate" => date('Y-m-d')
        ]);
        // check structure for additional diagnosis logs
        // return dd($response[0]);
        
        $response->assertJsonStructure([
            '*' => [
                'id',
                'external_id',
                'visit_date',
                'activity_type',
                'status',
                'assessment',
                'notes',
                'is_pregnant',
                'created_by',
                'authorized_by',
                'authorization_via',
                'assigned_to',
                'livestock_id',
                'created_at',
                'updated_at',
                'symptoms' => [
                    '*' => [
                        'id',
                        'pivot' => [
                            'diagnosis_log_id',
                            'symptom_id',
                            'diagnosis_logs.external_id'
                        ]
                    ]
                ],
                'health_conditions' => [
                    '*' => [
                        'id',
                        'pivot' => [
                            'diagnosis_log_id',
                            'health_condition_id',
                            'diagnosis_logs.external_id'
                        ]
                    ]
                ],
                'interventions' => [
                    '*' => [
                        'id',
                        'pivot' => [
                            'diagnosis_log_id',
                            'intervention_id',
                            'diagnosis_logs.external_id'
                        ]
                    ]
                ]
           ]
        ]);

        // $response->assertJson(fn (AssertableJson $json) =>
        //     $json
        //     diagnosis_logs.external_id
            // ->where('0.id', $livestock->diagnosisLogs[0]->id)
            // ->where('0.visit_date', $livestock->diagnosisLogs[0]->visit_date)
            // ->where('0.activity_type', $livestock->diagnosisLogs[0]->activity_type)
            // ->where('0.status', $livestock->diagnosisLogs[0]->status)
            // ->where('0.assessment', $livestock->diagnosisLogs[0]->assessment)
            // ->where('0.notes', $livestock->diagnosisLogs[0]->notes)
            // ->where('0.created_by', $livestock->diagnosisLogs[0]->created_by)
            // ->where('0.authorized_by', $livestock->diagnosisLogs[0]->authorized_by)
            // ->where('0.assigned_to', $livestock->diagnosisLogs[0]->assigned_to)
            // ->where('0.livestock_id', strval($livestock->id))
            // ->has('1.livestock_id')
            // ->has('0.symptoms')
            // ->where('0.symptoms.0.id', $symptom->id)
            // ->where('0.symptoms.0.pivot.diagnosis_log_id', strval($diagnosislogs[0]->id))
            // ->where('0.symptoms.0.pivot.symptom_id', strval($symptom->id))
            // ->has('0.health_conditions')
            // ->where('0.health_conditions.0.id', $healthCondition->id)
            // ->where('0.health_conditions.0.pivot.diagnosis_log_id', strval($diagnosislogs[0]->id))
            // ->where('0.health_conditions.0.pivot.health_condition_id', strval($healthCondition->id))
            // ->has('0.interventions')
            // ->where('0.interventions.0.id', $intervention->id)
            // ->where('0.interventions.0.pivot.diagnosis_log_id', strval($diagnosislogs[0]->id))
            // ->where('0.interventions.0.pivot.intervention_id', strval($intervention->id))
        //     ->etc()
        // );

    
        //check structure for additional related info
        // $response->assertJson(fn (AssertableJson $json) =>
        //         $json
        //         ->has('0.symptoms')
        //         ->has('0.health_conditions')
        //         ->has('0.interventions')
        //         ->etc()
        // );   

    }

    public function test_diagnosis_sync_down_invalid_date_logged_in()
    {
    
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

        $symptom = Symptom::factory()->create([ 'id' => 1]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 1]);
        $intervention = Intervention::factory()->create([ 'id' => 1]);
        
        $livestock = Livestock::factory()->create([ 'id' => 1 ]);
        $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1]);

        $DiagnosisLogHealthCondition = DiagnosisLogHealthCondition::factory()->create(['diagnosis_log_id' => 1, 'health_condition_id' => 1]);
        $DiagnosisLogSymptom = DiagnosisLogSymptom::factory()->create(['diagnosis_log_id' => 1, 'symptom_id' => 1]);
        $DiagnosisLogIntervention = DiagnosisLogIntervention::factory()->create(['diagnosis_log_id' => 1, 'intervention_id' => 1]);

        // $yawa = UserAdminLevelTwo::factory()->create(['user_id' => 1, 'admin_level_two_id' => $livestock->farmer->adminLevelThree->adminLevelTwo->id]);
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();
        
        $response = $this->actingAs($user)->json('get', 'api/DiagnosisLogs/syncDown', [
            "syncFromDate" => "2019-09-15",
            "syncToDate" => "2019-09-15"
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(0)
        );

    }

    public function test_diagnosis_sync_down_unassigned_user_date_logged_in()
    {
    
        $this->withoutExceptionHandling();

        $user1 = User::factory()->create(['id' => 1]);
        $user2 = User::factory()->create(['id' => 2]);
        $data1 = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $data2 = UserRoles::factory()->create([ 'user_id' => 2,'role' => 'Vet']);

        $symptom = Symptom::factory()->create([ 'id' => 1]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 1]);
        $intervention = Intervention::factory()->create([ 'id' => 1]);
        
        $livestock = Livestock::factory()->create([ 'id' => 1 ]);
        $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1]);

        $DiagnosisLogHealthCondition = DiagnosisLogHealthCondition::factory()->create(['diagnosis_log_id' => 1, 'health_condition_id' => 1]);
        $DiagnosisLogSymptom = DiagnosisLogSymptom::factory()->create(['diagnosis_log_id' => 1, 'symptom_id' => 1]);
        $DiagnosisLogIntervention = DiagnosisLogIntervention::factory()->create(['diagnosis_log_id' => 1, 'intervention_id' => 1]);

        // $yawa = UserAdminLevelTwo::factory()->create(['user_id' => 1, 'admin_level_two_id' => $livestock->farmer->adminLevelThree->adminLevelTwo->id]);
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user1->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();
        
        $response = $this->actingAs($user2)->json('get', 'api/DiagnosisLogs/syncDown', [
            "syncFromDate" => "2021-09-15",
            "syncToDate" => date('Y-m-d')
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(0)
        );

    }

    public function test_diagnosis_sync_down_media_not_logged_in()
    {
        //no logged in user
        $response = $this->json('get', 'api/DiagnosisLogs/MediaSyncDown');    
        $response->assertStatus(401);
    }

    public function test_diagnosis_sync_up_media_not_logged_in()
    {
        //no logged in user
        $response = $this->json('post', 'api/DiagnosisLogs/mediaSyncUp');    
        $response->assertStatus(401);
    }

    public function test_diagnosis_sync_up_not_logged_in()
    {
        $response = $this->json('post', 'api/DiagnosisLogs/synchedUp');    
        $response->assertStatus(401);
    }
    
    public function test_diagnosis_sync_up_logged_in()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $user2 = User::factory()->create(['id' => 2]);
        $user8 = User::factory()->create(['id' => 8]);

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

        $symptom = Symptom::factory()->create([ 'id' => 32]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 22]);
        $intervention1 = Intervention::factory()->create([ 'id' => 106]);
        $intervention2 = Intervention::factory()->create([ 'id' => 107]);
        
        $livestock = Livestock::factory()->create([ 'id' => 143 ]);
        // $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1]);

        $response = $this->actingAs($user)->post('api/DiagnosisLogs/synchedUp', [
            
            "diagnosisHealthConditionPivots" => [
                [
                    "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                    "diagnosis_log_id" => 1,
                    "health_condition_id" => 22,
                    "id" => 1
                ]
            ],
            "diagnosisInterventionPivots" => [
                [
                    "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                    "diagnosis_log_id" => 1,
                    "id" => 1,
                    "intervention_id" => 106
                ],
                [
                    "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                    "diagnosis_log_id" => 1,
                    "id" => 2,
                    "intervention_id" => 107
                ]
            ],
            
            "diagnosisLogEntity" => [
                "activity_type" => "DV",
                "assessment" => "CR",
                "assigned_to" => 8,
                "authorization_via" => 2,
                "authorized_by" => 8,
                "created_at" => "2021-12-10 10:23:25.883000",
                "created_by" => 8,
                "diagnosis_id" => 0,
                "external_uuid" => "012af205-4d6b-433b-b641-adada77e70e8",
                "livestock_id" => 143,
                "notes" => "uu",
                "status" => "OP",
                "updated_at" => "2021-12-10 10:23:25.883000",
                "visit_date" => "2021-12-10 10:23:25.883000",
                "is_pregnant" => 0
            ],

            "diagnosisSymptomPivots" => [
                [
                  "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                  "diagnosis_log_id" => 1,
                  "id" => 1,
                  "symptom_id" => 32
                ]
            ]

        ]);
        
        // return dd($response);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json
            ->where('message', "Synched Up Successfully")
            ->where('id', 1)
            ->etc()
        ); 

        ob_end_flush();
    }

    public function test_diagnosis_sync_up_multiple_logged_in()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $user2 = User::factory()->create(['id' => 2]);
        $user8 = User::factory()->create(['id' => 8]);
        $user15 = User::factory()->create(['id' => 15]);

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

        $symptom = Symptom::factory()->create([ 'id' => 32]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 22]);
        $intervention1 = Intervention::factory()->create([ 'id' => 106]);
        $intervention2 = Intervention::factory()->create([ 'id' => 107]);
        
        $livestock = Livestock::factory()->create([ 'id' => 143 ]);
        // $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1]);

        $response = $this->actingAs($user)->post('api/DiagnosisLogs/synchedUpMultiple', [
            [
                "diagnosisHealthConditionPivots" => [
                    [
                        "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                        "diagnosis_log_id" => 1,
                        "health_condition_id" => 22,
                        "id" => 1
                    ]
                ],
                "diagnosisInterventionPivots" => [
                    [
                        "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                        "diagnosis_log_id" => 1,
                        "id" => 1,
                        "intervention_id" => 106
                    ],
                    [
                        "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                        "diagnosis_log_id" => 1,
                        "id" => 2,
                        "intervention_id" => 107
                    ]
                ],
                
                "diagnosisLogEntity" => [
                    "activity_type" => "DV",
                    "assessment" => "CR",
                    "assigned_to" => 8,
                    "authorization_via" => 2,
                    "authorized_by" => 8,
                    "created_at" => "2021-12-10 10:23:25.883000",
                    "created_by" => 8,
                    "diagnosis_id" => 0,
                    "external_uuid" => "012af205-4d6b-433b-b641-adada77e70e8",
                    "livestock_id" => 143,
                    "notes" => "uu",
                    "status" => "OP",
                    "updated_at" => "2021-12-10 10:23:25.883000",
                    "visit_date" => "2021-12-10 10:23:25.883000",
                    "is_pregnant" => 0
                ],

                "diagnosisSymptomPivots" => [
                    [
                    "diagnosis_external_id" => "012af205-4d6b-433b-b641-adada77e70e8",
                    "diagnosis_log_id" => 1,
                    "id" => 1,
                    "symptom_id" => 32
                    ]
                ]
            ],
            
            [
                "diagnosisHealthConditionPivots" => [
                    [
                      "diagnosis_external_id" => "ba034318-6a04-4b3e-8b1f-eb7aed55b91b",
                      "diagnosis_log_id" => 0,
                      "health_condition_id" => 22,
                      "id" => 2
                    ]
                  ],
                  
                  "diagnosisInterventionPivots" => [
                    [
                      "diagnosis_external_id" => "ba034318-6a04-4b3e-8b1f-eb7aed55b91b",
                      "diagnosis_log_id" => 0,
                      "id" => 3,
                      "intervention_id" => 106
                    ],
                    [
                      "diagnosis_external_id" => "ba034318-6a04-4b3e-8b1f-eb7aed55b91b",
                      "diagnosis_log_id" => 0,
                      "id" => 4,
                      "intervention_id" => 107
                    ]
                  ],

                  "diagnosisLogEntity" => [
                    "activity_type" => "DV",
                    "assessment" => "CR",
                    "assigned_to" => 8,
                    "authorized_by" => 15,
                    "created_at" => "2021-12-10 10:23:52.851000",
                    "created_by" => 8,
                    "diagnosis_id" => 0,
                    "external_uuid" => "ba034318-6a04-4b3e-8b1f-eb7aed55b91b",
                    "livestock_id" => 143,
                    "notes" => "hu",
                    "status" => "OP",
                    "updated_at" => "2021-12-10 10:23:52.851000",
                    "visit_date" => "2021-12-10 10:23:52.851000"
                    ],

                  "diagnosisSymptomPivots" => [
                    [
                      "diagnosis_external_id" => "ba034318-6a04-4b3e-8b1f-eb7aed55b91b",
                      "diagnosis_log_id" => 0,
                      "id" => 2,
                      "symptom_id" => 32
                    ]
                  ]
            ]

        ]);
        
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json
            ->where('0.external_id', '012af205-4d6b-433b-b641-adada77e70e8')
            ->where('0.id', 1)
            ->where('0.status', 'success')
            ->where('1.external_id', 'ba034318-6a04-4b3e-8b1f-eb7aed55b91b')
            ->where('1.id', 2)
            ->where('1.status', 'success')
            ->etc()
        ); 

        ob_end_flush();
    }

    public function test_diagnosis_sync_up_media_logged_in()
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        
        $symptom = Symptom::factory()->create([ 'id' => 1]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 1]);
        $intervention = Intervention::factory()->create([ 'id' => 1]);
        
        $livestock = Livestock::factory()->create([ 'id' => 1 ]);
        $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1, 'external_id' => '6991bf3e-aee6-4374-bbf0-c0141514ec85']);

        $DiagnosisLogHealthCondition = DiagnosisLogHealthCondition::factory()->create(['diagnosis_log_id' => 1, 'health_condition_id' => 1]);
        $DiagnosisLogSymptom = DiagnosisLogSymptom::factory()->create(['diagnosis_log_id' => 1, 'symptom_id' => 1]);
        $DiagnosisLogIntervention = DiagnosisLogIntervention::factory()->create(['diagnosis_log_id' => 1, 'intervention_id' => 1]);
        
        $xxx = UploadedFile::fake()->image('[2]_[2NIZ19080]_[6991bf3e-aee6-4374-bbf0-c0141514ec85]_2021-11-11-001.jpg');
        
        $response = $this->actingAs($user)->post('api/DiagnosisLogs/mediaSyncUp', [
            // UploadedFile::fake()->image($filename)
            'media_diagnosislog' => [$xxx]
        ]);
        
        $response->assertStatus(Response::HTTP_OK);

        Storage::disk('public')->assertExists('profile/diagnosis_logs/2/2NIZ19080/6991bf3e-aee6-4374-bbf0-c0141514ec85/2021-11-11-001.jpg');
        Storage::disk('local')->assertMissing('profile/diagnosis_logs/2/2NIZ19080/6991bf3e-aee6-4374-bbf0-c0141514ec85/2021-11-11-001.jpg');

        ob_end_flush();
    }

    public function test_diagnosis_sync_up_media_multiple_logged_in()
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        
        $symptom = Symptom::factory()->create([ 'id' => 1]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 1]);
        $intervention = Intervention::factory()->create([ 'id' => 1]);
        
        $livestock = Livestock::factory()->create([ 'id' => 1 ]);
        $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1, 'external_id' => '6991bf3e-aee6-4374-bbf0-c0141514ec85']);

        $DiagnosisLogHealthCondition = DiagnosisLogHealthCondition::factory()->create(['diagnosis_log_id' => 1, 'health_condition_id' => 1]);
        $DiagnosisLogSymptom = DiagnosisLogSymptom::factory()->create(['diagnosis_log_id' => 1, 'symptom_id' => 1]);
        $DiagnosisLogIntervention = DiagnosisLogIntervention::factory()->create(['diagnosis_log_id' => 1, 'intervention_id' => 1]);
        
        $xxx = UploadedFile::fake()->image('[2]_[2NIZ19080]_[6991bf3e-aee6-4374-bbf0-c0141514ec85]_2021-11-11-001.jpg');
        $xxx1 = UploadedFile::fake()->image('[2]_[2NIZ19080]_[6991bf3e-aee6-4374-bbf0-c0141514ec85]_2021-11-11-002.jpg');
        $xxx2 = UploadedFile::fake()->image('[2]_[2NIZ19080]_[6991bf3e-aee6-4374-bbf0-c0141514ec85]_2021-11-11-003.jpg');
        
        $response = $this->actingAs($user)->post('api/DiagnosisLogs/mediaSyncUp', [
            // UploadedFile::fake()->image($filename)
            'media_diagnosislog' => [$xxx,$xxx1,$xxx2]
        ]);
        
        $response->assertStatus(Response::HTTP_OK);

        Storage::disk('public')->assertExists('profile/diagnosis_logs/2/2NIZ19080/6991bf3e-aee6-4374-bbf0-c0141514ec85/2021-11-11-001.jpg');
        Storage::disk('local')->assertMissing('profile/diagnosis_logs/2/2NIZ19080/6991bf3e-aee6-4374-bbf0-c0141514ec85/2021-11-11-001.jpg');

        ob_end_flush();
    }

    public function test_diagnosis_sync_down_media_checkfilesize_not_logged_in()
    {
        
        //no logged in user
        $response = $this->json('get', 'api/DiagnosisLogs/mediaCheckFileSize', [
            "syncFromDate" => "2021-09-15",
            "syncToDate" => date('Y-m-d')
        ]); 
        $response->assertStatus(401);
        
    }

    public function test_diagnosis_sync_down_media_checkfilesize_logged_in()
    {

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

        $symptom = Symptom::factory()->create([ 'id' => 1]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 1]);
        $intervention = Intervention::factory()->create([ 'id' => 1]);
        
        $livestock = Livestock::factory()->create([ 'id' => 1 ]);
        $diagnosislogs = DiagnosisLog::factory()->count(2)->create(['livestock_id' => 1, 'external_id' => '6991bf3e-aee6-4374-bbf0-c0141514ec85']);

        $mediaDiagnosisLogs = MediaDiagnosisLog::factory()->create([ 'diagnosis_log_id' => 1, 'type' => 'jpg','path_name' => 'profile/diagnosis_logs/2/2NIZ19080/6991bf3e-aee6-4374-bbf0-c0141514ec85/2021-11-11-001.jpg']);
        
        $DiagnosisLogHealthCondition = DiagnosisLogHealthCondition::factory()->create(['diagnosis_log_id' => 1, 'health_condition_id' => 1]);
        $DiagnosisLogSymptom = DiagnosisLogSymptom::factory()->create(['diagnosis_log_id' => 1, 'symptom_id' => 1]);
        $DiagnosisLogIntervention = DiagnosisLogIntervention::factory()->create(['diagnosis_log_id' => 1, 'intervention_id' => 1]);

        // $yawa = UserAdminLevelTwo::factory()->create(['user_id' => 1, 'admin_level_two_id' => $livestock->farmer->adminLevelThree->adminLevelTwo->id]);
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();
        
        $response = $this->actingAs($user)->json('get', 'api/DiagnosisLogs/mediaCheckFileSize', [
            "syncFromDate" => "2021-09-15",
            "syncToDate" => date('Y-m-d')
        ]);

        // check structure for additional diagnosis logs
        $response->assertJson(fn (AssertableJson $json) =>
            $json
            ->has('Total_Size')
            ->etc()
        );
        // return dd($response);

        $this->withoutExceptionHandling();
    }

    public function test_diagnosis_sync_down_media_logged_in()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $symptom = Symptom::factory()->create([ 'id' => 1]);
        $healthCondition = HealthCondition::factory()->create([ 'id' => 1]);
        $intervention = Intervention::factory()->create([ 'id' => 1]);
        $livestock = Livestock::factory()->create([ 'id' => 1, 'carabao_code' => '2NIZ19080']);

        $diagnosislogs = DiagnosisLog::factory()->create(['livestock_id' => 1, 'external_id' => '6991bf3e-aee6-4374-bbf0-c0141514ec85']);

        $mediaDiagnosisLogs = MediaDiagnosisLog::factory()->create([ 'diagnosis_log_id' => 1, 'type' => 'jpg','path_name' => 'profile/diagnosis_logs/2/2NIZ19080/6991bf3e-aee6-4374-bbf0-c0141514ec85/2021-11-11-001.jpg']);
        
        $DiagnosisLogHealthCondition = DiagnosisLogHealthCondition::factory()->create(['diagnosis_log_id' => 1, 'health_condition_id' => 1]);
        $DiagnosisLogSymptom = DiagnosisLogSymptom::factory()->create(['diagnosis_log_id' => 1, 'symptom_id' => 1]);
        $DiagnosisLogIntervention = DiagnosisLogIntervention::factory()->create(['diagnosis_log_id' => 1, 'intervention_id' => 1]);

        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();
        

        $xxx = UploadedFile::fake()->image("[$livestock->farmer_id]_[$livestock->carabao_code]_[$diagnosislogs->external_id]_2021-11-11-001.jpg");
        
        $response = $this->actingAs($user)->post('api/DiagnosisLogs/mediaSyncUp', [
            'media_diagnosislog' => [$xxx]
        ]);
        
        $expected_filename = $user->id.'_2021-01-15_'.date('Y-m-d').'.zip';

        $response = $this->actingAs($user)->json('get', 'api/DiagnosisLogs/MediaSyncDown', [
            "syncFromDate" => "2021-01-15",
            "syncToDate" => date('Y-m-d')
        ]);
        

        //check file type
        $content_type = $response->headers->get('content-type');
        $this->assertEquals($content_type, "application/zip" );

        //check file name
        $header = $response->headers->get('content-disposition');
        $this->assertEquals($header, "attachment; filename=$expected_filename" );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertDownload($expected_filename);

        ob_end_flush();
    }

}