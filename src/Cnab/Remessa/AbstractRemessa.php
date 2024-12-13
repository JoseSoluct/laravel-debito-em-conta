<?php
namespace Josea\LaravelDebitoEmConta\Cnab\Remessa;
use Josea\LaravelDebitoEmConta\Util;
use Illuminate\Support\Str;
use Josea\LaravelDebitoEmConta\Exception\ValidationException;
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
    protected $identificacao;

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
     * Função para gerar o cabeçalho do arquivo.
     *
     * @return mixed
     */
    abstract protected function header();
}
