<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use Skyronic\Cookie\FormatHelper;

class FormatterTests extends PHPUnit_Framework_TestCase
{
    public function testBasename () {
        $this->assertEquals('input', FormatHelper::getBaseName("foo/bar/input.txt"));
        $this->assertEquals('input', FormatHelper::getBaseName("FOO/BAr/input"));
        $this->assertEquals('input', FormatHelper::getBaseName("foo\\bar\\input"));
    }

    public function testNamespace () {
        $this->assertEquals("App\\Tmp\\Foo", FormatHelper::namespaceForClass("app/Tmp/Foo.php"));
        $this->assertEquals("Test\\Unit\\Tmp\\Foo", FormatHelper::namespaceForClass("test/Tmp/Foo.php", "test", "Test\\Unit"));

        $this->assertEquals("App\\Tmp\\Foo", FormatHelper::namespaceForClass("app\\Tmp\\Foo.php"));
        $this->assertEquals("Test\\Unit\\Tmp\\Foo", FormatHelper::namespaceForClass("test\\Tmp\\Foo.php", "test", "Test\\Unit"));
    }

}