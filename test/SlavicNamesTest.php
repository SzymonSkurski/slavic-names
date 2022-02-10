<?php


use PHPUnit\Framework\TestCase;
use SlavicNames\Exception\InvalidCharException;
use SlavicNames\Exception\NotSupportedGenderException;
use SlavicNames\SlavicNames;

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
            $name = SlavicNames::instance()->getName($char);
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
            SlavicNames::instance()->getName($char);
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
            $name = SlavicNames::instance()->getName($char);
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
            $name = SlavicNames::instance()->getName($char);
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
            $name = SlavicNames::instance()->getName($char);
        } catch (InvalidCharException | NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertSame('n', strtolower($name[0]));
    }

    /**
     * @test
     */
    public function testGetRandomName_defaultMale(): void
    {
        $name = '';
        try {
            $e = '';
            $name = SlavicNames::instance()->getRandomName();
        } catch (NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertNotEmpty($name);
    }

    public function testGetRandomName_randomGender(): void
    {
        $names = [];
        $err = [];
        foreach (range(1, 100) as $key) {
            try {
                $names[$key] = SlavicNames::instance()->getRandomName(SlavicNames::RANDOM);
            } catch (NotSupportedGenderException $e) {
                $err[] = $e->getMessage();
            }
        }
        $this->assertEmpty($err);
        $this->assertTrue(count($names) > count(array_unique($names))); //little bit optimistic
    }

    /**
     * @test
     */
    public function testGetRandomName_notSupportedGender(): void
    {
        try {
            $e = '';
            SlavicNames::instance()->getRandomName(666);
        } catch (NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertStringStartsWith('unsupported gender', $e);
    }

    /**
     * @test
     */
    public function testGetName_female(): void
    {
        //there is only 1 female name f
        $name = '';
        try {
            $e = '';
            $name = SlavicNames::instance()->getName('f', SlavicNames::FEMALE);
        } catch (InvalidCharException | NotSupportedGenderException $e) {
            $e = $e->getMessage();
        }
        $this->assertEmpty($e);
        $this->assertSame('Falislawa', $name);
    }
}
