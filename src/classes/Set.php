<?php


namespace SQLBuild;


use Exception;


/**
 * Class Set - установка значения для UPDATE
 * @package SQLBuild
 */
final class Set {

	private $value;
	private $type1;
	private $type2;

    /**
     * Set constructor.
     * @param String $value
     * @param int $type1
     * @param int $type2
     */
	public function __construct(String $value, int $type1 = SQLType::AUTO, int $type2 = SQLType::AUTO)
	{
		$this->value = $value;
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
        if (is_null($value))
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

    /**
     * @return String
     * @throws Exception
     */
	public function render(): String
    {
        $template = ' %s=%s';

        preg_match_all('/(^[^=]+)/su', $this->value, $tempMatches);
        $key = $tempMatches[0][0];
        preg_match_all('/=.*/su', $this->value, $tempMatches);
        $value = substr($tempMatches[0][0], 1);

        return sprintf(
            $template,
            // скорее всего это аргумент `arg`.. поэтому при SQLG::AUTO делаем SQLG:ARG
            // чтоб не опеределил как string
            $this->createSQlValue($key, ($this->type1 == SQLType::AUTO) ? SQLType::ARG : $this->type1),
            $this->createSQlValue($value, $this->type2));
    }
}