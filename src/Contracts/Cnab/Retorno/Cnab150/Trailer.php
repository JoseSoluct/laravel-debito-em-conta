<?php

namespace Josea\LaravelDebitoEmConta\Contracts\Cnab\Retorno\Cnab150;

interface Trailer
{
    /**
     * @return mixed
     */
    public function getTipoRegistro();

    /**
     * @return mixed
     */
    public function getNumeroLoteRemessa();

    /**
     * @return mixed
     */
    public function getQtdLotesArquivo();

    /**
     * @return mixed
     */
    public function getQtdRegistroArquivo();

    /**
     * @return array
     */
    public function toArray();
}
