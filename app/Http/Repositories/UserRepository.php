<?php

namespace App\Http\Repositories;

use RuntimeException;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Laravel\Passport\Bridge\User;
use Zend\Soap\Client as SoapClient;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Hashing\HashManager  $hasher
     * @return void
     */
    public function __construct(/*HashManager $hasher*/)
    {
        // $this->hasher = $hasher->driver();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.'.$provider.'.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }


        $client = new SoapClient('http://177.184.8.118/TOTVSBusinessConnect/wsEdu.asmx?wsdl');
        $autentica = $client->AutenticarSenhaUsuario([
            'Login' => $username,
            'senha' => $password
        ]);

        if (! $autentica) {
            return;
        } elseif (! $user = (new $model)->where('username', $username)->first()) {
            $client = new SoapClient('http://177.184.8.118/TOTVSBusinessConnect/wsConsultaSQL.asmx?wsdl');
            $consulta = $client->RealizarConsultaSQLAuth([
                'Usuario' => env('SOAP_USER'),
                'Senha' => env('SOAP_PASSWORD'),
                'codSentenca' => 'WS_LFB_01',
                'codColigada' => 0,
                'codAplicacao' => 'S',
                'parameters' => 'USUARIO=' . $username
            ]);

            $json = json_decode(json_encode(simplexml_load_string($this->removeNamespaceFromXML($consulta->RealizarConsultaSQLAuthResult))), true);
            // dd($json['Resultado']['NOME']);

            $user = (new $model)::create([
                'name' => $json['Resultado']['NOME'],
                'email' => $json['Resultado']['EMAIL'],
                'username' => $json['Resultado']['CODUSUARIO']
            ]);
        }

        return new User($user->getAuthIdentifier());
    }

    public function removeNamespaceFromXML($xml)
    {
        // Because I know all of the the namespaces that will possibly appear in
        // in the XML string I can just hard code them and check for
        // them to remove them
        $toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
        // This is part of a regex I will use to remove the namespace declaration from string
        $nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

        // Cycle through each namespace and remove it from the XML string
        foreach ($toRemove as $remove) {
            // First remove the namespace from the opening of the tag
            $xml = str_replace('<' . $remove . ':', '<', $xml);
            // Now remove the namespace from the closing of the tag
            $xml = str_replace('</' . $remove . ':', '</', $xml);
            // This XML uses the name space with CommentText, so remove that too
            $xml = str_replace($remove . ':commentText', 'commentText', $xml);
            // Complete the pattern for RegEx to remove this namespace declaration
            $pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
            // Remove the actual namespace declaration using the Pattern
            $xml = preg_replace($pattern, '', $xml, 1);
        }

        // Return sanitized and cleaned up XML with no namespaces
        return $xml;
    }
}
