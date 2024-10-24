<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class generateJWTApple extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-apple-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = new \DateTimeImmutable();

    $jwtConfig = Configuration::forSymmetricSigner(
        new Sha256(),
        InMemory::file('storage/apple-jwt/AuthKey.pem')
    );
    $token = $jwtConfig->builder()
        ->issuedBy(env('APPLE_KEY_ID'))
        ->issuedAt($now)
        ->expiresAt($now->modify('+1 month'))
        ->permittedFor('https://appleid.apple.com')
        ->relatedTo(env('APPLE_SERVICE_ID'))
        ->withHeader('kid', env('APPLE_KEY_ID'))
        ->getToken($jwtConfig->signer(), $jwtConfig->signingKey());

    // echo $token->toString();
    $this->setNewEnv("APPLE_CLIENT_SECRET", $token->toString());
    // echo app()->environmentFilePath();
    return 0;
    }
    private function setNewEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($value),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
}
