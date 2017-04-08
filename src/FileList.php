<?php


namespace Skyronic\FileGenerator;

use Symfony\Component\Finder\Finder;

class FileList
{
    /**
     * Config for the
     *
     * @var array
     */
    private $config;

    /**
     * FileList constructor.
     * @param $config
     */
    public function __construct($config)
    {

        $this->config = $config;
    }

    /**
     * source directory
     *
     * @var string
     */
    protected $dir;

    /**
     * All the items
     *
     * @var array
     */
    protected $items = [];

    /**
     * All items indexed by key
     *
     * @var array
     */
    protected $itemsByKey = [];

    /**
     * @param $dir
     */
    public function readDirectory ($dir) {
        $finder = new Finder();
        $fileList = $finder->in($dir)
            ->name("*".$this->config['extension'])
            ->files();


        foreach ($fileList as $file) {
            $fp = new FileParser($this->config);
            $fp->readFile($file);
            $name = $fp->getBasename();

            $this->items []= $fp;
            $this->itemsByKey[$name] = $fp;
        }
    }

    public function getAllParams () {
        $allParams = [];

        foreach ($this->items as $item) {
            /** @var FileParser $item */
            $pList = $item->getParams();
            foreach ($pList as $key => $type) {
                $param = [];
                $param['key'] = $key;
                $param['fileKey'] = $item->getBasename();
                $param['name'] = $item->getName();
                if ($type === 'flag') {
                    $param['type'] = 'flag';
                }
                else {
                    // important, mark all of them as optional for now...
                    $param['type'] = 'optional';
                }
                $allParams []= $param;
            }
        }

        return $allParams;
    }

    public function listItems () {
        $result = [];

        foreach ($this->items as $item) {
            /** @var FileParser $item */
            $result []= [
                'key' => $item->getBasename(),
                'label' => $item->getName()
            ];
        }

        return $result;
    }

    public function getItem ($key) {
        if (!isset($this->itemsByKey[$key])) {
            throw new FileGeneratorException("No such generator [ $key ]");
        }

        return $this->itemsByKey[$key];
    }
}