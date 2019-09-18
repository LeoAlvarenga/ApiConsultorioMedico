<?php


namespace App\Controller;


use App\Entity\Medico;
use App\Helper\MedicoFactory;
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
    /**
     * @var MedicoFactory
     */
    private $medicoFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();

        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

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
    public function buscarUm(int $id): Response
    {
        $medico = $this->buscaMedico($id);

        $codigoResposta = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoResposta);
    }

    /**
     * @Route("medicos/{id}", methods={"PUT"})
     */
    public function alteraMedico(int $id, Request $request): Response
    {
        $corpoReq = $request->getContent();
        $dadoEmJson = json_decode($corpoReq);

        $medico = $this->buscaMedico($id);

        if (is_null($medico)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("medicos/{id}", methods={"DELETE"})
     */
    public function deleta(int $id): Response
    {
        $medico = $this->buscaMedico($id);

        if(is_null($medico)){
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function buscaMedico(int $id)
    {
        $repositorioMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $repositorioMedicos->find($id);
        return $medico;
    }
}