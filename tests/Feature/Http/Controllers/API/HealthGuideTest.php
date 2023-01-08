<?php

namespace Tests\Feature\Http\Controllers\API;



use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Carbon\Carbon;

use App\Models\User;
use App\Models\UserRoles;
use App\Models\HealthCondition;
use App\Models\MediaHealthCondition;
use App\Models\OrganSystem;
use App\Models\Symptom;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use phpDocumentor\Reflection\Types\Null_;
use Tests\TestCase;

class HealthGuideTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_organ_system_structure()
    {

        $os = OrganSystem::factory()->count(3)->create();

        //check if api endpoint is existing
        $response = $this->get('api/systems');
        $response->assertStatus(Response::HTTP_OK);
        
        // Check for JsonStructure for OrganSystemAPI
        $response->assertJsonStructure(
            [
                '*' => [
                    'id',
                    'name',
                    'local_term',
                    'created_at',
                    'updated_at',
                    'health_conditions' => [
                        '*' => [
                            'id',
                            'name',
                            'local_term',
                            'organ_system_id',
                            'common_in_region',
                            'description',
                            'how_to_diganose',
                            'treatment',
                            'advice_to_farmer',
                            'preventive_measure',
                            'quick_action',
                            'zoonotic',
                            'created_at',
                            'updated_at',
                            'hc_interventions' => [
                                '*' => [
                                    'id',
                                    'created_at',
                                    'updated_at',
                                    'health_condition_id',
                                    'description',
                                    'need_license',
                                    'pregnant_applicable',
                                ]
                            ],
                            'symptoms' => [
                                '*' => [
                                    "id",
                                    "name",
                                    "local_term",
                                    "parent_symptom",
                                    "created_at",
                                    "updated_at",
                                    "pivot" => [
                                        "health_condition_id",
                                        "symptom_id",
                                        "differential",
                                        "id",
                                    ],
                                    "organ_systems_symptoms" => [
                                        '*' => [
                                            "id",
                                            "organ_system_id",
                                            "symptom_id",
                                        ]
                                    ],
                                ]
                            ],
                            'media_health_conditions' => [
                                '*' => [
                                    "id",
                                    "health_condition_id",
                                    "path_name",
                                    "type",
                                    "created_at",
                                    "updated_at"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(3)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                         ->etc()
                 )
        );
        

        ob_get_clean();
    }

    public function test_specific_organ_system()
    {
        $this->withoutExceptionHandling();

        $os = OrganSystem::factory()
        ->has(HealthCondition::factory()->count(2))
        ->count(3)->create();

        //check if api endpoint is existing
        $response = $this->post('api/specificSystem', [
            'id' => 1
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('0.health_conditions')
        );

        ob_get_clean();
    }

    public function test_edit_hc_with_media()
    {
       // Edit health Condition
       $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => "IHealthContentManager"]);
       $user = User::factory()->create(['id' => 1]);

       $OrganSys = OrganSystem::factory()->create([
           'id' => 2
       ]);

       $HC1 = HealthCondition::factory()->create([
           'name' => "hc1",
           'organ_system_id' => 2,
           'local_term' => "hc1",
           'local_term' => "hc1",
       ]);

       $xxx = UploadedFile::fake()->image('xxx.jpg');

       $response2 = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedHC',[
           'id' => 1,
           'organ_system_id' => $HC1->organ_system_id,
           'name' => 'hc2',
           'local_term' => 'hc2',
           'attached_media_health_condition' => [$xxx]
       ]);

       $response2->assertStatus(302);
       $response2->assertSessionHasNoErrors();


       //Check if record is inserted
       $EditedHC = HealthCondition::where('id', '1')->get()->first();
       $this->assertEquals('hc2', $EditedHC->name);
       $this->assertEquals('hc2', $EditedHC->local_term);

       ob_end_flush(); 
    }

    public function test_hc_media_api_structure()
    {
        $expected_filename = 'mediahc-'.Carbon::now()->format('Y-m-d').'.zip';

        //check if api endpoint is existing
        $response = $this->json('get', 'api/downloadHCMediaZipped');
        
        //check file type
        $content_type = $response->headers->get('content-type');
        $this->assertEquals($content_type, "application/zip" );

        //check file name
        $header = $response->headers->get('content-disposition');
        $this->assertEquals($header, "attachment; filename=$expected_filename" );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertDownload($expected_filename);
    }

    public function test_symptoms_media_api_structure()
    {
        $expected_filename = 'symptoms-'.Carbon::now()->format('Y-m-d').'.zip';

        //check if api endpoint is existing
        $response = $this->json('get', 'api/downloadSymptomMediaZipped');
        
        // //check file type
        $content_type = $response->headers->get('content-type');
        $this->assertEquals($content_type, "application/zip" );

        $header = $response->headers->get('content-disposition');
        $this->assertEquals($header, "attachment; filename=$expected_filename" );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertDownload($expected_filename);
    }
}
