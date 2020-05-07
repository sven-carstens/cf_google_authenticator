<?php declare(strict_types=1);
/**@author Robin 'codeFareith' von den Bergen <robinvonberg@gmx.de>
 * @copyright (c) 2018 by Robin von den Bergen
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0.0
 *
 * @link https://github.com/codeFareith/cf_google_authenticator
 * @see https://www.fareith.de
 * @see https://typo3.org
 */
namespace CodeFareith\CfGoogleAuthenticator\Tests\Unit\Domain\Immutable;

use CodeFareith\CfGoogleAuthenticator\Domain\Immutable\AuthenticationSecret;
use CodeFareith\CfGoogleAuthenticator\Tests\Unit\BaseTestCase;
use InvalidArgumentException;
use PHPUnit\Runner\Version;

class AuthenticationSecretTest extends BaseTestCase
{
    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider invalidIssuerProvider
     */
    public function testCannotBeCreatedFromInvalidIssuer(string $issuer, string $accountName, string $secretKey): void
    {
        $this->expectException(InvalidArgumentException::class);

        AuthenticationSecret::create($issuer, $accountName, $secretKey);
    }

    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider invalidAccountNameProvider
     */
    public function testCannotBeCreatedFromInvalidAccountName(string $issuer, string $accountName, string $secretKey): void
    {
        $this->expectException(InvalidArgumentException::class);

        AuthenticationSecret::create($issuer, $accountName, $secretKey);
    }

    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider validDataProvider
     */
    public function testCanBeCreatedFromValidData(string $issuer, string $accountName, string $secretKey): void
    {
        /** @noinspection UnnecessaryAssertionInspection */
        static::assertInstanceOf(
            AuthenticationSecret::class,
            AuthenticationSecret::create($issuer, $accountName, $secretKey)
        );
    }

    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider validDataProvider
     */
    public function testIssuer(string $issuer, string $accountName, string $secretKey): void
    {
        $class = AuthenticationSecret::create($issuer, $accountName, $secretKey);

        static::assertSame(
            $issuer,
            $class->getIssuer()
        );
    }

    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider validDataProvider
     */
    public function testAccountName(string $issuer, string $accountName, string $secretKey): void
    {
        $class = AuthenticationSecret::create($issuer, $accountName, $secretKey);

        static::assertSame(
            $accountName,
            $class->getAccountName()
        );
    }

    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider validDataProvider
     */
    public function testSecretKey(string $issuer, string $accountName, string $secretKey): void
    {
        $class = AuthenticationSecret::create($issuer, $accountName, $secretKey);

        static::assertSame(
            $secretKey,
            $class->getSecretKey()
        );
    }

    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider validDataProvider
     */
    public function testUri(string $issuer, string $accountName, string $secretKey): void
    {
        $class = AuthenticationSecret::create($issuer, $accountName, $secretKey);
        $uri = $class->getUri();

        static::assertStringStartsWith(
            AuthenticationSecret::BASE_URL,
            $uri
        );
        static::assertStringEndsWith(
            $issuer,
            $uri
        );

        if (version_compare(Version::id(), '7.0', '>=')) {
            static::assertStringContainsString(
                'issuer',
                $uri
            );
            static::assertStringContainsString(
                $issuer,
                $uri
            );
            static::assertStringContainsString(
                'secret',
                $uri
            );
            static::assertStringContainsString(
                $secretKey,
                $uri
            );
        } else {
            static::assertContains(
                'issuer',
                $uri
            );
            static::assertContains(
                $issuer,
                $uri
            );
            static::assertContains(
                'secret',
                $uri
            );
            static::assertContains(
                $secretKey,
                $uri
            );
        }
        static::assertStringMatchesFormat(
            '%s?%s',
            $uri
        );
    }

    /**
     * @param string $issuer
     * @param string $accountName
     * @param string $secretKey
     * @dataProvider validDataProvider
     */
    public function testLabel(string $issuer, string $accountName, string $secretKey): void
    {
        $class = AuthenticationSecret::create($issuer, $accountName, $secretKey);
        $label = $class->getLabel();

        static::assertStringStartsWith(
            $issuer,
            $label
        );
        static::assertStringEndsWith(
            $accountName,
            $label
        );
        static::assertStringMatchesFormat(
            '%s:%s',
            $label
        );
    }

    /**
     * @return array
     */
    public function invalidIssuerProvider(): array
    {
        return [
            ['Invalid:Issuer1', 'ValidAccountName1', 'SecretKey1'],
            ['Invalid:Issuer2', 'ValidAccountName2', 'SecretKey2'],
            ['Invalid:Issuer3', 'ValidAccountName3', 'SecretKey3']
        ];
    }

    /**
     * @return array
     */
    public function invalidAccountNameProvider(): array
    {
        return [
            ['ValidIssuer1', 'Invalid:AccountName1', 'SecretKey1'],
            ['ValidIssuer2', 'Invalid:AccountName2', 'SecretKey2'],
            ['ValidIssuer3', 'Invalid:AccountName3', 'SecretKey3']
        ];
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['Issuer1', 'AccountName1', 'SecretKey1'],
            ['Issuer2', 'AccountName2', 'SecretKey2'],
            ['Issuer3', 'AccountName3', 'SecretKey3']
        ];
    }
}
