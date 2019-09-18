<?php


namespace App\Controller;


use App\Entity\Medico;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();

        $dadoEmJSON = json_decode($corpoRequisicao);

        $medico = new Medico();

        $medico->crm = $dadoEmJSON->crm;
        $medico->nome = $dadoEmJSON->nome;

        $this->entityManager->persist($medico);
        //realizar varias operacoes com o banco

        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("medicos", methods={"GET"})
     */
    public function buscarTodos(): Response
    {
        $repositorioMedicos = $this->getDoctrine()->getRepository(Medico::class);

        $medicoList = $repositorioMedicos->findAll();

        return new JsonResponse($medicoList);
    }


    /**
     * @Route("medicos/{id}", methods={"GET"})
     */
    public function buscarUm(Request $request): Response
    {
        $id = $request->get('id');
        $repositorioMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $repositorioMedicos->find($id);

        $codigoResposta = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoResposta);
    }
}