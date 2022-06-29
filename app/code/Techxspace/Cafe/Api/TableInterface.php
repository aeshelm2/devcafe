<?php
namespace Techxspace\Cafe\Api;

interface TableInterface
{
    const TABLE_CODE = 'table_code';
    const TABLE_NAME = 'table_name';
    const TABLE_STATUS = 'table_status';
    const TABLE_STATUS_OCCUPIED = 'occupied';
    const TABLE_STATUS_VACANT = 'vacant';

    public function setTableName($tableCode);
    public function setTableCode($tableQr);
    public function setTableStatus($status);

    public function getTableName();
    public function getTableCode();
    public function getTableStatus();
}