<?php


namespace Skyronic\Cookie;

use Symfony\Component\Finder\Finder;

class FileList
{
    public function __construct()
    {

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
            ->name("*.boilerplate.txt")
            ->files();

        foreach ($fileList as $file) {
            $fp = new FileParser($file);
            $name = $fp->getName();

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
                if ($type === 'flag') {
                    $allParams[$key] = 'flag';
                }
                else {
                    // important, mark all of them as optional for now...
                    $allParams[$key] = 'optional';
                }
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
            throw new CookieException("No such generator [ $key ]");
        }

        return $this->itemsByKey[$key];
    }
}