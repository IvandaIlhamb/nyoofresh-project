<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
use App\Filament\Pages\HomePageSettings;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\DroppingResource;
use App\Filament\Resources\GajiModalPengeluaranResource;
use App\Filament\Resources\GajiResource;
use App\Filament\Resources\HasilPenjualanResource;
use App\Filament\Resources\MonitoringRekapDroppingResource;
use App\Filament\Resources\MonitoringRekapLapakNyoofreshResource;
use App\Filament\Resources\PemasukanResource;
use App\Filament\Resources\PengaturanResource;
use App\Filament\Resources\PengeluaranResource;
use App\Filament\Resources\ProdukResource;
use App\Filament\Resources\RekapPenjualanResource;
use App\Filament\Resources\SuplaiResource;
use App\Filament\Resources\UserManualResource;
use App\Filament\Resources\UserResource;
use App\Models\Gaji;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->resources([
                SuplaiResource::class, // Pastikan resource ini terdaftar
            ])
            ->colors([
                'primary' => '#344CB7',
            ])
            ->brandLogo(asset('logo.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('logo.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    // Tambahkan item Dashboard di atas semua grup navigasi
                    ->items([
                        NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(fn(): string => route('filament.admin.pages.dashboard')),
                    ])
                    ->groups([
                        ...(!auth()->user()->hasRole('admin')  
                        ? [
                        NavigationGroup::make('Lapak')
                            ->items([
                                ...ProdukResource::getNavigationItems(),
                                ...MonitoringRekapDroppingResource::getNavigationItems(),
                                ...MonitoringRekapLapakNyoofreshResource::getNavigationItems(),
                                ...DroppingResource::getNavigationItems(),
                                ...HasilPenjualanResource::getNavigationItems(),
                                // ...RekapPenjualanResource::getNavigationItems(),
                                ...SuplaiResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Keuangan')
                            ->items([
                                // ...GajiModalPengeluaranResource::getNavigationItems(),
                                ...PemasukanResource::getNavigationItems(),
                                ...PengeluaranResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Pengaturan')
                            ->items([
                                ...PengaturanResource::getNavigationItems(),
                                ...UserManualResource::getNavigationItems(),
                            ]),
                        ]
                        : [
                            NavigationGroup::make('Lapak')
                            ->items([
                                ...ProdukResource::getNavigationItems(),
                                ...MonitoringRekapDroppingResource::getNavigationItems(),
                                ...MonitoringRekapLapakNyoofreshResource::getNavigationItems(),
                                ...DroppingResource::getNavigationItems(),
                                ...HasilPenjualanResource::getNavigationItems(),
                                ...RekapPenjualanResource::getNavigationItems(),
                            ]),
                            NavigationGroup::make('Keuangan')
                                ->items([
                                    // ...GajiModalPengeluaranResource::getNavigationItems(),
                                    ...GajiResource::getNavigationItems(),
                                    ...PemasukanResource::getNavigationItems(),
                                    ...PengeluaranResource::getNavigationItems(),
                                ]),       
                            NavigationGroup::make('Setting')
                                ->items([
                                    ...UserResource::getNavigationItems(),
                                    NavigationItem::make('Role')
                                        ->icon('heroicon-o-user-group')
                                        ->isActiveWhen(fn(): bool => request()->routeIs([
                                            'filament.admin.resources.role.index',
                                            'filament.admin.resources.role.create',
                                            'filament.admin.resources.role.view',
                                            'filament.admin.resources.role.edit',
                                        ]))
                                        ->url(fn():string=> '/admin/roles'),
                                    NavigationItem::make('Permission')
                                        ->icon('heroicon-o-lock-closed')
                                        ->isActiveWhen(fn(): bool => request()->routeIs([
                                            'filament.admin.resources.permission.index',
                                            'filament.admin.resources.permission.create',
                                            'filament.admin.resources.permission.view',
                                            'filament.admin.resources.permission.edit',
                                        ]))
                                        ->url(fn():string=> '/admin/permissions'),
                                ]),
                            ])
                    ]);
            });
    }
}
