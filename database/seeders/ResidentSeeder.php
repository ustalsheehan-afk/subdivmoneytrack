<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResidentSeeder extends Seeder
{
    public function run()
    {
        $residents = [
            'Giahn Acido',
            'Mark Lawrence Alfarero',
            'Shannel Heart Alpuerto',
            'Jeah Amodia - girl',
            'Jenny Mae Arnado - girl',
            'Dan Harry Aves',
            'Messiah Bermudez - girl',
            'Clarence Besina',
            'Vinze Lianne Cinco',
            'Tristan Degamo',
            'Mhary Glyze Dungog',
            'Charles Dupalco',
            'Cyrus Ace Durano',
            'Hannah Mae Embong - girl',
            'Mark Espinosa',
            'Steven Jeff Godinez',
            'Francis Dave Hilvano',
            'Cindy Marie Igot - girl',
            'Sean Klifford Inot',
            'James Lowell Jugarap',
            'John Albert Lucero',
            'Mark Lee Lucero',
            'Jonard Maloloy-On',
            'Rex Sem Matutinao',
            'Jhon Raffy Montecastro',
            'Bernadette Narandan',
            'Neuton John Paz',
            'Melchor Perjes',
            'Carl Pino',
            'Sydney Pinote',
            'Bee Jay Ponteras',
            'John Carlo Pueblas',
            'Judel Quintero',
            'Mark Joseph Roble',
            'Sheena Rose Rosales - girl',
            'James Gann Sala',
            'Sheraine Faith Santesas - girl',
            'Jake Tolin',
            'Justin Tolomea',
            'Jethro Ybalez',
            'Reuven Ybañez',
            'Vergel Ycong',
            'Sai Vyean Zapanta - girl',
        ];

        $photoFiles = array_values(array_filter(
            Storage::disk('public')->files('residents'),
            fn ($path) => preg_match('/\.(jpe?g|png|gif|webp)$/i', $path) === 1
        ));
        sort($photoFiles);
        $boyPhoto = $photoFiles[0] ?? null;
        $girlPhoto = $photoFiles[1] ?? ($photoFiles[0] ?? null);

        $emails = [];
        $normalizedResidents = [];
        foreach ($residents as $rawName) {
            $isGirl = preg_match('/-\s*girl\s*$/i', $rawName) === 1;
            $fullName = trim(preg_replace('/-\s*girl\s*$/i', '', $rawName) ?? $rawName);
            $normalizedResidents[] = ['fullName' => $fullName, 'isGirl' => $isGirl];

            $local = (string) Str::of(Str::ascii($fullName))
                ->lower()
                ->replaceMatches('/[^a-z0-9]+/', '.')
                ->trim('.');
            $emails[] = "{$local}@example.com";
        }

        Resident::query()->where('email', 'like', '%@example.com')->delete();
        User::query()
            ->where('role', 'resident')
            ->where('email', 'like', '%@example.com')
            ->delete();

        foreach ($normalizedResidents as $index => $residentEntry) {
            $fullName = $residentEntry['fullName'];
            $isGirl = (bool) $residentEntry['isGirl'];
            $parts = preg_split('/\s+/', trim($fullName)) ?: [];

            $firstName = $parts[0] ?? $fullName;
            $lastName = $parts[1] ?? $firstName;
            if (count($parts) >= 3) {
                $firstName = $parts[0] . ' ' . $parts[1];
                $lastName = implode(' ', array_slice($parts, 2));
            }

            $email = $emails[$index];
            $status = (($index + 1) % 6 === 0) ? 'inactive' : 'active';

            $block = (int) floor($index / 4) + 1;
            $lot = (($index * 3) % 50) + 1;
            $moveInDate = Carbon::now()->subMonths(48 - $index)->startOfMonth();

            $contact = '09' . str_pad((string) (270000000 + $index), 9, '0', STR_PAD_LEFT);

            $photo = $isGirl ? $girlPhoto : $boyPhoto;

            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'resident',
                'active' => $status === 'active',
            ]);

            Resident::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'contact_number' => $contact,
                'photo' => $photo,
                'block' => $block,
                'lot' => $lot,
                'move_in_date' => $moveInDate,
                'status' => $status,
            ]);
        }
    }
}
