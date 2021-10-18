<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Console\GeneratorCommand;

class MakeUseCase extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:usecase {fileName} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'class';

        /**
     * The name of class being generated.
     *
     * @var string
     */
    private $useCaseClass;

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $model;

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire(){

        $this->setUseCaseClass();

        $path = $this->getPath($this->useCaseClass);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($this->useCaseClass));

        $this->info($this->type.' created successfully.');

        $this->line("<info>Created Repository :</info> $this->useCaseClass");
    }

    // /**
    //  * Execute the console command.
    //  *
    //  * @return mixed
    //  */
    // public function handle()
    // {
    //     $fileName = $this->argument('fileName');
    //     $model = $this->option('model');
    //     $path = (app_path('UseCase\\'.$model));
        
    //     if ($model == null) {
    //         $this->info('Debe agregar un modelo para UseCase');
    //         exit;
    //     }

    //     if (!File::isDirectory($path)) {
    //         File::makeDirectory($path, 0777, true, true);
    //     }

    // }

    /**
     * Set repository class name
     *
     * @return  void
     */
    private function setUseCaseClass()
    {
        $name = ucwords(strtolower($this->argument('fileName')));

        $this->model = $name;

        $modelClass = $this->parseName($name);

        $this->useCaseClass = $modelClass;

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if(!$this->argument('fileName')){
            throw new InvalidArgumentException("Missing required argument model name");
        }

        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyModel', $this->model, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return resource_path('aw/UseCase/stub.php');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $model = $this->option('model');
        return $rootNamespace . '\UseCase\\'.$model;
    }
}
