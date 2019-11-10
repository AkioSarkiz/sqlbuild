<?php

declare(strict_types=1);


namespace SQLBuild;


/**
 * Class Where подкласс для класса SQLBuild
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
     */
    private function createSQlValue(String $value, int $type): String
    {
        switch ($type) {

            /*
             |-----------------------------------------------------------------------------------------------------------------
             | Для автоматического определения типа
             |-----------------------------------------------------------------------------------------------------------------
             */
            case SQLType::AUTO:
                if (preg_match('/[0-9]/', $value) || preg_match('[^true$|^false$]', $value)) {
                    return $value;
                }
                elseif (preg_match('/[a-zA-Z]/', $value)) {
                    return sprintf('"%s"', SQLType::validStr($value));
                }
                else {
                    exit('error `52dsa`');
                }



            /*
            |-----------------------------------------------------------------------------------------------------------------
            | Для случаев, когда тип уже указан
            |-----------------------------------------------------------------------------------------------------------------
            */
            case SQLType::BOOL:
            case SQLType::INT:
                return $value;
            case SQLType::STRING:
                return sprintf('"%s"', SQLType::validStr($value));
            case SQLType::ARG:
                return sprintf('`%s`', $value);
            default:
                exit('error `54dsa`');
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

        return sprintf(
            $template,
            // скорее всего это аргумент `arg`.. поэтому при SQLG::AUTO делаем SQLG:ARG
            // чтоб не опеределил как string
            $this->createSQlValue($matches[0][0], ($this->type1 == SQLType::AUTO) ? SQLType::ARG : $this->type1),
            $operator,
            $this->createSQlValue($matches[0][1], $this->type2));
    }
}