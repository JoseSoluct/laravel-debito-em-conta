<?php
namespace Josea\LaravelDebitoEmConta;
use Illuminate\Support\Str;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;

/**
 * Class Util
 *
 * @TODO validar tamanho nosso numero
 * @TODO validar processar
 * @TODO validar float nos numeros
 */
final class Util
{
    /**
     * @var string[]
     */
    public static $bancos = [
        '246' => 'Banco ABC Brasil S.A.',
        '025' => 'Banco Alfa S.A.',
        '641' => 'Banco Alvorada S.A.',
        '029' => 'Banco Banerj S.A.',
        '000' => 'Banco Bankpar S.A.',
        '740' => 'Banco Barclays S.A.',
        '107' => 'Banco BBM S.A.',
        '077' => 'Banco Inter S.A.',
        '031' => 'Banco Beg S.A.',
        '739' => 'Banco BGN S.A.',
        '096' => 'Banco BM&F de Serviços de Liquidação e Custódia S.A',
        '318' => 'Banco BMG S.A.',
        '752' => 'Banco BNP Paribas Brasil S.A.',
        '248' => 'Banco Boavista Interatlântico S.A.',
        '218' => 'Banco Bonsucesso S.A.',
        '065' => 'Banco Bracce S.A.',
        '036' => 'Banco Bradesco BBI S.A.',
        '204' => 'Banco Bradesco Cartões S.A.',
        '394' => 'Banco Bradesco Financiamentos S.A.',
        '237' => 'Banco Bradesco S.A.',
        '225' => 'Banco Brascan S.A.',
        '208' => 'Banco BTG Pactual S.A.',
        '044' => 'Banco BVA S.A.',
        '263' => 'Banco Cacique S.A.',
        '473' => 'Banco Caixa Geral - Brasil S.A.',
        '040' => 'Banco Cargill S.A.',
        '233' => 'Banco Cifra S.A.',
        '745' => 'Banco Citibank S.A.',
        'M08' => 'Banco Citicard S.A.',
        'M19' => 'Banco CNH Capital S.A.',
        '215' => 'Banco Comercial e de Investimento Sudameris S.A.',
        '756' => 'Banco Cooperativo do Brasil S.A. - BANCOOB',
        '748' => 'Banco Cooperativo Sicredi S.A.',
        '222' => 'Banco Credit Agricole Brasil S.A.',
        '505' => 'Banco Credit Suisse (Brasil) S.A.',
        '229' => 'Banco Cruzeiro do Sul S.A.',
        '003' => 'Banco da Amazônia S.A.',
        '083' => 'Banco da China Brasil S.A.',
        '707' => 'Banco Daycoval S.A.',
        'M06' => 'Banco de Lage Landen Brasil S.A.',
        '024' => 'Banco de Pernambuco S.A. - BANDEPE',
        '456' => 'Banco de Tokyo-Mitsubishi UFJ Brasil S.A.',
        '214' => 'Banco Dibens S.A.',
        '001' => 'Banco do Brasil S.A.',
        '047' => 'Banco do Estado de Sergipe S.A.',
        '037' => 'Banco do Estado do Pará S.A.',
        '041' => 'Banco do Estado do Rio Grande do Sul S.A.',
        '004' => 'Banco do Nordeste do Brasil S.A.',
        '265' => 'Banco Fator S.A.',
        'M03' => 'Banco Fiat S.A.',
        '224' => 'Banco Fibra S.A.',
        '626' => 'Banco Ficsa S.A.',
        'M18' => 'Banco Ford S.A.',
        'M07' => 'Banco GMAC S.A.',
        '612' => 'Banco Guanabara S.A.',
        'M22' => 'Banco Honda S.A.',
        '063' => 'Banco Ibi S.A. Banco Múltiplo',
        'M11' => 'Banco IBM S.A.',
        '604' => 'Banco Industrial do Brasil S.A.',
        '320' => 'Banco Industrial e Comercial S.A.',
        '653' => 'Banco Indusval S.A.',
        '249' => 'Banco Investcred Unibanco S.A.',
        '184' => 'Banco Itaú BBA S.A.',
        '479' => 'Banco ItaúBank S.A',
        'M09' => 'Banco Itaucred Financiamentos S.A.',
        '376' => 'Banco J. P. Morgan S.A.',
        '074' => 'Banco J. 074 S.A.',
        '217' => 'Banco John Deere S.A.',
        '600' => 'Banco Luso Brasileiro S.A.',
        '389' => 'Banco Mercantil do Brasil S.A.',
        '746' => 'Banco Modal S.A.',
        '045' => 'Banco Opportunity S.A.',
        '079' => 'Banco Original do Agronegócio S.A.',
        '623' => 'Banco Panamericano S.A.',
        '611' => 'Banco Paulista S.A.',
        '643' => 'Banco Pine S.A.',
        '638' => 'Banco Prosper S.A.',
        '747' => 'Banco Rabobank International Brasil S.A.',
        '356' => 'Banco Real S.A.',
        '633' => 'Banco Rendimento S.A.',
        'M16' => 'Banco Rodobens S.A.',
        '072' => 'Banco Rural Mais S.A.',
        '453' => 'Banco Rural S.A.',
        '422' => 'Banco 422 S.A.',
        '033' => 'Banco Santander (Brasil) S.A.',
        '749' => 'Banco Simples S.A.',
        '366' => 'Banco Société Générale Brasil S.A.',
        '637' => 'Banco Sofisa S.A.',
        '012' => 'Banco Standard de Investimentos S.A.',
        '464' => 'Banco Sumitomo Mitsui Brasileiro S.A.',
        '082' => 'Banco Topázio S.A.',
        'M20' => 'Banco Toyota do Brasil S.A.',
        '634' => 'Banco Triângulo S.A.',
        '136' => 'Banco Unicred do Brasil',
        'M14' => 'Banco Volkswagen S.A.',
        'M23' => 'Banco Volvo (Brasil) S.A.',
        '655' => 'Banco Votorantim S.A.',
        '610' => 'Banco VR S.A.',
        '119' => 'Banco Western Union do Brasil S.A.',
        '370' => 'Banco WestLB do Brasil S.A.',
        '021' => 'BANESTES S.A. Banco do Estado do Espírito Santo',
        '719' => 'Banif-Banco Internacional do Funchal (Brasil)S.A.',
        '755' => 'Bank of America Merrill Lynch Banco Múltiplo S.A.',
        '073' => 'BB Banco Popular do Brasil S.A.',
        '250' => 'BCV - Banco de Crédito e Varejo S.A.',
        '078' => 'BES Investimento do Brasil S.A.-Banco de Investimento',
        '069' => 'BPN Brasil Banco Múltiplo S.A.',
        '070' => 'BRB - Banco de Brasília S.A.',
        '104' => 'Caixa Econômica Federal',
        '477' => 'Citibank S.A.',
        '133' => 'Cresol',
        '081' => 'Concórdia Banco S.A.',
        '487' => 'Deutsche Bank S.A. - Banco Alemão',
        '064' => 'Goldman Sachs do Brasil Banco Múltiplo S.A.',
        '062' => 'Hipercard Banco Múltiplo S.A.',
        '399' => 'HSBC Bank Brasil S.A.',
        '492' => 'ING Bank N.V.',
        '652' => 'Itaú Unibanco Holding S.A.',
        '341' => 'Itaú Unibanco S.A.',
        '435' => 'Delcred SCD S.A',
        '488' => 'JPMorgan Chase Bank',
        '751' => 'Scotiabank Brasil S.A. Banco Múltiplo',
        '409' => 'UNIBANCO - União de Bancos Brasileiros S.A.',
        '230' => 'Unicard Banco Múltiplo S.A.',
        '712' => 'Banco Ourinvest',
        '085' => 'AILOS - Sistema de Cooperativa de Crédito',
        'XXX' => 'Desconhecido',
    ];
    /**
     * @param object $obj
     * @param array $params
     */
    public static function fillClass(&$obj, array $params)
    {
        foreach ($params as $param => $value) {
            $param = Str::camel($param);
            if (method_exists($obj, 'getProtectedFields') && in_array(lcfirst($param), $obj->getProtectedFields())) {
                continue;
            }
            if (method_exists($obj, 'set' . Str::camel($param))) {
                $obj->{'set' . Str::camel($param)}($value);
            }
        }
    }

