<?php
namespace LeoGalleguillos\Blog\Model\Table;

use Generator;
use Zend\Db\Adapter\Adapter;

class Article
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function insert(
        int $blogId,
        int $userId,
        string $title,
        string $body
    ) {
        $sql = '
            INSERT
              INTO `article` (
                       `blog_id`, `user_id`, `title`, `body`, `created`
                   )
            VALUES (?, ?, ?, ?, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            $blogId,
            $userId,
            $title,
            $body,
        ];
        return $this->adapter
                    ->query($sql)
                    ->execute($parameters)
                    ->getGeneratedValue();
    }

    /**
     * @yield array
     * @return Generator
     */
    public function selectWhereBlogIdOrderByCreatedDesc(int $blogId): Generator
    {
        $sql = '
            SELECT `article_id`
                 , `blog_id`
                 , `user_id`, `title`, `body`, `views`, `created`
              FROM `article`
             WHERE `blog_id` = ?
             ORDER
                BY `created` DESC
                 ;
        ';
        $parameters = [
            $blogId,
        ];
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }
}