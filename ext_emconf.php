<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 extension for newsletter subscription',
    'description' => 'Customer Templates and Files',
    'category' => 'misc',
    'author' => 'Minh-Thien Nhan',
    'author_email' => 'timit06@yahoo.com',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.7.99',
            'tt_address' => '4.0.0-4.99.99',
            'direct_mail' => '5.0.0-5.99.99'
        ],
        'conflicts' => [],
        'suggests' => []
    ]
];