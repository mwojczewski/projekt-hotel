<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("room")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("room:details")]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?int $room_no = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?int $floor_no = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?float $size = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?int $beds = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?bool $balcony = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?bool $breakfast = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?bool $pets_allowed = null;

    #[ORM\Column]
    #[Groups("room")]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'room_id', targetEntity: Reservation::class, orphanRemoval: true)]
    #[Groups("reservation")]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'room_id', targetEntity: RoomPhoto::class, orphanRemoval: true)]
    #[Groups("photo")]
    private Collection $roomPhotos;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->roomPhotos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomNo(): ?int
    {
        return $this->room_no;
    }

    public function setRoomNo(int $room_no): self
    {
        $this->room_no = $room_no;

        return $this;
    }

    public function getFloorNo(): ?int
    {
        return $this->floor_no;
    }

    public function setFloorNo(int $floor_no): self
    {
        $this->floor_no = $floor_no;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function isBalcony(): ?bool
    {
        return $this->balcony;
    }

    public function setBalcony(bool $balcony): self
    {
        $this->balcony = $balcony;

        return $this;
    }

    public function isBreakfast(): ?bool
    {
        return $this->breakfast;
    }

    public function setBreakfast(bool $breakfast): self
    {
        $this->breakfast = $breakfast;

        return $this;
    }

    public function isPetsAllowed(): ?bool
    {
        return $this->pets_allowed;
    }

    public function setPetsAllowed(bool $pets_allowed): self
    {
        $this->pets_allowed = $pets_allowed;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRoomId($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRoomId() === $this) {
                $reservation->setRoomId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RoomPhoto>
     */
    public function getRoomPhotos(): Collection
    {
        return $this->roomPhotos;
    }

    public function addRoomPhoto(RoomPhoto $roomPhoto): self
    {
        if (!$this->roomPhotos->contains($roomPhoto)) {
            $this->roomPhotos->add($roomPhoto);
            $roomPhoto->setRoomId($this);
        }

        return $this;
    }

    public function removeRoomPhoto(RoomPhoto $roomPhoto): self
    {
        if ($this->roomPhotos->removeElement($roomPhoto)) {
            // set the owning side to null (unless already changed)
            if ($roomPhoto->getRoomId() === $this) {
                $roomPhoto->setRoomId(null);
            }
        }

        return $this;
    }

    public function getBeds(): ?int
    {
        return $this->beds;
    }

    public function setBeds(int $beds): self
    {
        $this->beds = $beds;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
