<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use App\Repository\UsuarioRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
//este ultimo servicio sirver para generar URLs en Symfony
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    //esto nos ayuda cuando accedemos como annonymus a algun sitio de tal modo que
    //symfony se guarda la ruta donde quisimos acceder antes de redirigirnos
    //al login y asi despues de iniciar sesion nos redirige a la pagina
    //a la que quisimos acceder previamente como annonymus

    private $userRepository;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;

   public function __construct(UsuarioRepository $userRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
   {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
   }
    

    public function supports(Request $request)
    # este método es llamado al principio de cada solictud
    #devuelve true si la solicitud contiene info de autenticacion que este autenticador
    #sabe cómo procesar y se llama al metodo getCredentials(). Si no, devuelve falso y no se llaman a los demas metodos del autenticador, como si no pasara nada.
    # Cuando enviamos el formulario de inicio de sesión, se envía a /login. Por lo tanto, nuestro 
    #autenticador solo debe intentar autenticar al usuario en esa situación exacta
    {
        return $request->attributes->get('_route') === 'app_login'
        && $request->isMethod('POST');
        #cheque si la url es /login y si se trasta de una solicitud POST.
    }

    public function getCredentials(Request $request)
    # Lee nuestras credenciales de autenticación de la solicitud y las devuelve.
    #en este caso, le devolveremos el usuario y la contraseña (se enviarian mas cosas si hubiera mas campos). 
    # Después de retornar, Symfony llama al método getUser().
    {
        #para leer los datos POST de la solicitud usar $request->request

        $credentials = [
            'usuario' => $request->request->get('usuario'),
            'clave' => $request->request->get('clave'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        // de esta manera seteamos el valor del usuario en la sesion de forma manual (es la unica forma)
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['usuario']
        );
        return $credentials;
    }

    #$credentials es el array devuelto por getCredentials, que pasa Symfony
    public function getUser($credentials, UserProviderInterface $userProvider)
    #Devuelve un objeto usuario con los datos de $credentials, o null si no se lo encuentra
    #Si devuelve un usuario, Symfony llamará a checkCredentials, si no se detiene el proceso
    {
        //los argumentos de CsrfToken() vienen del formulario a la hora de enviarlo
        //y el otro es el nombre que se leda en getCredentials() serian id y value.
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if(!$this->csrfTokenManager->isTokenValid($token))
            throw new InvalidCsrfTokenException();
        /** @var Usuario $user */
        $usuario = $this->userRepository->findOneBy(['usuario' => $credentials['usuario']]);

        return $usuario;
    }


    #la funcion recibe el arreglo $credentials y el usuario devuelto por getUser()
    public function checkCredentials($credentials, UserInterface $user)
    #chequea si la clave es corecta
    {
        
        return $this->passwordEncoder->isPasswordValid($user, $credentials['clave']);
    }
    //una vez que la autenticacion es exitosa, Symfony llama a esta fc



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {   //genra una URL para la ruta de nombre "app_homepage"
        //Symfony redirige a la URL devuelta en esta función

        //getTargetPath() recibe la funcion y la providerKey que es el nombre de nuestro firewall
        if($targetPath = $this->getTargetPath($request->getSession(), $providerKey))
            return new RedirectResponse($targetPath);

        //si la vble $targetPath no está seteada en la sesión, que puede pasar cuando
        //accedemos directamente al login, entonces se redirige a 'app_homepage'    
        $url = $this->router->generate('app_homepage');
        return new RedirectResponse($url);
    }


    # en un fallo, la clase authenticator llama a esta funcion que redirige.
    # al fallar el login, lo logico es devolver la misma pagina de login
    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }
}
