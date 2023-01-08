<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Farmer;
use App\Models\Livestock;



class CarabaoListExport implements FromCollection, WithHeadings
{

    public function __construct(array $farmersIDs, array $role)
    {
        $this->farmersIDs = $farmersIDs;
        $this->role = $role;
    }

    public function setParams(array $farmersIDs, array $role)
    {
        $this->farmersIDs = $farmersIDs;
        $this->role = $role;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        if(in_array("Admin", $this->role, true) || in_array("ReportsUser", $this->role, true)){

            return Livestock::join('farmers', 'farmers.id', '=', 'livestocks.farmer_id')
                ->join('admin_level_threes', 'admin_level_threes.id', '=', 'farmers.admin_level_three_id')
                ->join('admin_level_twos', 'admin_level_twos.id', '=', 'admin_level_threes.admin_level_two_id')
                ->join('admin_level_ones', 'admin_level_ones.id', '=', 'admin_level_twos.admin_level_one_id')
                ->select('admin_level_ones.name AS prov_name', 'admin_level_twos.name AS city_name', 'admin_level_threes.name AS brgy_name', 'last_name', 'first_name', 'pcc_system_id', 'carabao_code', 'sex', 'breed', 'year_of_birth', 'registration_date', )
                ->get();

        }else{

            return Livestock::join('farmers', 'farmers.id', '=', 'livestocks.farmer_id')
            ->join('admin_level_threes', 'admin_level_threes.id', '=', 'farmers.admin_level_three_id')
            ->join('admin_level_twos', 'admin_level_twos.id', '=', 'admin_level_threes.admin_level_two_id')
            ->join('admin_level_ones', 'admin_level_ones.id', '=', 'admin_level_twos.admin_level_one_id')
            ->select('admin_level_ones.name AS prov_name', 'admin_level_twos.name AS city_name', 'admin_level_threes.name AS brgy_name', 'last_name', 'first_name', 'pcc_system_id', 'carabao_code', 'sex', 'breed', 'year_of_birth', 'registration_date', )
            ->whereIn('farmer_id', $this->farmersIDs)
            ->get();
        }

    }

    //Customize heading
    public function headings(): array
    {
        return [
            'Province',
            'Municipality City',
            'Baranggay',
            'Last Name Farmer',
            'First Name Farmer',
            'Farmer Code',
            'Carabao Code',
            'Sex',
            'Breed',
            'Year of Birth',
            'Date of Registration',
        ];
    }
    
}