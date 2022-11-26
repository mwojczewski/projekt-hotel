<?php

namespace App\Entity;

use App\Repository\RoomPhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomPhotoRepository::class)]
class RoomPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("photo")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roomPhotos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room_id = null;

    #[ORM\Column(length: 45)]
    #[Groups("photo")]
    private ?string $name = null;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $file = null): self
    {
        $this->name = $file;

        return $this;
    }

    public function getNameUrl(): ?string
    {
        if (!$this->name) {
            return null;
        }
        if (strpos($this->name, '/') !== false) {
            return $this->name;
        }
        return sprintf('/uploads/images/%s', $this->name);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
