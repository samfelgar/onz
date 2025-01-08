<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Brick\Math\RoundingMode;
use Samfelgar\PixValidator\PixInterface;
use Webmozart\Assert\Assert;

readonly class ChargeRequest implements \JsonSerializable
{
    public function __construct(
        public string $txId,
        public int $expirationInSeconds,
        public ?Payer $payer,
        public ?string $loc,
        public Amount $amount,
        public PixInterface $pix,
        public ?string $payerRequestText,
    ) {
        Assert::regex($this->txId, '/^[a-zA-Z0-9]{26,35}$/');
        Assert::nullOrMaxLength($this->payerRequestText, 140);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'calendario' => [
                'expiracao' => $this->expirationInSeconds,
            ],
            'valor' => [
                'original' => (string)$this->amount->original->toScale(2, RoundingMode::HALF_UP),
                'modalidadeAlteracao' => $this->amount->canChange ? 1 : 0,
            ],
            'chave' => (string)$this->pix,
        ];

        if ($this->payerRequestText !== null) {
            $data['solicitacaoPagador'] = $this->payerRequestText;
        }

        if ($this->payer !== null) {
            $data['devedor'] = [
                $this->payer->document->key() => $this->payer->document->value(),
                'nome' => $this->payer->name,
                'contas' => \array_map(static function (Account $account): array {
                    return [
                        'numero' => $account->account,
                        'agencia' => $account->branch,
                        'ispb' => $account->ispb,
                    ];
                }, $this->payer->accounts),
            ];
        }

        if ($this->loc !== null) {
            $data['loc'] = $this->loc;
        }

        return $data;
    }
}
