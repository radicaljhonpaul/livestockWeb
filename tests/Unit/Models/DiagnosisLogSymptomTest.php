<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DiagnosisLogSymptomTest extends TestCase
{
    use RefreshDatabase;

    public function test_diagnosis_log_symptoms_has_expected_columns()
    {
        $this->withoutExceptionHandling();

        $this->assertTrue(Schema::hasColumn('diagnosis_log_symptoms','id'), 1); 
        $this->assertTrue(Schema::hasColumn('diagnosis_log_symptoms','diagnosis_log_id'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_log_symptoms','symptom_id'), 1);
    }
}
