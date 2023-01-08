<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HcSymptomTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_hc_symptom_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('hc_symptoms','id'), 1);
        $this->assertTrue(Schema::hasColumn('hc_symptoms','symptom_id'), 1);
        $this->assertTrue(Schema::hasColumn('hc_symptoms','health_condition_id'), 1);
        $this->assertTrue(Schema::hasColumn('hc_symptoms','differential'), 1);
    }

    // 
}
