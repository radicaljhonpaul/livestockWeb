<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
// Models
use App\Models\HcSymptom;
use App\Models\HealthCondition;
use App\Models\Intervention;
use App\Models\MediaHealthCondition;
use App\Models\MediaSymptom;
use App\Models\OrganSystem;
use App\Models\OrganSystemSymptom;
use App\Models\Symptom;
use App\Models\User;
use Illuminate\Support\Facades\Response;

// For Archiving
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class HealthGuide extends Controller
{
    /**
     *  Systems Method loads the OrgansSystems Models
    **/
    public function Systems(Request $request)
    {
        ob_start('ob_gzhandler');
            return OrganSystem::with(['healthConditions' => function($hc){
                return $hc->with('hcInterventions')
                    ->with(['symptoms' => function($sym){
                        return $sym
                            ->with('organSystemsSymptoms')
                            ->orderByDesc('id')
                            ->get();
                    }])
                    ->with('mediaHealthCondition')
                    ->get();
                }])
            ->get();
        ob_end_flush();
    }

    // Specific System (Params: OrganSystemID)
    public function SpecificSystem(Request $request)
    {
        ob_start('ob_gzhandler');
            return OrganSystem::with(['healthConditions' => function($hc){
                return $hc->with('hcInterventions')
                    ->with('symptoms')
                    ->get();
                }])
                ->where('id',$request->id)
                ->get();
        ob_end_flush();
    }

    // Media for Health Condition (Params: id) | Text
    public function SpecificHCMedia(Request $request)
    {
        ob_start('ob_gzhandler');
            return MediaHealthCondition::where('id',$request->id)->get();
        ob_end_flush();
    }

    // Media for Symptom (Params: id) | Text
    public function SpecificSymptomMedia(Request $request)
    {
        ob_start('ob_gzhandler');
            return MediaSymptom::where('id',$request->id)->get();
        ob_end_flush();
    }

    // Media for Health Conditions Zipped Download
    public function DownloadHCMediaZipped(Request $request)
    {   
        if (is_file(public_path('mediahc-'.Carbon::now()->format('Y-m-d').'.zip'))) {
            return response()->download(public_path('mediahc-'.Carbon::now()->format('Y-m-d').'.zip'));
        }

        $ZipFile = 'mediahc-'.Carbon::now()->format('Y-m-d').'.zip';

        // Get real path for our folder
        $rootPath = realpath(public_path('storage/profile/hc'));

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open(public_path($ZipFile), ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath),RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                // Add current file to archive
                $zip->addFile($filePath, 'hc/'.$relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        return response()->download(public_path('mediahc-'.Carbon::now()->format('Y-m-d').'.zip'))->deleteFileAfterSend(true);;
    }

    public function DownloadSymptomMediaZipped(Request $request)
    {
        if (is_file(public_path('symptoms-'.Carbon::now()->format('Y-m-d').'.zip'))) {
            return response()->download(public_path('symptoms-'.Carbon::now()->format('Y-m-d').'.zip'));
        }

        $ZipFile = 'symptoms-'.Carbon::now()->format('Y-m-d').'.zip';

        // Get real path for our folder
        $rootPath = realpath(public_path('storage/profile/symptoms'));
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open(public_path($ZipFile), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath),RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                // Add current file to archive
                $zip->addFile($filePath, 'symptoms/'.$relativePath);
            }
        }
        // Zip archive will be created only after closing object
        $zip->close();
        return response()->download(public_path('symptoms-'.Carbon::now()->format('Y-m-d').'.zip'));
    }

}

