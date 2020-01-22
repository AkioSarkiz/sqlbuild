<?php declare(strict_types=1);


namespace SQLBuild;


/**
 * Class SelectCollection - коллекция чтоб выбирать колонок для SELECT
 * @package SQLBuild
 */
final class SelectCollection extends AbstractCollection
{
    /**
     * Добавление строки к уже существующей коллекции
     * @param String|array $args
     * @return Void
     * @throws \Exception
     */
    public function add($args): Void {
        if (!is_array($args))
            $args = [$args];
        foreach ($args as $arg)
        {
            if (is_string($arg) || is_int($arg))
            {
                array_push($this->objs, (string)$arg);
            } else {
                throw new \Exception('тип аргумента не строка или число, тип: ' . gettype($arg));
            }
        }
    }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
	public function render(): String 
	{
        if (count($this->objs) === 0)
            return '';
		if (count($this->objs) === 0) {
		    return '*';
		}
		return '`' . implode($this->objs, '`,`') . '`';
	}
}