<?php

namespace Josea\LaravelDebitoEmConta\Debito;

use Josea\LaravelDebitoEmConta\Contracts\Debito\Debito;

abstract class AbstractDebito implements Debito
{
    const UFIR = '01';
    const REAL = '03';

    /**
     * Campos necessários para o boleto
     *
     * @var array
     */
    private $camposObrigatorios = [
        'identificacao',
        'agencia',
        'codigocliente',
        'datavencimento',
        'valor',
        'codigomovimento'
    ];

    /**
     * Seta os campos obrigatórios
     *
     * @return $this
     */
    protected function setCamposObrigatorios()
    {
        $args = func_get_args();
        $this->camposObrigatorios = [];
        foreach ($args as $arg) {
            $this->addCampoObrigatorio($arg);
        }

        return $this;
    }

    /**
     * Adiciona os campos obrigatórios
     *
     * @return $this
     */
    protected function addCampoObrigatorio()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            ! is_array($arg) || call_user_func_array([$this, __FUNCTION__], $arg);
            ! is_string($arg) || array_push($this->camposObrigatorios, $arg);
        }

        return $this;
    }

    /**
     * Define a agência
     *
     * @param string $agencia
     *
     * @return AbstractDebito
     */
    public function setAgencia($agencia)
    {
        $this->agencia = (string) $agencia;

        return $this;
    }

    /**
     * Retorna a agência
     *
     * @return string
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Define a agência
     *
     * @param string $identificacao
     *
     * @return string
     */
    public function setIdentificacao($identificacao)
    {
        $this->identificacao = (string) $identificacao;

        return $this;
    }

    /**
     * Retorna a identificação
     *
     * @return string
     */
    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    /**
     * Define a data de vencimento
     *
     * @param string $datavencimento
     *
     * @return string
     */
    public function setDataVencimento($datavencimento)
    {
        $this->datavencimento = (string) $datavencimento;

        return $this;
    }

    /**
     * Retorna a data de vencimento
     *
     * @return string
     */
    public function getDataVencimento()
    {
        return $this->datavencimento;
    }

    /**
     * Define a valor do débito
     *
     * @param string $valordebito
     *
     * @return string
     */
    public function setValordebito($valordebito)
    {
        $this->valordebito = (string) $valordebito;

        return $this;
    }

    /**
     * Retorna a valor do débito
     *
     * @return string
     */
    public function getValordebito()
    {
        return $this->valordebito;
    }
}