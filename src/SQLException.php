<?php declare(strict_types=1);


namespace SQLBuild;


use Exception;
use Throwable;


class SQLException extends Exception
{
    private $vocabulary;

    const LANG = [
        'ru' => 0,
        'en' => 1
    ];

    const DEFAULT_ERROR = 'error';
    const DEFAULT_CODE = 500;
    const DEFAULT_LANG = self::LANG['en'];

    public function __construct(array $vocabulary = [], $code = self::DEFAULT_CODE, Throwable $previous = null)
    {
        if (count($vocabulary) !== count(self::LANG)) $vocabulary = self::createLangArr();
        parent::__construct($vocabulary[self::DEFAULT_LANG], $code, $previous);
        $this->vocabulary = $vocabulary;
    }

    public static function createLangArr(
        String $errorEN = self::DEFAULT_ERROR,
        String $errorRU = self::DEFAULT_ERROR
    ): array
    {
        return [
            self::LANG['en'] => $errorEN,
            self::LANG['ru'] => $errorRU
        ];
    }

    public function getMessageLang(int $lang): String
    {
        return $this->vocabulary[$lang];
    }
}