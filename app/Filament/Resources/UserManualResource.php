<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserManualResource\Pages;
use App\Filament\Resources\UserManualResource\RelationManagers;
use App\Models\UserManual;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserManualResource extends Resource
{
    // protected static ?string $model = UserManual::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function getNavigationUrl(): string
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return asset('user-manual-admin.pdf');
        } elseif ($user->hasRole('penjaga lapak')) {
            return asset('user-manual-penjagalapak.pdf');
        }elseif ($user->hasRole('dropping')) {
            return asset('user-manual-dropping.pdf');
        }elseif ($user->hasRole('supplier')) {
            return asset('user-manual-supplier.pdf');
        }
 
    }
}
