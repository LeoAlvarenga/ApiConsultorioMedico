<?php


namespace App\Controller;


use App\Entity\Medico;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController
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
}