<?php
namespace LeoGalleguillos\Blog\Model\Service\Blog;

use Exception;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\Blog\Model\Entity as BlogEntity;
use LeoGalleguillos\Blog\Model\Factory as BlogFactory;
use LeoGalleguillos\Blog\Model\Service as BlogService;
use LeoGalleguillos\Blog\Model\Table as BlogTable;
use LeoGalleguillos\String\Model\Service as StringService;
use LeoGalleguillos\User\Model\Entity as UserEntity;

class Create
{
    public function __construct(
        FlashService\Flash $flashService,
        BlogFactory\Blog $blogFactory,
        BlogTable\Blog $blogTable,
        StringService\UrlFriendly $urlFriendlyService
    ) {
        $this->flashService       = $flashService;
        $this->blogFactory        = $blogFactory;
        $this->blogTable          = $blogTable;
        $this->urlFriendlyService = $urlFriendlyService;
    }

    /**
     * Create.
     *
     * @param $userId
     * @return BlogEntity\Blog
     */
    public function create(
        UserEntity\User $userEntity = null
    ) : BlogEntity\Blog {
        $errors = [];

        if (empty($_POST['name'])) {
            $errors[] = 'Invalid name.';
        }
        if (empty($_POST['description'])) {
            $errors[] = 'Invalid description.';
        }

        if ($errors) {
            $this->flashService->set('errors', $errors);
            throw new Exception('Invalid form input.');
        }

        $blogSlug = $this->urlFriendlyService->getUrlFriendly($_POST['name']);
        $blogId = $this->blogTable->insert(
            $userEntity->getUserId(),
            $_POST['name'],
            $blogSlug,
            $_POST['description']
        );

        return $this->blogFactory->buildFromBlogId($blogId);
    }
}
