<?php

namespace Salnhan\NewsletterSubscribe\Controller;

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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FormFrontendController extends TYPO3\CMS\Form\Controller\FormFrontendController
 *
 * @author Minh-Thien Nhan <timit06@yahoo.com>
 */
class FormFrontendController extends \TYPO3\CMS\Form\Controller\FormFrontendController
{
    /**
     * Extends the original performAction() for confirmtion of  newsletter registration
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function performAction()
    {
        // Process confirm request from confirm email. Accept only boolean value of field 'confirm'
        if ($this->request->hasArgument('confirm') &&
            \is_bool((bool)$this->request->getArgument('confirm')) &&
            $this->request->hasArgument('activationCode') &&
            !empty($this->request->getArgument('activationCode'))
        ) {
            // Confirm registration if 'confirm' = 1, deny registration if 'confirm' = 0
            $confirmStatus = (bool)$this->request->getArgument('confirm');
            $successPageUid = $this->settings['newsletter']['userEmail']['confirm']['successPageUid'];
            $failurePageUid = $this->settings['newsletter']['userEmail']['confirm']['failurePageUid'];

            $verifyResult = $this->verifyActivationCode($this->request->getArgument('activationCode'), $confirmStatus);
            // If deny registration
            if (!$confirmStatus) {
                $successPageUid = $this->settings['newsletter']['userEmail']['deny']['successPageUid'];
                $failurePageUid = $this->settings['newsletter']['userEmail']['deny']['failurePageUid'];
            }
            $this->redirectAfterVerifyCode($verifyResult, (int)$successPageUid, (int)$failurePageUid);
        } else {
            $this->forward('render');
        }
    }

    /**
     * Redirect after verify activation code
     *
     * @param bool $verifyResult
     * @param int $successPageId
     * @param int $failurePageId
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function redirectAfterVerifyCode($verifyResult, $successPageId, $failurePageId)
    {
        $redirectPage = $verifyResult ? $successPageId : $failurePageId;

        $uriBuilder = $this->uriBuilder;
        $uri = $uriBuilder->setTargetPageUid($redirectPage)->build();
        $this->redirectToUri($uri, $delay=0, $statusCode=303);
    }

    /**
     * Verify activation code
     *
     * @param string $activationCode
     * @param bool $confirmStatus
     * @return bool
     */
    public function verifyActivationCode($activationCode, $confirmStatus)
    {
        $isValid = false;

        /** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        $queryBuilder->select('uid')
            ->from('tt_address')
            ->where(
                $queryBuilder->expr()->eq(
                    'activation_code',
                    $queryBuilder->createNamedParameter($activationCode, \PDO::PARAM_STR)
                ),
                $queryBuilder->expr()->eq(
                    'hidden',
                    $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)
                )
            );

        $result = $queryBuilder->execute()->fetch();

        if (!empty($result['uid'])) {
            $this->updateTtAddressRecord($queryBuilder, $activationCode, $confirmStatus);
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Update tt_address record to activate or reject newsletter registration
     *
     * @param \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder
     * @param string $activationCode
     * @param int $confirmStatus
     */
    private function updateTtAddressRecord($queryBuilder, $activationCode, $confirmStatus)
    {
        $queryBuilder->update('tt_address')
            ->where(
                $queryBuilder->expr()->eq(
                    'activation_code',
                    $queryBuilder->createNamedParameter($activationCode, \PDO::PARAM_STR)
                )
            );
        if ($confirmStatus) {
            $queryBuilder->set('hidden', 0);
        } else {
            $queryBuilder->set('deleted', 1);
        }
        $queryBuilder->execute();
    }
}
