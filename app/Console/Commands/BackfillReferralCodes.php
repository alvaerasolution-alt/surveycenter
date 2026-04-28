<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillReferralCodes extends Command
{
    protected $signature = 'users:backfill-referral-codes';
    protected $description = 'Generate referral codes for existing users that do not have one';

    public function handle(): int
    {
        $users = User::whereNull('referral_code')
            ->orWhere('referral_code', '')
            ->get();

        if ($users->isEmpty()) {
            $this->info('All users already have referral codes.');
            return self::SUCCESS;
        }

        $this->info("Found {$users->count()} user(s) without referral code.");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $user->referral_code = User::generateReferralCode($user->name);
            $user->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done! All users now have referral codes.');

        return self::SUCCESS;
    }
}
