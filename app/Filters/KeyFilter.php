<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class KeyFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {

      if (!$request->hasHeader('gate-key')) {
          $response = service('response');
          $response->setJSON(['status'=>'error','message'=>'No Key']);
          $response->setStatusCode(401);
          return $response;
      }

      $gkey = $request->header('gate-key')->getValue();
  		if($gkey != '8fa0559eac3de95fc4f07cff8e9c1ed882d02542')
  		{
  			$response = service('response');
        $response->setJSON(['status'=>'error','message'=>'Invalid Key']);
        $response->setStatusCode(401);
        return $response;
  		}

    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
