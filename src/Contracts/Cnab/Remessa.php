<?php
namespace Josea\LaravelDebitoEmConta\Contracts\Cnab;
interface Remessa extends Cnab
{
    public function gerar();
}