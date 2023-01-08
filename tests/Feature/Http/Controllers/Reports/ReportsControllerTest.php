<?php

namespace Tests\Feature\Http\Controllers\Reports;

use App\Models\DiagnosisLog;
use App\Models\User;
use App\Models\UserRoles;
use ClaudioDekker\Inertia\Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportsControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_page_rendered_for_admin_diagnosis_reports_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create DiagnosisLog - Obj not Array
        $diagnosisLog = DiagnosisLog::factory()->create();
        
        // return dd($diagnosisLog);
        // Hit AdminReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data', 1)
        );
        ob_get_clean();
    }
    
    public function test_page_rendered_for_admin_diagnosis_reports_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create DiagnosisLog - Obj not Array
        $diagnosisLog = DiagnosisLog::factory()->create();
        
        // return dd($diagnosisLog);
        // Hit AdminReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_admin_diagnosis_reports_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create DiagnosisLog - Obj not Array
        $diagnosisLog = DiagnosisLog::factory()->create();
        
        // return dd($diagnosisLog);
        // Hit AdminReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_admin_diagnosis_reports_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create DiagnosisLog - Obj not Array
        $diagnosisLog = DiagnosisLog::factory()->create();
        
        // return dd($diagnosisLog);
        // Hit AdminReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_admin_diagnosis_reports_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create DiagnosisLog - Obj not Array
        $diagnosisLog = DiagnosisLog::factory()->create();
        
        // return dd($diagnosisLog);
        // Hit AdminReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_admin_diagnosis_reports_as_reportsuser()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create DiagnosisLog - Obj not Array
        $diagnosisLog = DiagnosisLog::factory()->create();
        
        // return dd($diagnosisLog);
        // Hit AdminReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_user_can_view_specific_diagnosis_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create DiagnosisLog
        $diagnosisLog1 = DiagnosisLog::factory()->create([
            "id" => 1
        ]);
        $diagnosisLog2 = DiagnosisLog::factory()->create([
            "id" => 2
        ]);
        $diagnosisLog3 = DiagnosisLog::factory()->create([
            "id" => 3
        ]);

        // Hit ReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');
        $response->assertStatus(200);

        // View specific page
        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data', 3)
            ->has('Diagnosis.data.0.health_conditions')
            ->has('Diagnosis.data.0.symptoms')
            ->has('Diagnosis.data.0.interventions')
            ->where('Diagnosis.data.0.id', $diagnosisLog1->id)
            ->where('Diagnosis.data.0.external_id', $diagnosisLog1->external_id)
            ->where('Diagnosis.data.0.visit_date', $diagnosisLog1->visit_date)
            ->where('Diagnosis.data.0.activity_type', $diagnosisLog1->activity_type)
            ->where('Diagnosis.data.0.status', $diagnosisLog1->status)
            ->where('Diagnosis.data.0.assessment', $diagnosisLog1->assessment)
            ->where('Diagnosis.data.0.notes', $diagnosisLog1->notes)
            ->where('Diagnosis.data.0.created_by', strval($diagnosisLog1->created_by))
            ->where('Diagnosis.data.0.authorized_by.id', $diagnosisLog1->authorized_by)
            ->where('Diagnosis.data.0.authorization_via', $diagnosisLog1->authorization_via)
            ->where('Diagnosis.data.0.assigned_to.id', $diagnosisLog1->assigned_to)
            ->where('Diagnosis.data.0.livestock_id', strval($diagnosisLog1->livestock_id))
        );
        ob_get_clean();
    }
    
    public function test_user_can_view_specific_diagnosis_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create DiagnosisLog
        $diagnosisLog1 = DiagnosisLog::factory()->create([
            "id" => 1
        ]);

        // Hit ReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');
        $response->assertStatus(200);

        // View specific page
        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_user_can_view_specific_diagnosis_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create DiagnosisLog
        $diagnosisLog1 = DiagnosisLog::factory()->create([
            "id" => 1
        ]);

        // Hit ReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');
        $response->assertStatus(200);

        // View specific page
        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_user_can_view_specific_diagnosis_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create DiagnosisLog
        $diagnosisLog1 = DiagnosisLog::factory()->create([
            "id" => 1
        ]);

        // Hit ReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');
        $response->assertStatus(200);

        // View specific page
        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_user_can_view_specific_diagnosis_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create DiagnosisLog
        $diagnosisLog1 = DiagnosisLog::factory()->create([
            "id" => 1
        ]);

        // Hit ReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');
        $response->assertStatus(200);

        // View specific page
        $response->assertInertia(fn ($page) => $page
            ->component('Reports/ReportsHealthVisit')
            ->has('Diagnosis.data')
        );
        ob_get_clean();
    }

    public function test_user_can_view_sorted_diagnosis_as_admin()
    {
        // Create User
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        $diagnosisLog = DiagnosisLog::factory()->count(3)->create();
        
        // return dd($diagnosisLog);
        // Hit ReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');
        $response->assertStatus(200);

        $ddd = DiagnosisLog::get();

        $this->assertEquals($ddd->pluck('id'), $ddd->sortBy('assessment')->pluck('id'));
        
        ob_get_clean();
    }

    public function test_user_can_view_sorted_diagnosis_as_reportsusers()
    {
        // Create User
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);

        $diagnosisLog = DiagnosisLog::factory()->count(3)->create();
        
        // return dd($diagnosisLog);
        // Hit ReportsHealthVisit Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminReportsHealthVisit');
        $response->assertStatus(200);

        $ddd = DiagnosisLog::get();

        $this->assertEquals($ddd->pluck('id'), $ddd->sortBy('assessment')->pluck('id'));
        
        ob_get_clean();
    }

}
