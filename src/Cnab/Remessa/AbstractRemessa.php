<?php
namespace Josea\LaravelDebitoEmConta\Cnab\Remessa;
use Josea\LaravelDebitoEmConta\Util;
use Illuminate\Support\Str;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;
use Carbon\Carbon;
abstract class AbstractRemessa
{
    const HEADER = 'header';
    const DETALHE = 'detalhe';
    const TRAILER = 'trailer';

    protected $tamanho_linha = false;

    protected $tamanhos_linha = [];

    /**
     * Campos necessários para a remessa
     *
     * @var array
     */
    private $camposObrigatorios = [
        'convenio',
        'nomeempresa',
        'sequencial'
    ];


    protected $registro;
    protected $remessa;
    protected $convenio;
    protected $nomeempresa;
    protected $codbanco;
    protected $nomebanco;
    protected $datageracao;
    protected $sequencial;
    protected $versao;

    protected $identificacaocliente;
    protected $agencia;
    protected $conta;
    protected $identificacaobancaria;
    protected $datavencimento;
    protected $valordebito;
    protected $tipoidentificacao;
    protected $identificacao;
    protected $codigomovimento;

    /**
     * @return mixed
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param mixed $conta
     */
    public function setConta($conta): void
    {
        $this->conta = $conta;
    }

    /**
     * @return mixed
     */
    public function getDatavencimento()
    {
        return $this->datavencimento;
    }

    /**
     * @param mixed $datavencimento
     */
    public function setDatavencimento($datavencimento): void
    {
        $this->datavencimento = $datavencimento;
    }

    /**
     * @return mixed
     */
    public function getValordebito()
    {
        return $this->valordebito;
    }

    /**
     * @param mixed $valordebito
     */
    public function setValordebito($valordebito): void
    {
        $this->valordebito = $valordebito;
    }

    /**
     * @return mixed
     */
    public function getTipoidentificacao()
    {
        return $this->tipoidentificacao;
    }

    /**
     * @param mixed $tipoidentificacao
     */
    public function setTipoidentificacao($tipoidentificacao): void
    {
        $this->tipoidentificacao = $tipoidentificacao;
    }

    /**
     * @return mixed
     */
    public function getCodigomovimento()
    {
        return $this->codigomovimento;
    }

    /**
     * @param mixed $codigomovimento
     */
    public function setCodigomovimento($codigomovimento): void
    {
        $this->codigomovimento = $codigomovimento;
    }

    /**
     * @return mixed
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * @param mixed $agencia
     */
    public function setAgencia($agencia): void
    {
        $this->agencia = $agencia;
    }

    /**
     * @return mixed
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * @param mixed $registro
     */
    public function setRegistro($registro): void
    {
        $this->registro = $registro;
    }

    /**
     * @return mixed
     */
    public function getRemessa()
    {
        return $this->remessa;
    }

    /**
     * @param mixed $remessa
     */
    public function setRemessa($remessa): void
    {
        $this->remessa = $remessa;
    }

    /**
     * @return mixed
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param mixed $convenio
     */
    public function setConvenio($convenio): void
    {
        $this->convenio = $convenio;
    }

    /**
     * @return mixed
     */
    public function getNomeempresa()
    {
        return $this->nomeempresa;
    }

    /**
     * @param mixed $nomeempresa
     */
    public function setNomeempresa($nomeempresa): void
    {
        $this->nomeempresa = $nomeempresa;
    }

    /**
     * @return mixed
     */
    public function getCodbanco()
    {
        return $this->codbanco;
    }

    /**
     * @param mixed $codbanco
     */
    public function setCodbanco($codbanco): void
    {
        $this->codbanco = $codbanco;
    }

    /**
     * @return mixed
     */
    public function getNomebanco()
    {
        return $this->nomebanco;
    }

    /**
     * @param mixed $nomebanco
     */
    public function setNomebanco($nomebanco): void
    {
        $this->nomebanco = $nomebanco;
    }

    /**
     * @return mixed
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param mixed $sequencial
     */
    public function setSequencial($sequencial): void
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return mixed
     */
    public function getVersao()
    {
        return $this->versao;
    }

    /**
     * @param mixed $versao
     */
    public function setVersao($versao): void
    {
        $this->versao = $versao;
    }

    /**
     * @return mixed
     */
    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    /**
     * @param mixed $identificacao
     */
    public function setIdentificacao($identificacao): void
    {
        $this->identificacao = $identificacao;
    }

    /**
     * @return mixed
     */
    public function getIdentificacaocliente()
    {
        return $this->identificacaocliente;
    }

    /**
     * @param mixed $identificacaocliente
     */
    public function setIdentificacaocliente($identificacaocliente): void
    {
        $this->identificacaocliente = $identificacaocliente;
    }

