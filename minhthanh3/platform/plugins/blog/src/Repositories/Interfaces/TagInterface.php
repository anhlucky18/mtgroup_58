<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface TagInterface extends RepositoryInterface
{

    /**
     * @return mixed
     * @author Sang
     */
    public function getDataSiteMap();

    /**
     * @param int $limit
     * @return mixed
     * @author Sang
     */
    public function getPopularTags($limit);

    /**
     * @param bool $active
     * @return mixed
     * @author Sang
     */
    public function getAllTags($active = true);
}
