<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150;

use Illuminate\Support\Collection;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;
use Josea\LaravelDebitoEmConta\Cnab\Retorno\AbstractRetorno as AbstractRetornoGeneric;

/**
 * Class AbstractRetorno
 *
 * @method  Detalhe[] getDetalhes()
 * @method  Detalhe getDetalhe($i)
 * @method  Header getHeader()
 * @method  Trailer getTrailer()
 * @method  Detalhe detalheAtual()
 */
abstract class AbstractRetorno extends AbstractRetornoGeneric
{
    /**
     * @param string $file
     * @throws ValidationException
     */
    public function __construct($file)
    {
        parent::__construct($file);

        $this->header = new Header();
        $this->trailer = new Trailer();
    }

    /**
     * @param array $header
     *
     * @return bool
     */
    abstract protected function processarHeader(array $header);

    /**
     * @param array $detalhe
     *
     * @return bool
     */
    abstract protected function processarDetalhe(array $detalhe);

    /**
     * @param array $trailer
     *
     * @return bool
     */
    abstract protected function processarTrailer(array $trailer);

    /**
     * Incrementa o detalhe.
     */
    protected function incrementDetalhe()
    {
        $this->increment++;
        $detalhe = new Detalhe();
        $this->detalhe[$this->increment] = $detalhe;
    }

    /**
     * Processa o arquivo
     *
     * @return $this
     * @throws ValidationException
     */
    public function processar()
    {
        if ($this->isProcessado()) {
            return $this;
        }

        if (method_exists($this, 'init')) {
            call_user_func([$this, 'init']);
        }

        foreach ($this->file as $linha) {
            $recordType = $this->rem(1, 1, $linha);

            if ($recordType == 'A') {
                $this->processarHeader($linha);
            } elseif ($recordType == 'F') {
                $this->incrementDetalhe();
                if ($this->processarDetalhe($linha) === false) {
                    unset($this->detalhe[$this->increment]);
                    $this->increment--;
                }
            } elseif ($recordType == 'Z') {
                $this->processarTrailer($linha);
            }
        }

        if (method_exists($this, 'finalize')) {
            call_user_func([$this, 'finalize']);
        }

        return $this->setProcessado();
    }

    /**
     * Retorna o array.
     *
     * @return array
     */
    public function toArray()
    {
        $array = [
            'header'      => $this->header->toArray(),
            'trailer'     => $this->trailer->toArray(),
            'detalhes'    => new Collection(),
        ];

        foreach ($this->detalhe as $detalhe) {
            $array['detalhes']->push($detalhe->toArray());
        }

        return $array;
    }

    protected function getSegmentType($line)
    {
        return strtoupper($this->rem(14, 14, $line));
    }
}
