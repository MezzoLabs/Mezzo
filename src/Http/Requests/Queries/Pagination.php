<?php


namespace MezzoLabs\Mezzo\Http\Requests\Queries;


class Pagination
{
    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $limit;

    public function __construct(int $offset = 0, int $limit = 0)
    {

        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function offset()
    {
        return $this->offset;
    }

    public function isEmpty()
    {
        return $this->offset == 0 && $this->limit == 0;
    }


}