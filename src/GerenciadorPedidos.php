<?php

namespace Src;

use InvalidArgumentException;
use RuntimeException;

class GerenciadorPedidos
{
    private string $cliente;
    private array $itens;
    private float $desconto;

    public function __construct(
        string $cliente
    ) {
        $this->cliente = $cliente;
        $this->itens = [];
        $this->desconto = 0;
    }

    public function adicionarItem(string $produto, int $quantidade, float $preco): void
    {
        $this->itens[] = [
            "produto" => $produto,
            "quantidade" => $quantidade,
            "preco_unitario" => $preco
        ];
    }

    public function listarItens(): array
    {
        return $this->itens;
    }

    public function aplicarDesconto(string $codigo): void
    {
        if (strlen($codigo) > 6) {
            throw new InvalidArgumentException("Código inválido.");
        }

        if (!str_contains($codigo, "DESC")) {
            throw new InvalidArgumentException("Código inválido.");
        }

        $desc_value = intval(substr($codigo, -2));

        if ($desc_value <= 0) {
            throw new InvalidArgumentException("Código inválido.");
        }

        $this->desconto += $desc_value;
    }

    public function calcularTotal(): float
    {
        $itens_total = 0;

        foreach ($this->itens as $item) {
            $itens_total += $item["quantidade"] * $item["preco_unitario"];
        }

        return $itens_total * (1 - $this->desconto / 100);
    }

    public function validarPedido(): void
    {
        if (count($this->itens) <= 0 || $this->calcularTotal() <= 0) {
            throw new RuntimeException("Pedido está inválido.");
        }
    }

    public function confirmarPedido(): bool
    {
        $this->validarPedido();

        // Lógica para salvar no banco e notificar o cliente...

        return true;
    }
}
