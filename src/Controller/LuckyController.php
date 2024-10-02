<?php
// src/Controller/LuckyController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Psr\Log\LoggerInterface;

class LuckyController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/lucky/number', name: 'lucky_number')]
    public function number(Request $request): Response
    {
        $randomNumber = random_int(0, 100);

        $form = $this->createFormBuilder()
            ->add('userNumber', IntegerType::class, [
                'label' => 'Insira um número',
                'required' => true,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $userNumber = $data['userNumber'];

            // Log the user's input
            $this->logger->info('Número inserido pelo cliente: ' . $userNumber);

            if ($userNumber === $randomNumber) {
                $this->logger->info("O cliente acertou! O número gerado era: " . $randomNumber);
                dump("Você acertou! O número é: " . $randomNumber);
            } else {
                $this->logger->info("O cliente errou. O número gerado era: " . $randomNumber);
                dump("Diferente de $randomNumber. O número aleatório era: " . $randomNumber);
            }
        }

        return $this->render('lucky/number.html.twig', [
            'form' => $form->createView(),
            'randomNumber' => $randomNumber,
        ]);
    }
}
