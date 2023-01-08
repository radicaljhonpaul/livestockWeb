<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DiagnosisLogHCTest extends TestCase
{
    use RefreshDatabase;

    public function test_diagnosis_log_hc_has_expected_columns()
    {
        $this->withoutExceptionHandling();

        $this->assertTrue(Schema::hasColumn('diagnosis_log_health_conditions','id'), 1); 
        $this->assertTrue(Schema::hasColumn('diagnosis_log_health_conditions','diagnosis_log_id'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_log_health_conditions','health_condition_id'), 1);
    }
}
