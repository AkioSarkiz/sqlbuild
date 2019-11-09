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

	public function render(): String
    {
        return 'not supp';
    }
}