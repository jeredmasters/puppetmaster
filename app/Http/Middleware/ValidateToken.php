<?php

namespace App\Http\Middleware;
use Closure;
use App\Host;

class ValidateToken
{
  protected $token;

  public function __construct(){
    $this->host = null;
  }
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = null)
  {
    $secret = "ehb,m3^)?#T@`ASp*MA4{T#@";
    $token = $request->headers->get('x-token');
    $hash = $request->headers->get('x-hash');
    $md5 = md5($token . $secret);
    if ($hash == $md5){
      $host = Host::where('token', $token)->first();
      if ($host == null){
        $host = new Host;
        $host->token = $token;
        $host->ip = request()->ip();
        $host->save();
      }
      $this->host = $host;
      return $next($request);
    }
    return response()->json("no auth: " . $md5);
  }

  public function getHost(){
    return $this->host;
  }
}
