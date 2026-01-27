<?php

namespace App\Support;

use Illuminate\Support\Str;

class CategoryIconResolver
{
    public static function resolve(?string $categoryName): string
    {
        $name = Str::of($categoryName ?? '')->lower()->trim();

        return match (true) {
            $name->contains(['quim', 'reactivo', 'quím']) => 'heroicon-o-beaker',
            $name->contains(['herramient', 'tool']) => 'heroicon-o-wrench-screwdriver',
            $name->contains(['electr', 'robot', 'circuito']) => 'heroicon-o-bolt',
            $name->contains(['seguridad', 'safety', 'protección']) => 'heroicon-o-shield-check',
            $name->contains(['biolog', 'vida', 'cultivo']) => 'heroicon-o-leaf',
            $name->contains(['medici', 'medición', 'measurement', 'sensor']) => 'heroicon-o-chart-bar',
            $name->contains(['impres', '3d', 'fabricación', 'protoboard']) => 'heroicon-o-cube',
            $name->contains(['consumible', 'papelería']) => 'heroicon-o-clipboard-document-list',
            default => 'heroicon-o-archive-box',
        };
    }
}
