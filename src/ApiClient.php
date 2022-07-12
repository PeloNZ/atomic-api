<?php
namespace Atomic;

use DateInterval;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;

/**
 * Class ApiClient
 * @package Atomic
 */
class ApiClient
{
    private const API_VERSION = 'v1';

    /**
     * The number of days until the Atomic JWT needs refreshing.
     */
    private const JWT_DAYS_TTL = 7;

    private const JWT_AUDIENCE = 'https://atomic.io';

    /**
     * API Credential Roles
     * @link https://documentation.atomic.io/api/auth?id=api-credential-roles
     */
    private const AUTH = 'auth';

    private const EVENTS = 'events';

    private const WORKBENCH = 'workbench';

    private const ROLES = [
        self::AUTH,
        self::EVENTS,
        self::WORKBENCH,
    ];

    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $oauthUrl;
    /**
     * @var string
     */
    private $atomicApi;
    /**
     * @var string
     */
    private $siteId;
    /**
     * @var string
     */
    private $workbenchClientSecret;
    /**
     * @var string
     */
    private $workbenchClientId;
    /**
     * @var string
     */
    private $authClientSecret;
    /**
     * @var string
     */
    private $authClientId;
    /**
     * @var string
     */
    private $eventsClientSecret;
    /**
     * @var string
     */
    private $eventsClientId;

    /**
     * ApiClient constructor.
     * @param string $orgId
     * @param string $siteId
     * @param string $eventsClientId
     * @param string $eventsClientSecret
     * @param string $authClientId
     * @param string $authClientSecret
     * @param string $workbenchClientId
     * @param string $workbenchClientSecret
     */
    public function __construct(
        string $orgId,
        string $siteId,
        string $eventsClientId,
        string $eventsClientSecret,
        string $authClientId,
        string $authClientSecret,
        string $workbenchClientId,
        string $workbenchClientSecret
    ) {
        $this->setClient(new Client)
            ->setOauthUrl('https://master-atomic-io.auth.us-east-1.amazoncognito.com')
            ->setAtomicApi('https://' . $orgId . '.customer-api.atomic.io/' . self::API_VERSION . '/')
            ->setSiteId($siteId)
            ->setEventsClientId($eventsClientId)
            ->setEventsClientSecret($eventsClientSecret)
            ->setAuthClientId($authClientId)
            ->setAuthClientSecret($authClientSecret)
            ->setWorkbenchClientId($workbenchClientId)
            ->setWorkbenchClientSecret($workbenchClientSecret);
    }

    /**
     * @link https://documentation.atomic.io/api/auth?id=full-authentication-example
     * @return array
     */
    public function listSites()
    {
        return $this->get('sites', self::AUTH);
    }

    /**
     * @link https://documentation.atomic.io/api/card-creation?id=atomic-events
     * @param Event $event
     * @return array
     */
    public function createEvent(Event $event): array
    {
        $payload = $event->toJson();

        return $this->post('event', self::EVENTS, $payload);
    }

    /**
     * @link https://documentation.atomic.io/api/card-creation?id=card-cancellation
     * @param Event $event
     * @return array
     */
    public function cancelEvent(Event $event): array
    {
        $payload = $event->toJson();

        return $this->post('event/cancel', self::EVENTS, $payload);
    }

    /**
     * @link https://documentation.atomic.io/api/card-api?id=retrieving-cards
     * @param array $query
     * @return array
     */
    public function listCards(array $query): array
    {
        return $this->get('cards', self::EVENTS, $query);
    }

    /**
     * @link https://documentation.atomic.io/api/card-api?id=dismiss
     * @param array $query
     * @return array
     */
    public function dismissCards(array $query): array
    {
        return $this->put('cards/dismiss', self::EVENTS, $query);
    }

    /**
     * @link https://documentation.atomic.io/api/card-api?id=complete
     * @param Card $card
     * @return array
     */
    public function completeCard(Card $card): array
    {
        $query = [
            'lifecycleId' => $card->getLifeCycleId(),
            'multiple'    => 1,
        ];

        return $this->put('cards/complete', self::EVENTS, $query);
    }

    /** @throws \Exception */
    public function fetchUsers(?array $query = null): array
    {
        return $this->get('users', self::EVENTS, $query);
    }

    /** @throws \Exception */
    public function createUsers(Customer ...$customers): array
    {
        return $this->post('users', self::EVENTS, json_encode($customers, JSON_THROW_ON_ERROR));
    }

    public function createUserCustomFields(): void
    {
        $fields = array_map(fn (string $field) => [
            'name' => $field,
            'label' => $field,
            'type' => 'text',
        ], Customer::CUSTOM_FIELDS);

        $this->put('custom-profile-fields', self::EVENTS, null, $fields);
    }

