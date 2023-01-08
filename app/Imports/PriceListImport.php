<?php

namespace App\Imports;

use App\Models\IngredientSrpYear;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Throwable;

class PriceListImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    
    private $srp_year_id; 

    public function __construct(array $srp_year_id = [])
    {
        $this->srp_year_id = (int)$srp_year_id[0];
        // return dd($this->srp_year_id);
    }

    public function model(array $row)
    {
        // Check for srpyear_id

        return IngredientSrpYear::updateOrCreate(
            ['id' => $row['record_id']],
            ['price' => $row['price'], 'ingredient_id' => $row['feed_id'] ]
        );

    }

    public function rules(): array
    {
        return [
            // Above is alias for as it always validates in batches
            '*.id' => 'unique:ingredient_srp_year,id',

            '*.price' => 'numeric',

            // '*.srp_id' => ['same:'.$this->srp_year_id[0]],
            'srp_id' => function($attribute, $value, $onFailure) {
                if ($value !== $this->srp_year_id) {
                     $onFailure('Please make sure you are using the correct pricelist. Kindly use the downloaded pricelist on this page for updating the prices.');
                }
            }

        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.srp_id' => 'Please make sure you are using the correct pricelist. Kindly use the downloaded pricelist on this page for updating the prices.',
        ];
    }
    
}
