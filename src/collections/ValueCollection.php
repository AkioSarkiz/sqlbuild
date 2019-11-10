<?php

declare(strict_types=1);

namespace SQLBuild;

use Exception;

final class ValueCollection extends AbstractCollection
{
	private $temp;

	public function __construct(array $objs){ $this->objs = $objs; }

	public function render(): String
	{
		foreach ($this->objs as $obj) {
            try {
                $this->temp .= $obj->render() . ',';
            } catch (Exception $e) {
                throw $e;
            }
        }
		return substr($this->temp, 0, -1);
	}
}