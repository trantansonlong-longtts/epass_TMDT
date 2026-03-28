<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateHomepageContentRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminHomepageContentController extends Controller
{
    public function edit(): View
    {
        $content = SiteSetting::homepageContent();

        return view('admin.content.homepage', compact('content'));
    }

    public function update(UpdateHomepageContentRequest $request): RedirectResponse
    {
        $defaults = SiteSetting::homepageDefaults();
        $payload = [];

        foreach ($defaults as $key => $defaultValue) {
            if (str_ends_with($key, '_image')) {
                $payload[$key] = $this->storeImageSetting($request, $key, $defaults[$key]);
                continue;
            }

            $payload[$key] = $request->validated($key);
        }

        SiteSetting::putMany($payload);

        return redirect()
            ->route('admin.content.homepage.edit')
            ->with('success', 'Đã cập nhật nội dung trang chủ.');
    }

    private function storeImageSetting(UpdateHomepageContentRequest $request, string $key, mixed $default): ?string
    {
        $current = SiteSetting::homepageContent()[$key] ?? $default;

        if (! $request->hasFile($key)) {
            return $current;
        }

        if ($current) {
            Storage::disk($this->uploadDisk())->delete($current);
        }

        return $request->file($key)->store('homepage', $this->uploadDisk());
    }

    private function uploadDisk(): string
    {
        return (string) config('filesystems.upload_disk', 'public');
    }
}
