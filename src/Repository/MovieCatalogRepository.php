<?php

namespace App\Repository;

use App\Dto\MovieDto;
use App\Entity\MovieCatalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieCatalog|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieCatalog|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieCatalog[]    findAll()
 * @method MovieCatalog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieCatalogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieCatalog::class);
    }

    public function findLikeTitle(string $title): ?MovieCatalog
    {
        $qb = $this->createQueryBuilder('m');
        $query = $qb->where($qb->expr()->like('m.title', ':title'))
            ->setParameter('title', '%'.$title.'%')
            ->getQuery();
        return $query->getOneOrNullResult();
    }

    public function save(MovieDto $movieDto)
    {

        $movie = new MovieCatalog();
        $movie->setTitle($movieDto->title)
            ->setDirector($movieDto->director)
            ->setImdbId($movieDto->imdbId)
            ->setPlot($movieDto->plot)
            ->setPoster($movieDto->poster)
            ->setReleased(new \DateTime($movieDto->release))
            ->setType($movieDto->type)
            ->setYear((int)$movieDto->year);
        $this->getEntityManager()->persist($movie);
        $this->getEntityManager()->flush();
    }

}
