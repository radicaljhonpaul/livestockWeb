<?php

namespace Tests\Unit\Models;

use App\Models\HcSymptom;
use App\Models\HealthCondition;
use App\Models\MediaSymptom;
use App\Models\OrganSystem;
use App\Models\OrganSystemSymptom;
use App\Models\Symptom;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SymptomTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_symptom_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('symptoms','id'), 1);
        $this->assertTrue(Schema::hasColumn('symptoms','name'), 1);
        $this->assertTrue(Schema::hasColumn('symptoms','local_term'), 1);
        $this->assertTrue(Schema::hasColumn('symptoms','parent_symptom'), 1);
    }

    // Check if symptoms really belongs to several organ systems via pivot table
    public function test_symptom_belongs_to_many_organ_system_via_pivot()
    {
        $symptoms = Symptom::factory()
        ->has(OrganSystem::factory()->count(3)->create())
        ->create();

        // Checks if organSystems exist within the obj
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $symptoms->organSystems);
    }
    
    // Has many organSystemsSymptoms
    public function test_symptom_has_many_organSystemsSymptoms()
    {
        $symptoms = Symptom::factory()
        ->has(OrganSystemSymptom::factory()->count(3), 'organSystemsSymptoms')
        ->create();

        // Checks if there's an instance of 3 OrganSystemSymptom inside Symptoms
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $symptoms->organSystemsSymptoms);
        $this->assertEquals(3, $symptoms->organSystemsSymptoms->count());
    }

    // Has many media symptoms
    public function test_symptom_has_many_mediaSymptoms()
    {
        // Create Symptoms with 3 MediaSymptoms
        $Symptoms = Symptom::factory()
            ->has(MediaSymptom::factory()->count(3))
            ->create();
            
        // dd($Symptoms->mediaSymptoms);
        // Test if Symptoms has instance of mediaSymptoms
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $Symptoms->mediaSymptoms);
        // Test if Symptoms has 3 MediaSymptoms as declared above
        $this->assertEquals(3, $Symptoms->mediaSymptoms->count());
    }
}
