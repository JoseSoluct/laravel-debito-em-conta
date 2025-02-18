<?php

namespace Josea\LaravelDebitoEmConta\Contracts\Cnab\Retorno;
interface Detalhe
{
    public const DEBIT_SUCCESS = '00';
    public const INSUFFICIENT_FUNDS = '01';
    public const ACCOUNT_NOT_REGISTERED = '02';
    public const OTHER_RESTRICTIONS = '04';
    public const LIMIT_EXCEEDED = '05';
    public const AGENCY_CLOSING = '10';
    public const INVALID_AMOUNT = '12';
    public const INVALID_DATE = '13';
    public const INVALID_AGENCY = '14';
    public const INVALID_ACCOUNT = '15';
    public const DEBIT_BEFORE_PROCESSING = '18';
    public const CPF_CNPJ_MISMATCH = '19';
    public const NON_SOLIDARY_JOINT_ACCOUNT = '20';
    public const NO_AUTO_DEBIT_CONTRACT = '30';
    public const DEBIT_PROCESSED_DIFFERENT_DATE = '31';
    public const RECORD_MAINTENANCE = '96';
    public const CANCELLATION_NOT_FOUND = '97';
    public const CANCELLATION_TIME_EXPIRED = '98';
    public const CANCELLATION_CONFIRMED = '99';

    public const OCORRENCIA_ERRO = "9";

    public function getCodigoRegistro();
    public function getIdentificacaoConveniada();
    public function getAgencia();
    public function getIdentificacaoBanco();
    public function getDataVencimento();
    public function getValor();
    public function getCodigoRetorno();
    public function getUsoEmpresa();
    public function getIdentificacao();
    public function getCodigoMovimento();
    public function getError();

    public function hasError(): bool;

    public function hasOcorrencia(): bool;

    public function toArray(): array;
}
