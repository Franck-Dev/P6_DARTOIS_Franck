<?php

namespace App\Controller;

use App\Entity\Medias;
use App\Entity\Tricks;
use App\Form\TricksType;
use App\Repository\MediasRepository;
use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tricks")
 */
class TricksController extends AbstractController
{
    /**
     * @Route("/", name="tricks_index", methods={"GET"})
     */
    public function index(TricksRepository $tricksRepository, MediasRepository $mediasRepository): Response
    {
        //Récupération du Thumbail image pour chaque trick
        $tricks=$tricksRepository->findAll();
        $i=0;
        foreach($tricks as $trick) {
            $thumbail[$i]=$mediasRepository->myFindByTrick(1,$trick->getId(),'Picture');
            $i++;
        }
        
        return $this->render('tricks/index.html.twig', [
            'thumbails' => $thumbail,
        ]);
    }

    /**
     * @Route("/new", name="tricks_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $trick = new Tricks();
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->integrationMedia($form, $trick);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('tricks_show',['id' => $trick->getId()]);
        }

        return $this->render('tricks/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tricks_show", methods={"GET"})
     */
    public function show(Tricks $trick,MediasRepository $mediasRepository): Response
    {
        //On récupère la thumbail image du trick
        $thumbail=$mediasRepository->myFindByTrick(1,$trick->getId(),'Picture');
        //On récupère les images du trick
        $images=$mediasRepository->findBy(['Tricks'=>$trick->getId(), 'Type'=>'Picture']);
        //On récupère les videos du trick
        $videos=$mediasRepository->findBy(['Tricks'=>$trick->getId(), 'Type'=>'Video']);

        return $this->render('tricks/show.html.twig', [
            'trick' => $trick,
            'thumbail' => $thumbail,
            'images' => $images,
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tricks_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tricks $trick): Response
    {
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->integrationMedia($form, $trick);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tricks_show',['id' => $trick->getId()]);
        }

        return $this->render('tricks/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="tricks_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tricks $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tricks_show');
    }

    /**
     * @Route("/delete/medias/{id}", name="image_delete", methods={"DELETE"})
     */
    public function deleteImage(Medias $image, Request $request){
        $data = json_decode($request->getContent(), true);
        dump($request->getContent());
        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(),  $request->request->get('_token'))){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            $this->addFlash('success', 'Image supprimé');

        }else{
            $this->addFlash('error', 'Le protocole de suppression n\'est pas recpecté');
        }
        return $this->redirectToRoute('tricks_index');
    }

    private function integrationMedia($form, $trick) {
        // On récupère les images transmises
        $images = $form->get('medias')->getData();
            
        // On boucle sur les images
        foreach($images as $image){
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
            
            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            
            // On crée l'image dans la base de données
            $img = new Medias();
            $img->setName($fichier);
            $img->setType('Picture');
            $img->setLikes(1);
            $trick->addMedia($img);
        }

    }
}
