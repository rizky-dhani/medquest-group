<?php

namespace App\Filament\Resources\ITD\ITAssets\Widgets;

use App\Filament\Resources\ITD\ITAssets\ITAssetResource;
use App\Models\Company;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ITAssetWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Assets by Company';

    protected function getStats(): array
    {
        $stats = [];

        // Get companies with asset counts using Eloquent
        $companiesWithCounts = Company::withCount([
            'asset as total_assets_count',
            'asset as in_use_assets_count' => function ($query) {
                $query->whereNotNull('asset_user_id');
            },
            'asset as available_assets_count' => function ($query) {
                $query->whereNull('asset_user_id');
            },
            'asset as defect_assets_count' => function ($query) {
                $query->where('asset_condition', 'Defect');
            },
        ])
            ->having('total_assets_count', '>', 0)
            ->orderByDesc('total_assets_count')
            ->get();

        foreach ($companiesWithCounts as $company) {
            // Use the counted values from withCount for efficiency
            $totalAssets = $company->total_assets_count;
            $inUseAssets = $company->in_use_assets_count;
            $availableAssets = $company->available_assets_count;
            $defectAssets = $company->defect_assets_count;

            // Total assets stat for company
            $stats[] = Stat::make($company->initials ?? $company->name, (string) $totalAssets)
                ->description('Total Assets')
                ->color('primary')
                ->icon('heroicon-o-computer-desktop')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'company_id' => [
                            'value' => $company->id,
                        ],
                    ],
                ]));

            // Available assets stat for company
            $stats[] = Stat::make($company->initials ?? $company->name, (string) $availableAssets)
                ->description('Available')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'company_id' => [
                            'value' => $company->id,
                        ],
                        'asset_status' => [
                            'available' => 'true',
                            'in_use' => 'false',
                        ],
                    ],
                ]));

            // In use assets stat for company
            $stats[] = Stat::make($company->initials ?? $company->name, (string) $inUseAssets)
                ->description('In Use')
                ->color('warning')
                ->icon('heroicon-o-user')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'company_id' => [
                            'value' => $company->id,
                        ],
                        'asset_status' => [
                            'available' => 'false',
                            'in_use' => 'true',
                        ],
                    ],
                ]));

            // Defect assets stat for company (if any)
            if ($defectAssets > 0) {
                $stats[] = Stat::make($company->initials ?? $company->name, (string) $defectAssets)
                    ->description('Defect')
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->url(ITAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'company_id' => [
                                'value' => $company->id,
                            ],
                            'asset_condition' => [
                                'value' => 'Defect',
                            ],
                        ],
                    ]));
            }
        }

        return $stats;
    }
}
