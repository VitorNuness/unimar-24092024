<?php

use PHPUnit\Framework\TestCase;
use Src\GerenciadorPedidos;

class GerenciadorPedidosTest extends TestCase
{
   public function testAdicionarPedidos()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Fulano");

       $gerenciadorPedidos->adicionarItem("Batata", 20, 5);

       $this->assertEquals(1, count($gerenciadorPedidos->listarItens()));
       $this->assertEquals("Batata", $gerenciadorPedidos->listarItens()[0]["produto"]);
       $this->assertEquals(20, $gerenciadorPedidos->listarItens()[0]["quantidade"]);
       $this->assertEquals(5, $gerenciadorPedidos->listarItens()[0]["preco_unitario"]);
   }

   public function testAplicarDescontoValido()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Vitão");
       $gerenciadorPedidos->adicionarItem("TV", 1, 1000);
       $gerenciadorPedidos->aplicarDesconto("DESC10");
       $totalComDesconto = $gerenciadorPedidos->calcularTotal();
       $this->assertEquals(900, $totalComDesconto);
   }

   public function testAplicarDescontoInvalido()
   {
       $this->expectException(InvalidArgumentException::class);

       $gerenciadorPedidos = new GerenciadorPedidos("Vitão");

       $gerenciadorPedidos->aplicarDesconto("DESC99");
   }

   public function testCalcularTotalSemDesconto()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Fulano");

       $gerenciadorPedidos->adicionarItem("Produto A", 2, 50);
       $gerenciadorPedidos->adicionarItem("Produto B", 1, 100);

       $totalSemDesconto = $gerenciadorPedidos->calcularTotal();
       $this->assertEquals(200, $totalSemDesconto);
   }

   public function testValidarPedidoValido()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Fulano");

       $gerenciadorPedidos->adicionarItem("Produto A", 1, 100);

       $this->assertNull($gerenciadorPedidos->validarPedido());
   }

   public function testValidarPedidoSemItens()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Fulano");

       $this->expectException(RuntimeException::class);
       $gerenciadorPedidos->validarPedido();
   }

   public function testValidarPedidoTotalZero()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Fulano");
       $gerenciadorPedidos->adicionarItem("Produto A", 1, 0);
       $this->expectException(RuntimeException::class);
       $gerenciadorPedidos->validarPedido();
   }

   public function testConfirmarPedidoValido()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Fulano");

       $gerenciadorPedidos->adicionarItem("Produto A", 1, 100);

       $this->assertTrue($gerenciadorPedidos->confirmarPedido());
   }

   public function testConfirmarPedidoInvalido()
   {
       $gerenciadorPedidos = new GerenciadorPedidos("Fulano");

       $this->expectException(RuntimeException::class);
       $gerenciadorPedidos->confirmarPedido();
   }
}
