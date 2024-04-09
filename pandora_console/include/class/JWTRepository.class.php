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
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
enterprise_include_once('include/functions_metaconsole.php');

/**
 * JWT Repository.
 */
final class JWTRepository
{

    /**
     * Allowed methods to be called using AJAX request.
     *
     * @var array
     */
    public $AJAXMethods = ['create'];

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
     * Checks if target method is available to be called using AJAX.
     *
     * @param string $method Target method.
     *
     * @return boolean True allowed, false not.
     */
    public function ajaxMethod($method)
    {
        // Check access.
        check_login();

        return in_array($method, $this->AJAXMethods);
    }


    /**
     * Create token
     *
     * @return string
     */
    public function create(): string
    {
        global $config;
        try {
            $sha = new Sha256();
            $configJWT = Configuration::forSymmetricSigner(
                $sha,
                InMemory::plainText($this->signature)
            );

            $now = new DateTimeImmutable();
            $token = $configJWT->builder()->issuedAt($now)->canOnlyBeUsedAfter($now)->expiresAt($now->modify('+1 minute'))->withClaim('id_user', $config['id_user'])->getToken($configJWT->signer(), $configJWT->signingKey());

            return $token->toString();
        } catch (Exception $e) {
            return '';
        }
    }


    /**
     * Validate a JWT, USE FIRST setToken().
     *
     * @return boolean
     */
    public function validate():bool
    {
        try {
            $sha = new Sha256();
            $configJWT = Configuration::forSymmetricSigner(
                $sha,
                InMemory::plainText($this->signature)
            );
            $signed = new SignedWith($sha, InMemory::plainText($this->signature));
            $now = new DateTimeZone('UTC');
            $strictValid = new StrictValidAt(SystemClock::fromUTC());
            $constraints = [
                $signed,
                $strictValid,
            ];
            return $configJWT->validator()->validate($this->token, ...$constraints);
        } catch (Exception $e) {
            return false;
        }
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


    /**
     * Setting token.
     *
     * @param string $tokenString String token to setting.
     *
     * @return boolean
     */
    public function setToken(string $tokenString):bool
    {
        try {
            $encoder = new JoseEncoder();
            $parser = new Parser($encoder);
            $this->token = $parser->parse($tokenString);
            return true;
        } catch (Exception $e) {
            return false;
        }
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


    /**
     * Sync the signature with nodes for jwt.
     *
     * @param string|null $signature Signature to send nodes.
     *
     * @return void
     */
    public static function syncSignatureWithNodes(?string $signature):void
    {
        global $config;
        if (function_exists('metaconsole_get_servers') === true) {
            $sync = false;
            $servers = metaconsole_get_servers();
            foreach ($servers as $server) {
                $config['JWT_signature'] = 1;
                if (metaconsole_connect($server) == NOERR) {
                    config_update_value('JWT_signature', $signature, true);
                    $sync = true;
                }

                $config['JWT_signature'] = 1;
                metaconsole_restore_db();
            }

            if ($sync === true) {
                config_update_value('JWT_signature', $signature, true);
            }
        }
    }


}
