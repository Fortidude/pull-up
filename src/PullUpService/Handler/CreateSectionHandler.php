<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Section;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\SectionRepositoryInterface;

use PullUpService\Command\CreateSectionCommand;

class CreateSectionHandler
{
    /** @var SectionRepositoryInterface */
    protected $sectionRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(
        SectionRepositoryInterface $sectionRepository,
        User $user,
        $eventBus,
        $cachePath = null
    )
    {
        $this->sectionRepository = $sectionRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(CreateSectionCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        $exist = $this->sectionRepository->getByUserAndName($this->user, $command->name);
        if ($exist && $exist->isRemoved()) {
            // @TODO reactive / restore
        } elseif ($exist) {
            throw new \Exception ("SECTION_ALREADY_EXIST");
        }

        $section = Section::create($command->name, $command->description, $this->user);

        $this->sectionRepository->add($section);
    }
}