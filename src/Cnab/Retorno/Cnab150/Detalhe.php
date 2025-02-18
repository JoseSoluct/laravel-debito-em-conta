<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150;

use Carbon\Carbon;
use Josea\LaravelDebitoEmConta\Util;
use Josea\LaravelDebitoEmConta\MagicTrait;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;
use Josea\LaravelDebitoEmConta\Contracts\Cnab\Retorno\Cnab150\Detalhe as DetalheContract;

class Detalhe implements DetalheContract
{
    use MagicTrait;
    /**
     * @var string
     */
    protected $codigoRegistro;
    /**
     * @var string
     */
    protected $identificacaoConveniada;
    /**
     * @var string
     */
    protected $agencia;
    /**
     * @var string
     */
    protected $identificacaoBanco;
    /**
     * @var string
     */
    protected $dataVencimento;
    /**
     * @var string
     */
    protected $valor;
    /**
     * @var string
     */
    protected $codigoRetorno;
    /**
     * @var string
     */
    protected $usoEmpresa;
    /**
     * @var string
     */
    protected $identificacao;
    /**
     * @var string
     */
    protected $codigoMovimento;


    public function getCodigoRegistro(): string
    {
        return $this->codigoRegistro;
    }

    public function setCodigoRegistro(string $codigoRegistro)
    {
        $this->codigoRegistro = $codigoRegistro;
        return $this;
    }

    public function getIdentificacaoConveniada(): string
    {
        return $this->identificacaoConveniada;
    }

    public function setIdentificacaoConveniada(string $identificacaoConveniada)
    {
        $this->identificacaoConveniada = $identificacaoConveniada;
        return $this;
    }

    public function getAgencia(): string
    {
        return $this->agencia;
    }

    public function setAgencia(string $agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    public function getIdentificacaoBanco(): string
    {
        return $this->identificacaoBanco;
    }

    public function setIdentificacaoBanco(string $identificacaoBanco)
    {
        $this->identificacaoBanco = $identificacaoBanco;
        return $this;
    }

    public function getDataVencimento(): string
    {
        return $this->dataVencimento;
    }

    public function setDataVencimento(string $dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
        return $this;
    }

    public function getValor(): string
    {
        return $this->valor;
    }

    public function setValor(string $valor)
    {
        $this->valor = floatval($valor) / 100;
        return $this;
    }

    public function getCodigoRetorno(): string
    {
        return $this->codigoRetorno;
    }

    public function setCodigoRetorno(string $codigoRetorno)
    {
        $this->codigoRetorno = $codigoRetorno;
        return $this;
    }

    public function getUsoEmpresa(): string
    {
        return $this->usoEmpresa;
    }

    public function setUsoEmpresa(string $usoEmpresa)
    {
        $this->usoEmpresa = $usoEmpresa;
        return $this;
    }

    public function getIdentificacao(): string
    {
        return $this->identificacao;
    }

    public function setIdentificacao(string $identificacao)
    {
        $this->identificacao = $identificacao;
        return $this;
    }

    public function getCodigoMovimento(): string
    {
        return $this->codigoMovimento;
    }

    public function setCodigoMovimento(string $codigoMovimento)
    {
        $this->codigoMovimento = $codigoMovimento;
        return $this;
    }

    /**
     * @return string
     */
    public function getOcorrencia()
    {
        return $this->ocorrencia;
    }

    /**
     * @return bool
     */
    public function hasOcorrencia(): bool
    {
        $ocorrencias = func_get_args();

        if (count($ocorrencias) == 0 && ! empty($this->getOcorrencia())) {
            return true;
        }

        if (count($ocorrencias) == 1 && is_array(func_get_arg(0))) {
            $ocorrencias = func_get_arg(0);
        }

        if (in_array($this->getOcorrencia(), $ocorrencias)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $ocorrencia
     *
     * @return Detalhe
     */
    public function setOcorrencia($ocorrencia)
    {
        $this->ocorrencia = $ocorrencia;
        return $this;
    }

    /**
     * @return string
     */
    public function getOcorrenciaTipo()
    {
        return $this->ocorrenciaTipo;
    }

    /**
     * @param string $ocorrenciaTipo
     *
     * @return Detalhe
     */
    public function setOcorrenciaTipo($ocorrenciaTipo)
    {
        $this->ocorrenciaTipo = $ocorrenciaTipo;
        return $this;
    }

    /**
     * @return string
     */
    public function getOcorrenciaDescricao()
    {
        return $this->ocorrenciaDescricao;
    }

    /**
     * @param string $ocorrenciaDescricao
     *
     * @return Detalhe
     */
    public function setOcorrenciaDescricao($ocorrenciaDescricao)
    {
        $this->ocorrenciaDescricao = $ocorrenciaDescricao;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumeroControle()
    {
        return $this->numeroControle;
    }

    /**
     * Retorna se tem erro.
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getOcorrenciaTipo() == self::OCORRENCIA_ERRO;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return Detalhe
     */
    public function setError($error)
    {
        $this->ocorrenciaTipo = self::OCORRENCIA_ERRO;
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getRejeicao()
    {
        return $this->rejeicao;
    }

    /**
     * @param string $rejeicao
     *
     * @return Detalhe
     */
    public function setRejeicao($rejeicao)
    {
        $this->rejeicao = $rejeicao;
        return $this;

        return $this;
    }



}
