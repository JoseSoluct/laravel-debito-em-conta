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
        'identificacao',
        'agencia',
        'conta',
        'identificacaobancaria',
        'datavencimento',
        'valor',
        'cpfcnpj',
        'movimento'
    ];

    public function getMoeda(): int|string
    {
        return $this->moeda;
    }

    public function setMoeda(int|string $moeda): void
    {
        $this->moeda = $moeda;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    public function getDataVencimento(): Carbon
    {
        return $this->dataVencimento;
    }

    public function setDataVencimento(Carbon $dataVencimento): void
    {
        $this->dataVencimento = $dataVencimento;
    }

    public function getAgencia(): string
    {
        return $this->agencia;
    }

    public function setAgencia(string $agencia): void
    {
        $this->agencia = $agencia;
    }

    public function getAgenciaDv(): string
    {
        return $this->agenciaDv;
    }

    public function setAgenciaDv(string $agenciaDv): void
    {
        $this->agenciaDv = $agenciaDv;
    }

    public function getConta(): string
    {
        return $this->conta;
    }

    public function setConta(string $conta): void
    {
        $this->conta = $conta;
    }

    public function getContaDv(): string
    {
        return $this->contaDv;
    }

    public function setContaDv(string $contaDv): void
    {
        $this->contaDv = $contaDv;
    }

    /**
     * Moeda
     *
     * @var int
     */
    protected $moeda = '03';

    /**
     * Valor total do débito
     *
     * @var float
     */
    public $valor;

    /**
     * Data do débito
     *
     * @var Carbon
     */
    public $dataVencimento;

    /**
     * Agência
     *
     * @var string
     */
    protected $agencia;

    /**
     * Dígito da agência
     *
     * @var string
     */
    protected $agenciaDv;

    /**
     * Conta
     *
     * @var string
     */
    protected $conta;

    /**
     * Dígito da conta
     *
     * @var string
     */
    protected $contaDv;

    /**
     * AbstractDebito constructor.
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        Util::fillClass($this, $params);
        // Marca a data de emissão para hoje, caso não especificada
        if (! $this->getDataDocumento()) {
            $this->setDataDocumento(new Carbon());
        }
        // Marca a data de processamento para hoje, caso não especificada
        if (! $this->getDataProcessamento()) {
            $this->setDataProcessamento(new Carbon());
        }
        // Marca a data de vencimento para daqui a 5 dias, caso não especificada
        if (! $this->getDataVencimento()) {
            $this->setDataVencimento(new Carbon(date('Y-m-d', strtotime('+5 days'))));
        }
        // Marca a data de desconto
        if (! $this->getDataDesconto()) {
            $this->setDataDesconto($this->getDataVencimento());
        }
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
