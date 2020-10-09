
<?php
$EM_CONF['mask_kesearch_indexer'] = array(
    'title' => 'KeSearch Indexer for Mask Elements',
    'description' => 'Indexer for mask elements, both tt_content columns and tx_mask-tables.',
    'category' => 'backend',
    'version' => '1.5.4',
    'dependencies' => 'ke_search, mask',
    'state' => 'stable',
    'author' => 'Gregor Agnes',
    'author_email' => 'ga@zwo3.de',
    'author_company' => 'zwo3',
    'constraints' => array(
        'depends' => array(
            'typo3' => '9.5.0-10.4.99',
            'mask' => '4.1.2-5.99.99',
            'ke_search' => '3.1.0-3.99.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
);