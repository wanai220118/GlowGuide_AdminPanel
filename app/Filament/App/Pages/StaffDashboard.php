<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class StaffDashboard extends Page
{
    // StaffDashboard.php
    public static function canAccess(): bool
    {
        return auth()->user()->role === 'staff';
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.staff-dashboard';
}
