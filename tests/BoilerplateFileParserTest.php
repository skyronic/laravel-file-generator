<?php


namespace Tests;
use PHPUnit_Framework_TestCase;
use Skyronic\Cookie\FileParser;

class BoilerplateFileParserTest extends PHPUnit_Framework_TestCase
{
    public function testSomething () {
        $this->assertEquals(1, 1);
    }

    public function testSimple1 () {
        $fp = new FileParser(__DIR__."/fixtures/simple.boilerplate.txt");
        $this->assertEquals('simple', $fp->getBasename());
        $this->assertEquals($fp->getName(), "Simple Test 1");

        $params = $fp->getParams();
        $this->assertArrayHasKey('username', $params);

        $fp->render([
            'name' => 'bar',
            'username' => "World"
        ]);
        $this->assertEquals($fp->getOutPath(), "foo/bar.txt");
        $this->assertEquals($fp->getContents(), "Hello, World!\n");
    }

}