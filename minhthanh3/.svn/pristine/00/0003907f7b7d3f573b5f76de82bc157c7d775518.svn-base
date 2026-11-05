<?php

namespace Botble\Page\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface PageInterface extends RepositoryInterface
{

    /**
     * @return mixed
     * @author Sang
     */
    public function getDataSiteMap();

    /**
     * @param $limit
     * @author Sang
     */
    public function getFeaturedPages($limit);

    /**
     * @param $array
     * @param array $select
     * @return mixed
     * @author Sang
     */
    public function whereIn($array, $select = []);

    /**
     * @param $query
     * @param int $limit
     * @return mixed
     * @author Sang
     */
    public function getSearch($query, $limit = 10);

    /**
     * @param bool $active
     * @return mixed
     * @author Sang
     */
    public function getAllPages($active = true);
}
