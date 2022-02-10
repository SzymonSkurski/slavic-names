<?php


namespace SSystems\SlavicNames;


use Exception;
use SSystems\SlavicNames\Exception\InvalidCharException;
use SSystems\SlavicNames\Exception\NotSupportedGenderException;
use function PHPUnit\Framework\arrayHasKey;

class SlavicNames
{
    public const MALE = 1;
    public const FEMALE = 2;
    public const GENDERS = [self::MALE, self::FEMALE];
    public const GENDER_NAMES = [
        self::MALE => 'male',
        self::FEMALE => 'female', //TODO:: create female names dictionary
        //TODO:: create non binary gender and names dictionary
    ];

    private string $randChar = '';
    private int $gender = 0; //temporary only male names dictionary is ready

    public static function instance(): SlavicNames
    {
        return new SlavicNames();
    }

    /**
     * @param int $gender 0:random, 1:male, 2:female
     * @return string
     * @throws NotSupportedGenderException
     */
    public function getRandomName($gender = self::MALE): string
    {
        $this->setGender($gender);
        $dictionary = [];
        while (empty($dictionary)) {
            $dictionary = $this->getNamesDictionary();
            $this->setRandChar();
        }
        return $dictionary[array_rand($dictionary)];
    }

    /**
     * @param string $char single char [a-z] only
     * @param int $gender
     * @return string
     * @throws InvalidCharException
     * @throws NotSupportedGenderException
     */
    public function getName(string $char, $gender = self::MALE): string
    {
        $char = strtolower(trim($char)[0] ?? '');
        $this->checkChar($char);
        $this->randChar = $char;
        return $this->getRandomName($gender);
    }

    /**
     * @param int $gender
     * @throws NotSupportedGenderException
     */
    private function setGender(int $gender): void
    {
        if (!$gender) {
            $this->setRandomGender();
            return;
        }
        $this->checkGender($gender);
        $this->gender = $gender;
    }

    private function getNamesDictionary() : array
    {
        $path = $this->getRandPath();
        if (!file_exists($path)) {
            return [];
        }
        $library = file_get_contents($path, false);
        return explode(',', $library);
    }

    private function setRandomGender(): void
    {
        $this->gender = self::GENDERS[array_rand(self::GENDERS)];
    }

    /**
     * @param string $gender
     * @throws NotSupportedGenderException
     */
    private function checkGender(string $gender): void
    {
        if (!arrayHasKey($gender, self::GENDERS)) {
            throw new NotSupportedGenderException('unsupported gender:' . $gender);
        }
    }

    private function getGenderName() : string
    {
        return self::GENDER_NAMES[$this->gender];
    }

    private function getRandFilename() : string
    {
        return $this->getGenderName() . '_names_' . $this->getRandChar() . '.csv';
    }

    private function getRandPath() : string
    {
        $gender = $this->getGenderName();
        $file = $this->getRandFilename();
//        var_dump(compact('gender', 'file'));
        return __DIR__ . '/dictionary/names/' . $gender . '/' . $file;
    }

    private function getRandChar() : string
    {
        if (!$this->randChar) {
            $this->setRandChar();
        }
        return (string) $this->randChar;
    }

    private function setRandChar() : void
    {
        try {
            $rand = chr(random_int(97, 122)); // small caps [a-z]
        } catch (Exception $e) {
            $rand = 'a';
        }
        $this->randChar = $rand;
    }

    /**
     * @param string $char
     * @throws InvalidCharException
     */
    private function checkChar(string $char): void
    {
        $re = '/[a-z]/m';
        if (!preg_match($re, $char)) {
            throw new InvalidCharException($char);
        }
    }
}