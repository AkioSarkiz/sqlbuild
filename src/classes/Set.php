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