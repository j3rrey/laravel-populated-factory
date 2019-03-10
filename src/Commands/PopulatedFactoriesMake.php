<?php

namespace Coderello\PopulatedFactory\Commands;

use Coderello\PopulatedFactory\FactoryGenerator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PopulatedFactoriesMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:populated-factories {dir} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make populated factories';

    /**
     * Factory generator instance.
     *
     * @var FactoryGenerator
     */
    protected $factoryGenerator;

    /**
     * Create a new command instance.
     *
     * @param FactoryGenerator $factoryGenerator
     */
    public function __construct(FactoryGenerator $factoryGenerator)
    {
        parent::__construct();

        $this->factoryGenerator = $factoryGenerator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dir = $this->argument('dir');
        $models = $this->findModels($dir);

        foreach ($models as $modelPath) {
            $modelPath = $this->getRealtiveModelPath($modelPath, $dir);

            if (!$this->confirm('Do you want a factory for this Model? ' . $modelPath)) {
                continue;
            }

            if (!class_exists($modelPath)) {
                $this->error(
                    sprintf('"%s" Class could not be instatiated!', $modelPath)
                );

                continue;
            }

            $model = new $modelPath;
            if (!$model instanceof Model) {
                dump($model);
                $this->error(
                    sprintf('"%s" is not a model.', $model)
                );

                continue;
            }

            $modelName = last(explode('\\', $modelPath));

            /** @TODO implement naming */
            $factoryName = $modelName . 'Factory';

            $factoryPath = database_path(
                sprintf('factories/%s.php', $factoryName)
            );

            if (is_file($factoryPath) && !$this->option('force')) {
                $this->error(
                    sprintf('Factory for "%s" model already exists!', $modelName)
                );

                return;
            }

            $factoryContent = $this->factoryGenerator->generate($model);

            File::put($factoryPath, $factoryContent);

            $this->info(
                sprintf('Populated "%s" factory for "%s" model has been created successfully!', $factoryName, $modelName)
            );
        }
    }

    private function findModels(string $dir): array
    {
        $appPath = app_path();
        if (!empty($dir)) {
            $appPath = $appPath . '/' . $dir . '/';
        }

        $models = glob($appPath . '*.php');
        if (empty($models)) {
            $this->error('No Model/s found in ' . $appPath);
        }
        return $models;
    }


    private function getRealtiveModelPath(string $absolutPath, $userDir): string
    {
        $app = strpos($absolutPath, $userDir);
        $relativePath = substr($absolutPath, $app);
        $relativePath = str_replace('.php', '', $relativePath);
        $relativePath = str_replace('/', '\\', $relativePath);

        return 'App\\' . $relativePath;
    }
}
