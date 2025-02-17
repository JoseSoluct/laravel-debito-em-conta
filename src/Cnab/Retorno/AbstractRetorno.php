<?php
/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 25/11/2016
 * Time: 07:31
 */

namespace Josea\LaravelDebitoEmConta\Cnab\Retorno;

use Countable;
use Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150\Detalhe as Detalhe150Contract;
use Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150\Header as Header150Contract;
use Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150\Trailer as Trailer150Contract;
use Josea\LaravelDebitoEmConta\Cnab\Exception\ValidationException;
use Illuminate\Support\Collection;
use Josea\LaravelDebitoEmConta\Util;
use OutOfBoundsException;
use ReflectionClass;
use SeekableIterator;

abstract class AbstractRetorno implements Countable, SeekableIterator
{
    /**
     * Se cnab ja foi processado
     *
     * @var bool
     */
    protected $processado = false;

    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco;

    /**
     * Incremento de detalhes
     *
     * @var int
     */
    protected $increment = 0;

    /**
     * Arquivo transformado em array por linha.
     *
     * @var array
     */
    protected $file;

    /**
     * @var Header150Contract
     */
    protected $header;

    /**
     * @var Trailer150Contract
     */
    protected $trailer;

    /**
     * @var Detalhe150Contract[]
     */
    protected $detalhe = [];

    /**
     * Helper de totais.
     *
     * @var array
     */
    protected $totais = [];

    /**
     * @var int
     */
    protected $_position = 1;

    /**
     * @param string $file
     * @throws ValidationException
     */
    public function __construct($file)
    {
        $this->_position = 1;

        if (! $this->file = Util::file2array($file)) {
            throw new ValidationException('Arquivo: não existe');
        }

        $r = new ReflectionClass('\Josea\LaravelDebitoEmConta\Contracts\Debito\Debito');
        $constantNames = $r->getConstants();
        $bancosDisponiveis = [];
        foreach ($constantNames as $constantName => $codigoBanco) {
            if (preg_match('/^COD_BANCO.*/', $constantName)) {
                $bancosDisponiveis[] = $codigoBanco;
            }
        }

        if (! Util::isHeaderRetorno($this->file[0])) {
            throw new ValidationException('Arquivo de retorno inválido');
        }

        $banco = mb_substr($this->file[0], 42, 3);
        if (! in_array($banco, $bancosDisponiveis)) {
            throw new ValidationException(sprintf('Banco: %s, inválido', $banco));
        }
    }

    /**
     * Retorna o código do banco
     *
     * @return string
     */
    public function getCodigoBanco()
    {
        return $this->codigoBanco;
    }

    /**
     * @return string
     */
    public function getBancoNome()
    {
        return Util::$bancos[$this->codigoBanco];
    }

    /**
     * @return mixed
     */
    public function getFileContent()
    {
        return implode(PHP_EOL, $this->file);
    }

    /**
     * @return Collection
     */
    public function getDetalhes()
    {
        return new Collection($this->detalhe);
    }

    /**
     * @param $i
     *
     * @return Detalhe150Contract|null
     */
    public function getDetalhe($i)
    {
        return array_key_exists($i, $this->detalhe) ? $this->detalhe[$i] : null;
    }

    /**
     * @return Header150Contract
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return Trailer150Contract
     */
    public function getTrailer()
    {
        return $this->trailer;
    }

    /**
     * @return array
     */
    public function getTotais()
    {
        return $this->totais;
    }

    /**
     * Retorna o detalhe atual.
     *
     * @return Detalhe150Contract
     */
    protected function detalheAtual()
    {
        return $this->detalhe[$this->increment];
    }

    /**
     * Se está processado
     *
     * @return bool
     */
    protected function isProcessado()
    {
        return $this->processado;
    }

    /**
     * Seta cnab como processado
     *
     * @return $this
     */
    protected function setProcessado()
    {
        $this->processado = true;

        return $this;
    }

    /**
     * Incrementa o detalhe.
     */
    abstract protected function incrementDetalhe();

    /**
     * Processa o arquivo
     *
     * @return $this
     */
    abstract protected function processar();

    /**
     * Remove trecho do array.
     *
     * @param $i
     * @param $f
     * @param $array
     *
     * @return string
     * @throws ValidationException
     */
    protected function rem($i, $f, &$array)
    {
        return Util::remove($i, $f, $array);
    }

    public function current()
    {
        return $this->detalhe[$this->_position];
    }

    public function next()
    {
        $this->_position++;
    }

    public function key()
    {
        return $this->_position;
    }

    public function valid()
    {
        return isset($this->detalhe[$this->_position]);
    }

    public function rewind()
    {
        $this->_position = 1;
    }

    public function count()
    {
        return count($this->detalhe);
    }

    public function seek($offset)
    {
        $this->_position = $offset;
        if (! $this->valid()) {
            throw new OutOfBoundsException('"Posição inválida "$position"');
        }
    }
}
