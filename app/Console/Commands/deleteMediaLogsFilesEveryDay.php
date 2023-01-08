<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

class deleteMediaLogsFilesEveryDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mediaLogsFolder:deleteFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command deleted files inside public/medialogs folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (file_exists(public_path('/medialogs/'))) {
            // return "path does exist";
            $file = new Filesystem;
            $file->cleanDirectory(public_path('medialogs/'));
        }else{
            return "path does not exist";
        }
    }
}
