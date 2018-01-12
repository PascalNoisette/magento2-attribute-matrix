<?php
namespace Smile\Matrix\Api;

use Smile\Matrix\Api\Data\RowInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface RowRepositoryInterface 
{
    public function save(RowInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(RowInterface $page);

    public function deleteById($id);
}
