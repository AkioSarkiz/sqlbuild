<?php

declare(strict_types=1);


namespace SQLBuild;


use Exception;


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
        $this->type = $type;
    }

    /**
     * Отображение переменной для SQL запроса
     *
     * @return String
     * @throws Exception
     */
    public function render(): String
    {
        // для bool и int конвертация в string
        $this->value = (is_bool($this->value)) ? ($this->value) ? 'true' : 'false' : (is_int($this->value)) ? (String)$this->value : $this->value;

        switch ($this->type) {

            /*
             |-----------------------------------------------------------------------------------------------------------------
             | Для автоматического определения типа
             |-----------------------------------------------------------------------------------------------------------------
             */
            case SQLType::AUTO:
                if (preg_match('/[0-9]/', $this->value) || preg_match('/^true$|^false$/', $this->value)) {
                    return $this->value;
                } elseif (preg_match('/[a-zA-Z]/', $this->value)) {
                    return SQLType::validStr($this->value);
                } else {
                    throw new Exception("Error Processing Request", 1);
                }

            /*
            |-----------------------------------------------------------------------------------------------------------------
            | Для случаев, когда тип уже указан
            |-----------------------------------------------------------------------------------------------------------------
            */
            case SQLType::INT:
            case SQLType::BOOL:
                return $this->value;
            case SQLType::STRING:
                return SQLType::validStr($this->value);

            default:
                throw new Exception("Error!", 1);
        }
    }
}