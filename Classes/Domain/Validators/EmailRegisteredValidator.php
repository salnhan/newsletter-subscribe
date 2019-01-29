<?php

namespace Salnhan\NewsletterSubscribe\Domain\Validators;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository;

/**
 * Class EmailRegisteredValidator
 * Check if the email is already registered
 *
 * @author Minh-Thien Nhan <timit06@yahoo.com>
 */
class EmailRegisteredValidator extends AbstractValidator
{
    /**
     * @var array
     */
    protected $supportedOptions = [
        'isSubscribe' => [false, 'is a newsletter subscribe form (true/false)', 'boolean']
    ];


    /**
     * Checks if the given email already exists in database table tt_address.
     *
     * @param mixed $value The value that should be validated
     * @api
     */
    public function isValid($value)
    {
        if ($this->options['isSubscribe']) {
            $this->validateEmailForSubscribe($value);
        } else {
            $this->validateEmailForUnsubscribe($value);
        }
    }

    /**
     * validate email for newsletter subscribe
     *
     * @param string $email
     */
    protected function validateEmailForSubscribe($email)
    {
        /** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('tt_address');
        // don't care hidden is 0 or 1
        $queryBuilder->getRestrictions()->removeByType(\TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction::class);
        $queryBuilder->select('uid')
            ->from('tt_address')
            ->where(
                $queryBuilder->expr()->eq(
                    'email',
                    $queryBuilder->createNamedParameter($email, \PDO::PARAM_STR)
                )
            );

        $result = $queryBuilder->execute()->fetch();

        if (!empty($result)) {
            $this->addError(
                $this->translateErrorMessage(
                    'validation.email.existed',
                    'rlp'
                ),
                1547222415
            );
        }
    }

    /**
     * Validate email for newsletter unsubscribe
     *
     * @param $email
     */
    protected function validateEmailForUnsubscribe($email)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $addressRepository = $objectManager->get(AddressRepository::class);

        $result = $addressRepository->findOneByEmail($email);

        if (empty($result)) {
            $this->addError(
                $this->translateErrorMessage(
                    'validation.email.notExisted',
                    'rlp'
                ),
                1547467836
            );
        }
    }
}
