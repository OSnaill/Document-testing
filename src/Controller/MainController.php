<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, DocumentRepository $documentRepository): Response
    {
        $document = new Document();

        $allDocument = $documentRepository->findAll();
        $form = $this->createForm(DocumentType::class, $document);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded

            // ... perform some action, such as saving the task to the database

            $document->setImageName($document->getName());
            $em->persist($document);
            $em->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->renderForm('main/index.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form,
            'documents' => $allDocument
        ]);
    }

}
