<?php


namespace SQLBuild;


use Exception;
use SQLBuild\Traits\UpdateOperator;
use SQLBuild\Traits\UpdateType;
use SQLBuild\Traits\UpdateValue;


/**
 * Class Where - установка фильтра запрсоа
 * @see SQLBuild
 * @package Lib
 */
final class Where
{
    use UpdateOperator;
    use UpdateType;
    use UpdateValue;

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
     * @throws Exception
     */
    public function __construct(String $value, int $operator = SQLOperator::NONE, int $type1 = SQLType::AUTO, int $type2 = SQLType::AUTO)
    {
        $this->updateValue($value);
        $this->updateOperator($operator);
        $this->updateType1($type1);
        $this->updateType2($type2);
    }

    /**
     * @param $value
     * @return $this
     * @throws Exception
     */
    public function updateValue($value): self
    {
        self::updateValueTrait($this->value, $value);
        return $this;
    }

    /**
     * @param int $type1
     * @return $this
     * @throws Exception
     */
    public function updateType1(int $type1): self
    {
        self::updateTypeTrait($this->type1, $type1);
        return $this;
    }

    /**
     * @param int $type2
     * @return $this
     * @throws Exception
     */
    public function updateType2(int $type2): self
    {
        self::updateTypeTrait($this->type2, $type2);
        return $this;
    }

    /**
     * @param int $operator
     * @return $this
     * @throws Exception
     */
    public function updateOperator(int $operator = SQLOperator::NONE): self
    {
        self::updateOperatorTrait($this->operator, $operator);
        return $this;
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
            return " `$key` LIKE \"$value\"" . $sqlOperator;

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