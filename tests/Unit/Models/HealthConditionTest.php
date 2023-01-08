<?php

namespace Tests\Unit\Models;

use App\Models\HealthCondition;
use App\Models\Intervention;
use App\Models\MediaHealthCondition;
use App\Models\OrganSystem;
use App\Models\Symptom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HealthConditionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_hc_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('health_conditions','id'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','name'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','local_term'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','common_in_region'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','description'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','how_to_diganose'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','treatment'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','advice_to_farmer'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','preventive_measure'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','zoonotic'), 1);
        $this->assertTrue(Schema::hasColumn('health_conditions','quick_action'), 1);
    }

    public function test_hc_belongs_to_organ_system()
    {
        // Create HC for Organ System
        $os = OrganSystem::factory()->create();
        $hc = HealthCondition::factory()
        ->for($os)
        ->create();

        // dd($hc);
        // Count if its 1:1 ratio
        $this->assertEquals(1, $hc->organSystem->count());
        $this->assertInstanceOf(OrganSystem::class, $hc->organSystem);
    }

    public function test_hc_has_many_hc_interventions()
    {
        // Create hc with hc interventions
        $hc = HealthCondition::factory()
            ->has(Intervention::factory()->count(3), 'hcInterventions')
            ->create();

        // dd($hc);
        // Checks if there's an instance of 3 OrganSystemSymptom inside Symptoms
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $hc->hcInterventions);
        $this->assertEquals(3, $hc->hcInterventions->count());
    }

    public function test_hc_belongs_to_many_symptoms()
    {
        $hc = HealthCondition::factory()
        ->has(Symptom::factory()->count(3))
        ->create();
        
        // dd($hc);
        // Checks if organSystems exist within the obj
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $hc->symptoms);
        $this->assertEquals(3, $hc->symptoms->count());
    }
    public function test_hc_has_many_mediaHealthCondition()
    {
        // Create hc with hc interventions
        $hc = HealthCondition::factory()
            ->has(MediaHealthCondition::factory()->count(3), 'mediaHealthCondition')
            ->create();

        // Checks if there's an instance of 3 OrganSystemSymptom inside Symptoms
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $hc->mediaHealthCondition);
        $this->assertEquals(3, $hc->mediaHealthCondition->count());
    }

}
