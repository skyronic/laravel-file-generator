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

    public function testFlags () {
        $fp = new FileParser(__DIR__."/fixtures/flags.boilerplate.txt");

        $fp->render([
            'name' => 'something'
        ]);
        $content1 = $fp->getContents();
        $this->assertContains("ALWAYS_VISIBLE", $content1);
        $this->assertNotContains("FLAG1_SET", $content1);

        $fp->render([
            'name' => 'something',
            'flag1' => true
        ]);
        $content2 = $fp->getContents();
        $this->assertContains("ALWAYS_VISIBLE", $content2);
        $this->assertContains("FLAG1_SET", $content2);
    }

    public function testMissingRequiredArgException () {
        $fp = new FileParser(__DIR__."/fixtures/required_opt.boilerplate.txt");

        // we'll expect an exception to be thrown here.
        $this->expectExceptionMessage("Needs argument [ req1 ]");
        $fp->render([
            'name' => 'foo'
        ]);
    }

    public function testParamTypes() {
        $fp = new FileParser(__DIR__."/fixtures/required_opt.boilerplate.txt");

        // we'll expect an exception to be thrown here.
        $fp->render([
            'name' => 'foo',
            'req1' => "MY_REQUIRED_VALUE",
        ]);
        $content1 = $fp->getContents();
        $this->assertContains("MY_REQUIRED_VALUE", $content1);
        $this->assertContains("MY_DEFAULT_VALUE", $content1);
        $this->assertContains("ALWAYS_VISIBLE", $content1);

        $fp->render([
            'name' => 'foo',
            'req1' => "MY_REQUIRED_VALUE",
            'opt1' => "MY_OPTIONAL_VALUE"
        ]);
        $content1 = $fp->getContents();
        $this->assertContains("MY_OPTIONAL_VALUE", $content1);
        $this->assertContains("HAS_OPT_VALUE", $content1);

        $fp->render([
            'name' => 'foo',
            'req1' => "MY_REQUIRED_VALUE",
            'def1' => "MY_OVERRIDDEN_VALUE"
        ]);
        $content1 = $fp->getContents();
        $this->assertContains("MY_OVERRIDDEN_VALUE", $content1);
        $this->assertNotContains("MY_DEFAULT_VALUE", $content1);
    }

}