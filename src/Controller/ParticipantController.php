<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant')]
class ParticipantController extends AbstractController
{
    #[Route('/', name: 'app_participant_index', methods: ['GET'])]
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParticipantRepository $participantRepository): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participantRepository->save($participant, true);

            return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/new.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/profil', name: 'app_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,
                         ParticipantRepository $participantRepository,
                         CampusRepository $campusRepository,
                         UserPasswordHasherInterface $passwordHasher,
                         #[Autowire('%photo_dir%')] string $photoDir,
    ): Response {

        $participant = $this->getUser();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // je modifie le mot de passe si le mot de passe est modifié
            if($form->get('motdepasse')->getData() != null) {
                $hashedPassword = $passwordHasher->hashPassword($participant, $form->get('motdepasse')->getData());
                $participant->setPassword($hashedPassword);
            }

            if ($photo = $form['photoFileName']->getData()) {
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
                $participant->setPhotoFileName($filename);
            }

            $participantRepository->save($participant, true);

            $this->addFlash('success', 'Votre profil a bien été modifié' );

            return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_participant_show', methods: ['GET', 'POST'])]
    public function show(int $id, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($id);
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_participant_delete', methods: ['POST'])]
    public function delete(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $participantRepository->remove($participant, true);
        }

        return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
