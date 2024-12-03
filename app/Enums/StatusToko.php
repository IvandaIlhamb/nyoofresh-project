<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
 
enum StatusToko: string implements HasLabel, HasColor, HasIcon
{
    case Buka = 'Buka';
    case Tutup = 'Tutup';
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Buka => 'Buka',
            self::Tutup => 'Tutup',
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Buka => 'success',
            self::Tutup => 'danger',
        };
    }
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Buka => 'heroicon-o-check-circle',
            self::Tutup => 'heroicon-o-x-circle',
        };
    }
}