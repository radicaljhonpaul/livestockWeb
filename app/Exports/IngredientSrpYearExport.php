<?php

namespace App\Exports;

use App\Models\Ingredient;
use App\Models\IngredientSrpYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IngredientSrpYearExport implements FromCollection, WithHeadings
{

    public function __construct(int $srp_id)
    {
        $this->srp_id = $srp_id;
    }

    public function setSrpId(int $srp_id)
    {
        $this->srp_id = $srp_id;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return Ingredient::join('ingredient_srp_year', 'ingredient_srp_year.ingredient_id', '=', 'ingredients.id')
                    ->where('ingredient_srp_year.srp_year_id', $this->srp_id)
                    ->orderBy('ingredients.category_id','ASC')
                    ->join('categories', 'categories.id', '=', 'ingredients.category_id')
                    ->select('ingredient_srp_year.id', 'ingredient_srp_year.srp_year_id', 'categories.name AS category_name', 'ingredient_srp_year.ingredient_id', 'ingredients.name', 'ingredient_srp_year.price')
                    ->orderBy('ingredients.name','ASC')
                    ->get();
       
    }


    //Customize heading
    public function headings(): array
    {
        return [
            'Record Id',
            'SRP ID',
            'Category',
            'Feed Id',
            'Feed Name',
            'Price',
        ];
    }
}
