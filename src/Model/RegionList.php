<?php

namespace Mailery\Channel\Email\Amazon\Model;

use Doctrine\Common\Collections\ArrayCollection;

class RegionList extends ArrayCollection
{
    /**
     * @param array $channels
     */
    public function __construct(array $channels)
    {
        parent::__construct($channels);
    }
}
