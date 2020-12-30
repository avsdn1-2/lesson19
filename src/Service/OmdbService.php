<?php


namespace App\Service;


use App\Dto\MovieDto;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OmdbService implements OmdbServiceInterface
{
    private $client;
    private $apiKey;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->apiKey = $parameterBag->get('omdbApiKey');
        $this->client = new Client([
            'base_uri' => 'http://www.omdbapi.com'
        ]);
    }

    /**
     * Find film by ID.
     *
     * @param int $id
     * @return array
     */
    public function findById(int $id): ?MovieDto
    {
        $request = $this->client->request('GET', '/', [
            'query' => [
                'i' => 'tt'.$id,
                'apikey' => $this->apiKey
            ]
        ]);

        $content = json_decode($request->getBody()->getContents(), true);
        $content = $this->ensureResponseOk($content);
        return $this->toDto($content);
    }

    /**
     * Find film by title.
     *
     * @param string $title
     * @return array
     */
    public function findByTitle(string $title): ?MovieDto
    {
        //dd($title);
        $request = $this->client->request('GET', '/', [
            'query' => [
                't' => $title,
                'apikey' => $this->apiKey
            ]
        ]);

        $content = json_decode($request->getBody()->getContents(), true);
        //dd($content);
        $content = $this->ensureResponseOk($content);
        //dd($content);

        if ($content !== null) {
            return $this->toDto($content);
        } else {
            return null;
        }
    }

    /**
     * Check that no errors found.
     *
     * @param array $content
     * @return array|null
     */
    private function ensureResponseOk(array $content): ?array
    {
       // return (in_array('Error', $content)) ? null : $content;
        return (isset($content['Error']) ? null : $content);
    }

    private function toDto(array $content): MovieDto
    {
        $movieDto = new MovieDto();
        $movieDto->title = $content['Title'];
        $movieDto->year = $content['Year'];
        $movieDto->poster = $content['Poster'];
        $movieDto->plot = $content['Plot'];
        $movieDto->imdbId = $content['imdbID'];
        $movieDto->type = $content['Type'];
        $movieDto->director = $content['Director'];
        $movieDto->release = $content['Released'];
        return $movieDto;
    }
}
