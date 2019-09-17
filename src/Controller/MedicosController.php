<?php


namespace App\Controller;


use App\Entity\Medico;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController
{
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

        return new JsonResponse($medico);
    }
}