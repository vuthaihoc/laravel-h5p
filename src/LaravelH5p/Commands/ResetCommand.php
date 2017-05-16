<?php

namespace Chali5124\LaravelH5p\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class ResetCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laravel-h5p:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Laravel-H5p specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() {

        $this->line('');
        $this->info("Laravel-H5p Creating reset...");


        if ($this->confirm('Do you wish to continue? Remove all laravel-h5p published files.')) {
                
        }

    }

}
