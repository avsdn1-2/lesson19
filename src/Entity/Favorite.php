<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavoriteRepository::class)
 */
class Favorite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $film_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilmId(): ?int
    {
        return $this->film_id;
    }

    public function setFilmId(int $film_id): self
    {
        $this->film_id = $film_id;

        return $this;
    }
}
