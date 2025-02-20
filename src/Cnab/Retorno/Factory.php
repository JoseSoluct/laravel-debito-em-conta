<?php

namespace Josea\LaravelDebitoEmConta\Cnab\Retorno;

use Josea\LaravelDebitoEmConta\Exception\ValidationException;
use Josea\LaravelDebitoEmConta\Util;

class Factory
{
    /**
     * @param $file
     *
     * @return AbstractRetorno
     * @throws ValidationException
     */
    public static function make($file)
    {
        if (! $file_content = Util::file2array($file)) {
            throw new ValidationException("Arquivo: {$file} não existe" );
        }

        if (! Util::isHeaderRetorno($file_content[0])) {
            throw new ValidationException("Arquivo: $file, não é um arquivo de retorno");
        }

        $instancia = self::getBancoClass($file_content);

        return $instancia->processar();
    }

    /**
     * @param $file_content
     *
     * @return mixed
     * @throws ValidationException
     */
    private static function getBancoClass($file_content)
    {
        $banco = '';
        $namespace = '';
        $banco = mb_substr($file_content[0], 42, 3);
        if (Util::isCnab150($file_content)){
            $namespace .= __NAMESPACE__ . '\\Cnab150\\';
        }
        $bancoClass = $namespace . Util::getBancoClass($banco);

        if (! class_exists($bancoClass)) {
            throw new ValidationException('Banco não possui essa versão de CNAB');
        }

        return new $bancoClass($file_content);
    }
}
