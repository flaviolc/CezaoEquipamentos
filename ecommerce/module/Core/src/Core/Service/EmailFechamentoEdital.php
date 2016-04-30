<?php

namespace Core\Service;

use Zend\Form\Element\DateTime;

use Core\Service\Service;


/**
 * Serviço para enviar e-mails na data de fechamento do edital
 * @category Core
 * @package Service
 * @author Maico
 */
class EmailFechamentoEdital extends Service {

	public function getEmailFechamentoEdital(){
	$dataAtual = new \DateTime('now');
			$dataAtual = $dataAtual->format('Y-m-d');
	
			$objectManager = $this
			->getServiceLocator()
			->get('Doctrine\ORM\EntityManager');
			$session = $this->getService('Session');
	
			$editalVagas = $objectManager
			->createQueryBuilder()
			->select('EditalVaga.data_fechamento_edital','Sl.email as solicitante','Su.nome as supervisor', 'EditalVaga.id as idEdital',
					'EditalVaga.data_de_vinculo')
					->from('Estagios\Model\EditalVaga', 'EditalVaga')
					->join('EditalVaga.vinculo_vaga_edital', 'VinculoVagaEdital')
					->join('VinculoVagaEdital.aprovacao_vaga', 'AprovacaoVaga')
					->join('AprovacaoVaga.vaga', 'Vaga')
					->join('Vaga.solicitante', 'Solicitante')
					->join('Solicitante.pessoa', 'Sl')
					->join('Vaga.setor_area_vaga', 'Setor')
					->join('Vaga.vaga_caracteristica', 'VagaCaracteristica')
					->join('VagaCaracteristica.supervisor', 'Supervisor')
					->join('Supervisor.pessoa', 'Su')
					->where("(EditalVaga.data_fechamento_edital = '$dataAtual') and
							VinculoVagaEdital = (SELECT MAX(VVE.id) FROM \Estagios\Model\VinculoVagaEdital VVE JOIN VVE.edital EV
							WHERE EV.id = EditalVaga.id)");
	
			$query = $editalVagas->getQuery();
			$result = $query->getResult();

			foreach ($result as $edital){
	
				$Solicitante = $edital["solicitante"];
				$data = $edital["data_fechamento_edital"]->format('Y-m-d');
				$numeroEdital =$edital['idEdital'];
				$dataVinculo = $edital['data_de_vinculo']->format('Y');
	
				$service = $this->getService('Core\Service\Email');
				$texto = "O edital numero $numeroEdital/$dataVinculo encerra suas inscrições no dia de hoje.";
				$from = 'SETOR DE ESTÁGIOS E MONITORIAS';
				$to = array('email' => "$Solicitante", 'name' => 'SMI');
				$title = 'Edital aberto ' . $numeroEdital . '/' . $dataVinculo ;
				
				$cc = array('orestes.d@unochapeco.edu.br', 'jhonib10@unochapeco.edu.br', 'cezar08@unochapeco.edu.br');				
			
				$service->send($texto, $from, $to, $arquivo = null, $title, $cc);
			}
	}
}

?>
