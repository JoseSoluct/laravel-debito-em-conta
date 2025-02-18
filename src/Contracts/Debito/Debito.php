<?php

namespace Josea\LaravelDebitoEmConta\Contracts\Debito;

interface Debito
{
    const COD_BANCO_SICREDI = '748';
    public function getIdentificacaocliente();

    public function getAgencia();

    public function getIdentificacaobanco();

    public function getDatavencimento();

    public function getValordebito();
    public function getUsoempresa();

    public function getIdentificacao();

    public function getRegistro();

    public function getMoeda();

    public function getTipoidentificacao();

    public function getMovimento();
    
    public function getConta();
}
