<?php

declare(strict_types=1);


namespace SQLBuild;


final class Set {

	private $value;
	private $type1;
	private $type2;

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
                if (preg_match('/[0-9]/', $value) || preg_match('[^true$|^false$]', $value)) {
                    return $value;
                }
                else {
                    return sprintf('"%s"', SQLType::validStr($value));
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
                return sprintf('"%s"', SQLType::validStr($value));
            case SQLType::ARG:
                return sprintf('`%s`', $value);
            default:
                throw new Exception();
        }
    }

	public function render(): String
    {
        $template = ' %s=%s';

        preg_match_all('([^=]+)', $this->value, $matches);

        return sprintf(
            $template,
            // скорее всего это аргумент `arg`.. поэтому при SQLG::AUTO делаем SQLG:ARG
            // чтоб не опеределил как string
            $this->createSQlValue($matches[0][0], ($this->type1 == SQLType::AUTO) ? SQLType::ARG : $this->type1),
            $this->createSQlValue($matches[0][1], $this->type2));
    }
}