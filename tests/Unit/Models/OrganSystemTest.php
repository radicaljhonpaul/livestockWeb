<?php

namespace Tests\Unit\Models;

use App\Models\HealthCondition;
use App\Models\OrganSystem;
use App\Models\Symptom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OrganSystemTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_organ_system_has_expected_columns()
    {
        $this->withoutExceptionHandling();
        $this->assertTrue(Schema::hasColumn('organ_systems','id'), 1);
        $this->assertTrue(Schema::hasColumn('organ_systems','name'), 1);
        $this->assertTrue(Schema::hasColumn('organ_systems','local_term'), 1);
    }

    public function test_organ_system_has_many_health_conditions()
    {

        $os = OrganSystem::factory()->create();
        $hc = HealthCondition::factory()->create(['organ_system_id' => $os->id]);
        $hc_1 =  HealthCondition::factory()->create(['organ_system_id' => $os->id]);

        // hc exists in organ_system object
        $this->assertTrue($os->healthConditions->contains($hc));

        //Many hc under organ_system
        $this->assertEquals(2, $os->healthConditions->count());
    }

    public function test_organ_system_belongs_to_many_symptoms()
    {
        $os = OrganSystem::factory()
        ->has(Symptom::factory()->count(3))
        ->create();

        // dd($os->symptoms);
        // Checks if organSystems exist within the obj
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $os->symptoms);
        $this->assertEquals(3, $os->symptoms->count());
    }

}
