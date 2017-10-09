<?php

use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{

    /**
     * @dataProvider validScriptsProvider
     */
    public function testParseGood($script)
    {
        $parser = new \Sieve\Parser();
        $parser->parse($script);
        $this->assertInstanceOf(\Sieve\Tree::class, $parser->GetParseTree());
        $parser->parse($parser->getScriptText());
        $this->assertInstanceOf(\Sieve\Tree::class, $parser->GetParseTree());
    }

    /**
     * @dataProvider badScriptsProvider
     * @expectedException \Sieve\SieveException
     */
    public function testParseBad($script)
    {
        $parser = new \Sieve\Parser();
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