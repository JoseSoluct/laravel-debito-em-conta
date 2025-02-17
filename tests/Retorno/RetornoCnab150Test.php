<?php

namespace Retorno;

use Illuminate\Support\Collection;
use Josea\LaravelDebitoEmConta\Cnab\Retorno\Cnab150\Detalhe;
use Josea\LaravelDebitoEmConta\Cnab\Retorno\Factory;
use Josea\LaravelDebitoEmConta\Tests\TestCase;

class RetornoCnab150Test extends TestCase
{
    public function testRetornoSicrediCnab150()
    {
        $retorno = Factory::make(__DIR__ . '/files/sicredi.ret');
        $retorno->processar();
        $this->assertNotNull($retorno->getHeader());
        $this->assertNotNull($retorno->getDetalhes());
        $this->assertNotNull($retorno->getTrailer());
        $this->assertEquals('748', $retorno->getCodigoBanco());

        $this->assertInstanceOf(Collection::class, $retorno->getDetalhes());
        if (count($retorno->getDetalhes()) > 0 ) {
            $this->assertInstanceOf(Detalhe::class, $retorno->getDetalhe(1));
            foreach ($retorno->getDetalhes() as $detalhe) {
                $this->assertInstanceOf(Detalhe::class, $detalhe);
                $this->assertArrayHasKey('valor', $detalhe->toArray());
            }
        }else{

        }

    }
}
