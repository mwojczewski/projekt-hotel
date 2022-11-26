<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("reservation")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room_id = null;

    #[ORM\Column]
    #[Groups("reservation")]

    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups(["reservation", "reservation_public"])]

    private ?\DateTimeImmutable $starts_at = null;

    #[ORM\Column]
    #[Groups(["reservation", "reservation_public"])]

    private ?\DateTimeImmutable $ends_at = null;

    #[ORM\Column]
    #[Groups("reservation")]

    private ?bool $paid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomId(): ?Room
    {
        return $this->room_id;
    }

    public function setRoomId(?Room $room_id): self
    {
        $this->room_id = $room_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->starts_at;
    }

    public function setStartsAt(\DateTimeImmutable $starts_at): self
    {
        $this->starts_at = $starts_at;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->ends_at;
    }

    public function setEndsAt(\DateTimeImmutable $ends_at): self
    {
        $this->ends_at = $ends_at;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }
}
