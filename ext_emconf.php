
<?php
$EM_CONF['mask_kesearch_indexer'] = array(
    'title' => 'KeSearch Indexer for Mask Elements',
    'description' => 'Indexer for mask elements, both tt_content columns and tx_mask-tables.',
    'category' => 'backend',
    'version' => '2.0.0',
    'dependencies' => 'ke_search, mask',
    'state' => 'stable',
    'author' => 'Gregor Agnes',
    'author_email' => 'ga@zwo3.de',
    'author_company' => 'zwo3',
    'constraints' => array(
        'depends' => array(
            'typo3' => '11.5.0-11.5.99',
            'mask' => '7.0.21-7.0.99',
            'ke_search' => '4.2.0-4.9.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
);