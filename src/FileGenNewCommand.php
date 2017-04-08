<?php


namespace Skyronic\FileGenerator;


use Illuminate\Console\Command;

class FileGenNewCommand extends Command
{
    public $signature = "generate:new {type} {--d|description=My Template}";

    public function handle () {
        $type = $this->argument('type');
        $desc = $this->option('description');

        $extension = config('filegen.extension');
        $separator = config('filegen.separator');

        $baseDir = rtrim(FileHelper::fixDirSeparator(config('filegen.dir')), DIRECTORY_SEPARATOR);

        $outPath = $baseDir.DIRECTORY_SEPARATOR.$type.$extension;

        $outDir = dirname($outPath);
        if(!file_exists($outDir)) {
            mkdir($outDir,0777, true);
        }
        if (file_exists($outPath)) {
            throw new FileGeneratorException("Boilerplate file already exists [ $outPath ]");
        }

        $content = <<<CONTENT
{
   "name": "$desc",
   "out": "edit/me/{{ \$name }}.txt",
   "params": {
        "myParam": "optional"
   }
}
$separator

Template goes here. Blade syntax works. You can use a parameter like {{ \$myParam }}

CONTENT;

        file_put_contents($outPath, $content);
        // strip base path from outpath
        $friendlyPath = str_replace(base_path('').'/', "", $outPath);
        $this->info ("Created new boilerplate at [ $friendlyPath ]");

    }
}