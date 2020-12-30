<?php


namespace App\Service;


use App\Dto\MovieDto;

interface CatalogServiceInterface
{
    /**
     * Search film by title
     *
     * @param string $title
     * @return MovieDto|null
     */
    public function search(string $title): ?MovieDto;

    /**
     * Add film to catalog.
     *
     * @param MovieDto $movieDto
     * @return mixed
     */
    //public function add(MovieDto $movieDto);
    public function add(string $title);
}