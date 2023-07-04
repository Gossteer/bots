<?php

declare(strict_types=1);

namespace App\Infrastructure\Utils;

use App\Domain\SharedCore\Port\Utils\IUuidService;

class UuidService implements IUuidService
{
    private ?string $hostnameHashPart = null;

    public function getUuid(): string
    {
        return $this->uuidV7();
    }

    /**
     * Стандартная версия uuid для всех наших сервисов - v7
     * Данная реализация, считающая время с точностью до 10 нс является оптимальной для PHP
     * @see https://datatracker.ietf.org/doc/html/draft-peabody-dispatch-new-uuid-format-01#section-4.4
     */
    public function uuidV7(): string
    {
        $time = microtime(true);
        $timeStamp = (int)$time;
        $nanoSeconds = $time - $timeStamp;
        $hexTs = $this->leadingZero(9, dechex($timeStamp));
        $subSeconds = $this->leadingZero(6, dechex($this->fractionToBits($nanoSeconds)));
        $var = dechex(0b1000000000000000 | (int)bindec($this->getHostNameHash()));
        return substr($hexTs, 0, 8) . "-" . substr($hexTs, 8, 1) . substr($subSeconds, 0, 3) //timeComponent
            . "-7" . substr($subSeconds, 3, 3) . "-" . $var . "-"
            . $this->getRndPart() . $this->getRndPart();
    }

    private function getRndPart(): string
    {
        $rndPart = dechex(mt_rand(0, 0xffffff));
        return $this->leadingZero(6, $rndPart);
    }

    private function leadingZero(int $needLength, string $part): string
    {
        $l = strlen($part);
        if ($needLength > $l) {
            return str_repeat("0", $needLength - $l) . $part;
        }
        return $part;
    }

    private function getHostNameHash(): string
    {
        if (null === $this->hostnameHashPart) {
            $this->hostnameHashPart = mb_substr(
                base_convert(
                    mb_substr((string)preg_replace("~[^a-z]~u", "", (string)gethostname()), 0, 4),
                    36,
                    2,
                ),
                0,
                14,
            );
        }
        return $this->hostnameHashPart;
    }

    private function fractionToBits(float $fraction): int
    {
        //в обратную сторону: $bits / 16777216
        return (int)round($fraction * 16777216); // 2**24 - 24 bits
    }

    public function getUuidWithoutDashes(): string
    {
        return (string)str_replace('-', '', self::getUuid());
    }
}
