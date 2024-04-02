<?php
/**
 * Class to JWT.
 *
 * @category   Class
 * @package    Pandora FMS
 * @subpackage Token
 * @version    1.0.0
 * @license    See below
 *
 *    ______                 ___                    _______ _______ ________
 * |   __ \.-----.--.--.--|  |.-----.----.-----. |    ___|   |   |     __|
 * |    __/|  _  |     |  _  ||  _  |   _|  _  | |    ___|       |__     |
 * |___|   |___._|__|__|_____||_____|__| |___._| |___|   |__|_|__|_______|
 *
 * ============================================================================
 * Copyright (c) 2005-2023 Pandora FMS
 * Please see https://pandorafms.com/community/ for full contribution list
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation for version 2.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * ============================================================================
 */

// Begin.
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

/**
 * JWT Repository.
 */
final class JWTRepository
{

    /**
     * Signature
     *
     * @var string
     */
    private $signature;

    /**
     * Token
     *
     * @var Token
     */
    private $token;


    /**
     * Constructor
     *
     * @param string $_signature Signature of JWT.
     */
    public function __construct(string $_signature)
    {
        $this->signature = $_signature;
    }


    /**
     * Create token
     *
     * @return string
     */
    public function create(): string
    {
        global $config;
        $sha = new Sha256();
        $configJWT = Configuration::forSymmetricSigner(
            $sha,
            InMemory::plainText($this->signature)
        );

        $now = new DateTimeImmutable();
        $token = $configJWT->builder()->issuedAt($now)->canOnlyBeUsedAfter($now)->expiresAt($now->modify('+1 minute'))->withClaim('id_user', $config['id_user'])->getToken($configJWT->signer(), $configJWT->signingKey());

        return $token->toString();
    }


    /**
     * Validate a JWT, USE FIRST setToken().
     *
     * @return boolean
     */
    public function validate():bool
    {
        $sha = new Sha256();
        $configJWT = Configuration::forSymmetricSigner(
            $sha,
            InMemory::plainText($this->signature)
        );
        $signed = new SignedWith($sha, InMemory::plainText($this->signature));
        $constraints = [$signed];

        return $configJWT->validator()->validate($this->token, ...$constraints);
    }


    /**
     * Get payload of token.
     *
     * @return object
     */
    public function payload():object
    {
        return $this->token->claims();
    }


    public function setToken(string $tokenString)
    {
        $encoder = new JoseEncoder();
        $parser = new Parser($encoder);
        $this->token = $parser->parse($tokenString);
    }


    /**
     * Generate random signature.
     *
     * @return string
     */
    public static function generateSignature(): string
    {
        return bin2hex(random_bytes(32));
    }


}
