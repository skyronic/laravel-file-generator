<?php


namespace Skyronic\Cookie;


use Illuminate\Console\Command;

class BakeInitCommand extends Command
{
    public $signature = "bake:new {type} {--d|description=My Template}";

    public function handle () {
        $type = $this->argument('type');
        $desc = $this->option('description');

        $extension = config('cookie.extension');
        $separator = config('cookie.separator');

        $outPath = rtrim(config('cookie.dir'), "/").DIRECTORY_SEPARATOR.$type.$extension;

        $outDir = dirname($outPath);
        if(!file_exists($outDir)) {
            mkdir($outDir, true);
        }
        if (file_exists($outPath)) {
            throw new CookieException("Boilerplate file already exists [ $outPath ]");
        }

        $content = <<<CONTENT
{
   "description": "$desc",
   "out": "edit/me/{{ \$name }}.txt",
   "params": {
        "myParam": "optional"
   }
}
$separator

Template goes here. You can use a parameter like {{ \$myParam }}

CONTENT;

        file_put_contents($outPath, $content);
        // strip base path from outpath
        $friendlyPath = str_replace(base_path('').'/', "", $outPath);
        $this->info ("Created new boilerplate at [ $friendlyPath ]");

    }
}