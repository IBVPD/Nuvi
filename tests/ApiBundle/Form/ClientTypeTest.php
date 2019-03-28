<?php

namespace NS\ApiBundle\Tests\Form;

use NS\AceBundle\Form\TagType;
use NS\ApiBundle\Form\ClientType;
use NS\ApiBundle\Form\Types\OAuthGrantTypes;
use OAuth2\OAuth2;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use NS\ApiBundle\Entity\Client;

/**
 * Description of ClientTypeTest
 *
 * @author gnat
 */
class ClientTypeTest extends TypeTestCase
{
    public function testForm(): void
    {
        $formData = [
            'name'              => 'ClientName',
            'redirectUris'      => 'https://localhost/,http://example.com/',
            'allowedGrantTypes' => [OAuth2::GRANT_TYPE_AUTH_CODE, OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS,
                OAuth2::GRANT_TYPE_REFRESH_TOKEN]
        ];

        $form     = $this->factory->create(ClientType::class);

        $form->submit($formData);
        $data = $form->getData();
        $this->assertInstanceOf(Client::class, $data);
        $this->assertEquals('ClientName', $data->getName());
        $this->assertEquals(['https://localhost/', 'http://example.com/'], $data->getRedirectUris());
        $this->assertEquals([OAuth2::GRANT_TYPE_AUTH_CODE, OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS,
            OAuth2::GRANT_TYPE_REFRESH_TOKEN], $data->getAllowedGrantTypes());
    }

    public function getExtensions(): array
    {
        $oauthType = new OAuthGrantTypes();
        $tagType   = new TagType();
        return [new PreloadedExtension([$oauthType, $tagType], [])];
    }
}
