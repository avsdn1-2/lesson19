<?php

namespace App\Entity;

use App\Dto\MovieDto;
use App\Repository\MovieCatalogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="films")
 * @ORM\Entity(repositoryClass=MovieCatalogRepository::class)
 */
class MovieCatalog
{
    /**
     * @return mixed
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * @param mixed $director
     * @return MovieCatalog
     */
    public function setDirector($director)
    {
        $this->director = $director;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlot()
    {
        return $this->plot;
    }

    /**
     * @param mixed $plot
     * @return MovieCatalog
     */
    public function setPlot($plot)
    {
        $this->plot = $plot;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImdbId()
    {
        return $this->imdbId;
    }

    /**
     * @param mixed $imdbId
     * @return MovieCatalog
     */
    public function setImdbId($imdbId)
    {
        $this->imdbId = $imdbId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return MovieCatalog
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param mixed $poster
     * @return MovieCatalog
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
        return $this;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $released;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $director;

    /**
     * @ORM\Column(type="text")
     */
    private $plot;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $imdbId;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     */
    private $poster;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getReleased(): ?\DateTimeInterface
    {
        return $this->released;
    }

    public function setReleased(\DateTimeInterface $released): self
    {
        $this->released = $released;

        return $this;
    }

    public function toDto(): MovieDto
    {
        $movieDto = new MovieDto();
        $movieDto->title = $this->title;
        $movieDto->year = $this->year;
        $movieDto->type = $this->type;
        $movieDto->director = $this->director;
        $movieDto->imdbId = $this->imdbId;
        $movieDto->plot = $this->plot;
        $movieDto->poster = $this->poster;
        $movieDto->release = $this->released->format('d M Y');
        return $movieDto;
    }
}
