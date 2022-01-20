<?php

/***************************************************************
 *  Copyright notice
 *  (c) 2019 Gregor Agnes (zwo3.de) <ga@zwo3.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Zwo3\MaskKesearchIndexer;

use Doctrine\DBAL\FetchMode;
use Tpwd\KeSearch\Lib\Db;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class AdditionalContentFields
 *
 * @package Zwo3\MaskKesearchIndexer
 */
class AdditionalContentFields
{

    /**
     * @var string
     */
    public $maskColumns = '';

    /**
     * AdditionalContentFields constructor.
     */
    public function __construct()
    {
        // get the mask fields (columns) from tt_content
        $this->maskColumns = $this->getMaskFieldsFromTable();
    }

    /**
     * @param string $fields
     * @param \Tpwd\KeSearch\Indexer\Types\Page $pageIndexer
     */
    public function modifyPageContentFields(&$fields, $pageIndexer)
    {
        // Add the mask fields from the tt_content table to the list of fields.
        if ($this->maskColumns) {
            $fields .= "," . $this->maskColumns;
        }
    }

    /**
     * @param string $bodytext
     * @param array $ttContentRow
     * @param \Tpwd\KeSearch\Indexer\Types\Page $pageIndexer
     */
    public function modifyContentFromContentElement(string &$bodytext, array $ttContentRow, $pageIndexer)
    {
        if ($this->maskColumns) {
            $columns = explode(',', $this->maskColumns);
            foreach ($columns as $column) {
                if (!is_numeric($ttContentRow[$column])) {
                    // add the content to bodytext
                    $bodytext .= strip_tags($ttContentRow[$column]);
                } elseif ($ttContentRow[$column] && is_array($ttContentRow)) {
                    // it's a dependend table, index the columns from the dependent table
                    $maskColumnsOfDependentTable = preg_split('/,/', $this->getMaskFieldsFromTable($column), null, PREG_SPLIT_NO_EMPTY);
                    if ($maskColumnsOfDependentTable) {
                        $bodytext = $this->getContentFromMaskFields($ttContentRow['pid'], $column, $maskColumnsOfDependentTable);
                    }
                }
            }
        }
    }

    /**
     * @param int $pid
     * @param string $table
     * @param arry $columns
     * @return string
     */
    private function getContentFromMaskFields($pid, $table, $columns)
    {$queryBuilder = Db::getQueryBuilder($table);
        $pageQuery = $queryBuilder
            ->select(...$columns)
            ->from($table)
            ->where(
                $queryBuilder->expr()
                    ->eq(
                        'pid', $queryBuilder->createNamedParameter($pid)
                    )
            )
            ->execute();

        $bodytext = '';
        while ($row = $pageQuery->fetch()) {
            foreach ($row as $content) {
                if (!empty($bodytext) && !empty($content)) {
                    $bodytext .= ' ';
                }
                $bodytext .= strip_tags($content);
            }
        }

        return $bodytext;
    }

    /**
     * @param string $table
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getMaskFieldsFromTable($table = 'tt_content')
    {
        $link = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table);
        
        $increaseGroupConcatSql= 'SET SESSION group_concat_max_len = 1000000';
        $statement = $link->prepare($increaseGroupConcatSql);
        $statement->execute();

        $sql = "SELECT GROUP_CONCAT(COLUMN_NAME) as columns
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = '" . $table . "'
    AND table_schema = '" . $link->getDatabase() . "'
    AND column_name LIKE 'tx_mask_%'
    GROUP BY table_name
    ";

        $statement = $link->prepare($sql);
        $statement->execute();

        while ($row = $statement->fetch(FetchMode::ASSOCIATIVE)) {
            return $row['columns'];
        }
    }
}