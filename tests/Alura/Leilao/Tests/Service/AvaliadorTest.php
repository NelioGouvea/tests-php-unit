<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;


    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoemOrdemAleatoria
     * @dataProvider leilaoemOrdemCrescente
     * @dataProvider leilaoemOrdemDecrescente
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {
        // Executa código a ser testado | Act - When
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Verifica se o resultado é o esperado | Assert - Then
        self::assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider leilaoemOrdemAleatoria
     * @dataProvider leilaoemOrdemCrescente
     * @dataProvider leilaoemOrdemDecrescente
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {
        // Executa código a ser testado | Act - When
        $this->leiloeiro->avalia($leilao);
        $menorValor =  $this->leiloeiro->getMenorValor();

        // Verifica se o resultado é o esperado | Assert - Then
        self::assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoemOrdemAleatoria
     * @dataProvider leilaoemOrdemCrescente
     * @dataProvider leilaoemOrdemDecrescente
     */
    public function testAvaliadorDeveBuscar3MaioresValores(Leilao $leilao)
    {
        // Executa código a ser testado | Act - When
        $this->leiloeiro->avalia($leilao);
        $maioresLances = $this->leiloeiro->getMaioresLances();

        // Verifica se o resultado é o esperado | Assert - Then
        self::assertCount(3, $maioresLances);
        self::assertEquals(2500, $maioresLances[0]->getValor());
        self::assertEquals(2000, $maioresLances[1]->getValor());
        self::assertEquals(1700, $maioresLances[2]->getValor());
    }

    /************** DADOS *************/
    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [
            'ordem-crescente'=>[$leilao]
        ];
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            'ordem-decrescente'=>[$leilao]
        ];
    }

    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            'ordem-aleatoria'=>[$leilao]
        ];
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio');
        $leilao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão finalizado');

        $leilao = new Leilao('Fiat 147 0KM');

        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }
}
