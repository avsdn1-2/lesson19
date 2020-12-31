<?php


namespace App\Service;


use App\Dto\MovieDto;
use App\Repository\FavoriteRepository;
use App\Repository\MovieCatalogRepository;

class CatalogService implements CatalogServiceInterface
{
    private $movieCatalogRepository;
    private $favoriteRepository;
    private $omdbService;

    public function __construct(MovieCatalogRepository $movieCatalogRepository,FavoriteRepository $favoriteRepository, OmdbServiceInterface $omdbService)
    {
        $this->movieCatalogRepository = $movieCatalogRepository;
        $this->favoriteRepository = $favoriteRepository;
        $this->omdbService = $omdbService;
    }
    /**
     * Search film by title
     *
     * @param string $title
     * @return MovieDto|null
     */
    public function search(string $title): ?MovieDto
    {
        $result = $this->movieCatalogRepository->findLikeTitle($title);
        if (!$result) {
            $movie = $this->omdbService->findByTitle($title);
            $this->movieCatalogRepository->save($movie);
            return $movie;
        }

        return $result->toDto();
    }

    public function searchById(int $id): ?MovieDto
    {
        $result = $this->movieCatalogRepository->findOneBy(['id'=>$id]);
        if (!$result) {
            return null;
        }
        return $result->toDto();
    }

    public function list(): ?array
    {
        $list = $this->movieCatalogRepository->findAll();
        $favorites = $this->favoriteRepository->findAll();

        return [
            'list' => $list,
            'favorites' => $favorites
               ];
    }

    /**
     * Add film to catalog.
     *
     * @param MovieDto $movieDto
     * @return mixed
     */
    //public function add(MovieDto $movieDto)
    public function add(string $title): ?int
    {
        $movie = $this->movieCatalogRepository->findOneBy(['title'=>$title]);

        if ($movie !== null)
        {
            //фильм уже есть в каталоге
            return 2;
        }

        $movie = $this->omdbService->findByTitle($title);
        if ($movie == null)
        {
            //фильм не найден на omdbService
            return 0;
        }

        $this->movieCatalogRepository->save($movie);
        return 1;
    }

}