<?php

namespace App\Mappers\DTO;

class InventoryExpirationMetricsDataDTO implements \JsonSerializable
{
    private int $expired;

    private int $expiredSoon;

    private int $endangered;

    public function __construct(
        ?int $expired,
        ?int $expiredSoon,
        ?int $endangered
    ) {
        $this->expired = $expired;
        $this->expiredSoon = $expiredSoon;
        $this->endangered = $endangered;
    }

    public function getExpired(): ?int
    {
        return $this->expired;
    }

    public function getExpiredSoon(): ?int
    {
        return $this->expiredSoon;
    }

    public function getEndangered(): ?int
    {
        return $this->endangered;
    }

    // --- SETTERS ---

    public function setExpired(?int $expired): self
    {
        $this->expired = $expired;

        return $this; // Permite encadenamiento de métodos
    }

    public function setExpiredSoon(?int $expiredSoon): self
    {
        $this->expiredSoon = $expiredSoon;

        return $this;
    }

    public function setEndangered(?int $endangered): self
    {
        $this->endangered = $endangered;

        return $this;
    }

    /**
     * Define cómo se serializa el objeto a JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'expired' => $this->expired,
            'expiredSoon' => $this->expiredSoon,
            'endangered' => $this->endangered,
        ];
    }
}
