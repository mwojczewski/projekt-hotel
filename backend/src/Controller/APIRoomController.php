<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class APIRoomController extends AbstractController
{

    // internal - checking data overlap
    private function checkOverlap(ReservationRepository $reservationRepository = null, int $room_id = null, \DateTimeImmutable $startTime = null, \DateTimeImmutable $endTime = null): int
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
        $startTime = \DateTimeImmutable::createFromFormat("d/m/Y H:i", "${f_day}/${f_mon}/${f_year} 13:00");
        $endTime =  \DateTimeImmutable::createFromFormat("d/m/Y H:i", "${t_day}/${t_mon}/${t_year} 11:00");
        return $this->json(['conflicts' => $this->checkOverlap($reservationRepository, $id, $startTime, $endTime)], Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }

    #[Route('/room/reservations/create', name: 'app_api_room_add_reservation', methods: ['POST'])]
    public function addReservation(ManagerRegistry $doctrine, ReservationRepository $reservationRepository = null, Request $request): Response
    {
        $id = $request->request->get('room_id');
        $starts_at = \DateTimeImmutable::createFromFormat("Y.m.d H:i:s", $request->request->get('starts_at') . " 13:00:00");
        $ends_at = \DateTimeImmutable::createFromFormat("Y.m.d H:i:s", $request->request->get('ends_at') . " 11:00:00");
        $paid = $request->request->get('paid') === 1;

        if (!$id || !$starts_at || !$ends_at)
            return $this->json(['error' => 'data_not_found'], Response::HTTP_NOT_FOUND, ["Content-Type" => "application/json"]);

        $room = $doctrine->getRepository(Room::class)->find($id);

        if (!$room) {
            return $this->json(['error' => 'room_not_found'], Response::HTTP_NOT_FOUND, ["Content-Type" => "application/json"]);
        }

        // czy termin jest wolny?
        if ($this->checkOverlap($reservationRepository, $id, $starts_at, $ends_at) !== 0) {
            return $this->json(['error' => 'range_overlap'], Response::HTTP_FORBIDDEN, ["Content-Type" => "application/json"]);
        }

        //tworzymy nowy wpis
        $em = $doctrine->getManager();

        $reservation = new Reservation();
        $reservation->setCreatedAt(new \DateTimeImmutable("now"));
        $reservation->setRoomId($room);
        $reservation->setStartsAt($starts_at);
        $reservation->setEndsAt($ends_at);
        $reservation->setPaid($paid);

        $em->persist($reservation);
        $em->flush();

        return $this->json($reservation, Response::HTTP_CREATED, ["Content-Type" => "application/json"], ['groups' => ['reservation']]);
    }

    #[Route('/room/reservations/{id}', name: 'app_api_room_reservations', methods: ['GET'])]
    public function reservations(Room $room = null): Response
    {
        return $this->json($room, Response::HTTP_OK, ["Content-Type" => "application/json"], ['groups' => ['reservation']]);
    }
}
