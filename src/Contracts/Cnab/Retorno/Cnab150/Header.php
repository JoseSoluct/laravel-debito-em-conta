<?php

namespace Josea\LaravelDebitoEmConta\Contracts\Cnab\Retorno\Cnab150;

interface Header
{

    /**
     * @return string
     */
    public function getNomeBanco();

    /**
     * @return string
     */
    public function getCodigoRegistro();

    /**
     * @return string
     */
    public function getCodigoRemessa();

    /**
     * @return string
     */
    public function getCodigoConvenio();

    /**
     * @return string
     */
    public function getNomeEmpresa();

    /**
     * @return string
     */
    public function getCodigoBanco();

    /**
     * @return string
     */
    public function getData();

    /**
     * @return string
     */
    public function getDataArquivo();

    /**
     * @return string
     */
    public function getSequencial();

    /**
     * @return string
     */
    public function getLayout();

    /**
     * @return string
     */
    public function getIdentificacaoServico();

    /**
     * @return array
     */
    public function toArray();
}
