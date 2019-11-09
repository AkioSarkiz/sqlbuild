<?php

declare(strict_types=1);


namespace SQLBuild;


use Exception;


final class Value
{
	private $value;
	private $type;

	public function __construct(String $value, int $type)
	{
		$this->value = $value;
		$this->type = $type;
	}

    /**
     * @return String
     * @throws Exception
     */
	public function render(): String
	{
		switch ($this->type) {

			case SQLType::AUTO:
                if (preg_match('/[a-zA-Z]/', $this->value))
                    return sprintf('"%s"', $this->value);
                elseif (preg_match('/[0-9]/', $this->value) || preg_match('[^true$|^false$]', $this->value))
                    return $this->value;
                throw new Exception("Error Processing Request", 1);

			case SQLType::INT:
			case SQLType::BOOL:
				return $this->value;
			
			case SQLType::STRING:
				return sprintf('"%s"', $this->value);

			default:
				throw new Exception("Error Processing Request", 1);
		}
	}
}