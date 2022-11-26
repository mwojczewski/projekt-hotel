<?php

namespace App\Controller;

use App\Entity\Room;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class APIRoomController extends AbstractController
{
    // internal - checking data overlap
    private function checkOverlap(ReservationRepository $reservationRepository = null, int $room_id = null, \DateTime $startTime = null, \DateTime $endTime = null): int
    {
        return count($reservationRepository->findConflicts($startTime, $endTime, $room_id));
    }
    #[Route('/rooms', name: 'app_api_room_index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository = null): Response
    {
        return $this->json($roomRepository->findAll(), Response::HTTP_OK,  ["Content-Type" => "application/json"], ['groups' => 'room']);
    }

    #[Route('/room/details/{id}', name: 'app_api_room_show', methods: ['GET'])]
    public function show(Room $room = null): Response
    {
        return $this->json($room, Response::HTTP_OK, ["Content-Type" => "application/json"], ['groups' => ['room', 'room:details', 'photo']]);
    }

    #[Route('/room/photos/{id}', name: 'app_api_room_photos', methods: ['GET'])]
    public function photos(Room $room = null): Response
    {
        return $this->json($room, Response::HTTP_OK, ["Content-Type" => "application/json"], ['groups' => ['photo']]);
    }

    #[Route('/room/reservations/check/{id}/{f_year}/{f_mon}/{f_day}/{t_year}/{t_mon}/{t_day}', name: 'app_api_room_reservations_check', methods: ['GET'])]
    public function reservationsCheck(ReservationRepository $reservationRepository = null, int $id, int $f_year, int $f_mon, int $f_day, int $t_year, int $t_mon, int $t_day): Response
    {
        $startTime = \DateTime::createFromFormat("d/m/Y H:i", "${f_day}/${f_mon}/${f_year} 13:00");
        $endTime =  \DateTime::createFromFormat("d/m/Y H:i", "${t_day}/${t_mon}/${t_year} 11:00");
        return $this->json(['conflicts' => $this->checkOverlap($reservationRepository, $id, $startTime, $endTime)], Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }

    #[Route('/room/reservations/{id}', name: 'app_api_room_reservations', methods: ['GET'])]
    public function reservations(Room $room = null): Response
    {
        return $this->json($room, Response::HTTP_OK, ["Content-Type" => "application/json"], ['groups' => ['reservation']]);
    }
}
