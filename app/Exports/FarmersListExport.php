<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Farmer;


class FarmersListExport implements FromCollection, WithHeadings
{

    public function __construct(array $farmersArea, array $role)
    {
        $this->farmersArea = $farmersArea;
        $this->role = $role;
    }

    public function setParams(array $farmersArea, array $role)
    {
        $this->farmersArea = $farmersArea;
        $this->role = $role;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        if(in_array("Admin", $this->role, true) || in_array("ReportsUser", $this->role, true)){

            return Farmer::join('admin_level_threes', 'admin_level_threes.id', '=', 'farmers.admin_level_three_id')
                ->join('admin_level_twos', 'admin_level_twos.id', '=', 'admin_level_threes.admin_level_two_id')
                ->join('admin_level_ones', 'admin_level_ones.id', '=', 'admin_level_twos.admin_level_one_id')
                ->select('admin_level_ones.name AS prov_name', 'admin_level_twos.name AS city_name', 'admin_level_threes.name AS brgy_name', 'last_name', 'first_name', 'pcc_system_id', 'birthdate', 'gender', 'mobile_number', 'phone_number', 'longitude', 'lat', 'program_consent', 'sms_consent')
                ->get()
                ->map(function($farmer){
                    $farmer->program_consent = str_replace('1', 'Yes', $farmer->program_consent);
                    $farmer->program_consent = str_replace('0', 'No', $farmer->program_consent);
                    $farmer->sms_consent = str_replace('1', 'Yes', $farmer->sms_consent);
                    $farmer->sms_consent = str_replace('0', 'No', $farmer->sms_consent);
                    return $farmer;
                });

        }else{

            return Farmer::join('admin_level_threes', 'admin_level_threes.id', '=', 'farmers.admin_level_three_id')
                ->join('admin_level_twos', 'admin_level_twos.id', '=', 'admin_level_threes.admin_level_two_id')
                ->join('admin_level_ones', 'admin_level_ones.id', '=', 'admin_level_twos.admin_level_one_id')
                ->select('admin_level_ones.name AS prov_name', 'admin_level_twos.name AS city_name', 'admin_level_threes.name AS brgy_name', 'last_name', 'first_name', 'pcc_system_id', 'birthdate', 'gender', 'mobile_number', 'phone_number', 'longitude', 'lat', 'program_consent', 'sms_consent')
                ->whereIn('admin_level_three_id', $this->farmersArea)
                ->get()
                ->map(function($farmer){
                    $farmer->program_consent = str_replace('1', 'Yes', $farmer->program_consent);
                    $farmer->program_consent = str_replace('0', 'No', $farmer->program_consent);
                    $farmer->sms_consent = str_replace('1', 'Yes', $farmer->sms_consent);
                    $farmer->sms_consent = str_replace('0', 'No', $farmer->sms_consent);
                    return $farmer;
                });
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
            'Birthdate',
            'Gender',
            'Mobile Number',
            'Phone Number',
            'GPS Long',
            'GPS Lat',
            'Program Consent',
            'SMS Consent',
        ];
    }

}