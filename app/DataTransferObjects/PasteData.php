<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

class PasteData
{
    /**
     * @param string $title
     * @param string $paste_content
     * @param string $access
     * @param string $expires_at
     * @param string $language
     * @param int $user_id
     * @param string $hash
     */
    public function __construct(
        public string $title,
        public string $paste_content,
        public string $access,
        public ?string $expires_at,
        public string $language,
        public int $user_id,
        public string $hash
    ) {}

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['paste_content'],
            $data['access'],
            $data['expires_at'],
            $data['language'],
            $data['user_id'],
            $data['hash']
        );
    }
}
