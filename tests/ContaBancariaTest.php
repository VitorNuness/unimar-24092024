<?php

use PHPUnit\Framework\TestCase;
use Src\ContaBancaria;

class ContaBancariaTest extends TestCase
{
    public function testSaldoAtualizadoAposDeposito()
    {
        $contaBancaria = new ContaBancaria("Fulano", 0);

        $contaBancaria->deposito(300);

        $this->assertEquals(300, $contaBancaria->getSaldo());
    }

    public function testDepositoZeradoOuNegativoRecusado()
    {
        $contaBancaria = new ContaBancaria("Fulano", 0);

        $contaBancaria->deposito(0);
        $contaBancaria->deposito(-1);

        $this->assertEquals(0, $contaBancaria->getSaldo());
    }

    public function testDepositoRegistradoNoHistorico()
    {
        $contaBancaria = new ContaBancaria("Fulano", 0);

        $contaBancaria->deposito(300);

        $this->assertEquals(1, count($contaBancaria->getHistoricoTransacoes()));
        $this->assertEquals("deposito", $contaBancaria->getHistoricoTransacoes()[0]["tipo"]);
        $this->assertEquals(300, $contaBancaria->getHistoricoTransacoes())[0]["valor"];
        $this->assertEquals(date("d/m/y"), $contaBancaria->getHistoricoTransacoes()[0]["data"]);
    }

    public function testSaldoAtualizadoAposSaque()
    {
        $contaBancaria = new ContaBancaria("Fulano", 300);

        $contaBancaria->saque(10);

        $this->assertEquals(290, $contaBancaria->getSaldo());
    }

    public function testSaqueMaiorQueSaldoNegado()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Saque negado.");

        $contaBancaria = new ContaBancaria("Fulano", 0);

        $contaBancaria->saque(10);

        $this->assertEquals(0, $contaBancaria->getSaldo());
    }

    public function testSaqueZeroOuNegativoNegado()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Saque negado.");

        $contaBancaria = new ContaBancaria("Fulano", 300);

        $contaBancaria->saque(0);
        $contaBancaria->saque(-1);

        $this->assertEquals(300, $contaBancaria->getSaldo());
    }

    public function testSaqueRegistradoNoHistorico()
    {
        $contaBancaria = new ContaBancaria("Fulano", 300);

        $contaBancaria->saque(10);

        $this->assertEquals(1, count($contaBancaria->getHistoricoTransacoes()));
        $this->assertEquals("saque", $contaBancaria->getHistoricoTransacoes()[0]["tipo"]);
        $this->assertEquals(10, $contaBancaria->getHistoricoTransacoes())[0]["valor"];
        $this->assertEquals(date("d/m/y"), $contaBancaria->getHistoricoTransacoes()[0]["data"]);
    }

    public function testTransacaoAtualizaAmbosOsSaldos()
    {
        $contaBancaria1 = new ContaBancaria("Fulano 1", 300);
        $contaBancaria2 = new ContaBancaria("Fulano 2", 500);

        $contaBancaria1->transferir(10, $contaBancaria2);

        $this->assertEquals(290, $contaBancaria1->getSaldo());
        $this->assertEquals(510, $contaBancaria2->getSaldo());
    }

    public function testTransacaoMaiorQueSaldoNegada()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Transação negada.");

        $contaBancaria1 = new ContaBancaria("Fulano 1", 300);
        $contaBancaria2 = new ContaBancaria("Fulano 2", 500);

        $contaBancaria1->transferir(310, $contaBancaria2);

        $this->assertEquals(300, $contaBancaria1->getSaldo());
        $this->assertEquals(500, $contaBancaria2->getSaldo());
    }

    public function testTransacaoRegistradaNoHistoricoDeAmbos()
    {
        $contaBancaria1 = new ContaBancaria("Fulano 1", 300);
        $contaBancaria2 = new ContaBancaria("Fulano 2", 500);

        $contaBancaria1->transferir(10, $contaBancaria2);

        $this->assertEquals(1, count($contaBancaria1->getHistoricoTransacoes()));
        $this->assertEquals("transacao enviada", $contaBancaria1->getHistoricoTransacoes()[0]["tipo"]);
        $this->assertEquals(10, $contaBancaria1->getHistoricoTransacoes()[0]["valor"]);
        $this->assertEquals(date('d/m/Y'), $contaBancaria1->getHistoricoTransacoes()[0]["data"]);

        $this->assertEquals(1, count($contaBancaria2->getHistoricoTransacoes()));
        $this->assertEquals("transacao recebida", $contaBancaria1->getHistoricoTransacoes()[0]["tipo"]);
        $this->assertEquals(10, $contaBancaria1->getHistoricoTransacoes()[0]["valor"]);
        $this->assertEquals(date('d/m/Y'), $contaBancaria1->getHistoricoTransacoes()[0]["data"]);
    }

    public function testJurosCalculadoEAdicionadoAoSaldo()
    {
        $contaBancaria = new ContaBancaria("Fulano", 300);

        $contaBancaria->calcularJuros(1);

        $this->assertEquals(303, $contaBancaria->getSaldo());
    }

    public function testCalcularJurosZeroOuNegativoNegado()
    {
        $contaBancaria = new ContaBancaria("Fulano", 300);

        $contaBancaria->calcularJuros(0);
        $contaBancaria->calcularJuros(-1);

        $this->assertEquals(300, $contaBancaria->getSaldo());
    }

    public function testJurosAdicionadoAoHistorico()
    {
        $contaBancaria = new ContaBancaria("Fulano", 300);

        $contaBancaria->calcularJuros(1);

        $this->assertEquals(1, count($contaBancaria->getHistoricoTransacoes()));
        $this->assertEquals("juros", $contaBancaria->getHistoricoTransacoes()[0]["tipo"]);
        $this->assertEquals(3, $contaBancaria->getHistoricoTransacoes()[0]["valor"]);
        $this->assertEquals(date('d/m/Y'), $contaBancaria->getHistoricoTransacoes()[0]["data"]);
    }

    public function testRetornarCliente()
    {
        $contaBancaria = new ContaBancaria("Fulano", 300);

        $this->assertEquals("Fulano", $contaBancaria->getCliente());
    }
}
