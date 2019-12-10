<?php


namespace SQLBuild;


use Exception;


/**
 * Class Value - значение для INSERT
 * @package SQLBuild
 */
final class Value
{
    private $value;
    private $type;

    /**
     * Value constructor.
     * @param mixed $value
     * @param int $type
     */
    public function __construct($value, int $type = SQLType::AUTO)
    {
        $this->value = $value;
        $this->type = $type ?? SQLType::NULL;
    }

    /**
     * Отображение переменной для SQL запроса
     *
     * @return String
     * @throws Exception
     */
    public function render(): String
    {
        if ($this->type === SQLType::NULL || is_null($this->value))
            return 'NULL';

        // для bool и int конвертация в string
        $this->value = (is_bool($this->value)) ? ($this->value) ? 'true' : 'false' : (is_int($this->value)) ? (String)$this->value : $this->value;

        switch ($this->type) {

            /*
             |-----------------------------------------------------------------------------------------------------------------
             | Для автоматического определения типа
             |-----------------------------------------------------------------------------------------------------------------
             */
            case SQLType::AUTO:
                if (preg_match('/^[0-9]+$/', $this->value) || preg_match('/^true$|^false$/', $this->value)) {
                    return $this->value;
                } else {
                    return sprintf('"%s"', SQLType::stringArg($this->value));
                }

            /*
            |-----------------------------------------------------------------------------------------------------------------
            | Для случаев, когда тип уже указан
            |-----------------------------------------------------------------------------------------------------------------
            */
            case SQLType::NULL:
                return 'NULL';

            case SQLType::BOOL:
                if ($this->value == 'true' || $this->value == 'false') {
                    return $this->value;
                }else {
                    throw new Exception('value: ' . $this->value . ' isn\'t bool type');
                }
            case SQLType::INT:
                return (string)(int)$this->value;
            case SQLType::STRING:
                return sprintf('"%s"', SQLType::stringArg($this->value));
            case SQLType::ARG:
                return sprintf('`%s`', $this->value);
            default:
                throw new Exception();
        }
    }

    public function __destruct()
    {
        unset($this->value);
        unset($this->type);
    }
}