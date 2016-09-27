<?php
namespace Kristenlk\OAuth2\Client\Test\Provider;

use Kristenlk\OAuth2\Client\Provider\Marketo;
use Mockery as m;
use ReflectionClass;


class MarketoTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('Kristenlk\OAuth2\Client\Provider\Marketo');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function setUp()
    {
        $this->provider = new Marketo([
            'clientId'      => 'mock_client_id',
            'clientSecret'  => 'mock_secret',
            'baseUrl'   => 'https://abc-123-456.example.com',
        ]);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals('/identity/oauth/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = m::mock('\Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn('{"access_token": "mock_access_token", "expires_in": 3600}');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('\GuzzleHttp\ClientInterface');
        $this->provider->setHttpClient($client);
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $token = $this->provider->getAccessToken('client_credentials');
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertLessThanOrEqual(time() + 3600, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertNull($token->getRefreshToken());
    }

    public function testCheckResponseThrowsIdentityProviderException()
    {
        $method = self::getMethod('checkResponse');
        $responseInterface = m::mock('Psr\Http\Message\ResponseInterface');
        $responseInterface->shouldReceive('getBody')->andReturn('{"error": "unauthorized", "error_description": "No client with requested id: abc123"}');
        $responseInterface->shouldReceive('getStatusCode')->andReturn(401);
        $data = ['error' => "unauthorized", "error_description" => "No client with requested id: abc123"];

        try {
            $method->invoke($this->provider, $responseInterface, $data);
        } catch (\Exception $e) {
            $this->assertEquals(401, $e->getCode());
            $this->assertEquals("unauthorized", $e->getMessage());
        }
    }
}