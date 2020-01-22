<?php


namespace SQLBuild;


final class JoinCollection extends AbstractCollection
{
    public function __construct(int $operator, String $nameTable)
    {
        $this->objs['operator'] = $operator;
        $this->objs['nameTable'] = $nameTable;
    }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        if (count($this->objs) === 0)
            return '';
        switch ($this->objs['operator'])
        {
            case SQLOperator::INNER:
                $this->objs['operator'] = 'INNER';
                break;
            case SQLOperator::RIGHT:
                $this->objs['operator'] = 'RIGHT';
                break;
            case SQLOperator::LEFT:
                $this->objs['operator'] = 'LEFT';
                break;
            case SQLOperator::FULL:
                $this->objs['operator'] = 'FULL';
                break;
        }

        return $this->objs['operator'] . ' JOIN `' . $this->objs['nameTable'] . '`';
    }
}