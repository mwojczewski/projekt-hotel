<?php

namespace App\Controller;

use App\Entity\RoomPhoto;
use App\Form\RoomPhotoType;
use App\Repository\RoomPhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/room_photo')]
class RoomPhotoController extends AbstractController
{
    #[Route('/', name: 'app_room_photo_index', methods: ['GET'])]
    public function index(RoomPhotoRepository $roomPhotoRepository): Response
    {
        return $this->render('room_photo/index.html.twig', [
            'room_photos' => $roomPhotoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_room_photo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RoomPhotoRepository $roomPhotoRepository, SluggerInterface $slugger): Response
    {
        $roomPhoto = new RoomPhoto();
        $form = $this->createForm(RoomPhotoType::class, $roomPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = md5($originalFilename);
                $newFilename = $safeFilename . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    die('upload error: ' . $e);
                }

                $roomPhoto->setName($newFilename);
            }

            $roomPhotoRepository->save($roomPhoto, true);

            return $this->redirectToRoute('app_room_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room_photo/new.html.twig', [
            'room_photo' => $roomPhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_room_photo_show', methods: ['GET'])]
    public function show(RoomPhoto $roomPhoto): Response
    {
        return $this->render('room_photo/show.html.twig', [
            'room_photo' => $roomPhoto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_room_photo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RoomPhoto $roomPhoto, RoomPhotoRepository $roomPhotoRepository): Response
    {
        $form = $this->createForm(RoomPhotoType::class, $roomPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roomPhotoRepository->save($roomPhoto, true);

            return $this->redirectToRoute('app_room_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room_photo/edit.html.twig', [
            'room_photo' => $roomPhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_room_photo_delete', methods: ['POST'])]
    public function delete(Request $request, RoomPhoto $roomPhoto, RoomPhotoRepository $roomPhotoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $roomPhoto->getId(), $request->request->get('_token'))) {
            $roomPhotoRepository->remove($roomPhoto, true);
        }

        return $this->redirectToRoute('app_room_photo_index', [], Response::HTTP_SEE_OTHER);
    }
}
