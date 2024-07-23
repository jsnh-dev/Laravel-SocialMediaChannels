<?php

namespace App\Console\Commands;

use App\Models\XApiCall;
use Illuminate\Console\Command;

class X extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:x';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiCall = new XApiCall();

        $data = $apiCall->getProfile();

        $apiCall->updateProfile($data);

        $apiCall->save();
    }
}
