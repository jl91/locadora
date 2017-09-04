<?php

namespace VideoPlace\Model\Database\Entity;


class UserEntity implements EntityInterface
{

    /**
     * @var int
     */
    private $id = null;

    /**
     * @var string
     */
    private $name = null;
    /**
     * @var string
     */
    private $email = null;
    /**
     * @var \DateTimeImmutable
     */
    private $createdAt = null;

    use DefaultEntityConstructor;

    public function getIdColumnName(): string
    {
        return 'id';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email ?? '';
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getTableName(): string
    {
        return 'user';
    }

    public function __set($name, $value)
    {
        if ($name == 'created_at') {
            $this->setCreatedAt(new \DateTimeImmutable($value));
        }
    }


}