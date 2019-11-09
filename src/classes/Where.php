<?php

declare(strict_types=1);


namespace SQLBuild;


/**
 * Class Where подкласс для класса SQLG || SQLGenerate
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

    public function __construct(String $value, int $operator = SQLOperator::NONE, int $type1 = SQLType::AUTO, int $type2 = SQLType::AUTO)
    {
        $this->value = $value;
        $this->operator = $operator;
        $this->type1 = $type1;
        $this->type2 = $type2;
    }

    /**
     * Созданее перменной в соответсвии с её типом
     *
     * @param String $value
     * @param int $type
     * @return String
     */
    private function createValue(String $value, int $type): String
    {
        switch ($type) {
            case SQLType::AUTO:
                if (preg_match('/[a-zA-Z]/', $value))
                    return sprintf('"%s"', $value);
                elseif (preg_match('/[0-9]/', $value) || preg_match('[^true$|^false$]', $value))
                    return $value;
                exit('error `52dsa`');

            case SQLType::BOOL:
            case SQLType::INT:
                return $value;

            case SQLType::STRING:
                return sprintf('"%s"', $value);

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

        if ($this->operator == SQLType::AND)
            $template .= ' AND';
        elseif ($this->operator == SQLType::OR)
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
            $this->createValue($matches[0][0], ($this->type1 == SQLType::AUTO) ? SQLType::ARG : $this->type1),
            $operator,
            $this->createValue($matches[0][1], $this->type2));
    }
}