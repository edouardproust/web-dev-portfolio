<?php

namespace App\Service;

use App\Path;
use App\Entity\Project;
use App\Helper\StringHelper;
use App\Repository\ToolRepository;
use App\Repository\TechnologyRepository;
use App\Repository\CodingLanguageRepository;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeService extends AbstractController
{
    private $codingLanguageRepository;
    private $toolRepository;
    private $technologyRepository;

    public function __construct(
        CodingLanguageRepository $codingLanguageRepository,
        ToolRepository $toolRepository,
        TechnologyRepository $technologyRepository
    ) {
        $this->codingLanguageRepository = $codingLanguageRepository;
        $this->toolRepository = $toolRepository;
        $this->technologyRepository = $technologyRepository;
    }

    public function getStatistics(): array
    {
        return [
            'daysOfCoding' => $this->calculateDaysOfCoding(),
            'languages' => $this->getLanguages(),
            'technologies' => $this->getTechnologies(),
            'tools' => $this->getTools(),
            'githubRepositories' => $this->getGithubRepositories()
        ];
    }

    /**
     * @param Project[] $projects
     * @return array
     */
    public function prepareFeaturedProjects(array $projects): array
    {
        $filteredList = [];
        foreach ($projects as $i => $project) {
            $filteredList[$i]['img'] = Path::UPLOADS_PROJECTS_THUMB . '/' . $project->getThumbnail();
            $filteredList[$i]['title'] = $project->getTitle();
            $filteredList[$i]['categories'] = $project->getCategories();
            $filteredList[$i]['url'] = $this->generateUrl('project_show', [
                'id' => $project->getId(),
                'slug'=> $project->getSlug()
            ]);
        }
        return $filteredList;
    }

    private function calculateDaysOfCoding()
    {
        $origin = new \DateTime('2020-05-01');
        $target = new \DateTime('now');
        $interval = $origin->diff($target);
        return $interval->days;
    }

    private function getLanguages()
    {
        $languages = $this->codingLanguageRepository->findAll();
        return [
            'languages' => $languages,
            'list' => StringHelper::arrayToSentence($languages, 'label'),
            'count' => count($languages)
        ];
    }

    private function getTechnologies()
    {
        $technologies = $this->technologyRepository->findAll();
        return [
            'technologies' => $technologies,
            'list' => StringHelper::arrayToSentence($technologies, 'label'),
            'count' => count($technologies)
        ];
    }

    private function getTools()
    {
        $tools = $this->toolRepository->findAll();
        return [
            'languages' => $tools,
            'list' => StringHelper::arrayToSentence($tools, 'label'),
            'count' => count($tools)
        ];
    }

    private static function getGithubRepositories()
    {
        $client = new \Github\Client();
        try {
            $repositories = $client->api('user')->repositories('edouardproust');
        } catch (\Exception $e) {
            // trigger_error('Github API: failed to connect.', E_USER_WARNING);
        }
        return $repositories ?? 14;
    }
}
