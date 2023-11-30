<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if(!empty($this->lances) && $this->ehDoUltimoUsuario($lance)){
            return;
        }

        $totalLancesPorUsuario = $this->qtdeLancesPorUsuario($lance->getUsuario());
        if($totalLancesPorUsuario >= 5){
            return ;
        }

        $this->lances[] = $lance;
    }

    /**
     * @param Lance $lance
     * @return bool
     */
    private function ehDoUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)]->getUsuario();
        return $lance->getUsuario() == $ultimoLance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    /**
     * @param Usuario $usuario
     * @return int
     */
    private function qtdeLancesPorUsuario(Usuario $usuario): int
    {
        $totalLancesUsuario =  array_reduce($this->lances, function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
            if ($lanceAtual->getUsuario() == $usuario) {
                return $totalAcumulado + 1;
            }
            return $totalAcumulado;
        }, 0);

        return $totalLancesUsuario;
    }
}
