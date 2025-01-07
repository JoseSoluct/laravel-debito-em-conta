<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Debito\Cnab150;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Josea\LaravelDebitoEmConta\Contracts\Debito\Debito;
use Josea\LaravelDebitoEmConta\MagicTrait;
use Josea\LaravelDebitoEmConta\Util;
use Throwable;

/**
 * Class AbstractDebito
 */
abstract class AbstractDebito implements Debito
{
    use MagicTrait;

    /**
     * Campos necessários para o boleto
     *
     * @var array
     */
    private $camposObrigatorios = [
        'identificacaocliente',
        'agencia',
        'conta',
        'identificacaobanco',
        'datavencimento',
        'valordebito',
        'identificacao',
        'movimento'
    ];

    protected $protectedFields = [
        'registro',
        'moeda',
        'tipoidentificacao',

    ];

    protected $identificacaocliente;
    protected $agencia;
    protected $conta;
    protected $identificacaobanco;
    protected $datavencimento;
    protected $valordebito;
    protected $identificacao;
    protected $registro;
    protected $moeda;
    protected $tipoidentificacao;
    protected $movimento;

    /**
     * @return mixed
     */
    public function getIdentificacaocliente()
    {
        return $this->identificacaocliente;
    }

    /**
     * @param mixed $identificacaocliente
     */
    public function setIdentificacaocliente($identificacaocliente): void
    {
        $this->identificacaocliente = $identificacaocliente;
    }

    /**
     * @return mixed
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * @param mixed $agencia
     */
    public function setAgencia($agencia): void
    {
        $this->agencia = $agencia;
    }

    /**
     * @return mixed
     */
    public function getIdentificacaobanco()
    {
        return $this->identificacaobanco;
    }

    /**
     * @param mixed $identificacaobanco
     */
    public function setIdentificacaobanco($identificacaobanco): void
    {

        $this->identificacaobanco = $identificacaobanco;
    }

    /**
     * @return mixed
     */
    public function getDatavencimento()
    {
        return $this->datavencimento;
    }

    /**
     * @param mixed $datavencimento
     */
    public function setDatavencimento($datavencimento): void
    {
        $this->datavencimento = $datavencimento;
    }

    /**
     * @return mixed
     */
    public function getValordebito()
    {
        return $this->valordebito;
    }

    /**
     * @param mixed $valordebito
     */
    public function setValordebito($valordebito): void
    {
        $this->valordebito = $valordebito;
    }

    /**
     * @return mixed
     */
    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    /**
     * @param mixed $identificacao
     */
    public function setIdentificacao($identificacao): void
    {
        $this->identificacao = $identificacao;
    }

    /**
     * @return mixed
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * @param mixed $registro
     */
    public function setRegistro($registro): void
    {
        $this->registro = $registro;
    }

    /**
     * @return mixed
     */
    public function getMoeda()
    {
        return $this->moeda;
    }

    /**
     * @param mixed $moeda
     */
    public function setMoeda($moeda): void
    {
        $this->moeda = $moeda;
    }

    /**
     * @return mixed
     */
    public function getTipoidentificacao()
    {
        if (strlen($this->getidentificacao()) == 11) {
            return '2';
        }
        if (strlen($this->getidentificacao()) == 15) {
            return '1';
        }

//        return $this->tipoidentificacao;
    }

    /**
     * @param mixed $tipoidentificacao
     */
    public function setTipoidentificacao($tipoidentificacao): void
    {
        $this->tipoidentificacao = $tipoidentificacao;
    }

    /**
     * @return mixed
     */
    public function getMovimento()
    {
        return $this->movimento;
    }

    /**
     * @param mixed $movimento
     */
    public function setMovimento($movimento): void
    {
        $this->movimento = $movimento;
    }

    /**
     * @return mixed
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param mixed $conta
     */
    public function setConta($conta): void
    {
        $this->conta = $conta;
    }



    /**
     * AbstractDebito constructor.
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        Util::fillClass($this, $params);
    }

    /**
     * @return array
     */
    public function getProtectedFields()
    {
        return $this->protectedFields;
    }

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
     * @return $this
     */
    public function copy()
    {
        return clone $this;
    }


}
