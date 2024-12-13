<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Debito\Cnab150\Banco;

use Josea\LaravelDebitoEmConta\Cnab\Debito\Cnab150\AbstractDebito;
use Josea\LaravelDebitoEmConta\Contracts\Debito\Debito;

class Sicredi extends AbstractDebito implements Debito
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}