<?php

namespace Mailery\Channel\Amazon\SES\Repository;

use Cycle\ORM\Select\Repository;
use Mailery\Brand\Entity\Brand;

class CredentialsRepository extends Repository
{
    /**
     * @param Brand $brand
     * @return self
     */
    public function withBrand(Brand $brand): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'brand_id' => $brand->getId(),
            ]);

        return $repo;
    }
}
