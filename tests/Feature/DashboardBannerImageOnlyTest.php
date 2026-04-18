<?php

namespace Tests\Feature;

use App\Models\DashboardBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardBannerImageOnlyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_dashboard_banner_without_image(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.dashboard-banners.store'), [
            'order' => 1,
            'is_active' => 1,
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_admin_can_create_dashboard_banner_with_valid_image(): void
    {
        Storage::fake('public');
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.dashboard-banners.store'), [
            'order' => 1,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('banner.jpg', 1600, 600),
        ]);

        $response->assertRedirect(route('admin.dashboard-banners.index'));
        $this->assertDatabaseCount('dashboard_banners', 1);

        $banner = DashboardBanner::query()->first();
        $this->assertInstanceOf(DashboardBanner::class, $banner);
        $this->assertTrue($banner->is_active);
        $this->assertNotEmpty($banner->image);
        $this->assertTrue(Storage::disk('public')->exists($banner->image));
    }

    public function test_admin_can_create_dashboard_banner_with_non_8_by_3_ratio_image(): void
    {
        Storage::fake('public');
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.dashboard-banners.store'), [
            'order' => 1,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('banner-non-ratio.jpg', 1400, 1000),
        ]);

        $response->assertRedirect(route('admin.dashboard-banners.index'));
        $this->assertDatabaseCount('dashboard_banners', 1);

        $banner = DashboardBanner::query()->first();
        $this->assertInstanceOf(DashboardBanner::class, $banner);
        $this->assertNotEmpty($banner->image);
        $this->assertTrue(Storage::disk('public')->exists($banner->image));
    }

    public function test_user_dashboard_only_loads_active_banners_with_image(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        DashboardBanner::query()->create([
            'title' => 'No image',
            'order' => 1,
            'is_active' => true,
            'image' => null,
        ]);

        DashboardBanner::query()->create([
            'title' => 'Inactive image',
            'order' => 2,
            'is_active' => false,
            'image' => 'dashboard-banners/inactive.jpg',
        ]);

        DashboardBanner::query()->create([
            'title' => 'Valid',
            'order' => 3,
            'is_active' => true,
            'image' => 'dashboard-banners/valid.jpg',
        ]);

        $response = $this->actingAs($user)->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertSee('storage/dashboard-banners/valid.jpg', false);
        $response->assertDontSee('storage/dashboard-banners/inactive.jpg', false);
    }
}
