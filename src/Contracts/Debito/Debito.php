<?php

namespace Josea\LaravelDebitoEmConta\Contracts\Debito;

interface Debito
{
    /**
     * @return mixed
     */
    public function getCpfCnpj();
    public function getAgencia();
    public function getIdentificacao();
}