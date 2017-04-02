<?php namespace App\Module\ModulesManagement\Console\Generators;

class MakeAction extends AbstractGenerator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make:action
    	{alias : The alias of the module}
    	{name : The class name}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../../resources/stubs/actions/action.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return 'Actions\\' . $this->argument('name');
    }
}
