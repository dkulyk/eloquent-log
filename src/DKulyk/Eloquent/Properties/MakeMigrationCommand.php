<?php

namespace DKulyk\Eloquent\Properties;

use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;

class MakeMigrationCommand extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature
        = 'eloquent-extra:properties
        {--path= : The location where the migration file should be created.}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eloquent-extra:properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the properties tables';

    /**
     * The migration creator instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationCreator
     */
    protected $creator;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new session table command instance.
     *
     * @param MigrationCreator $creator
     * @param Composer         $composer
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct();

        $this->creator = $creator;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire()
    {
        $files = $this->creator->getFilesystem();
        $name = 'create_properties_tables';

        $path = $this->creator->create($name, $this->getMigrationPath());

        $file = pathinfo($path, PATHINFO_FILENAME);

        $files->put($path, $files->get(__DIR__.'/stubs/database.stub'));

        $this->line("<info>Created Migration:</info> $file");

        $this->composer->dumpAutoloads();
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        if (!is_null($targetPath = $this->input->getOption('path'))) {
            return $this->laravel->basePath().'/'.$targetPath;
        }

        return parent::getMigrationPath();
    }
}
