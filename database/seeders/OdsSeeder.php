<?php

namespace Database\Seeders;

use App\Models\Ods;
use Illuminate\Database\Seeder;

class OdsSeeder extends Seeder
{
    public function run(): void
    {
        $ods = [
            ['number' => 1, 'title' => 'Erradicar a Pobreza', 'description' => 'Acabar com a pobreza em todas as suas formas.', 'color' => '#e5243b'],
            ['number' => 2, 'title' => 'Erradicar a Fome', 'description' => 'Acabar com a fome e alcancar a seguranca alimentar.', 'color' => '#dda63a'],
            ['number' => 3, 'title' => 'Saude de Qualidade', 'description' => 'Assegurar uma vida saudavel e promover o bem-estar para todos.', 'color' => '#4c9f38'],
            ['number' => 4, 'title' => 'Educacao de Qualidade', 'description' => 'Assegurar a educacao inclusiva e equitativa e de qualidade.', 'color' => '#c5192d'],
            ['number' => 5, 'title' => 'Igualdade de Genero', 'description' => 'Alcancar a igualdade de genero e empoderar todas as mulheres.', 'color' => '#ff3a21'],
            ['number' => 6, 'title' => 'Agua Potavel e Saneamento', 'description' => 'Assegurar a disponibilidade e gestao sustentavel da agua.', 'color' => '#26bde2'],
            ['number' => 7, 'title' => 'Energias Renovaveis e Acessiveis', 'description' => 'Assegurar o acesso a energia sustentavel e acessivel.', 'color' => '#fcc30b'],
            ['number' => 8, 'title' => 'Trabalho Digno e Crescimento Economico', 'description' => 'Promover o crescimento economico sustentado e inclusivo.', 'color' => '#a21942'],
            ['number' => 9, 'title' => 'Industria, Inovacao e Infraestruturas', 'description' => 'Construir infraestruturas resilientes e promover a industrializacao.', 'color' => '#fd6925'],
            ['number' => 10, 'title' => 'Reduzir as Desigualdades', 'description' => 'Reduzir a desigualdade dentro dos paises e entre eles.', 'color' => '#dd1367'],
            ['number' => 11, 'title' => 'Cidades e Comunidades Sustentaveis', 'description' => 'Tornar as cidades inclusivas, seguras, resilientes e sustentaveis.', 'color' => '#fd9d24'],
            ['number' => 12, 'title' => 'Producao e Consumo Responsaveis', 'description' => 'Assegurar padroes de producao e de consumo sustentaveis.', 'color' => '#bf8b2e'],
            ['number' => 13, 'title' => 'Acao Contra a Mudanca Global do Clima', 'description' => 'Tomar medidas urgentes para combater a mudanca climatica.', 'color' => '#3f7e44'],
            ['number' => 14, 'title' => 'Vida na Agua', 'description' => 'Conservacao e uso sustentavel dos oceanos e recursos marinhos.', 'color' => '#0a97d9'],
            ['number' => 15, 'title' => 'Vida Terrestre', 'description' => 'Proteger e promover o uso sustentavel dos ecossistemas terrestres.', 'color' => '#56c02b'],
            ['number' => 16, 'title' => 'Paz, Justica e Instituicoes Eficazes', 'description' => 'Promover sociedades pacificas e inclusivas para o desenvolvimento.', 'color' => '#00689d'],
            ['number' => 17, 'title' => 'Parcerias e Meios de Implementacao', 'description' => 'Fortalecer os meios de implementacao e revitalizar a parceria global.', 'color' => '#19486a'],
        ];

        foreach ($ods as $item) {
            Ods::updateOrCreate(['number' => $item['number']], array_merge($item, ['active' => true]));
        }
    }
}
