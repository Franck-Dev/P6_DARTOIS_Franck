<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/comments")
 */
class CommentsController extends AbstractController
{
    /**
     * @Route("/", name="comments_index", methods={"GET"})
     */
    public function index(CommentsRepository $commentsRepository, Tricks $trick=null): Response
    {
        if(!$trick) {
            return $this->render('comments/index.html.twig', [
                'comments' => $commentsRepository->findAll(),
            ]);
        } else {
            return $this->render('comments/index.html.twig', [
                'comments' => $commentsRepository->findBy(['Trick'=> $trick->getId()],['CreatedAt' => 'DESC'],5),
            ]);
        }
    }

    /**
     * @Route("/new", name="comments_new", methods={"GET","POST"})
     */
    public function new(Request $request, Tricks $trick=null, UserInterface $user=null): Response
    {
        
        $comment = new Comments();
        $comment->setTrick($request->get('trick'));
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setUser($user);
            if (!$comment->getId()) {
                $comment->setCreatedAt(new \datetime);
            } else {
                // $comment->setModifDat(new \datetime);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('tricks_show',['id' => $comment->getTrick()->getId()]);
        }

        return $this->render('comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comments_show", methods={"GET"})
     */
    public function show(Comments $comment): Response
    {
        return $this->render('comments/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comments_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comments $comment): Response
    {
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comments_index');
        }

        return $this->render('comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comments_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comments $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comments_index');
    }
    
    /**
     * loadComments Requête AJAX pour renvoyer la totalité du reste des commentaires
     * 
     * @Route("/loadComments/{id}", name="comments_load", condition="request.isXmlHttpRequest()")
     * 
     * @param  mixed $trick
     * @return void
     */
    public function loadComments(Tricks $trick, commentsRepository $commentsRepository, Request $request) {

        if($request->isXmlHttpRequest()) {
            $idTrick = $request->request->get('id');

            return $this->render('comments/index.html.twig', [
                'comments' => $commentsRepository->findBy(['Trick'=> $idTrick],['CreatedAt' => 'DESC'],null,5),
            ]);
        } else {
            return new JsonResponse(['Message'=> 'Pas requete AJAX']);
        }
        
    }
}
