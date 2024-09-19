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

    public function deposito($valor)
    {
        if ($valor > 0) {
            $this->saldo += $valor;
            $this->historicoTransacoes[] = ["tipo" => "deposito", "valor" => $valor, "data" => date("Y-m-d H:i:s")];
    }
    }

    public function saque($valor)
    {
        if ($valor > 0 && $valor <= $this->saldo) {
            $this->saldo -= $valor;
            $this->historicoTransacoes[] = ["tipo" => "saque", "valor" => $valor, "data" => date("Y-m-d H:i:s")];
        }elseif ($valor > $this-> saldo) {
            throw new  \RuntimeException("Saldo insuficiente");
        }
    }

    public function transferir($valor, ContaBancaria $contaDestino)
    {
        if ($valor > 0 && $valor <= $this->saldo) {
            $this->saldo -= $valor;
            $contaDestino->deposito($valor);
            $this->historicoTransacoes[] = ["tipo" => "transferencia", "valor" => $valor, "data" => date("Y-m-d H:i:s"), "destino" => $contaDestino->getCliente()];
        }else {
            throw new \RuntimeException("Saldo insuficiente para a transferencia");
        }
    }

    public function calcularJuros($taxa)
    {
        if ($taxa > 0) {
            $juros = $this->saldo * ($taxa / 100);
            $this->saldo += $juros;
            $this->historicoTransacoes[] = ["tipo" => "juros", "valor" => $juros, "data" => date("Y-m-d H:i:s")];
    }
}

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function getHistoricoTransacoes()
    {
        return $this->historicoTransacoes;
    }


}