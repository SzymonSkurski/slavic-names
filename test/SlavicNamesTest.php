<?php


use PHPUnit\Framework\TestCase;
use SlavicNames\Exception\InvalidCharException;
use SlavicNames\Exception\NotSupportedGenderException;

class SlavicNamesTest extends TestCase
{
    /**
     * @test
     */
    public function testGetName_notSupportedChar(): void
    {
        //there is no q names dictionary
        //expect a name that begins with a random letter
        $char = 'q';
        $name = '';
        try {
            $e = '';
            $name = SlavicNames\SlavicNames::instance()->getName($char);
        } catch (InvalidCharException | NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertNotEmpty($name);
        $this->assertNotSame(strtolower($char), strtolower($name[0]));
    }

    /**
     * @test
     */
    public function testGetName_invalidChar(): void
    {
        //pass invalid char, expect InvalidCharException
        $char = '123';
        try {
            $e = '';
            SlavicNames\SlavicNames::instance()->getName($char);
        } catch (InvalidCharException | NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertStringStartsWith('invalid character', $e);
    }

    /**
     * @test
     */
    public function testGetName_capitalLetter(): void
    {
        //pass capital letter; expect pass and return a name that begins with a char
        $char = 'B';
        $name = '';
        try {
            $e = '';
            $name = SlavicNames\SlavicNames::instance()->getName($char);
        } catch (InvalidCharException | NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertNotEmpty($name);
        $this->assertSame(strtolower($char), strtolower($name[0]));
    }

    /**
     * @test
     */
    public function testGetName_smallLetter(): void
    {
        //pass small letter; expect pass and return a name that begins with a char
        $char = 'b';
        $name = 'x';
        try {
            $e = '';
            $name = SlavicNames\SlavicNames::instance()->getName($char);
        } catch (InvalidCharException | NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertSame(strtolower($char), strtolower($name[0]));
    }

    /**
     * @test
     */
    public function testGetName_string(): void
    {
        //pass string; expect pass and return a name that begins with a first char of string
        $char = 'Nuts';
        $name = 'x';
        try {
            $e = '';
            $name = SlavicNames\SlavicNames::instance()->getName($char);
        } catch (InvalidCharException | NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertSame('n', strtolower($name[0]));
    }

    /**
     * @test
     */
    public function testGetRandomName(): void
    {
        $name = '';
        try {
            $e = '';
            $name = SlavicNames\SlavicNames::instance()->getRandomName();
        } catch (NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertNotEmpty($name);
        //TODO:: more tests after create female dictionary
    }
}
