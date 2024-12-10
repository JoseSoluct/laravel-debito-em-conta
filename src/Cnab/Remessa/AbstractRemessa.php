<?php
namespace Josea\LaravelDebitoEmConta\Cnab\Remessa;
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
        'registro',
        'remessa',
        'convenio',
        'nome_empresa',
        'cod_banco',
        'nome_banco',
        'datageracao',
        'sequencial',
        'versao',
        'identificacao',
    ];

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

    /**
     * Codigo do registro
     *
     * @var string 1 posição
     */
    protected $registro = null;

    /**
     * Código da Remessa
     *
     * @var int 9 posições
     */
    protected $remessa = null;

    /**
     * Código do Convênio
     *
     * @var string 20 posições
     */
    protected $convenio = null;

    /**
     * Nome da empresa
     *
     * @var string 20 posições
     */
    protected $nome_empresa = null;

    /**
     * A data que será informada no header da remessa
     *
     * @var Carbon;
     */
    protected $datageracao = null;

    /**
     * ID do arquivo remessa, sequencial.
     *
     * @var
     */
    protected $sequencial;

    /**
     * Versão do Layout
     *
     * @var
     */
    protected $versao;

    /**
     * Identificação do Serviço
     *
     * @var
     */
    protected $identificacao;

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
        return \Util::adiciona($this->atual, $i, $f, $value, $this->tamanho_atual);
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
     * @return string|null
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * @param string|null $registro
     */
    public function setRegistro($registro)
    {
        $this->registro = $registro;
    }

    /**
     * @return int|null
     */
    public function getRemessa()
    {
        return $this->remessa;
    }

    /**
     * @param int|null $remessa
     */
    public function setRemessa($remessa)
    {
        $this->remessa = $remessa;
    }

    /**
     * @return string|null
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param string|null $convenio
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
    }

    /**
     * @return string|null
     */
    public function getNomeEmpresa()
    {
        return $this->nome_empresa;
    }

    /**
     * @param string|null $nome_empresa
     */
    public function setNomeEmpresa($nome_empresa)
    {
        $this->nome_empresa = $nome_empresa;
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
    public function setSequencial($sequencial)
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
    public function setVersao($versao)
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
    public function setIdentificacao($identificacao)
    {
        $this->identificacao = $identificacao;
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
    public function setDatavencimento($datavencimento)
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
    public function setValordebito($valordebito)
    {
        $this->valordebito = $valordebito;
    }

    /**
     * @return mixed
     */
    public function getCodigomoeda()
    {
        return $this->codigomoeda;
    }

    /**
     * @param mixed $codigomoeda
     */
    public function setCodigomoeda($codigomoeda)
    {
        $this->codigomoeda = $codigomoeda;
    }

    /**
     * @return mixed
     */
    public function getTipoidentificacao()
    {
        if (strlen($this->identificacao) == 15) {
            return '1';
        }
        if (strlen($this->identificacao) == 13) {
            return '2';
        }
    }

    /**
     * @param mixed $tipoidentificacao
     */
    public function setTipoidentificacao($tipoidentificacao)
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
    public function setCodigomovimento($codigomovimento)
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
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
    }


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
    public function setConta($conta)
    {
        $this->conta = $conta;
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