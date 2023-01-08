<?php

namespace Tests\Unit\Models;

use App\Models\OrganSystem;
use App\Models\OrganSystemSymptom;
use App\Models\Symptom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OrganSystemSymptomTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_organ_system_symptom_has_expected_columns()
    {
        $this->withoutExceptionHandling();
        $this->assertTrue(Schema::hasColumn('organ_system_symptom','organ_system_id'), 1);
        $this->assertTrue(Schema::hasColumn('organ_system_symptom','symptom_id'), 1);
    }

    public function test_organ_system_symptom_has_obj_organ_system_and_symptoms()
    {
        // Create Factories and linked models
        $OSS = OrganSystemSymptom::factory()->create(['organ_system_id' => 1, 'symptom_id' => 1]);
        $OS = OrganSystem::factory()->create(['id' => $OSS->organ_system_id]);
        $Symptom = Symptom::factory()->create(['id' => $OSS->symptom_id, 'parent_symptom' => $OSS->symptom_id]);
        
        // Test if OSS has OS and Symptom Obj
        $this->assertTrue($OSS->organSystems->contains($OS));
        $this->assertTrue($OSS->symptoms->contains($Symptom));
    }
    
}
