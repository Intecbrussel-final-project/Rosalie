<?php
// model/manager/CommentManager.php
declare(strict_types=1);
namespace model\manager;
use PDO;
use model\abstract\AbstractManager;
use model\mapping\CommentMapping;

class CommentManager extends AbstractManager
{
    // nombre de commentaires publiés d'une recette (pour la pagination)
    public function countPublishedByRecipeId(int $recipeId): int
    {
        $stmt = $this->connect->prepare(
            "SELECT COUNT(*) FROM comments WHERE recipe_id = :recipe_id AND status = 'published'"
        );
        $stmt->execute(['recipe_id' => $recipeId]);
        return (int) $stmt->fetchColumn();
    }

    // commentaires publiés d'une recette (une page), avec le nom de l'auteur et sa note
    public function getPublishedByRecipeId(int $recipeId, int $limit = 3, int $offset = 0): array
    {
        $sql = "SELECT c.id, c.author_id, c.recipe_id, c.message, c.created_at, c.status,
                       u.username, r.rating
                FROM comments AS c
                JOIN users AS u ON u.id = c.author_id
                LEFT JOIN ratings AS r ON r.user_id = c.author_id AND r.recipe_id = c.recipe_id
                WHERE c.recipe_id = :recipe_id AND c.status = 'published'
                ORDER BY c.created_at DESC, c.id DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':recipe_id', $recipeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $comments = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $comments[] = new CommentMapping($row);
        }
        return $comments;
    }

    public function addComment(CommentMapping $comment): bool
    {
        $sql = "INSERT INTO comments (author_id, recipe_id, message)
                VALUES (:author_id, :recipe_id, :message)";
        $stmt = $this->connect->prepare($sql);
        return $stmt->execute([
            'author_id' => $comment->getAuthorId(),
            'recipe_id' => $comment->getRecipeId(),
            'message' => $comment->getMessage(),
        ]);
    }
}
