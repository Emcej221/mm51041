<?php
namespace App\Model;

use App\Service\Config;

class Comment
{
    private ?int $id = null;
    private ?int $postId = null;
    private ?string $author = null;
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Comment
    {
        $this->id = $id;
        return $this;
    }

    public function getPostId(): ?int
    {
        return $this->postId;
    }

    public function setPostId(?int $postId): Comment
    {
        $this->postId = $postId;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): Comment
    {
        $this->author = $author;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Comment
    {
        $this->content = $content;
        return $this;
    }

    public static function fromArray($array): Comment
    {
        $comment = new self();
        $comment->fill($array);
        return $comment;
    }

    public function fill($array): Comment
    {
        if (isset($array['id']) && !$this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['postId'])) {
            $this->setPostId($array['postId']);
        }
        if (isset($array['author'])) {
            $this->setAuthor($array['author']);
        }
        if (isset($array['content'])) {
            $this->setContent($array['content']);
        }

        return $this;
    }
    public static function findById($commentId): ?Comment
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM comment WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $commentId]);

        $commentArray = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($commentArray) {
            return self::fromArray($commentArray);
        } else {
            return null;
        }
    }
    public static function findByPostId($postId): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM comment WHERE postId = :postId';
        $statement = $pdo->prepare($sql);
        $statement->execute(['postId' => $postId]);

        $comments = [];
        $commentsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($commentsArray as $commentArray) {
            $comments[] = self::fromArray($commentArray);
        }

        return $comments;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (!$this->getId()) {
            $sql = "INSERT INTO comment (postId, author, content) VALUES (:postId, :author, :content)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'postId' => $this->getPostId(),
                'author' => $this->getAuthor(),
                'content' => $this->getContent(),
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE comment SET postId = :postId, author = :author, content = :content WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':postId' => $this->getPostId(),
                ':author' => $this->getAuthor(),
                ':content' => $this->getContent(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM comment WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setPostId(null);
        $this->setAuthor(null);
        $this->setContent(null);
    }
}

