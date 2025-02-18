<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150\Banco;

use Illuminate\Support\Arr;
use Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150\AbstractRetorno;
use Josea\LaravelDebitoEmConta\Contracts\Cnab\RetornoCnab150;
use Josea\LaravelDebitoEmConta\Contracts\Debito\Debito as DebitoContract;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;
use Josea\LaravelDebitoEmConta\Util;

class Sicredi extends AbstractRetorno implements RetornoCnab150
{
    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco = DebitoContract::COD_BANCO_SICREDI;

    /**
     * Array com as ocorrencias do banco;
     *
     * @var array
     */
    private $ocorrencias = [
        '00' => 'Débito efetuado',
        '31' => 'Débito efetuado em data diferente da data informada - Feriado na praça de débito',

    ];

    /**
     * Array com as possiveis rejeicoes do banco.
     *
     * @var array
     */
    private $rejeicoes = [
        '01' => " Débito não efetuado - Insuficiência de fundos",
        '02' => "Débito não efetuado - Conta corrente não cadastrada",
        '04' => " Débito não efetuado - Outras restrições",
        '05' => " Débito não efetuado – valor do débito excede valor limite aprovado.",
        '10' => " Débito não efetuado - Agência em regime de encerramento",
        '12' => " Débito não efetuado - Valor inválido",
        '13' => " Débito não efetuado - Data de lançamento inválida",
        '14' => " Débito não efetuado - Agência inválida",
        '15' => " Débito não efetuado - conta corrente inválida",
        '18' => " Débito não efetuado - Data do débito anterior à do processamento",
        '19' => " Débito não efetuado – Agência/Conta não pertence ao CPF/CNPJ informado",
        '20' => " Débito não efetuado – conta conjunta não solidária",
        '30' => " Débito não efetuado - Sem contrato de débito automático",
        '96' => " Manutenção do Cadastro",
        '97' => " Cancelamento - Não encontrado",
        '98' => " Cancelamento - Não efetuado, fora do tempo hábil",
        '99' => " Cancelamento - cancelado conforme solicitação",
    ];

    /**
     * Roda antes dos metodos de processar
     */
    protected function init()
    {
        $this->totais = [
            'liquidados' => 0,
            'entradas' => 0,
            'baixados' => 0,
            'protestados' => 0,
            'erros' => 0,
            'alterados' => 0,
        ];
    }

    /**
     * @param array $header
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarHeader(array $header)
    {

        $this->getHeader()
            ->setCodigoRegistro($this->rem(1, 1, $header))
            ->setCodigoConvenio($this->rem(3, 22, $header))
            ->setNomeEmpresa($this->rem(23, 42, $header))
            ->setCodigoBanco($this->rem(43, 45, $header))
            ->setNomeBanco($this->rem(46, 65, $header))
            ->setDataArquivo($this->rem(66, 73, $header))
            ->setSequencial($this->rem(74, 79, $header))
            ->setLayout($this->rem(80, 81, $header))
            ->setIdentificacaoServico($this->rem(82, 98, $header));

        return true;
    }

    /**
     * @param array $detalhe
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarDetalhe(array $detalhe)
    {

        $d = $this->detalheAtual();
        $d
            ->setCodigoRegistro($this->rem(1, 1, $detalhe))
            ->setIdentificacaoConveniada($this->rem(2, 26, $detalhe))
            ->setAgencia($this->rem(27, 30, $detalhe))
            ->setIdentificacaoBanco($this->rem(31, 44, $detalhe))
            ->setDataVencimento($this->rem(44, 52, $detalhe))
            ->setValor($this->rem(53, 67, $detalhe))
            ->setCodigoRetorno($this->rem(68, 69, $detalhe))
            ->setUsoEmpresa($this->rem(70, 129, $detalhe))
            ->setIdentificacao($this->rem(131,  145, $detalhe))
            ->setOcorrencia($this->rem(68, 69, $detalhe))
            ->setCodigoMovimento($this->rem(150, 150, $detalhe))
            ->setOcorrenciaDescricao(Arr::get($this->ocorrencias, $this->detalheAtual()->getOcorrencia(), 'Desconhecido'));

        /**
         * ocorrencias
         */
        $msgAdicional = str_split(sprintf('%08s', $this->rem(68, 69, $detalhe)), 2) + array_fill(0, 5, '');

        if ($d->hasOcorrencia('00', '31')) {
            $this->totais['liquidados']++;
            $d->setOcorrenciaTipo($d::DEBIT_SUCCESS);
        } elseif ($d->hasOcorrencia('01', '04', '05', '10', '12', '13', '14', '15', '18', '19', '20', '30', '20')) {
            $this->totais['erros']++;
            $error = Util::appendStrings(Arr::get($this->rejeicoes, $msgAdicional[0], ''), Arr::get($this->rejeicoes, $msgAdicional[1], ''), Arr::get($this->rejeicoes, $msgAdicional[2], ''), Arr::get($this->rejeicoes, $msgAdicional[3], ''), Arr::get($this->rejeicoes, $msgAdicional[4], ''));
            $d->setError($error);
        }

        return true;
    }

    /**
     * @param array $trailer
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarTrailer(array $trailer)
    {
        $this->getTrailer()
            ->setNumeroLote($this->rem(4, 7, $trailer))
            ->setTipoRegistro($this->rem(8, 8, $trailer))
            ->setQtdLotesArquivo((int)$this->rem(18, 23, $trailer))
            ->setQtdRegistroArquivo((int)$this->rem(24, 29, $trailer));

        return true;
    }
}
