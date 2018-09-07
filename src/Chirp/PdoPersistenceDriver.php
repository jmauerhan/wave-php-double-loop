<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Persistence\PersistenceDriverException;

class PdoPersistenceDriver implements PersistenceDriver
{
    /** @var \PDO */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param Chirp $chirp
     * @return bool
     * @throws PersistenceDriverException
     */
    public function save(Chirp $chirp): bool
    {
        try {
            $sql = "INSERT INTO chirp(id, chirp_text, author, created_at) " .
                   "VALUES(:id, :chirp_text, :author, :created_at)";

            $stmt   = $this->pdo->prepare($sql);
            $values = [
                'id'         => $chirp->getId(),
                'chirp_text' => $chirp->getText(),
                'author'     => $chirp->getAuthor(),
                'created_at' => $chirp->getCreatedAt()
            ];
            $result = $stmt->execute($values);
            if ($result === false) {
                throw new PersistenceDriverException(implode($stmt->errorInfo()));
            }
            return true;
        } catch (\PDOException $PDOException) {
            throw new PersistenceDriverException($PDOException->getMessage());
        }
    }

    /**
     * @return ChirpCollection
     *
     * @throws PersistenceDriverException
     */
    public function getAll(): ChirpCollection
    {
        $sql  = "SELECT id, chirp_text, author, created_at FROM chirp ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql, \PDO::FETCH_ASSOC);
        if ($stmt === false) {
            $errors = $this->pdo->errorInfo() ?? [];
            throw new PersistenceDriverException(implode($errors));
        }
        $rows   = $stmt->fetchAll();
        $chirps = array_map(
            function ($row) {
                return new Chirp(
                    $row['id'],
                    $row['chirp_text'],
                    $row['author'],
                    $row['created_at']
                );
            },
            $rows);

        return new ChirpCollection($chirps);
    }
}