    /**
     * @param        $tipo
     * @param        $valor
     * @param int $tamanho
     * @param int $dec
     * @param string $sFill
     *
     * @return string
     * @throws ValidationException
     */
    public static function formatCnab($tipo, $valor, $tamanho, $dec = 0, $sFill = '')
    {
        $tipo = self::upper($tipo);
        $valor = self::upper(self::normalizeChars($valor));
        if (in_array($tipo, ['9', 9, 'N', '9L', 'NL'])) {
            if ($tipo == '9L' || $tipo == 'NL') {
                $valor = self::onlyNumbers($valor);
            }
            $left = '';
            $sFill = 0;
            $type = 's';
            $valor = ($dec > 0) ? sprintf("%.{$dec}f", $valor) : $valor;
            $valor = str_replace([',', '.'], '', $valor);
        } elseif (in_array($tipo, ['A', 'X', 'Z'])) { // Adiciona 'x' como uma condição válida
            $left = '-';
            $type = 's';
        } else {
            throw new ValidationException('Tipo inválido');
        }

        // Verifica se o tipo é 'x' minúsculo e então retorna a string em minúsculas
        if ($tipo === 'Z') {
            return strtolower(sprintf("%$left$sFill$tamanho$type", mb_substr($valor, 0, $tamanho)));
        } else {
            return sprintf("%$left$sFill$tamanho$type", mb_substr($valor, 0, $tamanho));
        }
    }

