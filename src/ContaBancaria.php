<?php

namespace Src;

class ContaBancaria
{
    private $saldo;
    private $cliente;
    private $historicoTransacoes;

    public function __construct($cliente, $saldoInicial = 0)
    {
        $this->cliente = $cliente;
        $this->saldo = $saldoInicial;
        $this->historicoTransacoes = [];
    }
}
