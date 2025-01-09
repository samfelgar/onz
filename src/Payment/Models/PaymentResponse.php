<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

use Brick\Math\BigDecimal;
use Psr\Http\Message\ResponseInterface;

readonly class PaymentResponse
{
    public function __construct(
        public string $endToEndId,
        public \DateTimeImmutable $eventDate,
        public int $id,
        public PaymentAmount $paymentAmount,
        public string $type,
    ) {}

    public static function fromArray(array $data): PaymentResponse
    {
        $formats = [\DateTimeInterface::RFC3339_EXTENDED, \DateTimeInterface::ATOM];

        $eventDate = null;
        foreach ($formats as $format) {
            $date = \DateTimeImmutable::createFromFormat($format, $data['eventDate']);
            if ($date !== false) {
                $eventDate = $date;
            }
        }

        if ($eventDate === null) {
            throw new \InvalidArgumentException('Invalid event date format');
        }

        return new PaymentResponse(
            $data['endToEndId'],
            $eventDate,
            $data['id'],
            new PaymentAmount(
                BigDecimal::of($data['payment']['amount']),
                $data['payment']['currency'],
            ),
            $data['type'],
        );
    }

    public static function fromResponse(ResponseInterface $response): PaymentResponse
    {
        $data = \json_decode((string)$response->getBody(), true);
        return self::fromArray($data);
    }
}
