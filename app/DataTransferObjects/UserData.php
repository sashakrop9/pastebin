<?php

namespace App\DataTransferObjects;

class UserData
{
    /**
     * @param string $name
     * @param string $email
     * @param string|null $password
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password,
    ) {}

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['password'],
        );
    }
}
