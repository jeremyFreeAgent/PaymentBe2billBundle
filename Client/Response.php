<?php

namespace Rezzza\PaymentBe2billBundle\Client;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * This file is part of the RezzzaPaymentBe2billBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

/**
 * @author Jérémy Romey <jeremy@free-agent.fr>
 */
class Response
{
    public $body;

    public function __construct(array $parameters)
    {
        $this->body = new ParameterBag($parameters);
    }

    public function getOperationType()
    {
        return $this->body->get('OPERATIONTYPE', null);
    }

    public function getTransactionId()
    {
        return $this->body->get('TRANSACTIONID', null);
    }

    public function getExecutionCode()
    {
        return $this->body->get('EXECCODE');
    }

    public function getMessage()
    {
        return $this->body->get('MESSAGE');
    }

    public function isSuccess()
    {
        return '0000' == $this->getExecutionCode();
    }

    public function isError()
    {
        return !$this->isSuccess();
    }

    public function is3dSecureError()
    {
        return '0001' == $this->getExecutionCode();
    }

    public function isValidationError()
    {
        if (preg_match('/10[0-9][0-9]/', $this->getExecutionCode())) {
            return true;
        }

        return false;
    }

    public function isTransactionUpdateError()
    {
        return 1 == preg_match('/20[0-9][0-9]/', $this->getExecutionCode());
    }

    public function isConfigurationError()
    {
        return 1 == preg_match('/30[0-9][0-9]/', $this->getExecutionCode());
    }

    public function isBankError()
    {
        return 1 == preg_match('/40[0-9][0-9]/', $this->getExecutionCode());
    }

    public function isInternalError()
    {
        return 1 == preg_match('/50[0-9][0-9]/', $this->getExecutionCode());
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function toArray()
    {
        return $this->body->all();
    }

    public function toJson()
    {
        return json_encode($this->body->all());
    }
}
