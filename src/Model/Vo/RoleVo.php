<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Model\Vo;

use App\Util\ValidUtil;
use Symfony\Component\Validator\Constraints as Assert;

class RoleVo
{
    #[Assert\NotBlank(message: 'The role code should not be blank.')]
    #[Assert\Regex(pattern: '/^\w+$/')]
    private ?string $code = null;

    private ?string $comment = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        if (true === ValidUtil::isString($code)) {
            $this->code = 'ROLE_' . strtoupper($code);
        }
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
}