<?php

namespace App\Livewire\Public;

use App\Models\ITAsset;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class DetailAsset extends Component
{
    public $asset;

    public $assetId;

    public function mount($assetId)
    {
        $this->asset = ITAsset::where('assetId', $assetId)->first();
    }

    #[Title('Detail Asset')]
    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.public.detail-asset', [
            'asset' => $this->asset,
        ]);
    }
}
