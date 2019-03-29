<?php


namespace CoreBundle\Plugins\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Link
 * @package CoreBundle\Plugins\Utils
 * @deprecated
 */
class Link {
  /**
   * Generates a URL from the given parameters.
   *
   * @param string $route         The name of the route
   * @param mixed  $parameters    An array of parameters
   * @param int    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
   *
   * @return string The generated URL
   *
   * @see UrlGeneratorInterface
   */
  public static function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
  {
    return Core::$container->get('router')->generate($route, $parameters, $referenceType);
  }


  /**
   * Returns a RedirectResponse to the given URL.
   *
   * @param string $url    The URL to redirect to
   * @param int    $status The status code to use for the Response
   *
   * @return RedirectResponse
   */
  public static function redirect($url, $status = 302)
  {
    return new RedirectResponse($url, $status);
  }

  /**
   * Returns a RedirectResponse to the given route with the given parameters.
   *
   * @param string $route      The name of the route
   * @param array  $parameters An array of parameters
   * @param int    $status     The status code to use for the Response
   *
   * @return RedirectResponse
   */
  public static function redirectToRoute($route, array $parameters = array(), $status = 302)
  {
    return static::redirect(static::generateUrl($route, $parameters), $status);
  }

  public static function addHttp($url, $scheme = 'http://'){
    return parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
  }
  public static function addWww($url){
    $url = trim($url);
    $bits = parse_url($url);
    $newHost = substr($bits["host"],0,4) !== "www."?"www.".$bits["host"]:$bits["host"];
    $bits["path"] = $bits["path"] ? $bits["path"] : '';
    $url = $bits["scheme"]."://".$newHost.(isset($bits["port"])?":".$bits["port"]:"").$bits["path"].(!empty($bits["query"])?"?".$bits["query"]:"");
    return $url;
  }
  public static function addHttpAndWww($url, $scheme = null){
    $url = trim($url);
    $url = static::addHttp($url, $scheme);
    $url = static::addWww($url);
    if($scheme == 'https://'){
      $url = str_replace('http://', $scheme, $url);
    }
    return $url;
  }

}