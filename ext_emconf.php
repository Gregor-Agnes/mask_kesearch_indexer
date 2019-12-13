
<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Faceted Search Hook for Mask Elements',
    'description' => 'Hooks for Mask Elements, both tt_content columns and tx_mask-tables',
    'category' => 'backend',
    'version' => '0.2.0',
    'dependencies' => 'ke_search, mask',
    'state' => 'stable',
    'author' => 'Gregor Agnes',
    'author_email' => 'ga@zwo3.de',
    'author_company' => 'zwo3',
    'constraints' => array(
        'depends' => array(
            'typo3' => '9.5.0-9.5.99',
            'mask' => '4.1.2-4.99.99',
            'ke_search' => '3.0.6-3.99,99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
);