<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Entity\Main;

use App\Const\AppConst;
use App\Repository\Main\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Serializer\Groups(['user.list', 'user.info'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Serializer\Groups(['user.list'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[Assert\NotBlank]
    #[Serializer\Accessor(getter: 'getRoles')]
    #[Serializer\Type('array')]
    #[Serializer\Groups(['user.list', 'user.info'])]
    #[ORM\Column(type: Types::JSON)]
    private ?array $roles = [];


    #[Assert\NotBlank]
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Serializer\Groups(['user.list', 'user.info'])]
    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Serializer\Groups(['user.list', 'user.info'])]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $email = '';

    #[Assert\NotBlank]
    #[Serializer\Groups(['user.list', 'user.info'])]
    #[ORM\Column(length: 15, nullable: true)]
    private ?string $phone = '';

    #[Serializer\Groups(['user.list', 'user.info'])]
    #[ORM\Column(length: 15, nullable: true)]
    private ?string $tel = '';

    #[Serializer\Groups(['user.info'])]
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $comName = '';

    #[Serializer\Groups(['user.info'])]
    #[ORM\Column(length: 12, nullable: true)]
    private ?string $comNumber = '';



    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $recharge = '0';

    #[Assert\NotBlank]
    #[Serializer\Groups(['user.list'])]
    #[ORM\Column(length: 15, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?int $parentId = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lastInhNumber = '1';

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $approval = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $approvalNote = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $sellerId = null;



    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $status = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // todo 临时兼容代码
        $roles = [];
        foreach ($this->roles as $role) {
            if ('50' === $role) {
                $roles[] = AppConst::ROLE_SELLER;
            } else if ('70' === $role) {
                $roles[] = AppConst::ROLE_BRANCH;
            } else if ('80' === $role) {
                $roles[] = AppConst::ROLE_DISTRIBUTOR;
            } else if ('90' === $role) {
                $roles[] = AppConst::ROLE_HEAD_OFFICE;
            } else if ('99' === $role) {
                $roles[] = AppConst::ROLE_ADMIN;
            }
        }
        $new = array_diff($this->roles, ['50', '70', '80', '90', '99']);
        foreach ($new as $role) {
            if (false === in_array($role, $roles, true)) {
                $roles[] = $role;
            }
        }
        return array_unique($roles);
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getComName(): ?string
    {
        return $this->comName;
    }

    public function setComName(?string $comName): static
    {
        $this->comName = $comName;

        return $this;
    }

    public function getComNumber(): ?string
    {
        return $this->comNumber;
    }

    public function setComNumber(?string $comNumber): static
    {
        $this->comNumber = $comNumber;

        return $this;
    }

    public function getRecharge(): ?string
    {
        return $this->recharge;
    }

    public function setRecharge(string $recharge): static
    {
        $this->recharge = $recharge;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): static
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getLastInhNumber(): ?string
    {
        return $this->lastInhNumber;
    }

    public function setLastInhNumber(?string $lastInhNumber): static
    {
        $this->lastInhNumber = $lastInhNumber;

        return $this;
    }

    public function getApproval(): ?int
    {
        return $this->approval;
    }

    public function setApproval(?int $approval): static
    {
        $this->approval = $approval;

        return $this;
    }

    public function getApprovalNote(): ?string
    {
        return $this->approvalNote;
    }

    public function setApprovalNote(?string $approvalNote): static
    {
        $this->approvalNote = $approvalNote;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt?->format('Y-m-d H:i:s');
    }

    public function setCreatedAt(\DateTimeInterface $dateTime): static
    {
        $this->createdAt = $dateTime;

        return $this;
    }

    public function getSellerId(): ?string
    {
        return $this->sellerId;
    }

    public function setSellerId(?string $sellerId): static
    {
        $this->sellerId = $sellerId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
