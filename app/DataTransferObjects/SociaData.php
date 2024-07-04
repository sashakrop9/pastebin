<?php

namespace App\DataTransferObjects;

class SociaData
{
    /**
     * @param string $socia_id
     * @param string $token
     * @param string $refresh_token
     * @param int $user_id
     */
    public function __construct(
        public string $socia_id,
        public string $token,
        public int $user_id,
    ) {}

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['socia_id'],
            $data['token'],
            $data['user_id']
        );
    }
}