    /**
     * @link https://documentation.atomic.io/api/user-preferences?id=user-preferences
     * @param array $payload
     * @return array
     */
    public function updateUserPreferences(array $payload): array
    {
        return $this->put('userPreferences', self::EVENTS, $payload);
    }

    /**
     * @link https://documentation.atomic.io/api/removing-users
     * @param array $payload
     * @return array
     */
    public function deleteUsers(array $payload): array
    {
        return $this->put('deleteUsers', self::EVENTS, $payload);
    }

    /**
     * @link https://documentation.atomic.io/api/api-credentials?id=create-new-api-credential-pair
     * @param array $payload
     * @return array
     */
    public function createCredentials(array $payload): array
    {
        return $this->post('credentials', self::WORKBENCH, json_encode($payload));
    }

    /**
     * @link https://documentation.atomic.io/api/api-credentials?id=revoke-a-credential-pair
     * @param string $clientId
     * @return array
     */
    public function deleteCredentials(string $clientId): array
    {
        return $this->delete('credentials/' . $clientId, self::WORKBENCH);
    }

    /**
     * @link https://documentation.atomic.io/api/card-configuration?id=retrieve-a-card-by-id
     * @param string $cardId
     * @return array
     */
    public function getCard(string $cardId): array
    {
        return $this->get('card/' . $cardId, self::EVENTS);
    }

    /**
     * @link https://documentation.atomic.io/api/card-configuration?id=create-a-new-card-and-associated-event
     * @param array $payload
     * @return array
     */
    public function createCard(array $payload): array
    {
        return $this->post('card', self::EVENTS, json_encode($payload));
    }

    /**
     * @link https://documentation.atomic.io/api/card-configuration?id=update-an-existing-card-and-associated-event
     * @param string $cardId
     * @param array $payload
     * @return array
     */
    public function updateCard(string $cardId, array $payload): array
    {
        return $this->put('card/' . $cardId, null, $payload);
    }

    /**
     * @link https://documentation.atomic.io/api/card-configuration?id=archive-an-existing-card-and-associated-event
     * @param string $cardId
     * @return array
     */
    public function archiveCard(string $cardId): array
    {
        return $this->put('card/' . $cardId . '/archive', self::EVENTS);
    }

    /**
     * @link https://documentation.atomic.io/api/webhooks?id=subscribe-to-a-webhook
     * @param array $payload
     * @return array
     */
    public function subscribeWebhook(array $payload): array
    {
        return $this->post('webhook', self::WORKBENCH, json_encode($payload));
    }

    /**
     * @link https://documentation.atomic.io/api/webhooks?id=list-existing-subscriptions
     * @return array
     */
    public function listWebhooks(): array
    {
        return $this->get('webhook', self::WORKBENCH);
    }

    /**
     * @link https://documentation.atomic.io/api/webhooks?id=test-a-webhook-subscription
     * @param string $type
     * @return array
     */
    public function testWebhook(string $type): array
    {
        return $this->post('webhook?type=' . $type, self::WORKBENCH);
    }

    /**
     * @link https://documentation.atomic.io/api/webhooks?id=remove-a-webhook-subscription
     * @param string $webhookId
     * @return array
     */
    public function removeWebhook(string $webhookId): array
    {
        return $this->delete('webhook/' . $webhookId, self::WORKBENCH);
    }

    /**
     * @link https://documentation.atomic.io/api/webhooks?id=verifying-webhook-payloads
     * @return array
     */
    public function getWebhookKey(): array
    {
        return $this->get('public-key', self::WORKBENCH);
    }

    /**
     * Get a JWT for the client API.
     *
     * @param string $sub the user ID
     * @param string $apiKey the Atomic API key
     * @param string $issuer
     * @param string $privateKeyPath
     * @param string $publicKeyPath
     * @return string
     * @throws Exception
     */
    public function getJwt(
        string $sub,
        string $apiKey,
        string $issuer,
        string $privateKeyPath,
        string $publicKeyPath
    ): string {
        $now        = new DateTimeImmutable();
        $issuedAt   = $now;
        $expiresAt  = $now->add(new DateInterval('P' . self::JWT_DAYS_TTL . 'D'));
        $signer     = new Sha256();
        $privateKey = InMemory::plainText($privateKeyPath);
        $publicKey  = InMemory::plainText($publicKeyPath);

        $config = Configuration::forAsymmetricSigner($signer, $privateKey, $publicKey);

        $constraints = [
            new PermittedFor(self::JWT_AUDIENCE),
            new IssuedBy($issuer),
        ];

        $config->setValidationConstraints(...$constraints);

        $token = $config->builder()
            ->issuedBy($issuer)
            ->permittedFor(self::JWT_AUDIENCE)
            ->issuedAt($issuedAt)
            ->expiresAt($expiresAt)
            ->withClaim('apiKey', $apiKey)
            ->relatedTo($sub)
            ->getToken($signer, $privateKey);

        if($config->validator()->validate($token, ...$config->validationConstraints())) {
            return $token->toString();
        }

        return '';
    }

