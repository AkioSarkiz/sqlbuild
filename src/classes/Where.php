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
        if (strlen($value) == 0)
            throw new Exception('empty value');

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

    /**
     * Возвращает отформатирванию строку для запроса
     *
     * @return String
     */
    public function render(): String
    {
        $operator = null;
        $template = ' %s%s%s';

        if ($this->operator == SQLOperator::AND)
            $template .= ' AND';
        elseif ($this->operator == SQLOperator::OR)
            $template .= ' OR';

        preg_match_all('([^<>=!]+)', $this->value, $matches);

        if (preg_match('(>)', $this->value)) $operator = '>';
        elseif (preg_match('(<)', $this->value)) $operator = '<';
        elseif (preg_match('(!=)', $this->value)) $operator = '!=';
        elseif (preg_match('(=)', $this->value)) $operator = '=';

        try {
            if (count($matches[0]) != 2)
                throw new Exception('Where need 2 arg', 5);

            return sprintf(
                $template,
                // скорее всего это аргумент `arg`.. поэтому при SQLG::AUTO делаем SQLG:ARG
                // чтоб не опеределил как string
                $this->createSQlValue($matches[0][0], ($this->type1 == SQLType::AUTO) ? SQLType::ARG : $this->type1),
                $operator,
                $this->createSQlValue($matches[0][1], $this->type2));
        } catch (Exception $e) {
            throw $e;
        }
    }
}