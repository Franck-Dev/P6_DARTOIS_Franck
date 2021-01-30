<?php

namespace App\Controller;

use App\Entity\Medias;
use App\Entity\Tricks;
use App\Form\MediasType;
use App\Form\TricksType;
use App\Repository\MediasRepository;
use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, Medias $medias=null, ValidatorInterface $validator): Response
    {
        $trick = new Tricks();
        $form = $this->createForm(TricksType::class, $trick);
        $form2= $this->createForm(MediasType::class, $medias);

        $form->handleRequest($request);

        $errors = $validator->validate($trick);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->integrationMedia($form, $request, $trick);
            $trick->setCreatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('tricks_show',['id' => $trick->getId()]);
        }

        return $this->render('tricks/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'errors' => $errors,
            'modif' => false,
        ]);
    }

    /**
     * @Route("/{id}", name="tricks_show", methods={"GET"})
     */
    public function show(Tricks $trick, MediasRepository $mediasRepository, $modif=null): Response
    {
        $tbMedias=[];
        //On récupère un tableau avec tous les objets nécessaire à l'affichage du trick
        $tbMedias=$this->checkMedias($trick, $mediasRepository);
        $thumbail=$tbMedias[0];
        //On récupère les images du trick
        $images=$tbMedias[1];
        //On récupère les videos du trick
        $videos=$tbMedias[2];

        return $this->render('tricks/show.html.twig', [
            'trick' => $trick,
            'thumbail' => $thumbail,
            'images' => $images,
            'videos' => $videos,
            'modif' => $modif,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="tricks_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Tricks $trick, MediasRepository $mediasRepository, Medias $medias=null): Response
    {
        
        $tbMedias=[];
        //On récupère un tableau avec tous les objets nécessaire à l'affichage du trick
        $tbMedias=$this->checkMedias($trick, $mediasRepository);

        $form = $this->createForm(TricksType::class, $trick);
        $form2= $this->createForm(MediasType::class, $medias);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->integrationMedia($form, $request, $trick);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tricks_show',['id' => $trick->getId()]);
        }

        return $this->render('tricks/edit.html.twig', [
            'trick' => $trick,
            'thumbail' => $tbMedias[0],
            'images' => $tbMedias[1],
            'videos' => $tbMedias[2],
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'modif' => true,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="tricks_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Tricks $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/delete/medias/{id}", name="image_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteImage(Medias $image, Request $request){
        //$data = json_decode($request->getContent(), true);
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
    
    /**
     * Fonction permettant l'integration du média image dans la base et dans le dossier image
     *
     * @param  mixed $form
     * @param  Request $request
     * @param  Tricks $trick
     */
    private function integrationMedia($form, $request, $trick) {

        // On récupère les images transmises
        $images = $form->get('medias')->getData();
        $videos=$request->request->get('medias')['medias'];
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
        //On boucle sur les videos s'il y en a
        if (empty($videos[0]['medias'])) {
            
        } else {
            foreach ($videos as $video) {
                $vid = new Medias();
                $vid->setName($video['medias']);
                $vid->setType('Video');
                $vid->setLikes(1);
                $trick->addMedia($vid);
            }
        }
    }
    
    /**
     * checkMedias est une fonction qui permet de récupérer tous les medias liés à un trick
     *
     * @param  mixed $trick
     * @param  mixed $mediasRepository
     * @return void
     */
    private function checkMedias (Tricks $trick, MediasRepository $mediasRepository) {

        $tbMedias=[];
        //On récupère la thumbail image du trick
        $thumbail=$mediasRepository->myFindByTrick(1,$trick->getId(),'Picture');
        //On récupère les images du trick
        $images=$mediasRepository->findBy(['Tricks'=>$trick->getId(), 'Type'=>'Picture']);
        //On récupère les videos du trick
        $videos=$mediasRepository->findBy(['Tricks'=>$trick->getId(), 'Type'=>'Video']);

        $tbMedias=[$thumbail,$images,$videos];

        return $tbMedias;
    }
}
