<?php

namespace Zwo3\MaskKesearchIndexer;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MaskIndexer
{

    /**
     * @var string
     */
    protected $indexerConfigurationKey = 'maskindexer';

    /**
     * Adds the custom indexer to the TCA of indexer configurations, so that
     * it's selectable in the backend as an indexer type, when you create a
     * new indexer configuration.
     *
     * @param array $params
     * @param object $pObj
     */
    public function registerIndexerConfiguration(&$params, $pObj)
    {
        // Set a name and an icon for your indexer.
        $customIndexer = array(
            '[CUSTOM] Mask Indexer (ext:mask)',
            $this->indexerConfigurationKey,
            'EXT:mask_kesearch_indexer/ext_icon.svg'
        );
        $params['items'][] = $customIndexer;
    }
}