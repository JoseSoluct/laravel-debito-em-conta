<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150;

use Carbon\Carbon;
use Josea\LaravelDebitoEmConta\Contracts\Cnab\Retorno\Cnab150\Header as HeaderContract;
use Josea\LaravelDebitoEmConta\MagicTrait;

class Header implements HeaderContract
{
    use MagicTrait;

    protected string $nomeBanco;
    protected string $codigoRegistro;
    protected int $codigoRemessa;
    protected string $codigoConvenio;
    protected string $nomeEmpresa;
    protected int $codigoBanco;

    protected string $data;
    protected string $dataArquivo;
    protected int $sequencial;
    protected int $layout;
    protected string $identificacaoServico;

    public function getNomeBanco(): string
    {
        return $this->nomeBanco;
    }

    public function setNomeBanco(string $nomeBanco)
    {
        $this->nomeBanco = $nomeBanco;
        return $this;
    }

    public function getCodigoRegistro(): string
    {
        return $this->codigoRegistro;
    }

    public function setCodigoRegistro(string $codigoRegistro)
    {
        $this->codigoRegistro = $codigoRegistro;
        return $this;
    }

    public function getCodigoRemessa(): int
    {
        return $this->codigoRemessa;
    }

    public function setCodigoRemessa(int $codigoRemessa)
    {
        $this->codigoRemessa = $codigoRemessa;
        return $this;
    }

    public function getCodigoConvenio(): string
    {
        return $this->codigoConvenio;
    }

    public function setCodigoConvenio(string $codigoConvenio)
    {
        $this->codigoConvenio = $codigoConvenio;
        return $this;
    }

    public function getNomeEmpresa(): string
    {
        return $this->nomeEmpresa;
    }

    public function setNomeEmpresa(string $nomeEmpresa)
    {
        $this->nomeEmpresa = $nomeEmpresa;
        return $this;
    }

    public function getCodigoBanco(): int
    {
        return $this->codigoBanco;
    }

    public function setCodigoBanco(int $codigoBanco)
    {
        $this->codigoBanco = $codigoBanco;
        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getDataArquivo(): string
    {
        return $this->dataArquivo;
    }

    public function setDataArquivo(string $dataArquivo)
    {
        $this->dataArquivo = $dataArquivo;
        return $this;
    }

    public function getSequencial(): int
    {
        return $this->sequencial;
    }

    public function setSequencial(int $sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    public function getLayout(): int
    {
        return $this->layout;
    }

    public function setLayout(int $layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function getIdentificacaoServico(): string
    {
        return $this->identificacaoServico;
    }

    public function setIdentificacaoServico(string $identificacaoServico)
    {
        $this->identificacaoServico = $identificacaoServico;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'nomeBanco' => $this->nomeBanco,
            'codigoRegistro' => $this->codigoRegistro,
            'codigoRemessa' => $this->codigoRemessa,
            'codigoConvenio' => $this->codigoConvenio,
            'nomeEmpresa' => $this->nomeEmpresa,
            'codigoBanco' => $this->codigoBanco,
            'data' => $this->data,
            'dataArquivo' => $this->dataArquivo,
            'sequencial' => $this->sequencial,
            'layout' => $this->layout,
            'identificacaoServico' => $this->identificacaoServico,
        ];
    }

}
