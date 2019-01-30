<?php

namespace Salnhan\NewsletterSubscribe\Domain\Finishers;

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

use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;
use TYPO3\CMS\Form\Domain\Model\Renderable\RenderableInterface;

/**
 * Class GenerateActivationCode
 * This finisher generates a custom activation code for a database entry.
 *
 * @author Minh-Thien Nhan <timit06@yahoo.com>
 */
class GenerateActivationCode
{
    /**
     * @param \TYPO3\CMS\Form\Domain\Runtime\FormRuntime $formRuntime
     * @param \TYPO3\CMS\Form\Domain\Model\Renderable\RenderableInterface $renderable
     * @param mixed $elementValue submitted value of the element *before post processing*
     * @param array $requestArguments
     * @return string
     * @throws \Exception
     */
    public function afterSubmit(FormRuntime $formRuntime, RenderableInterface $renderable, $elementValue, array $requestArguments = [])
    {
        $identifier = $renderable->getIdentifier();

        if ($identifier === 'activationCode')
        {
            $token = bin2hex(random_bytes(16));
            $elementValue = $token;
        }

        return $elementValue;
    }
}
