<?php

namespace Domain\UseCase\ShowSendingReports;

use Domain\Model\Reports\MessageId;

class Command
{
    /**
     * @var MessageId
     */
    private $messageId;

    /**
     * @var string
     */
    private $searchString;

    /**
     * @var string
     */
    private $filterBy;

    /**
     * @var \DateTime
     */
    private $dateFrom;

    /**
     * @var \DateTime
     */
    private $dateTo;

    /**
     * @var string
     */
    private $sortBy;

    /**
     * @var string
     */
    private $sortOrder;

    /**
     * @param MessageId|null $messageId
     */
    public function __construct(MessageId $messageId = null)
    {
        $this->messageId = $messageId;
        $this->sortOrder = 'asc';
    }

    /**
     * @return MessageId
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @param string $searchString
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
    }

    /**
     * @return string
     */
    public function getFilterBy()
    {
        return $this->filterBy;
    }

    /**
     * @param string $filterBy
     */
    public function setFilterBy($filterBy)
    {
        $this->filterBy = $filterBy;
    }

    /**
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param int $dateFromTimestamp
     */
    public function setDateFrom($dateFromTimestamp)
    {
        $this->dateFrom = new \DateTime();
        $this->dateFrom->setTimestamp($dateFromTimestamp);
    }

    /**
     * @return \DateTime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param int $dateToTimestamp
     */
    public function setDateTo($dateToTimestamp)
    {
        $this->dateTo = new \DateTime();
        $this->dateTo->setTimestamp($dateToTimestamp);
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @param string $sortBy
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param string $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }
}
