<?php


namespace Skyronic\FileGenerator;


use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FileGenCommand extends Command
{
    /**
     * @var FileList
     */
    private $fileList;

    public $signature = "generate {type} {name} {--f|force} {--dry-run}";

    public $description = "Bake a fresh new file! Warm from the boilerplate oven.";

    /**
     * @var Exception
     */
    protected $configException = null;

    public function configure() {
        try {
            $config = config('filegen');
            $this->fileList = new FileList($config);
            if (file_exists(config('filegen.dir'))) {
                $this->fileList->readDirectory(config('filegen.dir'));
            }

            // Add the type and name parameters
            $allParams = $this->fileList->getAllParams();
            $options = [];
            foreach ($allParams as $param) {
                $type = $param['type'];
                $key = $param['key'];
                $fileKey = $param['fileKey'];
                $name = $param['name'];
                $optType = InputOption::VALUE_OPTIONAL;
                if($type === 'flag') {
                    $optType = InputOption::VALUE_NONE;
                }
                $options []= [$key, null, $optType, "For `$fileKey` [ $name ]"];
                $this->addOption($key, null, $optType, "For `$fileKey` [ $name ]");
            }
        }
        catch (\Exception $e) {
            $this->configException = $e;
            return;
        }
    }

    public function handle () {
        if ($this->configException !== null) {
            throw $this->configException;
        }
        // get the appropriate file parser
        /** @var FileParser $fp */
        $fp = $this->fileList->getItem($this->argument('type'));

        // Make a list of params
        $params = $this->options();
        $params['name'] = $this->argument('name');
        $params['path'] = '';

        $fp->render($params);
        $friendlyOutPath = $fp->getOutPath();

        // set the path as the friendly path
        $params['path'] = $friendlyOutPath;

        // do another render with the new params
        $fp->render($params);

        $outPath = base_path($friendlyOutPath);
        $contents = $fp->getContents();
        $outDir = dirname($outPath);

        if ($this->option('dry-run')) {
            $this->info ("Doing a dry run.");
            $this->info ("The following will be written to [ $friendlyOutPath ]:");
            $this->line($contents);
            exit ();
        }

        if (!file_exists($outDir)) {
            mkdir($outDir, 0777, true);
        }

        if (file_exists($outPath) && !$this->option('force')) {
            $this->error("File [ $friendlyOutPath ] already exists. Use --force to replace");
            exit();
        }

        file_put_contents($outPath, $contents);
        $this->info("Created file [ $friendlyOutPath ] ");
    }
}