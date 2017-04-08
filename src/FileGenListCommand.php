<?php


namespace Skyronic\FileGenerator;

use Illuminate\Console\Command;

class FileGenListCommand extends Command
{
    public $signature = "generate:list";
    public $description = "List all available boilerplate files";

    public function configure () {

    }
    public function handle () {
        $baseDir = FileHelper::fixDirSeparator(config('filegen.dir'));
        if(!file_exists($baseDir)) {
            throw new FileGeneratorException("Invalid boilerplate directory. Run `artisan generate:new` or `artisan vendor:publish --tag=goodies` to set up");
        }

        $fileList = new FileList(config('filegen'));
        $fileList->readDirectory($baseDir);

        $items = $fileList->listItems();
        $this->table(["Type", "Name"], $items);
        $this->line("\n\nUse `artisan generate <type>` to create a new file!");
    }
}