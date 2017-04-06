<?php


namespace Skyronic\Cookie;

use Illuminate\Console\Command;

class BakeListCommand extends Command
{
    public $signature = "bake:list";
    public $description = "List all available boilerplate files";

    public function configure () {

    }
    public function handle () {
        $baseDir = config('cookie.dir');
        if(!file_exists($baseDir)) {
            throw new CookieException("Invalid boilerplate directory. Run `artisan bake:new` or `artisan vendor:publish` to set up");
        }

        $fileList = new FileList(config('cookie'));
        $fileList->readDirectory($baseDir);

        $items = $fileList->listItems();
        $this->table(["Type", "Name"], $items);
        $this->line("\n\nUse `artisan bake <type>` to create a new file!");
    }
}