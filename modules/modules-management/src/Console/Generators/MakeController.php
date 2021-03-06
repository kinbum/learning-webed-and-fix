<?php namespace App\Module\ModulesManagement\Console\Generators;

class MakeController extends AbstractGenerator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make:controller
    	{alias : The alias of the module}
    	{name : The class name}
    	{--resource : Generate a controller with route resource}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('resource')) {
            return __DIR__ . '/../../../resources/stubs/controllers/controller.resource.stub';
        }

        return __DIR__ . '/../../../resources/stubs/controllers/controller.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return 'Http\\Controllers\\' . $this->argument('name');
    }

    protected function replaceParameters(&$stub)
    {
        $stub = str_replace([
            '{alias}',
        ], [
            $this->argument('alias'),
        ], $stub);
    }
}
