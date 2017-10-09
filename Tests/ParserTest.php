<?php

use PHPUnit\Framework\TestCase;
use LibSieve\Parser;
use LibSieve\Tree;

class ParserTest extends TestCase
{

    /**
     * @dataProvider validScriptsProvider
     */
    public function testParseGood($script)
    {
        $parser = new Parser();
        $parser->parse($script);
        $this->assertInstanceOf(Tree::class, $parser->GetParseTree());
        $parser->parse($parser->getScriptText());
        $this->assertInstanceOf(Tree::class, $parser->GetParseTree());
    }

    /**
     * @dataProvider badScriptsProvider
     * @expectedException \LibSieve\SieveException
     */
    public function testParseBad($script)
    {
        $parser = new Parser();
        $parser->parse($script);
    }

    public function validScriptsProvider()
    {
        $dir = __DIR__.'/good';
        $dh  = opendir($dir);
        while (($file = readdir($dh)) !== false) {
            if (preg_match('/(.+)\.siv$/', $file, $match)) {
                yield [file_get_contents("$dir/$file")];
            }
        }
    }

    public function badScriptsProvider()
    {
        $dir = __DIR__.'/bad';
        $dh  = opendir($dir);
        while (($file = readdir($dh)) !== false) {
            if (preg_match('/(.+)\.siv$/', $file, $match)) {
                yield [file_get_contents("$dir/$file")];
            }
        }
    }

}