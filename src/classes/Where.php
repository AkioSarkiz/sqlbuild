<?php


namespace SQLBuild;


use Exception;


/**
 * Class Where - установка фильтра запрсоа
 * @see SQLBuild
 * @package Lib
 */
final class Where
{
    /** @var String  */
    public $value;
    /** @var int  */
    public $operator;
    /** @var int  */
    public $type1;
    /** @var int  */
    public $type2;
    /** @var bool  */
    private $like = false;

    /**
     * Where constructor.
     * @param String $value - строка, которая описывает фильтр
     * @param int $operator - оператор после фильтра
     * @param int $type1 - тип пременной первого аргумента, если указано AUTO, то перый аргумент будет типа ARG str => `str`
     * @param int $type2 - тип пременной второго аргумента
     */
    public function __construct(String $value, int $operator = SQLOperator::NONE, int $type1 = SQLType::AUTO, int $type2 = SQLType::AUTO)
    {
        $this->value = $value;
        $this->operator = $operator;
        $this->type1 = $type1;
        $this->type2 = $type2;
    }

    /**
     * Отображение переменной для SQL запроса
     *
     * @param String $value
     * @param int $type
     * @return String
     * @throws Exception
     */
    private function createSQlValue(String $value, int $type): String
    {
        if ($type === SQLType::NULL || is_null($value))
            return 'NULL';

        switch ($type) {

            /*
             |-----------------------------------------------------------------------------------------------------------------
             | Для автоматического определения типа
             |-----------------------------------------------------------------------------------------------------------------
             */
            case SQLType::AUTO:
                if (preg_match('/^[0-9]*$/', $value) || preg_match('[^true$|^false$]', $value)) {
                    return $value;
                }
                else {
                    return sprintf('"%s"', SQLType::stringArg($value));
                }


            /*
            |-----------------------------------------------------------------------------------------------------------------
            | Для случаев, когда тип уже указан
            |-----------------------------------------------------------------------------------------------------------------
            */
            case SQLType::NOEDIT:
                return $value;

            case SQLType::BOOL:
                if ($value == 'true' || $value == 'false') {
                    return $value;
                }else {
                    throw new Exception('value: ' . $value . ' isn\'t bool type');
                }
            case SQLType::INT:
                return (string)(int)$value;
            case SQLType::STRING:
                return sprintf('"%s"', SQLType::stringArg($value));
            case SQLType::ARG:
                return sprintf('`%s`', $value);
            default:
                throw new Exception();
        }
    }

    public function addLike(): Where
    {
        $this->like = true;
        return $this;
    }

    /**
     * Возвращает отформатирванию строку для запроса
     *
     * @return String
     * @throws Exception
     */
    public function render(): String
    {
        $sqlOperator = '';
        $operator = null;
        $template = ' %s%s%s';

        if ($this->operator == SQLOperator::AND)
            $sqlOperator .= ' AND';
        elseif ($this->operator == SQLOperator::OR)
            $sqlOperator .= ' OR';

        preg_match_all('/(^[^><=]+)/su', $this->value, $tempMatches);
        $key = $tempMatches[0][0];
        preg_match_all('/([><=]).*/su', $this->value, $tempMatches);
        $value = substr($tempMatches[0][0], 1);

        if ($this->like)
            return " `$key` LIKE $value" . $sqlOperator;

        if (preg_match('/^\w*>/su', $this->value)) $operator = '>';
        elseif (preg_match('/^\w*</su', $this->value)) $operator = '<';
        elseif (preg_match('/^\w*=/su', $this->value)) $operator = '=';

        try {

            return sprintf(
                $template,
                // Скорее всего это аргумент `arg`, поэтому при SQLType::AUTO делаем SQLType:ARG
                // Чтоб не определил как string
                $this->createSQlValue($key, ($this->type1 == SQLType::AUTO) ? SQLType::ARG : $this->type1),
                $operator,
                $this->createSQlValue($value, $this->type2)) . $sqlOperator;
        } catch (Exception $e) {
            throw $e;
        } finally {
            unset($operator);
            unset($tempMatches);
            unset($template);
        }
    }
}