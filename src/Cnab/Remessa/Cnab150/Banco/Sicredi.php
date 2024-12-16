<?php
namespace Josea\LaravelDebitoEmConta\Cnab\Remessa\Cnab150\Banco;
use Carbon\Carbon;
use Josea\LaravelDebitoEmConta\Cnab\Remessa\Cnab150\AbstractRemessa;
use Josea\LaravelDebitoEmConta\Contracts\Cnab\Remessa;
use Josea\LaravelDebitoEmConta\Contracts\Debito\Debito;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;
use Josea\LaravelDebitoEmConta\Util;

class Sicredi extends AbstractRemessa implements Remessa
{
    const CODIGO_REGISTRO = 'A';
    const CODIGO_REMESSSA = 1;
    const CODIGO_RETORNO = 2;
    const CODIGO_BANCO = 748;
    const NOME_BANCO = 'SICREDI';
    const VERSAO_LAYOUT = '05';
    const IDENTIFICACAO_SERVICO = 'DEBITO AUTOMATICO';

    public function __construct(array $params)
    {
        parent::__construct($params);
    }

    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco = self::CODIGO_BANCO;

    protected function header()
    {
        $this->iniciaHeader();
        /**
         * HEADER DE ARQUIVO
         */
        $this->add(1,1,'A');
        $this->add(2,2,'1');
        $this->add(3,22,$this->getConvenio());
        $this->add(23,42, Util::formatCnab('X', $this->getNomeempresa(), 20));
        $this->add(43,45, self::CODIGO_BANCO);
        $this->add(46,65, self::NOME_BANCO);
        $this->add(66,73, $this->datageracao);
        $this->add(74,79, Util::formatCnab('9', $this->sequencial, 6));
        $this->add(80,81, '05');
        $this->add(82,98, 'DEBITO AUTOMATICO');
        $this->add(99,150, '');
        return $this;
    }

    /**
     * @param Debito $debito
     *
     * @return Sicredi
     * @throws ValidationException
     */
    public function addDebito(Debito $debito)
    {
        $this->debitos[] = $debito;
        $this->segmentoE($debito);
        return $this;
    }

    protected function segmentoE(Debito $debito){
        $this->iniciaDetalhe();
        $this->add(1,1,'E');
        $this->add(2,26, $this->getIdentificacao());
        $this->add(27,30, $this->getAgencia());
        $this->add(31,44, $this->getConta());
        $this->add(45,52, $this->getDatavencimento());
        $this->add(53,67, $this->getValordebito());
        $this->add(70,118, '');
        $this->add(119,128, 0);
        $this->add(129,129, 'X');
        $this->add(130,130, $this->getTipoidentificacao());
        $this->add(131,145, $this->getIdentificacao());
        $this->add(146,149, '');
        $this->add(150,150, $this->getCodigomovimento());
        return $this;
    }

    /**
     * @return
     * @throws ValidationException
     */
    protected function trailer()
    {
        $this->iniciaTrailer();

        $this->add(1, 1, 'Z');
        $this->add(2, 7, Util::formatCnab('9', $this->getCount(), 6));
        $this->add(8,24, Util::formatCnab('9', $this->getTotal(), 17));
        $this->add(25, 150, '');
        return $this;
    }
}
