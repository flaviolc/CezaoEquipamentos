<?php

namespace Core\Test;

use Zend\Db\Adapter\Adapter;
use Core\Db\TableGateway;
use Zend\Mvc\Application;
use Zend\Di\Di;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Mvc\MvcEvent;

abstract class TestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * @var Zend\Mvc\Application
     */
    protected $application;

    /**
     * @var Zend\Di\Di
     */
    protected $di;

    public function setup() {
        parent::setup();

        $config = include 'config/application.config.php';
        $config['module_listener_options']['config_static_paths'] = array(getcwd() . '/config/test.config.php');




        if (file_exists(__DIR__ . '/config/test.config.php')) {
            $moduleConfig = include __DIR__ . '/config/test.config.php';
            array_unshift($config['module_listener_options']['config_static_paths'], $moduleConfig);
        }

        $this->serviceManager = new ServiceManager(new ServiceManagerConfig(
                isset($config['service_manager']) ? $config['service_manager'] : array()
        ));
        $this->serviceManager->setService('ApplicationConfig', $config);
        $this->serviceManager->setFactory('ServiceListener', 'Zend\Mvc\Service\ServiceListenerFactory');

        $moduleManager = $this->serviceManager->get('ModuleManager');
        $moduleManager->loadModules();
        $this->routes = array();
        foreach ($moduleManager->getModules() as $m) {
            if ($m != 'ZendDeveloperTools' && $m != 'DoctrineModule' && $m != 'DoctrineORMModule')
                $moduleConfig = include __DIR__ . '/../../../../' . ucfirst($m) . '/config/module.config.php';

            if (isset($moduleConfig['router'])) {
                foreach ($moduleConfig['router']['routes'] as $key => $name) {
                    $this->routes[$key] = $name;
                }
            }
        }
        $this->serviceManager->setAllowOverride(true);

        $this->application = $this->serviceManager->get('Application');
        $this->event = new MvcEvent();
        $this->event->setTarget($this->application);

        $this->event->setApplication($this->application)
                ->setRequest($this->application->getRequest())
                ->setResponse($this->application->getResponse());
        //        ->setRouter($this->serviceManager->get('Router'));

        $this->createDatabase();
    }

    public function tearDown() {
        parent::tearDown();
        $this->dropDatabase();
    }

    /**
     * Retrieve TableGateway
     * 
     * @param  string $table
     * @return TableGateway
     */
    protected function getTable($table) {
        $sm = $this->serviceManager;
        $dbAdapter = $sm->get('DbAdapter');
        $schema = new $table;
        $schema = $schema->schemaName;

        $tableGateway = new TableGateway($dbAdapter, $table, new $table, $schema);
        $tableGateway->initialize();

        return $tableGateway;
    }

    /**
     * Retrieve EntityManager
     * 
     * @return Doctrine\ORM\EntityManager
     */
    public function getObjectManager() {
        $objectManager = $this->getService('Doctrine\ORM\EntityManager');
        return $objectManager;
    }

    /**
     * Retrieve Service
     *
     * @param  string $service
     * @return Service
     */
    protected function getService($service) {
        return $this->serviceManager->get($service);
    }

    /**
     * @return void
     */
    public function createDatabase() {
        $dbAdapter = $this->getAdapter();

        if (get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Sqlite') {
            //enable foreign keys on sqlite
            $dbAdapter->query('PRAGMA foreign_keys = ON;', Adapter::QUERY_MODE_EXECUTE);
        }

        if (get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Mysql') {
            //enable foreign keys on mysql
            $dbAdapter->query('SET FOREIGN_KEY_CHECKS = 1;', Adapter::QUERY_MODE_EXECUTE);
        }

        $queries = include \Bootstrap::getModulePath() . '/data/test.data.php';
        foreach ($queries as $query) {
            $dbAdapter->query($query['create'], Adapter::QUERY_MODE_EXECUTE);
        }
    }

    /**
     * @return void
     */
    public function dropDatabase() {
        $dbAdapter = $this->getAdapter();

        if (get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Sqlite') {
            //disable foreign keys on sqlite
            $dbAdapter->query('PRAGMA foreign_keys = OFF;', Adapter::QUERY_MODE_EXECUTE);
        }
        if (get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Mysql') {
            //disable foreign keys on mysql
            $dbAdapter->query('SET FOREIGN_KEY_CHECKS = 0;', Adapter::QUERY_MODE_EXECUTE);
        }

        $queries = include \Bootstrap::getModulePath() . '/data/test.data.php';
        foreach ($queries as $query) {
            $dbAdapter->query($query['drop'], Adapter::QUERY_MODE_EXECUTE);
        }
    }

    /**
     * 
     * @return Zend\Db\Adapter\Adapter
     */
    public function getAdapter() {
        return $this->serviceManager->get('DbAdapter');
    }

    /**
     * Alimenta o banco de dados
     */
    

    public function aprovador($Pessoa = null) {
        if (!$Pessoa) {
            $Pessoa = new \Estagios\Model\Pessoa();
            $Pessoa->setEmail('maria@uno.edu.br');
            $Pessoa->setMatricula(987456321);
            $Pessoa->setNome('Maria');
            $Pessoa->setTelefone('879898');
        }
        $this->getObjectManager()->persist($Pessoa);
        $Aprovador = new \Estagios\Model\Aprovador();
        $Aprovador->setId($Pessoa->getId());
        $Aprovador->setPessoa($Pessoa);
        $this->getObjectManager()->persist($Aprovador);
        $this->getObjectManager()->flush();
        return $Aprovador;
    }

    public function aprovacaoVaga($aprovador = null, $vaga = null, $statusAprovacao = null, $motivoIndeferido = null) {
        if (!$aprovador)
            $aprovador = $this->aprovador();
        if (!$vaga)
            $vaga = $this->vaga();
        if (!$statusAprovacao)
            $statusAprovacao = $this->statusAprovacao();
        if (!$motivoIndeferido)
            $motivoIndeferido = null;
        $AprovacaoVaga = new \Estagios\Model\AprovacaoVaga();
        $AprovacaoVaga->setAprovador($aprovador);
        $AprovacaoVaga->setDataAprovacao(new \DateTime('now'));
        $AprovacaoVaga->setVaga($vaga);
        $AprovacaoVaga->setStatusAprovacao($statusAprovacao);
        $AprovacaoVaga->setMotivoIndeferido($motivoIndeferido);

        return $AprovacaoVaga;
    }

    public function aprovadorSetorSolicitante($aprovador = null, $setorSolicitante = null, $nivelAprovacao = null) {
        if (!$aprovador)
            $aprovador = $this->aprovador();
        if (!$setorSolicitante)
            $setorSolicitante = $this->setorSolicitante($this->setor(), $this->solicitante());
        if (!$nivelAprovacao)
            $nivelAprovacao = $this->nivelAprovacao();

        $AprovadorSetorSolicitante = new \Estagios\Model\AprovadorSetorSolicitante();
        $AprovadorSetorSolicitante->setAprovador($aprovador);
        $AprovadorSetorSolicitante->setNivelAprovacao($nivelAprovacao);
        $AprovadorSetorSolicitante->setSetorSolicitante($setorSolicitante);
        $this->getObjectManager()->persist($AprovadorSetorSolicitante);
        return $AprovadorSetorSolicitante;
    }

    public function contaGerencial($codigoReduzido = null, $descGerencial = null) {
        if (!$codigoReduzido)
            $codigoReduzido = '234234';
        if (!$descGerencial)
            $descGerencial = '8923489';
        $ContaGerencial = new \Estagios\Model\ContaGerencial();
        $ContaGerencial->setCodigoReduzido($codigoReduzido);
        $ContaGerencial->setDescGerencial($descGerencial);
        $this->getObjectManager()->persist($ContaGerencial);
        return $ContaGerencial;
    }

    public function cursos($curso = null) {
        if (!$curso)
            $curso = 'Sistemas de Informação';
        $Curso = new \Estagios\Model\Curso();
        $Curso->setDescricao_curso('Sistemas de Informação');
        $this->getObjectManager()->persist($Curso);
        return $Curso;
    }

    public function cursosVagaCaracteristica($curso = null, $periodo = null, $vagaCaracteristica = null) {
        if (!$curso)
            $curso = $this->cursos();
        if (!$periodo)
            $periodo = 3;
        if (!$vagaCaracteristica)
            $vagaCaracteristica = $this->vagaCaracteristica();

        $CursosVagaCaracteristica = new \Estagios\Model\CursosVagaCaracteristica();
        $CursosVagaCaracteristica->setCurso($curso);
        $CursosVagaCaracteristica->setPeriodoCurso($periodo);
        $CursosVagaCaracteristica->setVagaCaracteristica($vagaCaracteristica);
        return $CursosVagaCaracteristica;
    }

    public function editalVaga($dataAbertura = null, $dataFechamento = null, $publicado = null, $bolsa = null) {
        if (!$dataAbertura)
            $dataAbertura = new \DateTime('now');
        if (!$dataFechamento)
            $dataFechamento = new \DateTime('now');
        if (!$publicado)
            $publicado = 0;
        if (!$bolsa)
            $bolsa = 300.50;

        $EditalVaga = new \Estagios\Model\EditalVaga();
        $EditalVaga->setBolsa($bolsa);
        $EditalVaga->setDataAberturaEdital($dataAbertura);
        $EditalVaga->setDataFechamentoEdital($dataFechamento);
        $EditalVaga->setPublicado($publicado);
        $EditalVaga->setDataDeVinculo(new \DateTime('now'));
        $this->getObjectManager()->persist($EditalVaga);

        return $EditalVaga;
    }

    public function estagiario($Pessoa = null) {
        if (!$Pessoa) {
            $Pessoa = new \Estagios\Model\Pessoa();
            $Pessoa->setEmail('half@uno.edu.br');
            $Pessoa->setMatricula(9874561);
            $Pessoa->setNome('HALF');
            $Pessoa->setTelefone('879877');
        }
        
        $this->getObjectManager()->persist($Pessoa);
        $Estagiario = new \Estagios\Model\Estagiario();
        $Estagiario->setId($Pessoa->getId());
        $Estagiario->setPessoa($Pessoa);
        $this->getObjectManager()->persist($Estagiario);
        
        return $Estagiario;
    }

    public function estagiarioVagaEdital($estagiario = null, $vaga_edital = null, $tipo_classificacao = null) {
        if (!$estagiario)
            $estagiario = $this->estagiario();
        if (!$vaga_edital)
            $vaga_edital = $this->vinculoVagaEdital();
        if (!$tipo_classificacao)
            $tipo_classificacao = $this->tipoClassificacaoEstagio();

        $EstagiarioVagaEdital = new \Estagios\Model\EstagiarioVagaEdital();
        $EstagiarioVagaEdital->setDataVinculo(new \DateTime('now'));
        $EstagiarioVagaEdital->setEstagiario($estagiario);
        $EstagiarioVagaEdital->setTipoClassificacaoEstagio($tipo_classificacao);
        $EstagiarioVagaEdital->setVagaEdital($vaga_edital);
        $this->getObjectManager()->persist($EstagiarioVagaEdital);
        return $EstagiarioVagaEdital;
    }

    public function formacao($formacao = null) {
        if (!$formacao)
            $formacao = 'Engenharia da Produção';
        $Formacao = new \Estagios\Model\Formacao();
        $Formacao->setDescFormacao('Engenharia da Produção');
        $this->getObjectManager()->persist($Formacao);
        return $Formacao;
    }

    public function motivoRecisao($desc_motivo = null) {
        if (!$desc_motivo)
            $desc_motivo = 'Foi empregado';

        $MotivoRecisao = new \Estagios\Model\MotivoRecisao();
        $MotivoRecisao->setDesc_motivo_recisao($desc_motivo);

        $this->getObjectManager()->persist($MotivoRecisao);
        return $MotivoRecisao;
    }

    public function nivelAprovacao() {
        $NivelAprovacao = new \Estagios\Model\NivelAprovacao();
        $NivelAprovacao->setDescNivel('APROVADOR NÍVEL 1');
        $this->getObjectManager()->persist($NivelAprovacao);
        $NivelAprovacao = new \Estagios\Model\NivelAprovacao();
        $NivelAprovacao->setDescNivel('APROVADOR NÍVEL 2');
        $this->getObjectManager()->persist($NivelAprovacao);
        $NivelAprovacao = new \Estagios\Model\NivelAprovacao();
        $NivelAprovacao->setDescNivel('APROVADOR NÍVEL 3');
        $this->getObjectManager()->persist($NivelAprovacao);

        return $NivelAprovacao;
    }

    public function pessoa($nome, $email, $telefone, $matricula) {
        $Pessoa = new \Estagios\Model\Pessoa();
        $Pessoa->setEmail($email);
        $Pessoa->setMatricula($matricula);
        $Pessoa->setNome($nome);
        $Pessoa->setTelefone($telefone);
        $this->getObjectManager()->persist($Pessoa);        
        return $Pessoa;
    }

    public function setor($setor = null) {
        if (!$setor)
            $setor = 'ACEA';
        $Setor = new \Estagios\Model\SetorAreaVaga();
        $Setor->setNomeSetor($setor);
        $this->getObjectManager()->persist($Setor);
        return $Setor;
    }

    public function setorSolicitante($setorAreaVaga = null, $Solicitante = null) {
        if (!$setorAreaVaga)
            $setorAreaVaga = $this->setor();
        if (!$Solicitante)
            $Solicitante = $this->solicitante();

        $SetorSolicitante = new \Estagios\Model\SetorSolicitante();
        $SetorSolicitante->setDataVinculo(new \DateTime('now'));
        $SetorSolicitante->setSetorAreaVaga($setorAreaVaga);
        $SetorSolicitante->setSolicitante($Solicitante);
        $SetorSolicitante->setUsuarioVinculo($this->aprovador());
        return $SetorSolicitante;
    }

    public function solicitante($Pessoa = null) {
        if (!$Pessoa) {
            $Pessoa = new \Estagios\Model\Pessoa();
            $Pessoa->setEmail('joao@uno.edu.br');
            $Pessoa->setMatricula(2008185678);
            $Pessoa->setNome('João');
            $Pessoa->setTelefone('342342342');
        }
        $this->getObjectManager()->persist($Pessoa);
        $Solicitante = new \Estagios\Model\Solicitante();
        $Solicitante->setId($Pessoa->getId());
        $Solicitante->setPessoa($Pessoa);
        $this->getObjectManager()->persist($Solicitante);
        return $Solicitante;
    }

    public function statusAprovacao() {
        $StatusAprovacao = new \Estagios\Model\StatusAprovacao();
        $StatusAprovacao->setDescStatus('DEFERIDO NÍVEL 1');
        $this->getObjectManager()->persist($StatusAprovacao);

        $StatusAprovacao = new \Estagios\Model\StatusAprovacao();
        $StatusAprovacao->setDescStatus('INDEFERIDO NÍVEL 1');
        $this->getObjectManager()->persist($StatusAprovacao);

        $StatusAprovacao = new \Estagios\Model\StatusAprovacao();
        $StatusAprovacao->setDescStatus('DEFERIDO NÍVEL 2');
        $this->getObjectManager()->persist($StatusAprovacao);

        $StatusAprovacao = new \Estagios\Model\StatusAprovacao();
        $StatusAprovacao->setDescStatus('INDEFERIDO NÍVEL 2');
        $this->getObjectManager()->persist($StatusAprovacao);

        $StatusAprovacao = new \Estagios\Model\StatusAprovacao();
        $StatusAprovacao->setDescStatus('DEFERIDO NÍVEL 3');
        $this->getObjectManager()->persist($StatusAprovacao);

        $StatusAprovacao = new \Estagios\Model\StatusAprovacao();
        $StatusAprovacao->setDescStatus('INDEFERIDO NÍVEL 3');
        $this->getObjectManager()->persist($StatusAprovacao);

        return $StatusAprovacao;
    }

    public function supervisor($Pessoa = null) {
        if (!$Pessoa) {
            $Pessoa = new \Estagios\Model\Pessoa();
            $Pessoa->setEmail('jorge@uno.edu.br');
            $Pessoa->setMatricula(123456789);
            $Pessoa->setNome('Jorge');
            $Pessoa->setTelefone('456546546');
        }
        $this->getObjectManager()->persist($Pessoa);
        $Supervisor = new \Estagios\Model\Supervisor();
        $Supervisor->setId($Pessoa->getId());
        $Supervisor->setPessoa($Pessoa);
        $Supervisor->setFormacao($this->formacao());
        $this->getObjectManager()->persist($Supervisor);
        return $Supervisor;
    }

    public function termoCompromissoEstagio($estagiarioVagaEdital = null, $dataInicioEstagio = null, $dataHoraEntrevista = null, $dataEmissaoTc = null, $observacao = null) {
        if (!$estagiarioVagaEdital)
            $estagiarioVagaEdital = $this->estagiarioVagaEdital();
        if (!$dataInicioEstagio)
            $dataInicioEstagio = new \DateTime('now');
        if (!$dataHoraEntrevista)
            $dataHoraEntrevista = new \DateTime('now');
        if (!$dataEmissaoTc)
            $dataEmissaoTc = new \DateTime('now');
        if (!$observacao)
            $observacao = 'Nada a declarar';

        $TermoCompromisso = new \Estagios\Model\TermoCompromissoEstagio();
        $TermoCompromisso->setDataEmissaoDoTc($dataEmissaoTc);
        $TermoCompromisso->setDataHoraEntrevista($dataHoraEntrevista);
        $TermoCompromisso->setDataInicioDoEstagio($dataInicioEstagio);
        $TermoCompromisso->setEstagiarioVagaEdital($estagiarioVagaEdital);
        $TermoCompromisso->setObservacaoTc($observacao);
        $this->getObjectManager()->persist($TermoCompromisso);
        return $TermoCompromisso;
    }

    public function termoRecisao($termoCompromisso = null, $motivo = null) {
        if (!$termoCompromisso)
            $termoCompromisso = $this->termoCompromissoEstagio();
        if (!$motivo)
            $motivo = $this->motivoRecisao();

        $TermoRecisao = new \Estagios\Model\TermoRecisao();
        $TermoRecisao->setData_recisao(new \DateTime('now'));
        $TermoRecisao->setMotivo_recisao($motivo);
        $TermoRecisao->setTermo_compromisso($termoCompromisso);

        $this->getObjectManager()->persist($TermoRecisao);

        return $TermoRecisao;
    }

    public function tipoClassificacaoEstagio() {
        $TipoClassificacaoEstagio = new \Estagios\Model\TipoClassificacaoEstagio();
        $TipoClassificacaoEstagio->setDesc_Classificacao_Estagio('Deferido');
        $this->getObjectManager()->persist($TipoClassificacaoEstagio);
        $TipoClassificacaoEstagio = new \Estagios\Model\TipoClassificacaoEstagio();
        $TipoClassificacaoEstagio->setDesc_Classificacao_Estagio('Indeferido');
        $this->getObjectManager()->persist($TipoClassificacaoEstagio);

        return $TipoClassificacaoEstagio;
    }

    public function tipoDeVaga() {
        $TipoDeVaga = new \Estagios\Model\TipoDeVaga();
        $TipoDeVaga->setDesc_Tipo('Existente');
        $this->getObjectManager()->persist($TipoDeVaga);
        $TipoDeVaga = new \Estagios\Model\TipoDeVaga();
        $TipoDeVaga->setDesc_Tipo('Nova');
        $this->getObjectManager()->persist($TipoDeVaga);
        return $TipoDeVaga;
    }

    public function vaga($contaGerencial = null, $nomeVaga = null, $setor = null, $solicitante = null, $tipoDeVaga = null) {
        if (!$contaGerencial)
            $contaGerencial = $this->contaGerencial();
        if (!$nomeVaga)
            $nomeVaga = 'SGI\CRS';
        if (!$setor)
            $setor = $this->setor();
        if (!$solicitante)
            $solicitante = $this->solicitante();
        if (!$tipoDeVaga)
            $tipoDeVaga = $this->tipoDeVaga();

        $Vaga = new \Estagios\Model\Vaga();
        $Vaga->setContaGerencial($contaGerencial);
        $Vaga->setDataAbertura(new \DateTime('now'));
        $Vaga->setNomeVaga($nomeVaga);
        $Vaga->setSetorAreaVaga($setor);
        $Vaga->setSolicitante($solicitante);
        $Vaga->setTipoDeVaga($tipoDeVaga);
        $this->getObjectManager()->persist($Vaga);
        return $Vaga;
    }

    public function vagaCaracteristica($vaga = null, $diasHorariosAtividades = null, $justificativaVaga = null, $atividade = null, $requisito = null, $cargaHoraria = null) {
        if (!$vaga)
            $vaga = $this->vaga();
        if (!$diasHorariosAtividades)
            $diasHorariosAtividades = "De segunda a sexta das 13:00 às 18:00";
        if (!$justificativaVaga)
            $justificativaVaga = "Para atender ao publico";
        if (!$atividade)
            $atividade = "Comunicação com público externo;Auxiliar com o sistema";
        if (!$requisito)
            $requisito = "Comprometimento;Pró-atividade;Boa comunicação;Organização;Vontade;";
        if (!$cargaHoraria)
            $cargaHoraria = 30;
        $VagaCaracteristica = new \Estagios\Model\VagaCaracteristica();
        $VagaCaracteristica->setVaga($vaga);
        $VagaCaracteristica->setDiasHorariosAtividades($diasHorariosAtividades);
        $VagaCaracteristica->setJustificativaVaga($justificativaVaga);
        $VagaCaracteristica->setAtividadesEditalVaga($atividade);
        $VagaCaracteristica->setRequisitosEditalVaga($requisito);
        $VagaCaracteristica->setCargaHorariaEditalVaga($cargaHoraria);
        $VagaCaracteristica->setDataModificacaoRegistro(null);
        $VagaCaracteristica->setUsuarioModificacaoRegistro(null);
        $this->getObjectManager()->persist($VagaCaracteristica);
        return $VagaCaracteristica;
    }

    public function vinculoVagaEdital($AprovacaoVaga = null, $Edital = null) {
        if (!$AprovacaoVaga)
            $AprovacaoVaga = $this->aprovacaoVaga();
        if (!$Edital)
            $Edital = $this->editalVaga();

        $VinculoVagaEdital = new \Estagios\Model\VinculoVagaEdital();
        $VinculoVagaEdital->setAprovacaoVaga($AprovacaoVaga);
        $VinculoVagaEdital->setEditalVaga($Edital);
        $this->getObjectManager()->persist($VinculoVagaEdital);
        return $VinculoVagaEdital;
    }

}