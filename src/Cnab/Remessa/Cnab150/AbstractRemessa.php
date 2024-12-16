<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Remessa\Cnab150;
use \Josea\LaravelDebitoEmConta\Cnab\Remessa\AbstractRemessa as BaseRemessa;
use Illuminate\Support\Str;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;

abstract class AbstractRemessa extends BaseRemessa
{
    protected $tamanho_linha = 150;

    /**
     * Caractere de fim de linha
     *
     * @var string
     */
    protected $fimLinha = "\r\n";

    /**
     * Caractere de fim de arquivo
     *
     * @var null
     */
    protected $fimArquivo = "\r\n";

    /**
     * Array contendo o cnab.
     *
     * @var array
     */
    protected $aRegistros = [
        self::HEADER       => [],
        self::DETALHE      => [],
        self::TRAILER      => [],
    ];

    /**
     * Inicia a edição do header
     */
    protected function iniciaHeader()
    {
        $this->aRegistros[self::HEADER] = array_fill(0, $this->tamanho_linha, ' ');
        $this->tamanhos_linha[self::HEADER] = $this->tamanho_linha;
        $this->atual = &$this->aRegistros[self::HEADER];
        $this->tamanho_atual = &$this->tamanhos_linha[self::HEADER];
    }

    /**
     * Inicia a edição do trailer (footer).
     */
    protected function iniciaTrailer()
    {
        $this->aRegistros[self::TRAILER] = array_fill(0, $this->tamanho_linha, ' ');
        $this->tamanhos_linha[self::TRAILER] = $this->tamanho_linha;
        $this->atual = &$this->aRegistros[self::TRAILER];
        $this->tamanho_atual = &$this->tamanhos_linha[self::TRAILER];
    }

    /**
     * Inicia uma nova linha de detalhe e marca com a atual de edição
     */
    protected function iniciaDetalhe()
    {
        $this->iRegistros++;
        $this->aRegistros[self::DETALHE][$this->iRegistros] = array_fill(0, $this->tamanho_linha, ' ');
        $this->tamanhos_linha[self::DETALHE][$this->iRegistros] = $this->tamanho_linha;
        $this->atual = &$this->aRegistros[self::DETALHE][$this->iRegistros];
        $this->tamanho_atual = &$this->tamanhos_linha[self::DETALHE][$this->iRegistros];
    }

    /**
     * Função que mostra a quantidade de linhas do arquivo.
     *
     * @return int
     */
    protected function getCountDetalhes()
    {
        return count($this->aRegistros[self::DETALHE]);
    }

    /**
     * Função que mostra a quantidade de linhas do arquivo.
     *
     * @return int
     */
    protected function getCount()
    {
        return $this->getCountDetalhes() + 4;
    }
    /**
     * Função que soma o valor total a ser debitado
     *
     * @return int
     */
    protected function getTotal()
    {
        $total = 0;
        foreach ($this->aRegistros[self::DETALHE] as $i => $registro) {
//            $total += $registro['valordebito'];
            $total = 10;
        }
        return $total;
    }

    /**
     * Gera o arquivo, retorna a string.
     *
     * @return string
     * @throws ValidationException
     */
    public function gerar()
    {
        if (! $this->isValid($messages)) {
            throw new ValidationException('Campos requeridos pelo banco, aparentam estar ausentes ' . $messages);
        }

        $stringRemessa = '';
        if ($this->iRegistros < 1) {
            throw new ValidationException('Nenhuma linha detalhe foi adicionada');
        }

        $this->header();
        $stringRemessa .= $this->valida($this->getHeader()) . $this->fimLinha;

        foreach ($this->getDetalhes() as $detalhe) {
            $stringRemessa .= $this->valida($detalhe) . $this->fimLinha;
        }

        $this->trailer();
        $stringRemessa .= $this->valida($this->getTrailer()) . $this->fimArquivo;

        return Encoding::toUTF8($stringRemessa);
    }
}
