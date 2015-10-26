<?php namespace ITC\Spark\Console;

use Illuminate\Console\Command;
use ITC\Spark\Output\GeneratorInterface as OutputGenerator;
use Storage;

class GenerateCommand extends Command
{
    protected $signature = 'spark:generate {--storage=local} {--root=http://localhost} {--lang=en}';

    protected $description = 'Generate static pages.';

    /**
     * Execute the command
     * @param void
     * @return void
     */
    public function handle()
    {
        $app = $this->laravel;

        $fs = $app->make('filesystem');
        $disk = $fs->disk($this->option('storage'));

        $app->make('config')->set('app.locale', $this->option('lang'));
        $app->make('url')->forceRootUrl($this->option('root'));

        $generator = $app->make(OutputGenerator::class);
        $generator->setStorage($disk);

        $paths = $generator->generate();

        $this->info('Generated:');

        foreach ($paths as $path)
        {
            $this->comment("  $path");
        }
    }
}