    /**
     * @param string $operation
     * @param string $role
     * @param array|null $query
     * @return array
     */
    private function get(string $operation, string $role, ?array $query = null): array
    {
        $uri = $this->atomicApi . $this->siteId . '/' . $operation;

        if ($operation === 'sites') {
            // sigh
            $uri = $this->atomicApi . $operation;
        }

        $options     = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken($role),
            ],
            'query' => $query,
        ];
        $request = $this->client->get($uri, $options);

        return json_decode((string) $request->getBody(), true);
    }

    /**
     * @param string $operation
     * @param string $role
     * @param string|null $payload
     * @return array
     */
    private function post(string $operation, string $role, ?string $payload = null): array
    {
        $uri         = $this->atomicApi . $this->siteId . '/' . $operation;
        $options     = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken($role),
            ],
            // Body must be JSON
            'body' => $payload,
        ];
        $request = $this->client->post($uri, $options);

        return json_decode((string) $request->getBody(), true);
    }

    /**
     * @param string $operation
     * @param string $role
     * @param array|null $query
     * @param array|null $payload
     * @return array
     */
    private function put(string $operation, string $role, ?array $query = null, ?array $payload = null): array
    {
        $uri         = $this->atomicApi . $this->siteId . '/' . $operation;
        $options     = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken($role),
            ],
            'query' => $query,
            'body'  => $payload,
        ];
        $request = $this->client->put($uri, $options);

        return json_decode((string) $request->getBody(), true);
    }

    /**
     * @param string $operation
     * @param string $role
     * @return array
     */
    private function delete(string $operation, string $role): array
    {
        $uri         = $this->atomicApi . $this->siteId . '/' . $operation;
        $options     = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken($role),
            ],
        ];
        $request = $this->client->delete($uri, $options);

        return json_decode((string) $request->getBody(), true);
    }

    /**
     * Get access token or request a new one.
     *
     * @param string $role
     * @return string
     */
    private function getAccessToken(string $role): string
    {
        if (!in_array($role, self::ROLES)) {
            throw new InvalidArgumentException('Invalid role: ' . $role);
        }

        $clientId     = $this->{$role . 'ClientId'};
        $clientSecret = $this->{$role . 'ClientSecret'};

        $credentials  = base64_encode("$clientId:$clientSecret");

        $request = $this->client->post(
            $this->oauthUrl . '/oauth2/token',
            [
                'query' => [
                    'grant_type' => 'client_credentials',
                    'client_id'  => $clientId,
                ],
                'headers' => [
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . $credentials,
                ],
            ]
        );

        $response = json_decode((string) $request->getBody());

        return $response->access_token;
    }

    /**
     * @param Client $client
     * @return ApiClient
     */
    private function setClient(Client $client): ApiClient
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param string $oauthUrl
     * @return ApiClient
     */
    private function setOauthUrl(string $oauthUrl): ApiClient
    {
        $this->oauthUrl = $oauthUrl;

        return $this;
    }

    /**
     * @param string $atomicApi
     * @return ApiClient
     */
    private function setAtomicApi(string $atomicApi): ApiClient
    {
        $this->atomicApi = $atomicApi;

        return $this;
    }

    /**
     * @param string $siteId
     * @return ApiClient
     */
    private function setSiteId(string $siteId): ApiClient
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @param string $workbenchClientSecret
     * @return ApiClient
     */
    private function setWorkbenchClientSecret(string $workbenchClientSecret): ApiClient
    {
        $this->workbenchClientSecret = $workbenchClientSecret;

        return $this;
    }

    /**
     * @param string $workbenchClientId
     * @return ApiClient
     */
    private function setWorkbenchClientId(string $workbenchClientId): ApiClient
    {
        $this->workbenchClientId = $workbenchClientId;

        return $this;
    }

    /**
     * @param string $authClientSecret
     * @return ApiClient
     */
    private function setAuthClientSecret(string $authClientSecret): ApiClient
    {
        $this->authClientSecret = $authClientSecret;

        return $this;
    }

    /**
     * @param string $authClientId
     * @return ApiClient
     */
    private function setAuthClientId(string $authClientId): ApiClient
    {
        $this->authClientId = $authClientId;

        return $this;
    }

    /**
     * @param string $eventsClientSecret
     * @return ApiClient
     */
    private function setEventsClientSecret(string $eventsClientSecret): ApiClient
    {
        $this->eventsClientSecret = $eventsClientSecret;

        return $this;
    }

    /**
     * @param string $eventsClientId
     * @return ApiClient
     */
    private function setEventsClientId(string $eventsClientId): ApiClient
    {
        $this->eventsClientId = $eventsClientId;

        return $this;
    }
}