    /**
     * Retorna somente os digitos da string
     *
     * @param string $string
     *
     * @return string
     */
    public static function onlyNumbers($string): string
    {
        return self::numbersOnly($string);
    }
    /**
     * Retorna somente os digitos da string
     *
     * @param string $string
     *
     * @return string
     */
    public static function numbersOnly($string): string
    {
        return preg_replace('/[^[:digit:]]/', '', $string);
    }

    /**
     * Retorna a String em MAIUSCULO
     *
     * @param string $string
     *
     * @return string
     */
    public static function upper($string)
    {
        return strtr(mb_strtoupper($string), 'àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ', 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß');
    }

    /**
     * Função para add valor a linha nas posições informadas.
     *
     * @param $line
     * @param int $i
     * @param int $f
     * @param $value
     * @param int $tamanhoLinha
     * @return array
     * @throws ValidationException
     */
    public static function adiciona(&$line, $i, $f, $value, $tamanhoLinha = 150)
    {
        $i--;

        if ($f > $tamanhoLinha) {
            throw new ValidationException('$ini ou $fim ultrapassam o limite máximo de ' . $tamanhoLinha);
        }

        if ($f < $i) {
            throw new ValidationException('$ini é maior que o $fim');
        }

        $t = $f - $i;

        if (mb_strlen($value) > $t) {
            throw new ValidationException(sprintf('String $valor maior que o tamanho definido em $ini e $fim: $valor=%s e tamanho é de: %s', mb_strlen($value), $t));
        }

        $value = sprintf("%{$t}s", $value);
        $value = preg_split('//u', $value, -1, PREG_SPLIT_NO_EMPTY) + array_fill(0, $t, '');

        return array_splice($line, $i, $t, $value);
    }

    /**
     * Retorna a String em minusculo
     *
     * @param string $string
     *
     * @return string
     */
    public static function lower($string)
    {
        return strtr(mb_strtolower($string), 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß', 'àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ');
    }

    /**
     * Função para limpar acentos de uma string
     *
     * @param string $string
     *
     * @return string
     */
    public static function normalizeChars($string)
    {
        $normalizeChars = [
            'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Ä' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'Eth',
            'Ñ' => 'N', 'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',

            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'å' => 'a', 'ä' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'eth',
            'ñ' => 'n', 'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ŕ' => 'r', 'ÿ' => 'y',

            'ß' => 'sz', 'þ' => 'thorn', 'º' => '', 'ª' => '', '°' => '',
        ];

        return preg_replace('/[^0-9a-zA-Z !+=*\-,.;:%@_]/', '', strtr($string, $normalizeChars));
    }
}