    /**
     * @return mixed
     */
    public function getIdentificacaobancaria()
    {
        return $this->identificacaobancaria;
    }

    /**
     * @param mixed $identificacaobancaria
     */
    public function setIdentificacaobancaria($identificacaobancaria): void
    {
        $this->identificacaobancaria = $identificacaobancaria;
    }



    /**
     * @var array
     */
    protected $debitos = [];

    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco;

    /**
     * Contagem dos registros Detalhes
     *
     * @var int
     */
    protected $iRegistros = 0;

    /**
     * Array contendo o cnab.
     *
     * @var array
     */
    protected $aRegistros = [
        self::HEADER  => [],
        self::DETALHE => [],
        self::TRAILER => [],
    ];

    /**
     * Variável com ponteiro para linha que esta sendo editada.
     *
     * @var
     */
    protected $atual;

    /**
     * Variável com ponteiro para o tamanho da linha que esta sendo editada.
     *
     * @var
     */
    protected $tamanho_atual;

    /**
     * Caractere de fim de linha
     *
     * @var string
     */
    protected $fimLinha = "\n";

    /**
     * Caractere de fim de arquivo
     *
     * @var null
     */
    protected $fimArquivo = null;





    public function __construct($params = [])
    {
        Util::fillClass($this, $params);
    }

    /**
     * @return string
     */
    public function getFimLinha()
    {
        return $this->fimLinha;
    }

    /**
     * Informa a data da remessa a ser gerada
     *
     * @param $data
     */
    public function setDatageracao($data)
    {
        $this->datageracao = $data;
    }

    /**
     * Retorna a data da remessa a ser gerada
     *
     * @param $format
     *
     * @return string;
     */
    public function getDatageracao($format)
    {
        if (is_null($this->datageracao)) {
            return Carbon::now()->format($format);
        }

        return $this->datageracao->format($format);
    }

    /**
     * Adiciona os campos obrigatórios
     *
     * @return $this
     */
    protected function addCampoObrigatorio()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            ! is_array($arg) || call_user_func_array([$this, __FUNCTION__], $arg);
            ! is_string($arg) || array_push($this->camposObrigatorios, $arg);
        }

        return $this;
    }

    /**
     * Função para add valor a linha nas posições informadas.
     *
     * @param int $i
     * @param int $f
     * @param         $value
     *
     * @return array
     * @throws ValidationException
     */
    protected function add($i, $f, $value)
    {
        return Util::adiciona($this->atual, $i, $f, $value, $this->tamanho_atual);
    }

    /**
     * Seta os campos obrigatórios
     *
     * @return $this
     */
    protected function setCamposObrigatorios()
    {
        $args = func_get_args();
        $this->camposObrigatorios = [];
        foreach ($args as $arg) {
            $this->addCampoObrigatorio($arg);
        }

        return $this;
    }

    /**
     * Método que valida se o banco tem todos os campos obrigatórios preenchidos
     *
     * @param $messages
     *
     * @return bool
     */
    public function isValid(&$messages)
    {
        foreach ($this->camposObrigatorios as $campo) {
            $test = call_user_func([$this, 'get' . ucfirst(Str::camel($campo))]);
            if ($test === '' || is_null($test)) {
                $messages .= "Campo $campo está em branco";

                return false;
            }
        }

        return true;
    }

    /**
     * Valida se a linha esta correta.
     *
     * @param array $a
     * @param int $extendido
     *
     * @return string
     * @throws ValidationException
     */
    protected function valida(array $a, $extendido = 0)
    {
        if ($this->tamanho_linha === false) {
            throw new ValidationException('Classe remessa deve informar o tamanho da linha');
        }

        $a = array_filter($a, 'mb_strlen');
        if (count($a) != ($this->tamanho_linha + $extendido)) {
            throw new ValidationException(sprintf('$a não possui %s posições, possui: %s', $this->tamanho_linha, count($a)));
        }

        return implode('', $a);
    }

    /**
     * Retorna o header do arquivo.
     *
     * @return mixed
     */
    protected function getHeader()
    {
        return $this->aRegistros[self::HEADER];
    }

    /**
     * Retorna os detalhes do arquivo
     *
     * @return Collection
     */
    protected function getDetalhes()
    {
        return collect($this->aRegistros[self::DETALHE]);
    }

    /**
     * Retorna o trailer do arquivo.
     *
     * @return mixed
     */
    protected function getTrailer()
    {
        return $this->aRegistros[self::TRAILER];
    }


    /**
     * Função para gerar o cabeçalho do arquivo.
     *
     * @return mixed
     */
    abstract protected function header();
}
