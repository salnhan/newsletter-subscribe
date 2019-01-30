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
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
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
        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var \FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository $addressRepository */
        $addressRepository = $objectManager->get(AddressRepository::class);

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $objectManager->get(Typo3QuerySettings::class);

        if ($this->options['isSubscribe']) {
            // Get active or not active email in tt_address
            // don't care value of hidden field
            $querySettings->setIgnoreEnableFields(true);

            // don't respect storage page
            $querySettings->setRespectStoragePage(false);

            $addressRepository->setDefaultQuerySettings($querySettings);
            $result = $addressRepository->findOneByEmail($value);

            // return error message if email is already existed
            if (!empty($result)) {
                $this->addError(
                    $this->translateErrorMessage(
                        'validation.email.existed',
                        'rlp'
                    ),
                    1547222415
                );
            }
        } else {
            // Get only active email
            $result = $addressRepository->findOneByEmail($value);
            // return error message, if email is still not existed
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
}
